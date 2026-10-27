<?php

// Generate local previews from fictional data without sending emails or changing the database.
require dirname(__DIR__, 2).'/vendor/autoload.php';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
Illuminate\Support\Facades\URL::forceRootUrl('https://vef.example');

$contact = new App\Models\Messages([
    'prenom' => 'Aïcha', 'nom' => 'Mensah', 'email' => 'aicha.mensah@example.test',
    'objet' => 'Organiser une collecte de fournitures scolaires',
    'message' => "Bonjour l’équipe VEF,\n\nNous aimerions organiser une collecte de fournitures scolaires avec notre groupe de bénévoles à Cotonou. Pourriez-vous nous indiquer les besoins prioritaires et les prochaines actions prévues ?\n\nMerci pour votre engagement et à bientôt !",
]);
$candidate = new App\Models\Candidature([
    'candidature_prenom' => 'Aïcha', 'candidature_nom' => 'Mensah',
    'candidature_email' => 'aicha.mensah@example.test', 'candidature_telephone' => '+229 01 97 00 00 00',
    'candidature_dateNaissance' => '1998-04-15', 'candidature_adresse' => 'Cotonou, quartier Gbedjromédé',
    'candidature_typeParticipation' => 'benevole', 'candidature_disponibilites' => 'Les samedis et pendant les vacances scolaires.',
    'candidature_preferenceAction' => 'Éducation et accompagnement des jeunes',
    'candidature_motivation' => "Je souhaite mettre mon expérience d’animatrice au service des enfants et participer aux actions de terrain.\n\nJe serais heureuse de rencontrer votre équipe pour imaginer ensemble un premier projet.",
]);
$contact->created_at = $candidate->created_at = Illuminate\Support\Carbon::parse('2026-10-27 14:30:00');
$directory = storage_path('app/private/email-previews');
if (! is_dir($directory)) {
    mkdir($directory, 0755, true);
}
foreach (['contact' => new App\Mail\NouveauMessage($contact), 'candidature' => new App\Mail\NouvelleCandidature($candidate)] as $name => $mail) {
    file_put_contents($directory.'/'.$name.'.html', $mail->render());
    echo $directory.'/'.$name.'.html'.PHP_EOL;
}
