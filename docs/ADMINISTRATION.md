# Évolution de l’administration VEF

## Audit et choix

Le site utilise Laravel 12, Blade, Tailwind et Alpine. L’administration existante utilise Inter, un dégradé #2563eb / #1e40af, des cartes blanches, des boutons arrondis et une navigation latérale. Les modules équipe, témoignages, messages et candidatures sont conservés. Les projets existants et leurs galeries sont étendus dans les mêmes tables. Les pages d’édition réutilisent les styles et la navigation de cette administration ; aucun second système d’authentification ni CMS n’est introduit.

Les opérations utilisent les routes web Laravel, les sessions et CSRF. Aucune API indépendante n’est nécessaire. Les nouvelles pages publiques utilisent les mêmes modèles Eloquent que l’Admin.

## Installation

1. Sauvegarder la base avant la migration, selon la procédure de déploiement habituelle.
2. Exécuter `php artisan migrate --force`.
3. Attribuer le rôle au compte responsable existant : `php artisan admin:grant adresse@example.org`.
4. Compiler les styles : `npm run build` (Node 20.19+ ou 22.12+ recommandé par Vite).

Aucun utilisateur ne devient administrateur automatiquement. Le rôle `is_admin` est absent des champs assignables depuis l’inscription et le profil. Tous les comptes ordinaires sont refusés côté serveur. Les anciens projets publics restent publiés lors de la migration et reçoivent un slug stable ; leurs anciennes images principales rejoignent la galerie si nécessaire. Aucun contenu de démonstration n’est ajouté.

## Utilisation

- **Projets** : ouvrir « Créer un projet » ou « Modifier ». Les sections facultatives vides sont masquées sur le site. Les champs structurés utilisent une entrée par ligne avec `|` comme séparateur ; leurs formats sont expliqués dans le formulaire.
- Choisir une image principale parmi les photos de la galerie. Les imports sont limités aux JPEG, PNG et WebP de 3 Mo, avec des noms générés côté serveur.
- Enregistrer puis prévisualiser la version enregistrée. Passer la visibilité à « Publié » pour la rendre publique ; « Brouillon » la retire du site. « Archivé » conserve le dossier dans l’Admin, sans page publique. Le statut « Réalisé » permet de conserver une page publique pour un projet terminé.
- Les slugs restent identiques quand le titre change. Leur modification explicite change l’URL : conserver le slug pour les liens déjà partagés.
- **Actualités** : gérer les catégories, écrire l’article, associer plusieurs projets et publier. Le contenu accepte `## Titre`, `### Sous-titre` et `[texte](/chemin-interne)`. Le HTML n’est jamais exécuté.
- Les articles publiés apparaissent dans les actualités, l’accueil et les pages des projets associés. Aucun déploiement n’est nécessaire pour les modifications éditoriales.
- Le sitemap dynamique se trouve à `/sitemap.xml`. Les projets et articles non publiés sont exclus ; les aperçus sont protégés et marqués noindex.

## Paiements Kkiapay

Kkiapay est la seule passerelle pour les dons généraux et les dons par projet, avec ou sans compte. Configurer les clés API et le webhook selon [le guide Kkiapay](KKIAPAY.md). Les formulaires acceptent les euros ou les FCFA ; le débit est toujours effectué en FCFA (XOF), arrondi au franc, à la parité de 1 EUR = 655,957 XOF. Les collectes restent enregistrées en équivalent EUR pour conserver les budgets et les historiques existants.

Seuls les paiements vérifiés côté serveur sont confirmés et comptabilisés. Le webhook `/kkiapay/webhook` est authentifié par son secret ; les succès sont revérifiés auprès de l’API Kkiapay. Le retour signé restaure la référence de transaction et déclenche une vérification par POST : aucune simple visite de la page de retour ne confirme de don. Les traitements empêchent le double comptage. Les dons par projet sont disponibles uniquement pour les projets publiés dont la collecte est activée.

L’Admin affiche les transactions et les collectes par projet, leur statut, le montant XOF et la référence Kkiapay. Les anciens enregistrements de paiement sont conservés pour la traçabilité ; les nouveaux paiements utilisent exclusivement Kkiapay. Les remboursements ne sont pas importés automatiquement.

