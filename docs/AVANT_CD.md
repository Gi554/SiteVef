# Préparer la CD après les corrections de l’audit

Les dépendances ont été mises à jour dans les versions majeures existantes (Laravel 12, Tailwind 3 et Vite 7). Les audits PHP de production et JavaScript ne signalent plus d’alerte à la validation de cette PR. La CI réexécute ces audits, les tests et les scénarios publics sur PHP 8.3 et 8.4.

Les formulaires utilisent une file SQL durable, décrite dans [EMAILS.md](EMAILS.md). Le retrait d’un projet conserve les dons affectés, décrit dans [ADMINISTRATION.md](ADMINISTRATION.md). Ces changements ne définissent pas encore un déploiement automatique : l’hébergeur et les environnements restent à choisir.

## Livrable et état persistant

Le livrable contient le code versionné, les dépendances PHP de production installées depuis `composer.lock` et les ressources construites avec `npm ci` puis `npm run build`. Il n’embarque pas le `.env` local, les bases, les tâches de test ni les données du poste de développement. Ne pas exposer le serveur de développement Vite en production.

Pour un déploiement par dossiers de version, conserver hors des releases puis relier à chaque version :

- le `.env` propre à l’environnement et sa clé `APP_KEY` stable ;
- la base de données (et son fichier si SQLite est retenu) ;
- `storage/`, en particulier les sessions/fichiers persistants et les logs ;
- `public/Style1/imagesProjets`, `public/Style1/imagesMembres`, `public/Style1/imagesActualites` et `public/Style1/imagesParrainage`, qui reçoivent les photos depuis l’admin.

Les uploads utilisent encore ces chemins publics historiques, pas le disque Laravel `public`. Ne pas les effacer lors d’une synchronisation du code. Les sauvegardes doivent couvrir la base et ces répertoires, avec un essai de restauration. Les noms de photos sont générés et les types contrôlés par les formulaires.

## Ordre de livraison à prévoir

1. Construire et vérifier le même commit que celui validé par la CI.
2. Sauvegarder la base et préparer les chemins persistants avant de remplacer une release.
3. Appliquer `php artisan migrate --force`, notamment la nouvelle colonne `projets.deleted_at`, avant de basculer le trafic.
4. Préparer les caches avec la configuration de l’environnement, puis basculer vers la nouvelle release.
5. Relancer les workers avec `php artisan queue:restart` et superviser la connexion `form-notifications`.
6. Vérifier les pages publiques, la connexion admin, les logs et la présence du worker ; effectuer les vérifications Kkiapay sandbox dans l’environnement de test.

Pour un nouvel environnement seulement, générer une clé Laravel unique. Ne jamais lancer `key:generate` à chaque livraison ni copier une clé depuis un exemple public. En production : `APP_ENV=production`, `APP_DEBUG=false`, domaine HTTPS dans `APP_URL`, cookies de session sécurisés, cache persistant partagé, transport email et clés Kkiapay correspondant au bon environnement. Les comptes locaux de test ne doivent pas être importés.

Les migrations de retrait des projets nécessitent un rollback réfléchi, décrit dans la documentation admin. Le pipeline CD devra privilégier un déploiement réversible du code et une stratégie de migration compatible plutôt qu’un rollback automatique de toutes les migrations.
