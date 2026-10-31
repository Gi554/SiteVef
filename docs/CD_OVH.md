# Livraison continue sur le VPS OVH

La branche principale reste `version1`. Une livraison provient exclusivement d’une CI verte sur le commit principal actuel. Une PR ou un fork ne peut pas déclencher une livraison. La variable GitHub **de dépôt** `CD_ENABLED` doit valoir `true` pour activer la production ; absente ou fausse, elle laisse la CD désactivée.

## Ce qui tourne sur le serveur

Docker Compose lance trois services : l’application Laravel avec PHP 8.3/Apache, le worker des notifications et Caddy pour HTTPS. L’application et le worker utilisent l’utilisateur non privilégié `www-data`. Seuls les ports 80 et 443 du proxy sont publiés ; PHP et la base SQLite ne sont pas exposés au réseau public. Les limites mémoire visent le VPS de 4 Go.

Caddy gère les certificats pour le domaine principal et redirige `www` vers celui-ci. Les enregistrements A et AAAA de ces noms doivent pointer vers le VPS et les ports HTTP/HTTPS doivent être accessibles. Le réseau Docker réservé est `172.30.44.0/24` ; seul le proxy `172.30.44.2` est reconnu par Laravel pour les en-têtes de protocole et d’IP. Ne pas remplacer cette confiance par `*`.

## Installation initiale, après validation humaine

L’installation exige le compte administrateur du VPS et un accès sudo fonctionnel. Elle est distincte des déploiements courants. Les scripts doivent provenir d’un commit relu ; ne pas exécuter de fichier provenant d’une PR inconnue avec sudo.

1. Générer une **nouvelle** clé Ed25519 dédiée à GitHub Actions, sans phrase secrète pour l’usage automatisé. Conserver la clé privée hors de Git et ne transmettre au serveur que le fichier `.pub`. Ne pas réutiliser la clé SSH d’administration.
2. Copier les cinq fichiers de `deploy/ovh/` et cette clé publique dans un dossier de préparation du compte d’administration sur le VPS.
3. Dans ce dossier, lancer :

```sh
sudo bash install.sh verredeaufraiche.com ./deploy.pub
```

Le script installe Docker depuis son dépôt officiel si nécessaire, crée `/srv/vef`, le compte `vef-deploy` et une clé autorisée limitée à la livraison. Il ne désactive pas le pare-feu, ne modifie pas la configuration SSH existante et ne lance pas encore le site. Si la configuration SSH du serveur limite les comptes admis, autoriser explicitement `vef-deploy` sans retirer le compte administrateur.

4. Configurer `/srv/vef/shared/laravel.env` sur le serveur. La clé Laravel est générée une seule fois, puis conservée. Le modèle initial active `APP_ENV=production`, désactive le debug et les inscriptions et utilise HTTPS, les sessions en base et le cache en base. Il laisse **les emails en mode log et Kkiapay en sandbox sans clés** : renseigner le transport SMTP, ses destinataires et les clés Kkiapay de l’environnement avant d’ouvrir ces services aux utilisateurs. Ne jamais copier le `.env` ou la base de développement.

Le script est relançable sans remplacer le fichier Laravel existant. Un changement de domaine nécessite une migration explicite de cette configuration et des DNS. La clé privée d’administration, les mots de passe sudo et les clés API restent hors du dépôt et du livrable.

## Réglages GitHub

Créer l’environnement **production**, éventuellement avec approbation humaine obligatoire. Y ajouter le secret `VPS_DEPLOY_KEY`, contenant uniquement la clé privée **de CD** créée pour `vef-deploy`.

Ajouter les variables de dépôt suivantes :

| Variable | Valeur |
| --- | --- |
| `VPS_HOST` | Adresse du VPS, actuellement `54.39.109.220` |
| `VPS_KNOWN_HOSTS` | Ligne de clé d’hôte SSH vérifiée pour ce VPS, provenant du fichier known_hosts validé par l’administrateur |
| `CD_ENABLED` | `true`, uniquement lorsque l’installation et la configuration sont prêtes |

