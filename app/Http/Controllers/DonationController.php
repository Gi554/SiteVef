<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Projet;
use App\Services\Kkiapay;
use App\Services\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function index()
    {
        return view('donation.index');
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'amount' => 'nullable|numeric|min:1|max:100000',
            'custom_amount' => 'nullable|numeric|min:1|max:65595700',
            'amount_currency' => 'nullable|in:eur,xof',
            'projet_id' => 'nullable|integer|exists:projets,id',
            'provider' => 'nullable|in:kkiapay',
        ]);
        $custom = $data['custom_amount'] ?? null;
        $centimes = $custom !== null
            ? Money::cents((float) $custom, $data['amount_currency'] ?? 'eur')
            : Money::cents((float) ($data['amount'] ?? 0));
        if ($centimes < 100 || $centimes > 10000000) {
            return back()->withInput()->with('error', 'Choisissez un montant entre 1 € et 100 000 € (ou son équivalent en FCFA).');
        }
        $project = null;
        if (! empty($data['projet_id'])) {
            $project = Projet::published()->where('dons_actifs', true)->findOrFail($data['projet_id']);
        }
        if (! Kkiapay::available()) {
            return back()->withInput()->with('error', 'Le paiement Kkiapay est momentanément indisponible.');
        }
        $xof = $custom !== null && ($data['amount_currency'] ?? 'eur') === 'xof'
            ? (int) round((float) $custom)
            : (int) round($centimes / 100 * Money::XOF_PER_EUR);
        $donation = Donation::create([
            'reference' => (string) Str::uuid(), 'projet_id' => $project?->id,
            'amount_centimes' => Money::cents($xof, 'xof'),
            'currency' => 'eur', 'status' => 'pending', 'provider' => 'kkiapay', 'amount_xof' => $xof,
        ]);

        return redirect(URL::temporarySignedRoute('donation.kkiapay', now()->addHours(24), ['donation' => $donation->reference]));
    }

    public function success()
    {
        return view('donation.success');
    }
}
