#!/usr/bin/env bash
set -euo pipefail

# Le compte de CD ne peut ni ouvrir un shell ni choisir une commande sudo.
if [[ ${SSH_ORIGINAL_COMMAND:-} =~ ^deploy\ ([a-f0-9]{40})$ ]]; then
    exec sudo -n /usr/local/sbin/vef-release "${BASH_REMATCH[1]}"
fi
printf 'Commande de déploiement refusée.\n' >&2
exit 64
