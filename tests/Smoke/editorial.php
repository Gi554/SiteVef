<?php

require dirname(__DIR__, 2).'/vendor/autoload.php';
// Les quotas sont enregistrés en mémoire, comme les données de ce scénario.
putenv('CACHE_STORE=array');
$_ENV['CACHE_STORE'] = $_SERVER['CACHE_STORE'] = 'array';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$app['env'] = 'testing';
$adminPath = '/'.trim(config('admin.path'), '/');
config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'session.driver' => 'array', 'cache.default' => 'array', 'mail.default' => 'array']);
Illuminate\Support\Facades\DB::purge();
Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
echo "Migrations OK\n";
$project = App\Models\Projet::create(['titre' => 'Test technique', 'description' => 'Résumé', 'slug' => 'test-technique', 'publication' => 'publie', 'statut' => 'en_cours']);
$article = App\Models\Article::create(['titre' => 'Suivi', 'slug' => 'suivi', 'contenu' => "## Un titre\nUn texte [projet](/projets/test-technique)", 'publication' => 'publie', 'published_at' => now()]);
$article->projets()->attach($project);
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
foreach (['/', '/projets', '/projets/test-technique', '/actualites', '/actualites/suivi', '/don', '/don/merci', '/sitemap.xml'] as $path) {
    $request = Illuminate\Http\Request::create($path);
    $response = $kernel->handle($request);
    echo "$path ".$response->getStatusCode()."\n";
    if ($response->getStatusCode() !== 200) {
        file_put_contents('/tmp/editorial-error.html', $response->getContent());
        exit(1);
    }
    $kernel->terminate($request, $response);
}
$user = App\Models\User::create(['name' => 'Test', 'email' => 'test@example.test', 'password' => 'not-used']);
Illuminate\Support\Facades\Auth::login($user);
$response = $kernel->handle(Illuminate\Http\Request::create($adminPath));
if ($response->getStatusCode() !== 403) {
    throw new Exception('Non-admin must be denied');
}
echo "Non-admin forbidden OK\n";
$user->forceFill(['is_admin' => true])->save();
foreach ([$adminPath, $adminPath.'/projets/'.$project->id.'/editer', $adminPath.'/projets/'.$project->id.'/apercu', $adminPath.'/articles/creer', $adminPath.'/articles/'.$article->id.'/editer', $adminPath.'/articles/'.$article->id.'/apercu'] as $path) {
    $response = $kernel->handle(Illuminate\Http\Request::create($path));
    echo "$path ".$response->getStatusCode()."\n";
    if ($response->getStatusCode() !== 200) {
        file_put_contents('/tmp/editorial-error.html', $response->getContent());
        exit(1);
    }
}

