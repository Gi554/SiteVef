<?php

/**
 * Vérifie le parcours de parrainage sans utiliser la base locale ni envoyer d’e-mail.
 * Exécution : php tests/Smoke/parrainage.php
 */
require dirname(__DIR__, 2).'/vendor/autoload.php';

use App\Mail\NouveauMessage;
use App\Models\Donation;
use App\Models\Messages;
use App\Models\ProgrammeParrainage;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

// Les quotas sont enregistrés en mémoire, comme les données de ce scénario.
putenv('CACHE_STORE=array');
$_ENV['CACHE_STORE'] = $_SERVER['CACHE_STORE'] = 'array';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(ConsoleKernel::class)->bootstrap();
$app['env'] = 'testing';
$adminPath = '/'.trim(config('admin.path'), '/');
config([
    'database.default' => 'sqlite',
    'database.connections.sqlite.database' => ':memory:',
    'session.driver' => 'array',
    'cache.default' => 'array',
    'mail.default' => 'array',
]);
DB::purge();
Artisan::call('migrate', ['--force' => true]);
Mail::fake();
$kernel = $app->make(HttpKernel::class);

function verify(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException($message);
    }

    echo $message." : OK\n";
}

function sendRequest(string $path, string $method = 'GET', array $data = [], array $files = [])
{
    global $kernel;

    return $kernel->handle(Request::create(
        $path, $method, $data, [], $files, ['HTTP_ACCEPT' => 'application/json']
    ));
}

function document(string $html): DOMXPath
{
    $dom = new DOMDocument;
    $previous = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8">'.$html);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);

    return new DOMXPath($dom);
}

$enfant = ProgrammeParrainage::where('code', ProgrammeParrainage::ENFANT)->firstOrFail();
$sante = ProgrammeParrainage::where('code', ProgrammeParrainage::SANTE)->firstOrFail();
verify($enfant->montant_mensuel_centimes === 2500 && $sante->montant_mensuel_centimes === null, 'Programmes initiaux conformes');

$response = sendRequest('/parrainage');
verify($response->getStatusCode() === 200, 'Page publique accessible');
$html = $response->getContent();
$xpath = document($html);
verify($xpath->query('//h1')->length === 1, 'Un titre principal unique');
verify(str_contains($html, '16 399 FCFA') && str_contains($html, '25 €'), 'Montant enfant et équivalent FCFA');
verify($xpath->query('//form')->length === 0, 'Parcours de présentation sans formulaire de paiement');
foreach ([$enfant, $sante] as $programme) {
    verify($xpath->query('//a[@href="'.$programme->contact_url.'"]')->length === 1, 'Contact dédié : '.$programme->code);
    $contact = sendRequest('/?parrainage='.$programme->code);
    $contactDocument = document($contact->getContent());
    verify($contactDocument->query('//input[@name="objet"]')->item(0)->getAttribute('value') === $programme->contact_subject, 'Objet de contact prérempli : '.$programme->code);
}
verify(sendRequest('/?parrainage[]=enfant')->getStatusCode() === 200, 'Paramètre mal formé ignoré');
verify(! str_contains(sendRequest('/?parrainage=inconnu')->getContent(), 'Votre demande concerne :'), 'Programme inconnu sans préremplissage');
verify(str_contains(sendRequest('/sitemap.xml')->getContent(), '/parrainage'), 'Parrainage dans le sitemap');

$subject = $enfant->contact_subject;
$response = sendRequest('/accueilMessage', 'POST', [
    'nom' => 'Test', 'prenom' => 'Parrainage', 'email' => 'parrainage@example.test',
    'objet' => $subject, 'message' => 'Je souhaite des renseignements sur ce programme.',
]);
verify($response->isRedirect() && Messages::where('objet', $subject)->exists(), 'Demande enregistrée dans les messages existants');
verify(Mail::getFacadeRoot()->sent(NouveauMessage::class)->count() === 1, 'Notification transmise au mail existant (simulée)');
verify(Donation::count() === 0, 'Aucune transaction créée par la prise de contact');

verify(sendRequest($adminPath.'/parrainage')->getStatusCode() === 401, 'Admin interdit aux visiteurs');
$user = User::create(['name' => 'Test', 'email' => 'admin-parrainage@example.test', 'password' => 'unused']);
Auth::login($user);
verify(sendRequest($adminPath.'/parrainage')->getStatusCode() === 403, 'Admin interdit aux comptes ordinaires');
verify(sendRequest($adminPath.'/parrainage/enfant', 'PUT', [])->getStatusCode() === 403, 'Modification interdite aux comptes ordinaires');
$user->forceFill(['is_admin' => true])->save();
verify(sendRequest($adminPath.'/parrainage')->getStatusCode() === 200, 'Formulaires accessibles aux administrateurs');

$fields = ['titre' => 'Accompagner un enfant', 'accroche' => 'L’école et les repas.', 'description' => 'Un accompagnement préparé avec notre équipe.', 'montant_mensuel' => '25.50'];
$response = sendRequest($adminPath.'/parrainage/enfant', 'PUT', $fields + ['code' => 'sante']);
verify($response->isRedirect() && $enfant->fresh()->montant_mensuel_centimes === 2550, 'Montant enregistré au centime');
verify($enfant->fresh()->code === ProgrammeParrainage::ENFANT, 'Identifiant du parcours protégé');
verify(str_contains(sendRequest('/parrainage')->getContent(), '25,50 €'), 'Modification immédiatement publique');
verify(str_contains(sendRequest('/')->getContent(), '25,50 €'), 'Accueil alimenté par les mêmes données');
verify(sendRequest($adminPath.'/parrainage/enfant', 'PUT', array_replace($fields, ['montant_mensuel' => '0']))->getStatusCode() === 422, 'Montant nul refusé');
verify(sendRequest($adminPath.'/parrainage/enfant', 'PUT', array_replace($fields, ['montant_mensuel' => '25.555']))->getStatusCode() === 422, 'Précision monétaire validée');
verify(sendRequest($adminPath.'/parrainage/sante', 'PUT', $fields)->getStatusCode() === 422, 'Programme santé conservé à montant libre');
verify(sendRequest($adminPath.'/parrainage/enfant', 'PUT', $fields, ['image' => UploadedFile::fake()->create('script.php', 1)])->getStatusCode() === 422, 'Fichier exécutable refusé');
verify(sendRequest($adminPath.'/parrainage/enfant', 'PUT', $fields, ['image' => UploadedFile::fake()->image('photo.jpg')])->getStatusCode() === 422, 'Description obligatoire pour une nouvelle photo');

$photoPath = null;
try {
    $response = sendRequest($adminPath.'/parrainage/enfant', 'PUT', $fields + ['image_alt' => 'Une action de l’association.'], ['image' => UploadedFile::fake()->image('photo.jpg')]);
    $photoPath = $enfant->fresh()->image;
    verify($response->isRedirect() && is_file(public_path($photoPath)), 'Import photo enregistré avec un nom généré');
    verify(! str_contains($photoPath, 'photo.jpg'), 'Nom original du fichier non réutilisé');
} finally {
    // Nettoie uniquement le fichier créé par cette vérification.
    if ($photoPath && str_starts_with($photoPath, 'Style1/imagesParrainage/')) {
        unlink(public_path($photoPath));
    }
}

echo "Vérifications terminées ; base exclusivement en mémoire.\n";