La clé d’hôte est vérifiée strictement ; le workflow n’accepte pas automatiquement une nouvelle clé à chaque connexion. Le compte de CD n’a ni shell interactif, ni redirection de port, ni accès sudo général. Son unique commande est `deploy` suivie d’un SHA de 40 caractères ; le script privilégié valide aussi cet argument.

Après activation, relancer **CI** manuellement sur `version1` si le dernier run ne contenait pas encore le livrable. Le workflow **CD OVH** suit automatiquement la réussite de cette CI. Un lancement manuel de CD peut aussi sélectionner une CI verte par son ID ; sans ID, il utilise la dernière CI verte admissible. Les artefacts sont conservés un jour : relancer CI si le livrable a expiré.

## Déroulement d’une livraison

La CI construit et démarre l’image de production, vérifie les pages publiques et la migration en base isolée, puis archive cette image avec son empreinte SHA-256 lorsque la CD est activée sur `version1`. Le workflow CD télécharge **cette même image**, vérifie son empreinte, l’origine du run et le SHA principal actuel, puis l’envoie par le canal SSH chiffré. Il ne reconstruit pas une autre image après validation.

Sur le VPS, un verrou empêche deux livraisons simultanées. Le script charge et vérifie la révision de l’image avant la maintenance. Pour une mise à jour, il met l’application en maintenance, arrête le worker et l’ancienne application, sauvegarde SQLite puis applique les migrations. Il démarre ensuite les nouveaux services et contrôle HTTPS ainsi que la stabilité du worker. Une brève indisponibilité est donc prévue ; ce mécanisme ne promet pas un déploiement sans coupure.

Si une migration ou le contrôle de santé échoue, le script relance l’image précédente lorsqu’elle existe. Il ne restaure pas automatiquement une ancienne base et n’exécute pas `migrate:rollback` : les migrations doivent rester compatibles avec cette stratégie, et une restauration SQL nécessite une décision explicite. L’image courante, la précédente et une autre image récente sont conservées.

## Données persistantes et sauvegardes

La base, les sessions, les files de tâches et le cache se trouvent dans `/srv/vef/shared/storage`. Les photos sont dans `/srv/vef/shared/uploads`. La configuration et les certificats HTTPS sont également hors des images. Ces chemins ne sont jamais remplacés par le code d’une livraison.

Les sauvegardes SQL avant migration sont dans `/srv/vef/backups`, hors des volumes accessibles aux services web et worker. Elles restent sur le même VPS : organiser aussi une sauvegarde externe des données, photos et configurations ainsi qu’une politique de conservation. Le script refuse une livraison avec moins de 2 Gio libres ; cela ne remplace pas la surveillance du disque et des tâches échouées.

## Premier administrateur et exploitation

Après la première livraison, sur le VPS avec le compte administrateur :

```sh
revision=$(sudo cat /srv/vef/current)
sudo env VEF_RELEASE="$revision" VEF_DOMAIN=verredeaufraiche.com \
  docker compose --env-file /dev/null -f /srv/vef/config/compose.yml \
  exec app php artisan admin:create responsable@example.com --name="Responsable VEF"
```

Remplacer l’email par celui du responsable. Le mot de passe est demandé de manière masquée, sans argument dans l’historique du shell. Un email déjà existant n’est ni écrasé ni promu par cette commande. L’accès web reste `/espace-equipe`.

Les services redémarrent automatiquement après un redémarrage du VPS. Pour inspecter les logs, utiliser la même commande Compose avec `logs --tail=100 app worker proxy`. Les tâches échouées se consultent avec `exec app php artisan queue:failed`, puis se relancent individuellement après correction. Ne pas lancer de worker avec un autre code que l’image déployée.

## Vérifications

La CI vérifie PHP 8.3/8.4, les audits de dépendances, le build, les tests Laravel, les parcours métier, le démarrage de l’image non privilégiée et le protocole SSH limité. L’installation réelle et l’émission du certificat nécessitent un accès sudo, les bons DNS et une validation sur le VPS ; les tests en conteneur ne les remplacent pas.
