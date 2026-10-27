<?php

use App\Mail\NouveauMessage;
use App\Mail\NouvelleCandidature;
use App\Models\Candidature;
use App\Models\Messages;
use Illuminate\Support\Facades\Mail;

test('contact notification preserves content and reply address in html and plain text', function () {
    $contact = new Messages([
        'prenom' => 'Aïcha', 'nom' => 'Mensah', 'email' => 'aicha@example.test',
        'objet' => 'Une action & un partenariat',
        'message' => "Bonjour l’équipe,\nJe souhaite aider.\n<script>alert('test')</script>",
    ]);
    $sent = Mail::mailer('array')->to('equipe@example.test')->send(new NouveauMessage($contact));
    $email = $sent->getOriginalMessage();
    expect($email->getSubject())->toBe('[VEF] Nouveau message : Une action & un partenariat')
        ->and($email->getReplyTo()[0]->getAddress())->toBe('aicha@example.test')
        ->and($email->getHtmlBody())->toContain('Une action &amp; un partenariat', '#messages', '&lt;script&gt;', 'Bonjour l’équipe,<br')
        ->not->toContain('<script>', '<value>', 'display: grid')
        ->and($email->getTextBody())->toContain('Une action & un partenariat', "Bonjour l’équipe,\nJe souhaite aider.", '#messages')
        ->not->toContain('&amp;', '<table', 'cid:')
        ->and($email->getAttachments())->toHaveCount(1)
        ->and($email->getAttachments()[0]->getContentType())->toBe('image/png')
        ->and($email->getAttachments()[0]->getDisposition())->toBe('inline');
});

test('application notification includes every detail and uses the shared email identity', function () {
    $candidate = new Candidature([
        'candidature_prenom' => 'Aïcha', 'candidature_nom' => 'Mensah',
        'candidature_email' => 'aicha@example.test', 'candidature_telephone' => '+229 01 97 00 00 00',
        'candidature_dateNaissance' => '1998-04-15', 'candidature_adresse' => 'Cotonou & environs',
        'candidature_typeParticipation' => 'autre', 'candidature_autreType' => 'Photographe bénévole',
        'candidature_disponibilites' => "Le samedi\nPendant les vacances",
        'candidature_preferenceAction' => 'Éducation',
        'candidature_motivation' => "Partager mon expérience.\n<img src=x onerror=alert(1)>",
    ]);
    $email = Mail::mailer('array')->to('equipe@example.test')->send(new NouvelleCandidature($candidate))->getOriginalMessage();
    expect($email->getSubject())->toBe('[VEF] Nouvelle candidature : Aïcha Mensah')
        ->and($email->getReplyTo()[0]->getAddress())->toBe('aicha@example.test')
        ->and($email->getHtmlBody())->toContain('Verre d’Eau Fraîche', 'Photographe bénévole', '15/04/1998', 'Cotonou &amp; environs', '#candidatures', 'Le samedi<br', '&lt;img')
        ->not->toContain('<img src=x', '<value>', 'display: grid')
        ->and($email->getTextBody())->toContain('Photographe bénévole', 'Cotonou & environs', '15/04/1998', "Le samedi\nPendant les vacances", '#candidatures')
        ->and($email->getAttachments())->toHaveCount(1);
});

test('the application email handles a missing birthday without inventing a date', function () {
    $candidate = new Candidature([
        'candidature_prenom' => 'Jean', 'candidature_nom' => 'Exemple',
        'candidature_email' => 'jean@example.test', 'candidature_typeParticipation' => 'benevole',
        'candidature_preferenceAction' => 'Santé', 'candidature_motivation' => 'Participer.',
    ]);
    $email = Mail::mailer('array')->to('equipe@example.test')->send(new NouvelleCandidature($candidate))->getOriginalMessage();
    expect($email->getHtmlBody())->toContain('Bénévole', 'Non renseignée')
        ->and($email->getTextBody())->toContain('Bénévole', 'Non renseignée');
});
