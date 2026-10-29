#!/usr/bin/env bash
set -euo pipefail
if [[ $EUID -ne 0 || $# -ne 2 ]]; then
    printf 'Usage : sudo bash install.sh domaine chemin-vers-cle-publique-CD\n' >&2
    exit 64
fi
domain=$1
public_key_file=$2
[[ $domain =~ ^[a-z0-9][a-z0-9.-]+\.[a-z]{2,}$ ]] || exit 64
[[ -f $public_key_file ]] || exit 66
public_key=$(awk 'NF {print $1 " " $2}' "$public_key_file")
[[ $public_key =~ ^ssh-ed25519\ [A-Za-z0-9+/=]+$ ]] || exit 64
ssh-keygen -lf "$public_key_file" >/dev/null
source_directory=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)
if [[ -f /srv/vef/domain && $(cat /srv/vef/domain) != "$domain" ]]; then
    printf 'Le domaine existant doit être migré explicitement avant de relancer l’installation.\n' >&2
    exit 64
fi

# Installation officielle Docker ; aucun paquet ni site existant n’est supprimé.
if ! command -v docker >/dev/null; then
    apt-get update
    apt-get install -y ca-certificates curl
    install -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
    chmod 0644 /etc/apt/keyrings/docker.asc
    . /etc/os-release
    [[ $ID == ubuntu ]] || exit 64
    printf 'deb [arch=%s signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu %s stable\n' \
        "$(dpkg --print-architecture)" "$VERSION_CODENAME" >/etc/apt/sources.list.d/docker-vef.list
    apt-get update
    apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
fi
systemctl enable --now docker
docker compose version

install -d -m 0755 /srv/vef /srv/vef/config /srv/vef/shared /srv/vef/shared/caddy
install -d -m 0750 -o 33 -g 33 \
    /srv/vef/backups \
    /srv/vef/shared/storage /srv/vef/shared/storage/app /srv/vef/shared/storage/app/private \
    /srv/vef/shared/storage/app/public \
    /srv/vef/shared/storage/framework /srv/vef/shared/storage/framework/cache \
    /srv/vef/shared/storage/framework/cache/data /srv/vef/shared/storage/framework/sessions \
    /srv/vef/shared/storage/framework/views /srv/vef/shared/storage/logs \
    /srv/vef/shared/uploads /srv/vef/shared/uploads/projets /srv/vef/shared/uploads/membres \
    /srv/vef/shared/uploads/actualites /srv/vef/shared/uploads/parrainage
if [[ ! -e /srv/vef/shared/storage/app/private/vef.sqlite ]]; then
    install -m 0640 -o 33 -g 33 /dev/null /srv/vef/shared/storage/app/private/vef.sqlite
fi

if [[ ! -e /srv/vef/shared/laravel.env ]]; then
    umask 077
    app_key=$(openssl rand -base64 32)
    cat >/srv/vef/shared/laravel.env <<ENV
APP_NAME="Verre d’Eau Fraîche"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:$app_key
APP_URL=https://$domain
APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
LOG_CHANNEL=stderr
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/storage/app/private/vef.sqlite
DB_BUSY_TIMEOUT=5000
DB_JOURNAL_MODE=WAL
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
CACHE_STORE=database
QUEUE_CONNECTION=database
TRUSTED_PROXIES=172.30.44.2
MAIL_MAILER=log
MAIL_TIMEOUT=20
KKIAPAY_SANDBOX=true
AUTH_REGISTRATION_ENABLED=false
ADMIN_PATH=espace-equipe
ENV
    chown root:33 /srv/vef/shared/laravel.env
    chmod 0640 /srv/vef/shared/laravel.env
fi
printf '%s\n' "$domain" >/srv/vef/domain
chmod 0644 /srv/vef/domain
install -m 0644 "$source_directory/compose.yml" /srv/vef/config/compose.yml
install -m 0644 "$source_directory/Caddyfile" /srv/vef/config/Caddyfile
install -m 0755 "$source_directory/release.sh" /usr/local/sbin/vef-release
install -m 0755 "$source_directory/ssh-command.sh" /usr/local/bin/vef-ssh-command

if ! id vef-deploy >/dev/null 2>&1; then
    useradd --create-home --shell /bin/bash vef-deploy
fi
install -d -m 0700 -o vef-deploy -g vef-deploy /home/vef-deploy/.ssh
key_entry="restrict,command=\"/usr/local/bin/vef-ssh-command\" $public_key"
touch /home/vef-deploy/.ssh/authorized_keys
grep -qxF "$key_entry" /home/vef-deploy/.ssh/authorized_keys \
    || printf '%s\n' "$key_entry" >>/home/vef-deploy/.ssh/authorized_keys
chown vef-deploy:vef-deploy /home/vef-deploy/.ssh/authorized_keys
chmod 0600 /home/vef-deploy/.ssh/authorized_keys
sudo_rule=$(mktemp)
trap 'rm -f "$sudo_rule"' EXIT
printf 'vef-deploy ALL=(root) NOPASSWD: /usr/local/sbin/vef-release *\n' >"$sudo_rule"
visudo -cf "$sudo_rule"
install -m 0440 "$sudo_rule" /etc/sudoers.d/vef-deploy
printf 'Installation prête. Configurer laravel.env avant le premier déploiement.\n'
