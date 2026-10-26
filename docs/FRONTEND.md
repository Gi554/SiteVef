# Interface publique

La présentation conserve les routes et les sections existantes. Le paiement et les données des programmes restent gérés par les contrôleurs existants.

## Organisation

- `resources/css/public-site.css` : palette, espacements, composants et adaptations mobiles. Les règles globales sont limitées à `.public-site` pour isoler les écrans d’administration et d’authentification.
- `resources/css/app.css` : point d’entrée Vite et Tailwind.
- `resources/views/components/project-card.blade.php` : carte commune à l’accueil et au catalogue, avec présentation de remplacement en l’absence de photo.
- `resources/views/components/parrainage-programme.blade.php` : présentation commune des programmes. Les montants et textes proviennent toujours de la base. Les images existantes d’éducation et de santé illustrent les programmes sans photo personnalisée.
- `resources/views/components/engagement-banner.blade.php` : invitation à soutenir l’association.

Les variables CSS en tête de `public-site.css` définissent les couleurs principales. Les classes descriptives suivent les parties du composant (`project-card__body`, par exemple). Aucun nouveau paquet JavaScript n’est nécessaire.

## Vérification

```sh
npm run build
php tests/Smoke/frontend.php
php tests/Smoke/parrainage.php
php tests/Smoke/editorial.php
```

Les scripts Smoke utilisent SQLite en mémoire et ne modifient pas la base locale. Les paiements et notifications y sont simulés.

La compilation fonctionne dans l’environnement fourni, mais Vite 7 demande Node.js 20.19+ ou 22.12+ ; utiliser une version compatible pour les prochaines installations et déploiements.

Une validation visuelle reste à effectuer sur navigateur, notamment à 375, 768 et 1440 pixels : menu mobile, cartes avec titres longs, images réelles et formulaire de don. Vérifier également la navigation au clavier et le zoom à 200 %. Les contrôles automatiques ne remplacent pas cette validation.
