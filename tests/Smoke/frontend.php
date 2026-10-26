<?php

/** Public presentation checks; isolated database, no real payments or messages. */
require dirname(__DIR__, 2).'/vendor/autoload.php';

use App\Models\Projet;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
$app['env'] = 'testing';
config([
    'database.default' => 'sqlite',
    'database.connections.sqlite.database' => ':memory:',
    'session.driver' => 'array',
    'cache.default' => 'array',
]);
DB::purge();
Artisan::call('migrate', ['--force' => true]);
$kernel = $app->make(HttpKernel::class);

function page(string $path): DOMXPath
{
    global $kernel;
    $response = $kernel->handle(Request::create($path));
    if ($response->getStatusCode() !== 200) {
        throw new RuntimeException('Page inaccessible : '.$path);
    }
    $dom = new DOMDocument;
    $previous = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());
    libxml_clear_errors();
    libxml_use_internal_errors($previous);

    return new DOMXPath($dom);
}

function expectCount(DOMXPath $page, string $query, int $count): void
{
    if ($page->query($query)->length !== $count) {
        throw new RuntimeException('Unexpected node count: '.$query);
    }
}

$empty = page('/projets');
expectCount($empty, '//h1', 1);
expectCount($empty, '//*[@class="empty-state md:col-span-2 lg:col-span-3"]', 1);
foreach (['en_cours', 'a_venir', 'realise'] as $status) {
    Projet::create([
        'titre' => 'Projet '.$status,
        'slug' => 'projet-'.$status,
        'description' => 'Une action concrète.',
        'publication' => 'publie',
        'statut' => $status,
    ]);
}
expectCount(page('/projets'), '//article', 3);
foreach (['en_cours', 'a_venir', 'realise'] as $status) {
    $filtered = page('/projets?statut='.$status);
    expectCount($filtered, '//article', 1);
    expectCount($filtered, '//nav[@aria-label="Filtrer les projets"]/a[@aria-current="page"]', 1);
    expectCount($filtered, '//article//h3/a[contains(@href,"projet-'.$status.'")]', 1);
}
foreach (['/', '/projets', '/parrainage'] as $path) {
    $document = page($path);
    expectCount($document, '//h1', 1);
    expectCount($document, '//a[@href="#contenu"]', 1);
    expectCount($document, '//main[@id="contenu"]', 1);
}
$programmes = page('/parrainage');
expectCount($programmes, '//article[starts-with(@id,"programme-")]', 2);
expectCount($programmes, '//article/img[@loading="lazy"]', 2);
echo "Public frontend checks passed (empty state, filters, project links, landmarks, programmes).\n";