function check($condition, $message)
{
    if (! $condition) {
        throw new RuntimeException($message);
    } echo $message." OK\n";
}
$draft = App\Models\Projet::create(['titre' => 'Confidentiel', 'slug' => 'confidentiel', 'publication' => 'brouillon', 'statut' => 'a_venir']);
check($kernel->handle(Illuminate\Http\Request::create('/projets/confidentiel'))->getStatusCode() === 404, 'Draft hidden from public');
$response = $kernel->handle(Illuminate\Http\Request::create($adminPath.'/enregistrer-projet', 'POST', [
    'titre' => 'Nouveau projet', 'publication' => 'brouillon', 'statut' => 'a_venir', 'budget' => '125.50',
    'indicateurs_texte' => 'Bénéficiaires | 25', 'etapes_texte' => 'Préparation | Recueil des besoins', 'depenses_texte' => 'Matériel | 125.50', 'faq_texte' => 'Comment aider ? | Contactez-nous', 'montants_texte' => '10, 25, 50',
]));
check($response->isRedirect(), 'Project form submitted');
$new = App\Models\Projet::where('titre', 'Nouveau projet')->firstOrFail();
check($new->budget_centimes === 12550 && $new->publication === 'brouillon' && $new->faq[0][0] === 'Comment aider ?', 'Project data persisted');
$response = $kernel->handle(Illuminate\Http\Request::create($adminPath.'/articles', 'POST', ['titre' => 'Article créé', 'publication' => 'publie', 'contenu' => '## Titre réel', 'projets' => [$new->id]]));
check($response->isRedirect(), 'Article form submitted');
$created = App\Models\Article::where('titre', 'Article créé')->firstOrFail();
check($created->projets->contains($new) && $created->published_at !== null, 'Article publication and project association');
$originalSlug = $new->slug;
$kernel->handle(Illuminate\Http\Request::create($adminPath.'/projet/'.$new->id, 'PUT', ['titre' => 'Titre modifié', 'publication' => 'publie', 'statut' => 'en_cours']));
check($new->fresh()->slug === $originalSlug, 'Project URL stable after title change');
config(['kkiapay.public_key' => 'public-test', 'kkiapay.private_key' => 'private-test', 'kkiapay.secret_key' => 'secret-test', 'kkiapay.webhook_secret' => 'webhook-test', 'kkiapay.sandbox' => true]);
$d = App\Models\Donation::create(['reference' => (string) Illuminate\Support\Str::uuid(), 'projet_id' => $project->id, 'amount_centimes' => 2500, 'amount_xof' => 16400, 'currency' => 'eur', 'status' => 'pending']);
Illuminate\Support\Facades\Http::preventStrayRequests();
Illuminate\Support\Facades\Http::fake(['api-sandbox.kkiapay.me/*' => Illuminate\Support\Facades\Http::response(['status' => 'SUCCESS', 'amount' => 16400, 'partnerId' => $d->reference])]);
$payload = json_encode(['event' => 'transaction.success', 'transactionId' => 'tx_test_123', 'partnerId' => $d->reference]);
function webhook($kernel, $payload, $secret)
{
    return $kernel->handle(Illuminate\Http\Request::create('/kkiapay/webhook', 'POST', [], [], [], ['HTTP_X_KKIAPAY_SECRET' => $secret, 'CONTENT_TYPE' => 'application/json'], $payload));
}
check(webhook($kernel, $payload, 'invalid')->getStatusCode() === 401 && $d->fresh()->status === 'pending', 'Invalid webhook rejected');
check(webhook($kernel, $payload, 'webhook-test')->getStatusCode() === 200 && $d->fresh()->status === 'confirmed', 'Verified Kkiapay payment confirmed');
webhook($kernel, $payload, 'webhook-test');
check($project->collected === 2500, 'Duplicate webhook does not double collection');
$kernel->handle(Illuminate\Http\Request::create('/don/merci?transaction_id=untrusted'));
check($project->collected === 2500, 'Browser return cannot change collection');
Illuminate\Support\Facades\Auth::logout();
check($kernel->handle(Illuminate\Http\Request::create($adminPath.'/projet/'.$project->id, 'DELETE'))->isRedirect(), 'Anonymous deletion blocked');
check(App\Models\Projet::find($project->id) !== null, 'Project retained after unauthorized deletion');
$ordinary = App\Models\User::create(['name' => 'Ordinary', 'email' => 'ordinary@example.test', 'password' => 'not-used']);
Illuminate\Support\Facades\Auth::login($ordinary);
foreach ([[$adminPath.'/projet/'.$project->id, 'PUT'], [$adminPath.'/articles/'.$article->id, 'DELETE'], [$adminPath.'/projet/'.$project->id, 'DELETE']] as [$path, $method]) {
    check($kernel->handle(Illuminate\Http\Request::create($path, $method))->getStatusCode() === 403, 'Ordinary account blocked: '.$method.' '.$path);
}
Illuminate\Support\Facades\Auth::login($user);
$request = Illuminate\Http\Request::create($adminPath.'/articles', 'POST', ['titre' => 'Upload interdit', 'contenu' => 'Texte', 'publication' => 'brouillon'], [], ['image' => Illuminate\Http\UploadedFile::fake()->create('script.php', 1, 'application/x-httpd-php')], ['HTTP_ACCEPT' => 'application/json']);
check($kernel->handle($request)->getStatusCode() === 422, 'Executable upload rejected');
check(! str_contains(App\Services\ArticleText::line('<script>alert(1)</script>'), ' <script>') && ! str_contains(App\Services\ArticleText::line('<script>alert(1)</script>'), '<script>'), 'Article HTML escaped');
check(App\Services\Money::cents(655957, 'xof') === 100000, 'FCFA conversion uses the official parity');
check(App\Services\Money::cents(10, 'eur') === 1000, 'Euro conversion preserves cents');
check(App\Services\Money::fcfa(10) === '6 560 FCFA', 'FCFA display rounded to whole francs');
Illuminate\Support\Facades\Auth::logout();
$response = $kernel->handle(Illuminate\Http\Request::create('/projets/test-technique'));
check(! str_contains($response->getContent(), 'id="don"'), 'Project donation section hidden when collection is closed');
$project->update(['dons_actifs' => true]);
$response = $kernel->handle(Illuminate\Http\Request::create('/projets/test-technique'));
check(str_contains($response->getContent(), 'name="amount_currency"') && str_contains($response->getContent(), 'FCFA / XOF'), 'Project donation form offers euros and FCFA');
echo "Editorial smoke checks passed; database existed only in memory.\n";
