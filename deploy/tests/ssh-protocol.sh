#!/usr/bin/env bash
set -euo pipefail
directory=$(mktemp -d)
cleanup() { rm -f "$directory/sudo" "$directory/result"; rmdir "$directory"; }
trap cleanup EXIT
cat >"$directory/sudo" <<'STUB'
#!/usr/bin/env bash
printf '%s\n' "$*" >"$PROTOCOL_RESULT"
STUB
chmod +x "$directory/sudo"
export PATH="$directory:$PATH"
export PROTOCOL_RESULT="$directory/result"
sha=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
SSH_ORIGINAL_COMMAND="deploy $sha" bash deploy/ovh/ssh-command.sh
[[ $(cat "$PROTOCOL_RESULT") == "-n /usr/local/sbin/vef-release $sha" ]]
for invalid in 'whoami' 'deploy main' "deploy $sha; id" "deploy $sha extra" 'deploy ../../etc/passwd' "deploy ${sha}a"; do
    rm -f "$PROTOCOL_RESULT"
    if SSH_ORIGINAL_COMMAND="$invalid" bash deploy/ovh/ssh-command.sh 2>/dev/null; then exit 1; fi
    [[ ! -e $PROTOCOL_RESULT ]]
done
printf 'Protocole de livraison : commandes non autorisées refusées.\n'
