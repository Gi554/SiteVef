# Refonte de l’interface publique VEF

La composition s’inspire du modèle partagé : grands titres, photographie immersive, alternance de sections, étapes de participation et comparaison des façons d’agir. Les couleurs restent celles de VEF : bleu, bleu marine, bleu clair et blanc. Les cartes de contribution ne sont pas des abonnements ou des tarifs ; elles conduisent aux vrais parcours du site.

## Fichiers à connaître

- `resources/views/app.blade.php` : en-tête, navigation, dialogue mobile, messages et pied de page communs.
- `resources/css/public-site.css` : palette et composants publics, avec adaptations pour ordinateur, tablette et mobile. Les styles utilisent des classes propres au site et ne ciblent pas l’administration ou les emails.
- `resources/js/public-navigation.js` : ouverture du dialogue mobile, fermeture, remise à jour de l’état accessible et gestion des menus de bureau. Le dialogue natif gère la touche Échap et la restitution du focus ; un parcours de tabulation explicite évite que le clavier sorte du menu vers la barre d’outils du navigateur.
- `resources/views/index.blade.php` : composition de l’accueil avec les vrais projets, actualités et témoignages lorsqu’ils existent.
- `resources/views/partials/home-contact.blade.php` : formulaire existant conservé, y compris le sujet prérempli pour le parrainage.
- `resources/views/parrainage/teaser.blade.php` et les composants de cartes : données provenant toujours des modèles existants.

La navigation conserve des liens réels et les FAQ utilisent `details` / `summary`. Le bouton de don reste visible sur mobile. Une entrée « Aller au contenu », des états de focus et un mode respectant `prefers-reduced-motion` sont prévus. Les montants, projets et programmes ne sont pas créés ou modifiés par cette refonte.

Les animations AOS et leur chargement depuis un CDN ont été retirés du gabarit public. Les anciens attributs `data-aos` encore présents sur certaines pages n’empêchent pas leur contenu de s’afficher. Les polices sont toujours chargées depuis Google Fonts ; les pages de confidentialité et de cookies ont été mises à jour pour décrire ce fonctionnement.

## Photographies

Les photos de présentation et d’actions déjà présentes dans le projet sont réutilisées ; les éléments qui doivent être confirmés par VEF restent listés dans `CONTENUS_A_COMPLETER.md`.

Une nouvelle photo de matériel médical remplace la petite illustration santé, trop peu définie pour les cartes :

- Fichier : `public/Style1/images/sante-illustration.webp`.
- Auteur : Etactics Inc.
- Source : https://unsplash.com/photos/medical-stethoscope-on-blue-background-JkWMM8x1sLo
- Licence : https://unsplash.com/license, consultation du 28 octobre 2026.
- Image téléchargée en WebP à 1 100 pixels de large, environ 36 Ko ; elle est servie localement, sans requête à Unsplash lors de la visite.
- Usage : illustration du domaine santé, pas photographie d’une action VEF ni preuve d’un résultat.

## Vérifier une modification

1. Compiler : `npm run build`.
2. Lancer la suite : `php artisan test --compact --fail-on-risky --fail-on-warning`.
3. Lancer les smoke tests éditorial et parrainage, qui utilisent des bases en mémoire.
4. Parcourir les pages à 320, 390, 768 et 1 440 pixels : absence de débordement, lisibilité des titres et accès aux boutons.
5. Contrôler le menu mobile au clavier : focus dans le dialogue, Échap, bouton de fermeture, retour du focus et changement de largeur.
6. Vérifier les FAQ et le formulaire de don : sélection d’un montant, état visuel choisi, saisie FCFA et équivalence affichée. Ne pas déclencher de paiement réel pour un simple contrôle graphique.

Les captures locales utilisent des images chargées avant la prise de vue ; elles restent dans le stockage privé ignoré par Git. La refonte s’appuie sur les pages de la PR de contenus #17 : tant qu’elle n’est pas fusionnée, sa PR cible la branche de contenus afin d’isoler la revue visuelle.
