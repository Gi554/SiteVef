<?php

namespace App\Http\Controllers;

use App\Mail\NouveauMessage;
use App\Models\Membre;
use App\Models\Messages;
use App\Models\ProgrammeParrainage;
use App\Models\Projet;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ControllerAccueil extends Controller
{
    public function index(Request $request)
    {
        $projets = Projet::published()->with('images')->get();
        $temoignages = Temoignage::where('actif', true)->orderBy('created_at', 'desc')->get();

        $actualites = \App\Models\Article::published()->with('category')->latest('published_at')->limit(3)->get();

        $programmesParrainage = ProgrammeParrainage::orderBy('id')->get();
        $requestedProgramme = $request->query('parrainage');
        $contactProgramme = is_string($requestedProgramme)
            ? $programmesParrainage->firstWhere('code', $requestedProgramme)
            : null;

        return view('index', compact(
            'projets', 'temoignages', 'actualites', 'programmesParrainage', 'contactProgramme'
        ));
    }

    public function equipe()
    {
        $membres = Membre::where('actif', true)
            ->orderBy('ordre')
            ->orderBy('created_at')
            ->get();

        // Postes uniques pour les boutons filtres (triés alphabétiquement)
        $postes = $membres->pluck('poste')->unique()->sort()->values();

        return view('equipe', compact('membres', 'postes'));
    }

    public function message(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'objet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contactMessage = Messages::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'objet' => $request->objet,
            'message' => $request->message,
            'lu' => false,
        ]);

        Mail::to('contact@verredeaufraiche.com')->send(new NouveauMessage($contactMessage));

        return redirect()->route('index')->with('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
    }
}
