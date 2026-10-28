<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\Kkiapay;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;

class KkiapayController extends Controller
{
    public function show(Donation $donation)
    {
        abort_unless($donation->provider === 'kkiapay' && Kkiapay::available(), 404);
        if ($donation->status === 'confirmed') {
            return redirect()->route('donation.success');
        }

        return view('donation.kkiapay', compact('donation'));
    }

    public function confirm(Request $request, Donation $donation, Kkiapay $kkiapay)
    {
        abort_unless($donation->provider === 'kkiapay', 404);
        $data = $request->validate(['transaction_id' => 'required|string|max:255']);
        try {
            $kkiapay->confirm($donation, $data['transaction_id']);
        } catch (ConnectionException|RequestException $e) {
            return back()->withInput()->with('error', 'La vérification est temporairement indisponible. Votre don reste en attente de confirmation.');
        }

        return redirect()->route('donation.success');
    }

    public function paymentReturn(Request $request, Donation $donation)
    {
        abort_unless($donation->provider === 'kkiapay' && Kkiapay::available(), 404);
        $data = $request->validate(['transaction_id' => 'required|string|max:255']);

        // This GET only restores the reference. Confirmation still requires the server-side API check.
        return view('donation.kkiapay', ['donation' => $donation, 'transactionId' => $data['transaction_id']]);
    }

    public function webhook(Request $request, Kkiapay $kkiapay)
    {
        if (! in_array($request->input('event'), ['transaction.success', 'transaction.failed'], true)) {
            return response('OK');
        }
        $data = $request->validate([
            'transactionId' => 'required|string|max:255',
            'partnerId' => 'required|uuid',
        ]);
        $donation = Donation::where('reference', $data['partnerId'])->where('provider', 'kkiapay')->first();
        if (! $donation) {
            return response('OK');
        }
        if ($request->input('event') === 'transaction.success') {
            // Failures propagate so Kkiapay retries when its API is unavailable.
            $kkiapay->confirm($donation, $data['transactionId'], true);
        } else {
            Donation::whereKey($donation->id)->where('status', 'pending')->update(['status' => 'failed']);
        }

        return response('OK');
    }
}
