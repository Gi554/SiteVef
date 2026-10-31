#!/usr/bin/env bash
set -euo pipefail
export VEF_RELEASE=${1:?Révision attendue}
export VEF_DOMAIN=localhost
[[ $VEF_RELEASE =~ ^[a-f0-9]{40}$ ]] || exit 64
directory=$(mktemp -d)
project="vef-compose-smoke-$$"
compose() { docker compose -p "$project" -f "$directory/compose.json" "$@"; }
cleanup() {
    compose down --volumes >/dev/null 2>&1 || true
    rm -f "$directory/compose.json" "$directory/production.json" "$directory/Caddyfile" "$directory/override.yml"
    rmdir "$directory"
}
trap cleanup EXIT
printf 'services:\n  app:\n    env_file: !reset []\n  worker:\n    env_file: !reset []\n' >"$directory/override.yml"
docker compose --env-file /dev/null -f deploy/ovh/compose.yml -f "$directory/override.yml" config \
    --no-env-resolution --no-path-resolution --format json >"$directory/production.json"
export VEF_SMOKE_DIRECTORY="$directory"
VEF_SMOKE_KEY="base64:$(openssl rand -base64 32)"
export VEF_SMOKE_KEY
python3 - <<'PYTHON'
import json
import os
from pathlib import Path
directory = Path(os.environ['VEF_SMOKE_DIRECTORY'])
config = json.loads((directory / 'production.json').read_text())
config['networks']['private'].pop('name', None)
environment = {
    'APP_ENV': 'testing', 'APP_DEBUG': 'false', 'APP_URL': 'http://localhost',
    'APP_KEY': os.environ['VEF_SMOKE_KEY'], 'DB_CONNECTION': 'sqlite',
    'DB_DATABASE': '/var/www/html/storage/app/private/compose-smoke.sqlite',
    'CACHE_STORE': 'database', 'SESSION_DRIVER': 'database',
    'QUEUE_CONNECTION': 'database', 'MAIL_MAILER': 'log', 'DB_BUSY_TIMEOUT': '5000',
}
for name in ('app', 'worker'):
    service = config['services'][name]
    service.pop('env_file', None)
    service['environment'] = environment.copy()
    service['volumes'] = [{'type': 'volume', 'source': 'data', 'target': '/var/www/html/storage'}]
proxy = config['services']['proxy']
proxy['ports'] = [{'target': 80, 'published': '0', 'host_ip': '127.0.0.1', 'protocol': 'tcp'}]
proxy['volumes'] = [{'type': 'bind', 'source': str(directory / 'Caddyfile'), 'target': '/etc/caddy/Caddyfile', 'read_only': True}]
config['volumes'] = {'data': {}}
(directory / 'Caddyfile').write_text(':80 {\n reverse_proxy app:8080\n}\n')
(directory / 'compose.json').write_text(json.dumps(config))
PYTHON
compose run --rm --no-deps app /bin/sh -c \
    'touch storage/app/private/compose-smoke.sqlite && php artisan migrate --force'
compose up -d
endpoint=$(compose port proxy 80)
ready=false
for _ in {1..30}; do
    if curl --fail --silent --max-time 3 "http://$endpoint/up" >/dev/null; then ready=true; break; fi
    sleep 2
done
if ! $ready; then compose logs --tail 40; exit 1; fi
compose exec -T app php artisan down --retry=30
compose exec -T app php artisan up
curl --fail --silent --max-time 5 "http://$endpoint/up" >/dev/null
worker=$(compose ps -q worker)
[[ -n $worker && $(docker inspect --format '{{.State.Running}} {{.RestartCount}}' "$worker") == 'true 0' ]]
printf 'Application, worker et proxy démarrent ensemble sur le réseau de production avec des données isolées.\n'
