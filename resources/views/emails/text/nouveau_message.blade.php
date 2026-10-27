{{-- Vue MIME texte uniquement : préserver les caractères sans échappement HTML. --}}
VERRE D’EAU FRAÎCHE
Nouveau message de contact
Reçu le {!! ($contactMessage->created_at ?? now())->format('d/m/Y à H:i') !!}

De : {!! $contactMessage->prenom !!} {!! $contactMessage->nom !!}
Email : {!! $contactMessage->email !!}
Objet : {!! $contactMessage->objet !!}

MESSAGE
{!! $contactMessage->message !!}

Consulter les messages : {!! route('admin.projets.index').'#messages' !!}

Vous pouvez répondre directement à cet email pour contacter cette personne.
Association humanitaire Verre d’Eau Fraîche — Cotonou, Bénin
{!! url('/') !!}
