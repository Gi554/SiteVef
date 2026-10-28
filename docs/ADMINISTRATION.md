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