## Vérification

`php tests/Smoke/editorial.php` utilise exclusivement une base SQLite en mémoire : migrations, rendu public et Admin, accès ordinaires refusés, brouillons invisibles, publication, webhook Kkiapay authentifié et API simulée, idempotence, absence de confirmation par une simple visite et refus des suppressions anonymes.

`php artisan test --compact --filter=KkiapayDonationTest` vérifie le paiement général et par projet, les montants, les clés absentes, les références, les liens signés et les retours du prestataire. Les paiements et la livraison réelle du webhook restent à valider en sandbox avant l’activation en production. Aucun paiement réel n’est effectué par les tests automatisés.

## Refonte de l’espace de gestion

L’administration reprend les bleus du site avec une navigation blanche, des cartes et une hiérarchie plus lisible. Le tableau de bord et les éditeurs de projets, d’actualités et de parrainage partagent désormais le même cadre.

### Où modifier quoi ?

- `resources/views/admin/layout.blade.php` : structure commune, en-tête, retours de validation et chargement des ressources.
- `resources/views/admin/partials/sidebar.blade.php` : groupes de navigation et compte connecté.
- `resources/views/admin/partials/dashboard.blade.php` : synthèse des dons confirmés, messages, projets et candidatures, dernières transactions et fréquentation.
- `resources/views/admin/partials/workspace-panels.blade.php` : formulaires et listes des modules existants. Les actualités et dons restent dans `editorial-panels.blade.php`.
- `resources/views/admin/partials/workspace-modals.blade.php` : modification d’un membre et détail d’une candidature.
- `public/Style1/css/admin-workspace.css` : présentation commune, adaptation mobile et styles de focus, chargés après les styles historiques.
- `public/Style1/js/admin-workspace.js` : navigation par fragment, menu mobile, recherche, fenêtres et changement de statut des messages.
- `app/Http/Controllers/AdminController.php` : récupération des données et calcul des totaux du tableau de bord.

### Comportement à connaître

Les liens comme `/administrateur#messages` ouvrent directement le bon module ; les boutons précédent et suivant du navigateur fonctionnent. Sur téléphone, le bouton Menu ouvre la navigation ; Échap ou un clic à l’extérieur la referme. Les grands tableaux défilent dans leur propre cadre, également accessible au clavier.

Le statut lu/non lu est enregistré par la route Laravel existante avec le jeton CSRF. Le compteur et les derniers messages se mettent à jour après une réponse réussie du serveur. Une erreur réseau conserve le statut affiché et indique de réessayer. Les messages longs proposent « Lire la suite ».

Le total des dons exclut les paiements en attente ou échoués. La valeur en euros utilise les centimes enregistrés ; la ligne FCFA additionne les montants XOF enregistrés, sans reconversion des euros arrondis. Les paiements historiques sans montant XOF ne contribuent pas à cette seconde ligne. Aucun paiement n’est validé manuellement depuis cette interface.

Le graphique compte les visites uniques par adresse IP **et par jour** ; il ne représente pas un nombre de personnes uniques sur le mois. Les valeurs mensuelles restent consultables sous forme de tableau. Il ne charge plus Chart.js depuis un CDN.

Les fenêtres gardent le focus à l’intérieur et rendent le focus au bouton d’ouverture à la fermeture. Les contenus utilisateurs restent échappés par Blade ; les détails de candidature sont construits avec `textContent` en JavaScript.

### Vérifications

```sh
php artisan test --compact --fail-on-risky --fail-on-warning
php tests/Smoke/editorial.php
php tests/Smoke/parrainage.php
node --check public/Style1/js/admin-workspace.js
```

Les tests `AdminWorkspaceTest.php` couvrent les accès, les totaux confirmés avec conservation du montant XOF et l’enregistrement du statut des messages. Les contrôles navigateur ont été effectués à 1440 px et 390 px avec une base de démonstration séparée : huit panneaux, trois éditeurs, recherche, fenêtres, sauvegarde d’un membre et échec réseau simulé. Cette base et les captures ne font pas partie du dépôt.
