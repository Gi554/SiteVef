# Protection des routes et des paiements

## Adresse de l’administration

L’espace de gestion est accessible à `/espace-equipe` par défaut. `ADMIN_PATH` permet de choisir un autre chemin dans la configuration de l’environnement (3 à 80 lettres, chiffres, tirets ou tirets bas, sans slash). Il faut recharger le cache de configuration et des routes après une modification sur un serveur qui les utilise.

Les noms des routes `admin.*` restent stables. Les liens Blade et les deux appels JavaScript de gestion utilisent ces noms pour fonctionner avec le préfixe configuré. Toutes les routes de gestion sont regroupées dans `routes/admin.php` et exigent une session connectée et le droit `admin`. Les anciens chemins `/administrateur/*` et l’alias de suppression `/posts/{id}` ne sont plus enregistrés et ne redirigent pas vers la nouvelle adresse.

Le chemin est un choix de navigation, pas un secret d’authentification. Un visiteur qui connaît la nouvelle adresse ne reçoit aucun droit supplémentaire. Après connexion, un administrateur arrive directement sur cet espace.

## Comptes

`AUTH_REGISTRATION_ENABLED=false` ferme les écrans GET et POST d’inscription avec une réponse 404 ; les comptes existants restent utilisables. La réouverture nécessite un choix explicite dans l’environnement. Les champs envoyés par un utilisateur ne peuvent pas lui attribuer `is_admin`.

Les tentatives de connexion restent limitées par email et adresse IP dans `LoginRequest`. Une seconde limite par IP empêche de contourner cette protection en changeant d’adresse email. Les demandes d’email de réinitialisation et les inscriptions, lorsqu’elles sont ouvertes, ont leur propre quota.

## Quotas applicatifs

Les limites sont définies dans `AppServiceProvider`. Un dépassement renvoie HTTP 429 avec `Retry-After`.

| Opération | Limite |
| --- | --- |
| Connexion | 10 requêtes/minute/IP, en plus de la limite existante email + IP |
| Email de réinitialisation | 5 requêtes/heure/IP |
| Inscription activée | 3 requêtes/heure/IP |
| Consultation admin | 120 requêtes/minute/compte |
| Modification admin | 30 requêtes/minute/compte |
| Création d’un don | 10 requêtes/minute/IP et 30/heure/IP |
| Pages et confirmation Kkiapay | 20 requêtes/minute/IP ; 5 POST/minute/don, 30 GET/minute/don |
| Webhook authentifié | 120 notifications/minute/IP |

Les signatures des liens Kkiapay et le secret des webhooks sont contrôlés avant les quotas correspondants. Une requête non authentifiée ne peut donc pas consommer le quota d’une notification authentique. Les limites par don et par compte sont communes aux différentes adresses IP ; les quotas par IP n’empêchent pas à eux seuls une attaque répartie entre de nombreuses sources.

## Paiements

Le parcours de don reste public. Le serveur refuse les écritures du navigateur sans jeton CSRF et les confirmations sans lien signé valide. Le webhook est le seul endpoint exempté de CSRF : son middleware vérifie le secret `x-kkiapay-secret` avec `hash_equals` avant tout traitement.

Un clic, une requête forgée ou un retour GET ne confirme pas un paiement. Pour un nouveau succès, le serveur interroge l’API Kkiapay et contrôle le statut, le montant et l’association au don selon le parcours existant. Une transaction ne peut pas financer deux dons. Une répétition du même succès déjà enregistré est acquittée sans nouvel appel API et sans changer la date de confirmation. Une notification avec un autre identifiant de transaction est refusée pour un don déjà confirmé. Une indisponibilité du prestataire ne transforme pas un paiement en succès.

L’URL et le secret webhook existants restent valables. Aucun changement n’est nécessaire dans le tableau de bord Kkiapay pour cette évolution.

Les pages privées et les liens de paiement portent `Cache-Control: no-store, private`, `Referrer-Policy: no-referrer`, `X-Frame-Options: DENY` et `X-Robots-Tag: noindex, nofollow`. Le suivi des visites exclut les routes privées, les liens signés de paiement et les réponses en erreur.

## Configuration du serveur

Les quotas utilisent le cache Laravel : conserver un stockage persistant et partagé entre les instances, par exemple le cache base de données ou Redis. Le cache `array` est réservé aux tests. Si un proxy est installé, configurer uniquement les proxys de confiance pour identifier les bonnes IP. En production, utiliser HTTPS, `APP_DEBUG=false` et `SESSION_SECURE_COOKIE=true`. Le filtrage des volumes distribués se règle aussi au niveau du serveur ou du proxy lors de la mise en place de la CD.

## Vérifications pour l’équipe

```sh
php artisan test --compact --fail-on-risky --fail-on-warning
php tests/Smoke/editorial.php
php tests/Smoke/parrainage.php
```

`RouteSecurityTest.php` vérifie chaque route admin en visiteur et compte ordinaire, les anciens endpoints, la fermeture des inscriptions, les quotas par IP et par don, le rejeu des webhooks, les en-têtes privés et CSRF. CSRF est explicitement réactivé dans ces tests, car Laravel le désactive habituellement dans son environnement de test. Un scénario avec un jeton valide vérifie également qu’un vrai donateur peut encore démarrer son paiement. Les appels Kkiapay y sont simulés ; aucun paiement réel n’est effectué.

Références : [notifications Kkiapay](https://docs.kkiapay.me/v1/tableau-de-bord/webhook), [limitation des routes Laravel](https://laravel.com/docs/12.x/routing#rate-limiting).
