<?php

use App\Mail\NouveauMessage;
use App\Mail\NouvelleCandidature;
use App\Models\Candidature;
use App\Models\Messages;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Mail::fake();
    config(['forms.contact_recipient' => 'contact@example.test', 'forms.application_recipient' => 'candidatures@example.test']);
});

function contactSubmission(): array
{
    return ['nom' => 'Martin', 'prenom' => 'Camille', 'email' => 'camille@example.test', 'objet' => 'Bénévolat', 'message' => 'Comment aider votre association ?'];
}

function applicationSubmission(): array
{
    return [
        'nom' => 'Martin', 'prenom' => 'Camille', 'email' => 'camille@example.test',
        'dateNaissance' => '1995-02-03', 'telephone' => '0600000000', 'adresse' => 'Paris',
        'typeParticipation' => 'benevole', 'disponibilites' => 'Le samedi',
        'preferenceAction' => 'locale', 'motivation' => 'Donner un peu de mon temps.', 'rgpd' => '1',
    ];
}

function processOneFormNotification($test): void
{
    $test->artisan('queue:work', [
        'connection' => 'form-notifications', '--queue' => 'notifications', '--once' => true,
        '--tries' => 5, '--timeout' => 30,
    ])->assertSuccessful();
}

test('contact is accepted and queued durably without sending SMTP from the request', function () {
    $this->post(route('message'), contactSubmission())->assertRedirect(route('index'))->assertSessionHas('success');
    expect(Messages::count())->toBe(1)->and(DB::table('jobs')->where('queue', 'notifications')->count())->toBe(1);
    Mail::assertNothingSent();
    processOneFormNotification($this);
    Mail::assertSent(NouveauMessage::class, fn ($mail) => $mail->hasTo('contact@example.test') && $mail->contactMessage->id === Messages::sole()->id);
    expect(DB::table('jobs')->count())->toBe(0);
});

test('application is accepted and its queued email uses the configured recipient', function () {
    $this->post(route('candidature.store'), applicationSubmission())->assertRedirect()->assertSessionHas('success');
    expect(Candidature::count())->toBe(1)->and(DB::table('jobs')->count())->toBe(1);
    Mail::assertNothingSent();
    processOneFormNotification($this);
    Mail::assertSent(NouvelleCandidature::class, fn ($mail) => $mail->hasTo('candidatures@example.test'));
});

test('a worker retries SMTP failure without inserting the form again', function () {
    $this->post(route('message'), contactSubmission())->assertRedirect();
    Mail::shouldReceive('to')->with('contact@example.test')->andReturnSelf();
    Mail::shouldReceive('send')->once()->andThrow(new RuntimeException('SMTP indisponible (test)'));
    processOneFormNotification($this);
    $job = DB::table('jobs')->sole();
    expect($job->attempts)->toBe(1)->and($job->reserved_at)->toBeNull()
        ->and($job->available_at)->toBeGreaterThan(now()->timestamp)
        ->and(Messages::count())->toBe(1);
    Mail::swap(new MailManager($this->app));
    Mail::fake();
    $this->travel(31)->seconds();
    processOneFormNotification($this);
    Mail::assertSent(NouveauMessage::class, 1);
    expect(Messages::count())->toBe(1)->and(DB::table('jobs')->count())->toBe(0);
});

test('failure to persist the notification rolls back the whole submission', function () {
    Queue::shouldReceive('connection')->with('form-notifications')->andThrow(new RuntimeException('File indisponible (test)'));
    $this->postJson(route('message'), contactSubmission())->assertServerError();
    expect(Messages::count())->toBe(0)->and(DB::table('jobs')->count())->toBe(0);
    Mail::assertNothingSent();
});

test('public form contents are bounded before saving or scheduling notifications', function () {
    $this->postJson(route('message'), array_replace(contactSubmission(), ['message' => str_repeat('a', 10001)]))
        ->assertUnprocessable()->assertJsonValidationErrors('message');
    $this->postJson(route('candidature.store'), array_replace(applicationSubmission(), ['motivation' => str_repeat('a', 10001), 'disponibilites' => str_repeat('a', 2001)]))
        ->assertUnprocessable()->assertJsonValidationErrors(['motivation', 'disponibilites']);
    expect(Messages::count())->toBe(0)->and(Candidature::count())->toBe(0)->and(DB::table('jobs')->count())->toBe(0);
});
