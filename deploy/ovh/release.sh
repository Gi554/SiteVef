#!/usr/bin/env bash
set -Eeuo pipefail
[[ $EUID -eq 0 && $# -eq 1 && $1 =~ ^[a-f0-9]{40}$ ]] || exit 64
cd /srv/vef
exec 9>/srv/vef/deploy.lock
flock -w 600 9
revision=$1
domain=$(cat /srv/vef/domain)
[[ $domain =~ ^[a-z0-9][a-z0-9.-]+\.[a-z]{2,}$ ]] || exit 64
previous=''
if [[ -f /srv/vef/current ]]; then
    previous=$(cat /srv/vef/current)
    [[ $previous =~ ^[a-f0-9]{40}$ ]] || exit 64
fi

compose() {
    local version=$1
    shift
    VEF_RELEASE=$version VEF_DOMAIN=$domain docker compose --env-file /dev/null \
        -f /srv/vef/config/compose.yml "$@"
}

available=$(df --output=avail -B1 /srv/vef | tail -n1 | tr -d ' ')
(( available >= 2147483648 )) || { printf 'Au moins 2 Gio libres sont nécessaires.\n' >&2; exit 1; }

# Le flux SSH contient uniquement une image Docker, jamais un script à exécuter.
timeout 600 bash -o pipefail -c 'gzip -dc | docker image load'
image="sitevef:$revision"
actual=$(docker image inspect --format '{{index .Config.Labels "org.opencontainers.image.revision"}}' "$image")
[[ $actual == "$revision" ]] || { printf "Révision de l'image incorrecte.\n" >&2; exit 65; }
docker pull caddy:2-alpine

maintenance=false
rollback() {
    local status=$?
    trap - ERR
    if $maintenance && [[ -n $previous ]]; then
        printf 'Échec : redémarrage de la version précédente, sans rollback SQL automatique.\n' >&2
        compose "$previous" up -d app worker proxy || true
        compose "$previous" exec -T app php artisan up || true
    elif $maintenance; then
        compose "$revision" stop app worker proxy || true
    fi
    exit "$status"
}
trap rollback ERR

if [[ -n $previous ]]; then
    compose "$previous" exec -T app php artisan down --retry=30
    maintenance=true
    compose "$previous" stop worker
    compose "$previous" stop app
    # VACUUM INTO produit une sauvegarde SQLite cohérente, y compris avec WAL.
    # Le code PHP doit conserver ses variables sans expansion par Bash.
    # shellcheck disable=SC2016
    compose "$revision" run --rm --no-deps -v /srv/vef/backups:/backup app php -r '
        $db=getenv("DB_DATABASE");
        if (!is_file($db) || filesize($db)===0) exit(0);
        $backup="/backup/".gmdate("Ymd-His")."-".bin2hex(random_bytes(4)).".sqlite";
        $pdo=new PDO("sqlite:".$db);
        $pdo->exec("VACUUM INTO ".$pdo->quote($backup));
    '
fi

maintenance=true
compose "$revision" run --rm --no-deps app php artisan migrate --force --no-interaction
compose "$revision" up -d app worker proxy
compose "$revision" exec -T app php artisan up

healthy=false
for _ in {1..60}; do
    if curl --fail --silent --show-error --max-time 5 \
        --resolve "$domain:443:127.0.0.1" "https://$domain/up" >/dev/null 2>&1; then
        healthy=true
        break
    fi
    sleep 3
done
$healthy || { printf 'Le contrôle HTTPS a échoué.\n' >&2; false; }
worker=$(compose "$revision" ps -q worker)
[[ -n $worker && $(docker inspect --format '{{.State.Running}} {{.RestartCount}}' "$worker") == 'true 0' ]] \
    || { printf "Le worker de notification n'est pas stable.\n" >&2; false; }
printf '%s\n' "$revision" >/srv/vef/current.tmp
mv /srv/vef/current.tmp /srv/vef/current
maintenance=false
trap - ERR
kept=0
while read -r tag; do
    [[ $tag =~ ^[a-f0-9]{40}$ && $tag != "$revision" && $tag != "$previous" ]] || continue
    kept=$((kept + 1))
    if (( kept > 1 )); then docker image rm --no-prune "sitevef:$tag" || true; fi
done < <(docker image ls sitevef --format '{{.Tag}}')
printf 'Livraison %s disponible sur https://%s\n' "$revision" "$domain"
