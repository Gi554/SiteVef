# Comprendre la CI du projet

La CI (intégration continue) exécute automatiquement les vérifications sur GitHub. Elle aide à repérer un problème avant de fusionner une modification. Elle ne déploie pas le site et n’effectue aucun paiement réel.

## Déroulement d’un changement

1. Partir de `version1` à jour et créer une branche dédiée, par exemple `codex/correction-contact`.
2. Faire des commits courts qui expliquent les changements.
3. Envoyer cette branche sur GitHub et ouvrir une pull request vers `version1`.
4. Ouvrir l’onglet **Checks** de la pull request. Deux contrôles sont attendus : `Tests PHP 8.3` et `Tests PHP 8.4`.
5. Si un contrôle échoue, ouvrir le détail de l’étape rouge, lire l’erreur et corriger sur la même branche. Un nouveau commit relance la CI.
6. Faire relire puis fusionner la pull request seulement après validation humaine. Ne pas pousser directement sur `version1`.

Le workflow `.github/workflows/ci.yml` est déclenché sur chaque pull request vers `version1`, après un push sur `version1`, et manuellement depuis l’onglet Actions une fois le workflow présent sur la branche par défaut. Un nouveau lancement annule le précédent pour la même pull request ; les deux versions PHP sont testées même si l’une échoue.

## Ce que les contrôles vérifient

| Étape | Pourquoi |
| --- | --- |
| Validation Composer | Vérifier la cohérence de `composer.json` et `composer.lock`. |
| Installation depuis les fichiers lock | Tester les mêmes versions de dépendances pour tous les développeurs. |
| Compilation Vite avec Node 22 | Vérifier que les ressources CSS et JavaScript peuvent être produites. |
| Tests Laravel | Vérifier l’authentification, les emails et les paiements Kkiapay simulés. Les tests risqués et les avertissements font échouer cette étape. |
| Smoke éditorial | Vérifier les pages, les droits admin, les projets et les collectes sans doublon. |
| Smoke parrainage | Vérifier l’édition des programmes, les validations et les imports de photos. |

Les vérifications tournent sur Ubuntu, avec PHP 8.3 et 8.4. Le code applicatif déclare PHP 8.2 minimum, mais les dépendances de test Pest 4 / PHPUnit 12 nécessitent PHP 8.3 minimum : la CI ne valide donc pas PHP 8.2.

Chaque job dispose de sa propre base SQLite en mémoire, d’un transport email en mémoire et d’une clé Laravel générée pour le lancement. Les clés Kkiapay réelles ne sont pas utilisées. Les tests de paiement remplacent l’API par des réponses simulées. Les vérifications de parrainage nettoient la photo qu’elles créent. Les clés API, bases locales et emails de l’équipe ne doivent pas être ajoutés aux secrets de cette CI.

## Reproduire une erreur en local

Depuis le dépôt, avec PHP 8.3+ et Node 22 :

```sh
composer validate --strict
composer install --no-interaction --prefer-dist
npm ci --no-audit --no-fund
npm run build
php artisan test --compact --fail-on-risky --fail-on-warning
php tests/Smoke/editorial.php
php tests/Smoke/parrainage.php
```

À la première installation, copier `.env.example` vers `.env` puis exécuter `php artisan key:generate`. Ne jamais remplacer un `.env` existant pour lancer les tests. La suite et les smoke tests utilisent des bases en mémoire.

## Protection de la branche et limites

Le workflow signale les erreurs ; il n’interdit pas à lui seul les pushs directs ou les fusions avec des tests rouges. Un administrateur du dépôt doit configurer une règle pour `version1` : pull request obligatoire, au moins une approbation, et les deux contrôles ci-dessus obligatoires avant fusion. Cette configuration GitHub n’est pas incluse dans le fichier YAML.

Les actions sont figées sur des SHA de commits plutôt que sur des références mobiles ; leurs versions doivent être révisées lors d’une mise à jour. Le jeton du workflow ne peut que lire le dépôt. Aucune fusion automatique ni publication n’est configurée.

La livraison et le déploiement continus (CD) seront ajoutés séparément lorsque l’hébergement et les environnements seront définis. Le rendu Gmail/Outlook et les vrais retours ou webhooks Kkiapay restent des vérifications distinctes : une CI verte ne les remplace pas.
