<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Membre;
use App\Models\Messages;
use App\Models\Projet;
use App\Models\Temoignage;
use App\Models\Visite;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $projets = Projet::with('images')->get();

        $messages = Messages::orderBy('created_at', 'desc')->get();
        $candidatures = Candidature::orderBy('created_at', 'desc')->get();
        $temoignages = Temoignage::orderBy('created_at', 'desc')->get();
        $membres = Membre::orderBy('ordre')->orderBy('created_at')->get();

        // Statistiques visiteurs
        $today = Carbon::today();
        $debutMois = Carbon::now()->startOfMonth();
        $debutMoisPrec = Carbon::now()->subMonth()->startOfMonth();
        $finMoisPrec = Carbon::now()->subMonth()->endOfMonth();

        $visiteursAujourdhui = Visite::whereDate('date', $today)->count();
        $visiteursMoisActuel = Visite::whereBetween('date', [$debutMois, Carbon::now()])->count();
        $visiteursMoisPrec = Visite::whereBetween('date', [$debutMoisPrec, $finMoisPrec])->count();

        $evolutionVisiteurs = $visiteursMoisPrec > 0
            ? round((($visiteursMoisActuel - $visiteursMoisPrec) / $visiteursMoisPrec) * 100, 1)
            : ($visiteursMoisActuel > 0 ? 100 : 0);

        // Données pour le graphique — 12 derniers mois
        $graphLabels = [];
        $graphDonnees = [];
        for ($i = 11; $i >= 0; $i--) {
            $mois = Carbon::now()->subMonths($i);
            $debut = $mois->copy()->startOfMonth();
            $fin = $mois->copy()->endOfMonth();
            $graphLabels[] = $mois->isoFormat('MMM YYYY');
            $graphDonnees[] = Visite::whereBetween('date', [$debut, $fin])->count();
        }

        $stats = [
            'projets' => $projets->count(),
            'messages' => $messages->count(),
            'messages_non_lus' => $messages->where('lu', false)->count(),
            'candidatures' => $candidatures->count(),
            'temoignages' => $temoignages->count(),
            'membres' => $membres->count(),
            'visiteurs_jour' => $visiteursAujourdhui,
            'visiteurs_mois' => $visiteursMoisActuel,
            'visiteurs_mois_prec' => $visiteursMoisPrec,
            'evolution_visiteurs' => $evolutionVisiteurs,
        ];

        $articles = \App\Models\Article::with('category')->latest()->get();
        $categories = \App\Models\Category::orderBy('nom')->get();
        $donations = \App\Models\Donation::with('projet')->latest()->paginate(30);
        $confirmed = \App\Models\Donation::where('status', 'confirmed')->sum('amount_centimes');
        $confirmedXof = \App\Models\Donation::where('status', 'confirmed')->sum('amount_xof');
        $pending = \App\Models\Donation::where('status', 'pending')->count();
        $recentDonations = \App\Models\Donation::with('projet')->latest()->limit(5)->get();

        return view('admin.admin', compact('articles', 'categories', 'donations', 'projets', 'messages', 'candidatures', 'temoignages', 'membres', 'stats', 'graphLabels', 'graphDonnees', 'confirmed', 'confirmedXof', 'pending', 'recentDonations'));
    }

    public function store(Request $request)
    {
        return $this->saveProject($request, new Projet);
    }

    public function update(Request $request, int $id)
    {
        return $this->saveProject($request, Projet::with('images')->findOrFail($id));
    }

    private function saveProject(Request $request, Projet $projet)
    {
        $data = \App\Services\EditorialData::project($request, $projet->exists ? $projet : null);
        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $projet, $data) {
            $projet->fill($data)->save();
            $removed = $request->input('supprimer_images', []);
            $projet->images()->whereIn('id', $removed)->delete();
            $order = (int) $projet->images()->max('ordre');
            foreach ($request->file('images', []) as $file) {
                $projet->images()->create(['chemin' => \App\Services\EditorialData::upload($file, 'imagesProjets'), 'ordre' => ++$order]);
            }
            $projet->image = $projet->images()->first()?->chemin ?? ($removed ? null : $projet->image);
            if ($request->filled('couverture_id')) {
                $cover = $projet->images()->find($request->couverture_id);
                if ($cover) {
                    foreach ($projet->images()->get() as $i => $photo) {
                        $photo->update(['ordre' => $i + 1]);
                    } $cover->update(['ordre' => 0]);
                    $projet->image = $cover->chemin;
                }
            } elseif ($projet->images()->exists()) {
                $projet->image = $projet->images()->first()->chemin;
            } elseif ($removed) {
                $projet->image = null;
            }
            $projet->save();
        });

        return redirect()->route('admin.projets.edit', $projet)->with('success', 'Projet enregistré.');
    }

    public function destroy(int $id)
    {
        $projet = Projet::findOrFail($id);
        $projet->delete();

        return redirect()->back()->with('success', 'Projet supprimé avec succès !');
    }

    public function toggleRead(int $id)
    {
        $message = Messages::findOrFail($id);
        $message->lu = ! $message->lu;
        $message->save();

        return response()->json([
            'success' => true,
            'lu' => $message->lu,
        ]);
    }
}
