# Paiement Kkiapay (issue #14)

Kkiapay est la seule passerelle de paiement pour les dons généraux et les dons par projet (Mobile Money et carte). Le montant Kkiapay est arrondi au franc et affiché avant paiement. Les montants libres saisis en XOF sont conservés directement ; les collectes existantes restent comptabilisées en équivalent EUR. L’administration affiche le prestataire, le montant XOF et l’identifiant de transaction.

## Configuration locale et sandbox

Renseigner uniquement dans `.env` les clés du compte marchand Kkiapay :

```dotenv
KKIAPAY_PUBLIC_KEY=
KKIAPAY_PRIVATE_KEY=
KKIAPAY_SECRET_KEY=
KKIAPAY_SANDBOX=true
KKIAPAY_WEBHOOK_SECRET=
```

Seule la clé publique est transmise au navigateur. Les autres clés ne doivent jamais être commitées. Le secret webhook est un secret distinct, choisi dans le tableau de bord Kkiapay et recopié dans `.env`.

Exécuter `php artisan migrate`, puis `php artisan config:clear`. Pour recevoir les notifications, définir `APP_URL` sur une URL HTTPS publique et ajouter dans le tableau de bord Kkiapay le webhook `https://votre-domaine/kkiapay/webhook`, événements `transaction.success` et `transaction.failed`, avec le même secret webhook. En local, utiliser une URL de tunnel HTTPS lorsque les notifications doivent être testées.

Le lien de paiement signé reste accessible 24 heures. Le retour du widget transmet un identifiant de transaction au serveur, qui interroge l’API Kkiapay. La confirmation exige le statut `SUCCESS`, le montant XOF exact et la référence du don renvoyée par Kkiapay (`partnerId` ou `stateData.donation_reference`). Les notifications sont authentifiées via `x-kkiapay-secret`, puis les succès revérifiés par l’API. Une référence issue d’un webhook authentifié peut servir au rapprochement lorsque la réponse API ne l’inclut pas. Une transaction ne peut confirmer qu’un don ; un échec tardif ne déconfirme pas un succès. Une indisponibilité API ne confirme aucun paiement et renvoie une erreur aux notifications pour permettre une nouvelle tentative.

## Validation avant activation

Si le retour automatique du widget est interrompu, rouvrir le lien de paiement et utiliser « Déjà payé ? Vérifier mon don » avec l’identifiant de la facture. Le callback signé du widget restaure aussi cet identifiant puis relance la vérification par POST. Aucun retour GET ne confirme le paiement. Un opérateur peut également exécuter `php artisan donations:verify-kkiapay REFERENCE_DU_DON IDENTIFIANT_TRANSACTION` : cette commande vérifie le paiement avec l’API et applique les mêmes contrôles de montant, de référence et de doublon.

`php artisan test --compact --filter=KkiapayDonationTest` vérifie les protections avec l’API simulée et une base en mémoire. `npm run build` vérifie les ressources du site.

Avec les clés sandbox, effectuer un don général et un don pour un projet, tester un échec puis un succès, fermer la page avant le retour et contrôler la notification, vérifier le montant dans le tableau de bord Kkiapay et l’apparition d’un seul don confirmé dans l’administration. Vérifier avec une vraie réponse sandbox les champs renvoyés par l’API et la transmission de `partnerId` / `stateData` par le widget. Aucun paiement réel ne fait partie des tests automatisés.

Après validation sandbox seulement, utiliser les clés de production et `KKIAPAY_SANDBOX=false`, configurer le webhook de production puis vider le cache de configuration.

Sources officielles :

- https://docs.kkiapay.me/v1/plugin-et-sdk/sdk-javascript
- https://docs.kkiapay.me/v1/tableau-de-bord/webhook
- https://github.com/kkiapay/php-sdk/blob/master/src/Kkiapay.php
- https://github.com/kkiapay/php-sdk/blob/master/src/Constants.php
