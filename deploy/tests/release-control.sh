#!/usr/bin/env bash
set -euo pipefail
# Ce scénario ne doit jamais s’exécuter dans un /srv/vef déjà existant.
[[ ${VEF_ISOLATED_TEST:-} == 1 && $EUID == 0 && ! -e /srv/vef ]] || exit 64
directory=$(mktemp -d)
mkdir -p /srv/vef
cleanup() {
    rm -f /srv/vef/current /srv/vef/current.tmp /srv/vef/domain /srv/vef/deploy.lock
    rmdir /srv/vef
    rm -f "$directory/docker" "$directory/curl" "$directory/sleep" "$directory/df" "$directory/flock" "$directory/log" "$directory/output"
    rmdir "$directory"
}
trap cleanup EXIT
printf '%s\n' example.org >/srv/vef/domain
export MOCK_LOG="$directory/log"
export MOCK_SHA=bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
previous=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
cat >"$directory/docker" <<'STUB'
#!/usr/bin/env bash
set -eu
printf '%s|%s\n' "${VEF_RELEASE:-}" "$*" >>"$MOCK_LOG"
if [[ $1 == image && $2 == load ]]; then cat >/dev/null; exit 0; fi
if [[ $1 == image && $2 == inspect ]]; then printf '%s\n' "${MOCK_LABEL:-$MOCK_SHA}"; exit 0; fi
if [[ $1 == image && $2 == ls ]]; then exit 0; fi
if [[ $1 == inspect ]]; then printf 'true 0\n'; exit 0; fi
if [[ $* == *' ps -q worker' ]]; then printf 'worker-fixture\n'; exit 0; fi
if [[ $* == *' artisan migrate '* && ${MOCK_MIGRATION_FAIL:-0} == 1 ]]; then exit 12; fi
STUB
# Le faux curl doit lire cette variable au moment de son exécution.
# shellcheck disable=SC2016
printf '%s\n' '#!/usr/bin/env bash' 'exit "${MOCK_HEALTH_FAIL:-0}"' >"$directory/curl"
printf '%s\n' '#!/usr/bin/env bash' 'exit 0' >"$directory/sleep"
cp "$directory/sleep" "$directory/flock"
printf '%s\n' '#!/usr/bin/env bash' "printf 'Avail\\n9999999999\\n'" >"$directory/df"
chmod +x "$directory/docker" "$directory/curl" "$directory/sleep" "$directory/df" "$directory/flock"
export PATH="$directory:$PATH"
release() { printf 'image fictive\n' | gzip | bash /scripts/ovh/release.sh "$MOCK_SHA" >"$directory/output" 2>&1; }

release
[[ $(cat /srv/vef/current) == "$MOCK_SHA" ]]

printf '%s\n' "$previous" >/srv/vef/current
: >"$MOCK_LOG"
export MOCK_MIGRATION_FAIL=1
if release; then exit 1; fi
[[ $(cat /srv/vef/current) == "$previous" ]]
grep -q "^$previous|.* up -d app worker proxy$" "$MOCK_LOG"
grep -q "^$previous|.* artisan up$" "$MOCK_LOG"
if grep -q 'migrate:rollback' "$MOCK_LOG"; then exit 1; fi
unset MOCK_MIGRATION_FAIL

: >"$MOCK_LOG"
export MOCK_LABEL=incorrect
if release; then exit 1; fi
[[ $(cat /srv/vef/current) == "$previous" ]]
if grep -q ' artisan down' "$MOCK_LOG"; then exit 1; fi
unset MOCK_LABEL

: >"$MOCK_LOG"
export MOCK_HEALTH_FAIL=1
if release; then exit 1; fi
[[ $(cat /srv/vef/current) == "$previous" ]]
grep -q "^$previous|.* up -d app worker proxy$" "$MOCK_LOG"
printf 'Livraison simulée : succès, migration refusée, image incorrecte et panne HTTPS vérifiés.\n'
