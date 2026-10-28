# Notifications VEF (issue #3)

Les emails de contact et de candidature utilisent le même habillage : logo VEF intégré au message, en-tête bleu, coordonnées, texte conservant ses retours à la ligne, bouton vers la rubrique concernée de l’administration et pied de page commun. Les adresses destinataires, objets et adresses de réponse sont conservés. La précision « autre participation » est également affichée lorsqu’elle est renseignée.

Le gabarit partagé est `resources/views/emails/layout.blade.php`. Les composants `emails.field` et `emails.message` structurent le contenu. La mise en page utilise des tableaux et des styles intégrés, avec adaptation des colonnes sur petit écran ; aucune police distante, aucun JavaScript ou CSS du site n’est requis. Le logo est une pièce jointe intégrée (CID). Une version MIME texte est fournie pour chaque email ; ses champs ne sont pas échappés en HTML afin de préserver les caractères, tandis que les vues HTML échappent les données utilisateur.

Définir `APP_URL` avec le domaine HTTPS public avant un envoi en production : les liens d’administration et de site sont construits à partir de cette configuration.

## Vérifications et aperçus locaux

- `php artisan test --filter=EmailNotificationsTest` : transport en mémoire, contenu HTML et texte, échappement, adresse de réponse et logo intégré. Aucun email externe n’est envoyé.
- `php tests/Preview/emails.php` : génère deux aperçus HTML avec des données fictives dans `storage/app/private/email-previews/`, sans modifier la base ni envoyer de message.
- `node tests/Preview/render-emails.cjs` : avec Playwright et Edge installés, produit les captures aux largeurs 760 et 390 px et vérifie l’absence de débordement horizontal. `VEF_PLAYWRIGHT_MODULE` peut pointer vers une installation locale de Playwright.

Les aperçus sont privés et ne créent aucune route publique. Leur inspection dans un navigateur ne remplace pas un essai de réception dans les clients mail utilisés par l’équipe.

## Enregistrement durable et reprise après panne

Les formulaires enregistrent maintenant la demande **et** la tâche `SendFormNotification` dans la même transaction SQL. La connexion de queue `form-notifications` utilise la même base que les demandes ; elle n’est pas remplacée par le pilote global `sync` des tests. Une panne d’écriture de la tâche annule aussi l’enregistrement de la demande. Aucune connexion SMTP n’est ouverte pendant la requête du visiteur.

Le visiteur reçoit une confirmation d’enregistrement. La demande est consultable dans l’admin même si le transport email tombe ensuite en panne. La notification est traitée par un worker, avec cinq tentatives et des délais successifs de 30, 120, 600 et 1 800 secondes. Le timeout SMTP est de 20 secondes par défaut (`MAIL_TIMEOUT`) ; celui de la tâche est de 30 secondes.

Le serveur de déploiement doit superviser ce processus, avec redémarrage automatique :

```sh
php artisan queue:work form-notifications --queue=notifications --timeout=30 --tries=5
```

Après livraison d’une nouvelle version, demander aux workers de se relancer avec le nouveau code :

```sh
php artisan queue:restart
```

Après épuisement des tentatives, inspecter les tâches échouées puis relancer uniquement la tâche concernée après réparation du transport :

```sh
php artisan queue:failed
php artisan queue:retry IDENTIFIANT_DE_LA_TACHE
```

Le stockage du cache doit être partagé avec les workers pour que `queue:restart` soit pris en compte. Les tables `jobs` et `failed_jobs` sont créées par les migrations existantes. Les payloads des tâches référencent les modèles ; ils ne recopient pas le texte des messages dans le job.

`VEF_CONTACT_RECIPIENT` et `VEF_APPLICATION_RECIPIENT` configurent les destinataires ; leurs valeurs par défaut conservent les adresses déjà utilisées. En local, `MAIL_MAILER=log` permet une vérification sans email externe. En production, définir le transport et `APP_URL` avant de lancer le worker. Les retries d’un serveur SMTP ne garantissent pas une livraison exactement une fois si le prestataire accepte un message puis perd sa réponse.

`FormDeliveryTest.php` vérifie l’enregistrement sans SMTP, l’envoi par un vrai worker avec transport simulé, la reprise après échec sans recréer la demande, le rollback si la file est indisponible et la taille maximale des formulaires.
