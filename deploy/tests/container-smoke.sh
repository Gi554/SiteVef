#!/usr/bin/env bash
set -euo pipefail
revision=${1:?Révision attendue}
[[ $revision =~ ^[a-f0-9]{40}$ || $revision == local ]] || exit 64
name="vef-smoke-$$"
cleanup() { docker rm -f "$name" >/dev/null 2>&1 || true; }
trap cleanup EXIT
app_key="base64:$(openssl rand -base64 32)"
docker run -d --name "$name" --cap-drop ALL --security-opt no-new-privileges:true \
    -e APP_ENV=testing -e APP_DEBUG=false -e APP_KEY="$app_key" \
    -e APP_URL=http://localhost:8080 -e DB_CONNECTION=sqlite \
    -e DB_DATABASE=/tmp/smoke.sqlite -e CACHE_STORE=database -e SESSION_DRIVER=database \
    "sitevef:$revision" /bin/sh -c \
    'touch /tmp/smoke.sqlite && php artisan migrate --force && php artisan config:cache && php artisan route:cache && exec apache2-foreground'
ready=false
for attempt in {1..30}; do
    if docker exec "$name" php -r 'exit(@file_get_contents("http://localhost:8080/up") === false ? 1 : 0);'; then
        ready=true
        break
    fi
    sleep 2
done
if ! $ready; then docker logs "$name"; exit 1; fi
docker exec "$name" php -r '
    foreach (["/", "/projets", "/parrainage", "/don"] as $path) {
        $body=@file_get_contents("http://localhost:8080".$path);
        if ($body===false) { fwrite(STDERR, "Page indisponible : ".$path); exit(1); }
    }
    if (is_file("/var/www/html/.env") || is_dir("/var/www/html/.git")) exit(1);
    if (file_exists("/var/www/html/storage/app/private/ovh-vef-admin")) exit(1);
    if (posix_geteuid()===0) exit(1);
    echo "Image non privilégiée, pages publiques et migration : OK\n";
'
