@extends('emails.layout', [
    'emailTitle' => 'Un nouveau message pour VEF',
    'preheader' => $contactMessage->prenom.' '.$contactMessage->nom.' vous contacte : '.$contactMessage->objet,
    'category' => 'Prendre contact',
    'receivedAt' => $contactMessage->created_at ?? now(),
    'intro' => 'Une personne souhaite échanger avec l’association. Retrouvez ses coordonnées et son message ci-dessous.',
    'actionUrl' => route('admin.projets.index').'#messages',
    'actionLabel' => 'Consulter les messages',
    'senderLabel' => 'cette personne',
    'formLabel' => 'de contact',
])

@section('email_content')
    <x-emails.field label="De">{{ $contactMessage->prenom }} {{ $contactMessage->nom }}</x-emails.field>
    <x-emails.field label="Email"><a href="mailto:{{ $contactMessage->email }}" style="color:#1d4ed8;text-decoration:underline;">{{ $contactMessage->email }}</a></x-emails.field>
    <x-emails.field label="Objet">{{ $contactMessage->objet }}</x-emails.field>
    <x-emails.message label="Son message" :text="$contactMessage->message" />
@endsection
