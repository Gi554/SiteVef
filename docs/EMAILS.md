# Notifications VEF (issue #3)

Les emails de contact et de candidature utilisent le même habillage : logo VEF intégré au message, en-tête bleu, coordonnées, texte conservant ses retours à la ligne, bouton vers la rubrique concernée de l’administration et pied de page commun. Les adresses destinataires, objets et adresses de réponse sont conservés. La précision « autre participation » est également affichée lorsqu’elle est renseignée.

Le gabarit partagé est `resources/views/emails/layout.blade.php`. Les composants `emails.field` et `emails.message` structurent le contenu. La mise en page utilise des tableaux et des styles intégrés, avec adaptation des colonnes sur petit écran ; aucune police distante, aucun JavaScript ou CSS du site n’est requis. Le logo est une pièce jointe intégrée (CID). Une version MIME texte est fournie pour chaque email ; ses champs ne sont pas échappés en HTML afin de préserver les caractères, tandis que les vues HTML échappent les données utilisateur.

Définir `APP_URL` avec le domaine HTTPS public avant un envoi en production : les liens d’administration et de site sont construits à partir de cette configuration.

## Vérifications et aperçus locaux

- `php artisan test --filter=EmailNotificationsTest` : transport en mémoire, contenu HTML et texte, échappement, adresse de réponse et logo intégré. Aucun email externe n’est envoyé.
- `php tests/Preview/emails.php` : génère deux aperçus HTML avec des données fictives dans `storage/app/private/email-previews/`, sans modifier la base ni envoyer de message.
- `node tests/Preview/render-emails.cjs` : avec Playwright et Edge installés, produit les captures aux largeurs 760 et 390 px et vérifie l’absence de débordement horizontal. `VEF_PLAYWRIGHT_MODULE` peut pointer vers une installation locale de Playwright.

Les aperçus sont privés et ne créent aucune route publique. Leur inspection dans un navigateur ne remplace pas un essai de réception dans les clients mail utilisés par l’équipe.
