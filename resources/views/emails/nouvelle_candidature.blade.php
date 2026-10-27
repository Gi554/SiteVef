@extends('emails.layout', [
    'emailTitle' => 'Une nouvelle envie de s’engager',
    'preheader' => $candidature->candidature_prenom.' '.$candidature->candidature_nom.' souhaite rejoindre Verre d’Eau Fraîche.',
    'category' => 'Rejoindre l’association',
    'receivedAt' => $candidature->created_at ?? now(),
    'intro' => 'Une nouvelle candidature vous attend. Voici les informations utiles pour faire connaissance et préparer un premier échange.',
    'actionUrl' => route('admin.projets.index').'#candidatures',
    'actionLabel' => 'Consulter les candidatures',
    'senderLabel' => 'cette personne',
    'formLabel' => 'de candidature',
])

@section('email_content')
    <x-emails.field label="Nom complet">{{ $candidature->candidature_prenom }} {{ $candidature->candidature_nom }}</x-emails.field>
    <x-emails.field label="Type de participation">
        <span style="display:inline-block;padding:4px 12px;background-color:#dbeafe;border-radius:6px;color:#1d4ed8;font-size:13px;line-height:21px;font-weight:bold;">{{ ['benevole' => 'Bénévole', 'stagiaire' => 'Stagiaire', 'autre' => 'Autre'][$candidature->candidature_typeParticipation] ?? ucfirst($candidature->candidature_typeParticipation) }}</span>
        @if($candidature->candidature_typeParticipation === 'autre' && $candidature->candidature_autreType)
            <br>{{ $candidature->candidature_autreType }}
        @endif
    </x-emails.field>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;table-layout:fixed;">
        <tr>
            <td class="email-column" width="55%" valign="top" style="width:55%;padding-right:18px;">
                <x-emails.field label="Email"><a href="mailto:{{ $candidature->candidature_email }}" style="color:#1d4ed8;text-decoration:underline;">{{ $candidature->candidature_email }}</a></x-emails.field>
            </td>
            <td class="email-column" width="45%" valign="top" style="width:45%;">
                <x-emails.field label="Téléphone">{{ $candidature->candidature_telephone }}</x-emails.field>
            </td>
        </tr>
    </table>
    <x-emails.field label="Date de naissance">{{ $candidature->candidature_dateNaissance ? \Carbon\Carbon::parse($candidature->candidature_dateNaissance)->format('d/m/Y') : 'Non renseignée' }}</x-emails.field>
    <x-emails.field label="Adresse">{{ $candidature->candidature_adresse }}</x-emails.field>
    <x-emails.field label="Disponibilités">{!! nl2br(e($candidature->candidature_disponibilites)) !!}</x-emails.field>
    <x-emails.field label="Préférence d’action">{{ ucfirst($candidature->candidature_preferenceAction) }}</x-emails.field>
    <x-emails.message label="Sa motivation" :text="$candidature->candidature_motivation" />
@endsection
