# Informations à fournir par VEF

Cette liste sert à compléter le site avec des éléments vérifiés. Les pages actuelles ne contiennent ni chiffres d’impact inventés, ni partenaires fictifs, ni témoignages créés pour remplir la page. Les champs administratifs inconnus sont explicitement signalés sur les mentions légales.

## Avant publication des informations légales

| À demander | Où le renseigner / à quoi cela sert |
| --- | --- |
| Dénomination juridique exacte, forme et référence officielle d’enregistrement | Confirmer la présentation de VEF et compléter `VEF_REGISTRATION`. |
| Nom et qualité de la personne responsable de publication | `VEF_PUBLICATION_DIRECTOR`. |
| Dénomination de l’hébergeur, adresse et moyen de contact | `VEF_HOSTING_NAME`, `VEF_HOSTING_ADDRESS`, `VEF_HOSTING_CONTACT`. |
| Adresse postale complète et confirmation du téléphone / email de contact | Vérifier les coordonnées déjà présentes dans le site. |
| Prestataire et pays d’hébergement, service d’envoi des emails et pays de traitement | Compléter les destinataires et éventuels transferts dans la confidentialité. |
| Durées retenues pour les contacts, candidatures, visites, comptes et paiements | Définir une politique réelle, y compris les copies en messagerie et sauvegardes, puis mettre en place son application. Aucun effacement automatique n’est actuellement promis. |
| Personne chargée de traiter les demandes liées aux données et procédure de réponse | Confirmer le contact affiché, les accès autorisés et le traitement des demandes. |
| Situation des formalités auprès de l’APDP et cadre juridique applicable | Faire valider les textes et les formalités correspondant aux traitements réels. Aucun numéro de déclaration ou certificat de conformité n’est revendiqué. |
| Politique pour les incidents de paiement et demandes de remboursement | Compléter les conditions et la FAQ sans promettre de remboursement automatique ni de reçu fiscal. |

Les valeurs administratives sont configurables dans `.env` et restent vides dans `.env.example`. Après modification sur le serveur : `php artisan config:clear`. Les sections juridiques sont des textes de travail à faire valider avant publication, pas une attestation de conformité. L’hébergement n’est pas encore choisi : ce point reste ouvert avec la CD.

## Donner davantage de matière aux pages

| Sujet | Éléments attendus |
| --- | --- |
| Histoire de VEF | Date et contexte de création, étapes marquantes, texte validé par les responsables. |
| Équipe | Noms, fonctions, biographies courtes, photos et accord de publication. |
| Projets | Besoin constaté, lieu, dates, objectifs, publics, étapes, budget, collecte et résultats documentés. |
| Impact | Chiffres datés et sourcés, méthode de calcul, différence entre bénéficiaires directs et participations. |
| Photos et récits de terrain | Fichiers de bonne qualité, légendes, crédits et autorisations, avec attention particulière aux personnes vulnérables et mineures. |
| Témoignages | Propos authentiques, contexte, identité ou anonymisation souhaitée et autorisation de publication. |
| Partenaires | Liste confirmée, rôle de chacun, logo et autorisation de l’utiliser. |
| Transparence | Rapports d’activité, bilans financiers publiables, utilisation des fonds et explication du périmètre des collectes affichées. |
| Bénévolat / stages | Profils recherchés, tâches concrètes, modalités d’accueil, lieux, périodes et contact. Aucun agrément Service Civique n’est présumé. |
| Actualités | Textes, dates, photos et association aux projets concernés. Les articles restent publiés depuis l’administration. |

Les projets, articles, membres et témoignages se gèrent dans l’administration existante. Les textes de présentation et les FAQ sont des vues Blade et `config/public_content.php` : ils ne prétendent pas offrir une édition depuis l’administration.

## Repères de rédaction et vérifications

- Décrire le rôle du don général et du don par projet, sans inventer de ventilation financière.
- Ne pas transformer la facture Kkiapay en promesse de reçu fiscal ou d’avantage fiscal.
- Expliquer que l’association vérifie un paiement auprès du prestataire ; un don général ne nécessite pas d’accord d’un administrateur.
- Ne pas présenter le compteur de visites comme anonyme : le code actuel enregistre une adresse IP, une date et une page. Ne pas ajouter une fausse bannière de consentement qui ne contrôlerait rien.
- La carte Google intégrée est remplacée par un lien explicite ; les polices et animations externes restent à documenter et à réévaluer avant une éventuelle politique de blocage des ressources tierces.
- Les preuves chiffrées, témoignages, partenaires et documents manquants seront ajoutés quand VEF les fournira, plutôt que remplacés par des contenus de démonstration.

Sources institutionnelles consultées pour les repères de confidentialité :

- APDP Bénin, droits des personnes : https://service.apdp.bj/actualites/mes_droits
- APDP Bénin, principes et droits : https://archive.apdp.bj/proteger/
- CNIL, distinction entre cookies techniques et autres traceurs (repère européen, sans présumer son champ d’application à VEF) : https://cnil.fr/fr/cookies-et-autres-traceurs/que-dit-la-loi

Validation technique : `php artisan test --filter=PublicInformationTest`, suite Laravel complète et `npm run build`. Les tests de navigation ne remplacent pas la validation des informations administratives, de la politique de conservation ou des obligations applicables.
