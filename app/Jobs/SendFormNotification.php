<?php

namespace App\Jobs;

use App\Mail\NouveauMessage;
use App\Mail\NouvelleCandidature;
use App\Models\Candidature;
use App\Models\Messages;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFormNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public int $timeout = 30;

    public array $backoff = [30, 120, 600, 1800];

    public function __construct(public Messages|Candidature $submission)
    {
        // L'insertion dans jobs fait partie de la transaction de la demande.
        // Le pilote sync ne doit jamais contacter SMTP pendant le formulaire.
        $this->onConnection('form-notifications')->onQueue('notifications')->beforeCommit();
    }

    public function handle(): void
    {
        if ($this->submission instanceof Messages) {
            Mail::to(config('forms.contact_recipient'))->send(new NouveauMessage($this->submission));
        } else {
            Mail::to(config('forms.application_recipient'))->send(new NouvelleCandidature($this->submission));
        }
    }
}
