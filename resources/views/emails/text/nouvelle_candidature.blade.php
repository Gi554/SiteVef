{{-- Vue MIME texte uniquement : préserver les caractères sans échappement HTML. --}}
VERRE D’EAU FRAÎCHE
Nouvelle candidature
Reçue le {!! ($candidature->created_at ?? now())->format('d/m/Y à H:i') !!}

Nom complet : {!! $candidature->candidature_prenom !!} {!! $candidature->candidature_nom !!}
Participation : {!! ['benevole' => 'Bénévole', 'stagiaire' => 'Stagiaire', 'autre' => 'Autre'][$candidature->candidature_typeParticipation] ?? ucfirst($candidature->candidature_typeParticipation) !!}
@if($candidature->candidature_typeParticipation === 'autre' && $candidature->candidature_autreType)
Précision : {!! $candidature->candidature_autreType !!}
@endif
Email : {!! $candidature->candidature_email !!}
Téléphone : {!! $candidature->candidature_telephone !!}
Date de naissance : {!! $candidature->candidature_dateNaissance ? \Carbon\Carbon::parse($candidature->candidature_dateNaissance)->format('d/m/Y') : 'Non renseignée' !!}
Adresse : {!! $candidature->candidature_adresse !!}
Disponibilités : {!! $candidature->candidature_disponibilites !!}
Préférence d’action : {!! ucfirst($candidature->candidature_preferenceAction) !!}

MOTIVATION
{!! $candidature->candidature_motivation !!}

Consulter les candidatures : {!! route('admin.projets.index').'#candidatures' !!}

Vous pouvez répondre directement à cet email pour contacter cette personne.
Association humanitaire Verre d’Eau Fraîche — Cotonou, Bénin
{!! url('/') !!}
