<?php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class Kkiapay
{
    public static function available(): bool
    {
        return (bool) (config('kkiapay.public_key') && config('kkiapay.private_key') && config('kkiapay.secret_key'));
    }

    public function confirm(Donation $donation, string $transactionId, bool $trustedWebhook = false): void
    {
        abort_unless(self::available(), 503);
        $base = config('kkiapay.sandbox') ? 'https://api-sandbox.kkiapay.me' : 'https://api.kkiapay.me';
        $transaction = Http::acceptJson()->withHeaders([
            'X-API-KEY' => config('kkiapay.public_key'),
            'X-PRIVATE-KEY' => config('kkiapay.private_key'),
            'X-SECRET-KEY' => config('kkiapay.secret_key'),
        ])->connectTimeout(5)->timeout(15)->post($base.'/api/v1/transactions/status', [
            'transactionId' => $transactionId,
        ])->throw()->json();

        $reference = data_get($transaction, 'partnerId')
            ?? data_get($transaction, 'stateData.donation_reference');
        // Browser parameters never authorize a payment; the API must bind it to this donation.
        if (data_get($transaction, 'status') !== 'SUCCESS'
            || ! is_numeric(data_get($transaction, 'amount'))
            || (float) $transaction['amount'] !== (float) $donation->amount_xof
            || (isset($transaction['currency']) && strtoupper($transaction['currency']) !== 'XOF')
            || ($reference !== $donation->reference && ! ($trustedWebhook && $reference === null))) {
            throw ValidationException::withMessages(['payment' => 'Ce paiement ne correspond pas au don ou n’est pas encore confirmé.']);
        }

        DB::transaction(function () use ($donation, $transactionId) {
            $donation = Donation::whereKey($donation->id)->lockForUpdate()->firstOrFail();
            if ($donation->provider !== 'kkiapay'
                || ($donation->kkiapay_transaction_id && $donation->kkiapay_transaction_id !== $transactionId)
                || Donation::where('kkiapay_transaction_id', $transactionId)->where('id', '!=', $donation->id)->exists()) {
                throw ValidationException::withMessages(['payment' => 'Cette transaction est déjà associée à un autre don.']);
            }
            if ($donation->status !== 'confirmed') {
                $donation->update(['kkiapay_transaction_id' => $transactionId, 'status' => 'confirmed', 'confirmed_at' => now()]);
            }
        });
    }
}
