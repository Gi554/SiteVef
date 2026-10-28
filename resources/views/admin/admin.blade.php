<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administration — Verre d'Eau Fraîche</title>
    <link rel="icon" type="image/png" href="{{ asset('Style1/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Style1/css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <style>
        /* ── Améliorations admin ─────────────────────────────────── */
        .badge-unread {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 20px; height: 20px; padding: 0 6px;
            background: #ef4444; color: #fff; border-radius: 999px;
            font-size: 11px; font-weight: 700; margin-left: 6px;
        }
        .msg-row.unread td { font-weight: 600; background: #eff6ff; }
        .msg-row.read td   { color: #6b7280; }
        .toggle-read-btn {
            padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;
            cursor: pointer; border: 1px solid; transition: all .2s;
        }
        .toggle-read-btn.unread { background:#dbeafe; color:#1d4ed8; border-color:#bfdbfe; }
        .toggle-read-btn.read   { background:#f3f4f6; color:#6b7280; border-color:#e5e7eb; }
        .stat-card { position: relative; }
        .stat-label { font-size: 13px; color: #6b7280; margin-top: 4px; }
        .alert-success {
            background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46;
            padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;
        }
        .temoignage-item {
            display: flex; gap: 16px; align-items: flex-start;
            background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px;
            padding: 16px; margin-bottom: 12px;
        }
        .temoignage-item blockquote { flex: 1; font-style: italic; color: #374151; margin: 0; }
        .temoignage-item .meta { font-size: 13px; color: #6b7280; margin-top: 6px; }
        .candidature-detail { font-size: 13px; line-height: 1.7; }
        .tag {
            display: inline-block; padding: 2px 10px; border-radius: 999px;
            font-size: 12px; font-weight: 600;
        }
        .tag-stage         { background:#dbeafe; color:#1d4ed8; }
        .tag-benevole      { background:#dcfce7; color:#15803d; }
        .tag-autre         { background:#f3e8ff; color:#7c3aed; }
        .tag-locale        { background:#fef9c3; color:#92400e; }
        .tag-nationale     { background:#ffedd5; color:#9a3412; }
        .tag-internationale{ background:#dbeafe; color:#1e40af; }
        .sidebar-footer a  { color: #93c5fd; }
        .section-divider   { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        .stat-card-visitors { background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%); color: #fff; }
        .stat-card-visitors h3 { color: rgba(255,255,255,0.85); }
        .stat-card-visitors .stat-number { color: #fff; }
        .stat-card-visitors .stat-label  { color: rgba(255,255,255,0.7); }
        .evolution-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; margin-top: 4px;
        }
        .evolution-positive { background: #d1fae5; color: #065f46; }
        .evolution-negative { background: #fee2e2; color: #991b1b; }
        .evolution-neutral  { background: #f3f4f6; color: #6b7280; }
        .chart-card {
            background: #fff; border-radius: 12px; border: 1px solid #e5e7eb;
            padding: 24px; margin-top: 28px; box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .chart-card h2 { font-size: 16px; font-weight: 700; color: #111827; margin: 0 0 20px; }
    </style>
<link rel="stylesheet" href="{{ asset('Style1/css/editorial-admin.css') }}">
<link rel="stylesheet" href="{{ asset('Style1/css/project-status.css') }}">
</head>
<body>
<div class="admin-container">

    {{-- ── Sidebar ──────────────────────────────────────────────────── --}}
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('Style1/images/logo.png') }}" alt="VEF" style="height:48px; filter: brightness(0) invert(1); margin-bottom:8px;">
            <p style="color:#bfdbfe; font-size:13px;">Administration</p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="#" class="nav-item active" data-panel="dashboard">
                    <span class="nav-icon">📊</span><span>Dashboard</span>
                </a></li>
                <li><a href="#" class="nav-item" data-panel="projets">
                    <span class="nav-icon">📁</span><span>Projets</span>
                </a></li>
                <li><a href="#" class="nav-item" data-panel="membres">
                    <span class="nav-icon">👥</span>
                    <span>Notre Équipe</span>
                    @if($stats['membres'] > 0)
                        <span class="badge-unread" style="background:#2563eb;">{{ $stats['membres'] }}</span>
                    @endif
                </a></li>
                <li><a href="#" class="nav-item" data-panel="temoignages">
                    <span class="nav-icon">💬</span><span>Témoignages</span>
                </a></li>
                <li><a href="#" class="nav-item" data-panel="messages">
                    <span class="nav-icon">📧</span>
                    <span>Messages reçus</span>
                    @if($stats['messages_non_lus'] > 0)
                        <span class="badge-unread">{{ $stats['messages_non_lus'] }}</span>
                    @endif
                </a></li>
                <li><a href="#" class="nav-item" data-panel="candidatures">
                    <span class="nav-icon">📝</span><span>Candidatures</span>
                    @if($stats['candidatures'] > 0)
                        <span class="badge-unread" style="background:#059669;">{{ $stats['candidatures'] }}</span>
                    @endif
                </a></li>
                <li><a href="#actualites" class="nav-item" data-panel="actualites"><span class="nav-icon">📰</span><span>Actualités</span></a></li>
                <li><a href="#dons" class="nav-item" data-panel="dons"><span class="nav-icon">💙</span><span>Dons et collectes</span></a></li>
                <li><a href="{{ route('admin.parrainage.edit') }}" class="nav-item"><span class="nav-icon" aria-hidden="true">♡</span><span>Parrainage</span></a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="{{ url('/') }}">← Retour au site</a>
            <br>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:8px; display:inline;">
                @csrf
                <button type="submit" style="background:none; border:none; color:#f87171; cursor:pointer; font-size:13px;">
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Contenu principal ────────────────────────────────────────── --}}
    <main class="admin-main">

        @if(session('success'))
            <div class="alert-success" style="margin: 16px 24px 0;">✅ {{ session('success') }}</div>
        @endif

        @if($errors->any())<div class="validation-errors" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @include('admin.partials.editorial-panels')
        {{-- ── Dashboard ──────────────────────────────────────────── --}}
        <div class="admin-panel active" id="dashboard">
            <div class="panel-header">
                <h1>Dashboard</h1>
                <p>Vue d'ensemble de votre association</p>
            </div>
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>Projets en cours</h3>
                    <p class="stat-number">{{ $projets->where('publication','publie')->where('statut','en_cours')->count() }}</p>
                </div>
                <div class="stat-card">
                    <h3>Messages reçus</h3>
                    <p class="stat-number">{{ $stats['messages'] }}</p>
                    <p class="stat-label">dont <strong>{{ $stats['messages_non_lus'] }}</strong> non lus</p>
                </div>
                <div class="stat-card">
                    <h3>Candidatures</h3>
                    <p class="stat-number">{{ $stats['candidatures'] }}</p>
                </div>
                <div class="stat-card">
                    <h3>Témoignages</h3>
                    <p class="stat-number">{{ $stats['temoignages'] }}</p>
                </div>
            </div>

            @include('admin.partials.editorial-stats')
            {{-- ── Statistiques visiteurs ──────────────────────────────── --}}
            <div class="dashboard-stats" style="margin-top:16px;">
                <div class="stat-card stat-card-visitors">
                    <h3>Visiteurs aujourd'hui</h3>
                    <p class="stat-number">{{ $stats['visiteurs_jour'] }}</p>
                    <p class="stat-label">visiteurs uniques</p>
                </div>
                <div class="stat-card stat-card-visitors">
                    <h3>Visiteurs ce mois</h3>
                    <p class="stat-number">{{ $stats['visiteurs_mois'] }}</p>
                    <p class="stat-label">depuis le 1er du mois</p>
                </div>
                <div class="stat-card stat-card-visitors">
                    <h3>Mois précédent</h3>
                    <p class="stat-number">{{ $stats['visiteurs_mois_prec'] }}</p>
                    @php
                        $evo = $stats['evolution_visiteurs'];
                        $evoClass = $evo > 0 ? 'evolution-positive' : ($evo < 0 ? 'evolution-negative' : 'evolution-neutral');
                        $evoIcon  = $evo > 0 ? '▲' : ($evo < 0 ? '▼' : '—');
                    @endphp
                    <p class="stat-label">
                        <span class="evolution-badge {{ $evoClass }}">
                            {{ $evoIcon }} {{ abs($evo) }}%
                        </span>
                        vs mois dernier
                    </p>
                </div>
                <div class="stat-card" style="background:#f8fafc;">
                    <h3 style="color:#6b7280;">Total visiteurs</h3>
                    <p class="stat-number" style="color:#374151;">{{ array_sum($graphDonnees) }}</p>
                    <p class="stat-label">sur les 12 derniers mois</p>
                </div>
            </div>

            {{-- ── Graphique visiteurs 12 mois ─────────────────────────── --}}
            <div class="chart-card">
                <h2>📈 Visiteurs uniques — 12 derniers mois</h2>
                <canvas id="visiteurs-chart" height="80"></canvas>
            </div>

            <div class="dashboard-recent" style="margin-top:32px;">
                <h2>Derniers messages reçus</h2>
                @forelse($messages->take(3) as $msg)
                    <div style="padding:12px 0; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center; gap:12px;">
                        <div>
                            <strong style="{{ $msg->lu ? 'color:#6b7280' : 'color:#111827' }}">
                                {{ $msg->prenom }} {{ $msg->nom }}
                            </strong>
                            <span style="color:#6b7280; font-size:13px;"> — {{ $msg->objet }}</span>
                        </div>
                        <span style="color:#9ca3af; font-size:12px; white-space:nowrap;">{{ $msg->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p style="color:#9ca3af; font-size:14px;">Aucun message pour le moment.</p>
                @endforelse
                @if($messages->count() > 3)
                    <p style="margin-top:12px; font-size:13px;">
                        <a href="#" class="nav-item-link" data-panel="messages" style="color:#2563eb;">Voir tous les messages →</a>
                    </p>
                @endif
            </div>
        </div>

        {{-- ── Projets ─────────────────────────────────────────────── --}}
        <div class="admin-panel" id="projets">
            <div class="panel-header">
                <h1>Projets</h1>
                <p>Gérer les projets de l'association</p>
            </div>
            <div class="panel-content">
                <details class="editorial-section"><summary>Créer un projet</summary><form method="post" action="{{ route('admin.projets.enregistrer') }}" enctype="multipart/form-data">@csrf @include('admin.partials.project-form', ['projet'=>new \App\Models\Projet()])</form></details>
                <hr class="section-divider">

                <div class="list-section">
                    <h2>Projets existants ({{ $projets->count() }})</h2>
                    @if($projets->isEmpty())
                        <p style="color:#9ca3af;">Aucun projet pour le moment.</p>
                    @else
                        <div class="existing-projects">
                            @foreach($projets as $projet)
                            <div class="project-item">
                                <div style="position:relative; flex-shrink:0;">
                                    <img src="{{ asset($projet->image) }}" alt="{{ $projet->titre }}"
                                         class="project-thumb" style="width:120px; height:80px; object-fit:cover; border-radius:8px;">
                                    @if($projet->images->count() > 1)
                                        <span style="position:absolute; bottom:4px; right:4px; background:rgba(0,0,0,.65); color:#fff; font-size:11px; padding:2px 6px; border-radius:999px;">
                                            +{{ $projet->images->count() - 1 }}
                                        </span>
                                    @endif
                                </div>
                                <div class="project-info" style="flex:1;">
                                    <h3>{{ $projet->titre }}</h3>
                                    <p style="color:#6b7280; font-size:13px;">{{ $projet->description }}</p>
                                    <p style="color:#9ca3af; font-size:12px; margin-top:4px;">Ajouté le {{ $projet->created_at->format('d/m/Y') }}</p>
                                    <div style="display:flex; gap:8px; margin-top:8px;">
                                        <a href="{{ route('admin.projets.edit',$projet) }}" class="btn btn-secondary">Modifier</a><a href="{{ route('admin.projets.preview',$projet) }}" class="btn btn-secondary">Prévisualiser</a><span class="tag">{{ ['brouillon'=>'Brouillon','publie'=>'Publié','archive'=>'Archivé'][$projet->publication] ?? $projet->publication }}</span><x-project-status :status="$projet->statut" />
                                        <form action="{{ route('posts.destroy', $projet->id) }}" method="POST"
                                              onsubmit="return confirm('Supprimer ce projet définitivement ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary" style="color:#ef4444; border-color:#fca5a5; background:#fef2f2; font-size:12px; padding:4px 12px;">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Membres ──────────────────────────────────────────────── --}}
        <div class="admin-panel" id="membres">
            <div class="panel-header">
                <h1>Notre Équipe</h1>
                <p>Gérer les membres affichés sur le site</p>
            </div>
            <div class="panel-content">

                {{-- Formulaire d'ajout --}}
                <div class="form-section">
                    <h2>Ajouter un membre</h2>
                    <form method="POST" action="{{ route('admin.membres.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div class="form-group">
                                <label for="m-prenom">Prénom <span class="required">*</span></label>
                                <input type="text" id="m-prenom" name="prenom" required placeholder="Prénom du membre">
                            </div>
                            <div class="form-group">
                                <label for="m-nom">Nom <span class="required">*</span></label>
                                <input type="text" id="m-nom" name="nom" required placeholder="Nom du membre">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="m-poste">Poste / Rôle <span class="required">*</span></label>
                            <input type="text" id="m-poste" name="poste" required placeholder="Ex : Présidente, Trésorier, Responsable terrain...">
                        </div>

                        <div class="form-group">
                            <label for="m-bio">Biographie courte</label>
                            <textarea id="m-bio" name="bio" rows="3" maxlength="500"
                                      placeholder="Quelques mots sur ce membre (max 500 caractères)..."></textarea>
                        </div>

                        <div class="grid" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div class="form-group">
                                <label for="m-photo">Photo de profil</label>
                                <input type="file" id="m-photo" name="photo" accept="image/jpeg,image/png,image/webp">
                                <p style="font-size:12px; color:#9ca3af; margin-top:4px;">JPG, PNG ou WebP — max 3 Mo</p>
                            </div>
                            <div class="form-group">
                                <label for="m-ordre">Ordre d'affichage</label>
                                <input type="number" id="m-ordre" name="ordre" value="0" min="0"
                                       placeholder="0 = premier affiché">
                                <p style="font-size:12px; color:#9ca3af; margin-top:4px;">Les plus petits numéros apparaissent en premier</p>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Ajouter le membre</button>
                            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                        </div>
                    </form>
                </div>

                <hr class="section-divider">

                {{-- Liste des membres --}}
                <div class="list-section">
                    <h2>Membres enregistrés ({{ $membres->count() }})</h2>

                    @if($membres->isEmpty())
                        <p style="color:#9ca3af; font-size:14px;">Aucun membre pour le moment. Ajoutez le premier ci-dessus !</p>
                    @else
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:16px; margin-top:16px;">
                            @foreach($membres as $m)
                            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:20px; display:flex; flex-direction:column; gap:12px;">

                                {{-- En-tête : photo + nom + poste --}}
                                <div style="display:flex; align-items:center; gap:14px;">
                                    @if($m->photo)
                                        <img src="{{ asset($m->photo) }}" alt="{{ $m->nom_complet }}"
                                             style="width:56px; height:56px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb; flex-shrink:0;">
                                    @else
                                        <div style="width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg,#2563eb,#1e40af); display:flex; align-items:center; justify-content:center; flex-shrink:0; border:2px solid #e5e7eb;">
                                            <span style="color:#fff; font-weight:700; font-size:16px;">
                                                {{ strtoupper(substr($m->prenom,0,1)) }}{{ strtoupper(substr($m->nom,0,1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div style="min-width:0;">
                                        <p style="font-weight:700; color:#111827; font-size:15px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            {{ $m->prenom }} {{ $m->nom }}
                                        </p>
                                        <p style="color:#2563eb; font-size:13px; font-weight:600;">{{ $m->poste }}</p>
                                        <div style="margin-top:4px;">
                                            <span class="tag" style="{{ $m->actif ? 'background:#d1fae5;color:#065f46;' : 'background:#fee2e2;color:#991b1b;' }}">
                                                {{ $m->actif ? 'Visible' : 'Masqué' }}
                                            </span>
                                            <span style="font-size:11px; color:#9ca3af; margin-left:6px;">Ordre : {{ $m->ordre }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bio --}}
                                @if($m->bio)
                                <p style="font-size:13px; color:#6b7280; line-height:1.6; border-top:1px solid #e5e7eb; padding-top:10px;">
                                    {{ Str::limit($m->bio, 120) }}
                                </p>
                                @endif

                                {{-- Actions --}}
                                <div style="display:flex; gap:8px; flex-wrap:wrap; border-top:1px solid #e5e7eb; padding-top:12px;">
                                    <button onclick="openEditMembre(this)"
                                            data-id="{{ $m->id }}"
                                            data-prenom="{{ $m->prenom }}"
                                            data-nom="{{ $m->nom }}"
                                            data-poste="{{ $m->poste }}"
                                            data-bio="{{ $m->bio ?? '' }}"
                                            data-ordre="{{ $m->ordre }}"
                                            class="btn btn-secondary" style="font-size:12px; padding:5px 14px; flex:1;">
                                        ✏️ Modifier
                                    </button>

                                    <form method="POST" action="{{ route('admin.membres.toggle', $m->id) }}" style="flex:1;">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary"
                                                style="font-size:12px; padding:5px 14px; width:100%; {{ $m->actif ? 'color:#d97706;border-color:#fde68a;background:#fffbeb;' : 'color:#059669;border-color:#6ee7b7;background:#ecfdf5;' }}">
                                            {{ $m->actif ? '🙈 Masquer' : '👁 Afficher' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.membres.destroy', $m->id) }}"
                                          onsubmit="return confirm('Supprimer {{ $m->prenom }} {{ $m->nom }} définitivement ?')"
                                          style="flex:0 0 auto;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary"
                                                style="font-size:12px; padding:5px 12px; color:#ef4444; border-color:#fca5a5; background:#fef2f2;">
                                            🗑
                                        </button>
                                    </form>
                                </div>

                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- ── Témoignages ─────────────────────────────────────────── --}}
        <div class="admin-panel" id="temoignages">
            <div class="panel-header">
                <h1>Témoignages</h1>
                <p>Gérer les témoignages affichés sur le site</p>
            </div>
            <div class="panel-content">
                <div class="form-section">
                    <h2>Ajouter un témoignage</h2>
                    <form method="POST" action="{{ route('admin.temoignages.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="t-auteur">Auteur <span class="required">*</span></label>
                            <input type="text" id="t-auteur" name="auteur" required placeholder="Nom de la personne">
                        </div>
                        <div class="form-group">
                            <label for="t-role">Rôle / Fonction</label>
                            <input type="text" id="t-role" name="role" placeholder="Ex: Bénévole, Partenaire, Bénéficiaire...">
                        </div>
                        <div class="form-group">
                            <label for="t-texte">Témoignage <span class="required">*</span></label>
                            <textarea id="t-texte" name="texte" rows="4" required placeholder="Le témoignage de la personne..."></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Publier le témoignage</button>
                            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                        </div>
                    </form>
                </div>

                <hr class="section-divider">

                <div class="list-section">
                    <h2>Témoignages publiés ({{ $temoignages->count() }})</h2>
                    @if($temoignages->isEmpty())
                        <p style="color:#9ca3af;">Aucun témoignage pour le moment.</p>
                    @else
                        @foreach($temoignages as $t)
                        <div class="temoignage-item">
                            <div style="flex:1;">
                                <blockquote>"{{ Str::limit($t->texte, 150) }}"</blockquote>
                                <div class="meta">— <strong>{{ $t->auteur }}</strong>{{ $t->role ? ', ' . $t->role : '' }}</div>
                                <div style="margin-top:8px;">
                                    <span class="tag" style="{{ $t->actif ? 'background:#d1fae5;color:#065f46;' : 'background:#fee2e2;color:#991b1b;' }}">
                                        {{ $t->actif ? 'Visible' : 'Masqué' }}
                                    </span>
                                </div>
                            </div>
                            <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-end;">
                                <form method="POST" action="{{ route('admin.temoignages.toggle', $t->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" style="font-size:12px; padding:4px 12px;">
                                        {{ $t->actif ? 'Masquer' : 'Afficher' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.temoignages.destroy', $t->id) }}"
                                      onsubmit="return confirm('Supprimer ce témoignage ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" style="font-size:12px; padding:4px 12px; color:#ef4444; border-color:#fca5a5; background:#fef2f2;">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Messages reçus ──────────────────────────────────────── --}}
        <div class="admin-panel" id="messages">
            <div class="panel-header">
                <h1>Messages reçus
                    @if($stats['messages_non_lus'] > 0)
                        <span class="badge-unread">{{ $stats['messages_non_lus'] }} non lus</span>
                    @endif
                </h1>
                <p>Gérer les messages du formulaire de contact</p>
            </div>
            <div class="panel-content-full">
                <div class="table-controls">
                    <input type="text" id="messages-search" placeholder="Rechercher dans les messages..." class="search-input">
                </div>
                <div class="table-container">
                    <table class="data-table" id="messages-table">
                        <thead>
                            <tr>
                                <th>Statut</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Objet</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                            <tr class="msg-row {{ $msg->lu ? 'read' : 'unread' }}" data-id="{{ $msg->id }}" data-search="{{ strtolower($msg->nom . ' ' . $msg->prenom . ' ' . $msg->email . ' ' . $msg->objet . ' ' . $msg->message) }}">
                                <td>
                                    <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:{{ $msg->lu ? '#d1d5db' : '#3b82f6' }}; margin-right:4px;"></span>
                                    {{ $msg->lu ? 'Lu' : 'Non lu' }}
                                </td>
                                <td>{{ $msg->prenom }} {{ $msg->nom }}</td>
                                <td><a href="mailto:{{ $msg->email }}" style="color:#2563eb;">{{ $msg->email }}</a></td>
                                <td>{{ $msg->objet }}</td>
                                <td style="max-width:280px; white-space:normal; word-break:break-word;">{{ Str::limit($msg->message, 100) }}</td>
                                <td style="white-space:nowrap;">{{ $msg->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button class="toggle-read-btn {{ $msg->lu ? 'read' : 'unread' }}"
                                            data-id="{{ $msg->id }}"
                                            onclick="toggleRead({{ $msg->id }}, this)">
                                        {{ $msg->lu ? 'Marquer non lu' : 'Marquer lu' }}
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="loading-state">Aucun message pour le moment.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Candidatures ─────────────────────────────────────────── --}}
        <div class="admin-panel" id="candidatures">
            <div class="panel-header">
                <h1>Candidatures ({{ $candidatures->count() }})</h1>
                <p>Personnes souhaitant rejoindre l'association</p>
            </div>
            <div class="panel-content-full">
                <div class="table-controls">
                    <input type="text" id="candidatures-search" placeholder="Rechercher..." class="search-input">
                    <select id="candidatures-filter" class="filter-select">
                        <option value="">Tous les types</option>
                        <option value="stagiaire">Stagiaire</option>
                        <option value="benevole">Bénévole</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="table-container">
                    <table class="data-table" id="candidatures-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Type</th>
                                <th>Préférence</th>
                                <th>Disponibilités</th>
                                <th>Date</th>
                                <th>Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidatures as $c)
                            <tr data-type="{{ $c->candidature_typeParticipation }}"
                                data-search="{{ strtolower($c->candidature_nom . ' ' . $c->candidature_prenom . ' ' . $c->candidature_email) }}">
                                <td><strong>{{ $c->candidature_prenom }} {{ $c->candidature_nom }}</strong></td>
                                <td><a href="mailto:{{ $c->candidature_email }}" style="color:#2563eb;">{{ $c->candidature_email }}</a></td>
                                <td><a href="tel:{{ $c->candidature_telephone }}">{{ $c->candidature_telephone }}</a></td>
                                <td>
                                    <span class="tag tag-{{ $c->candidature_typeParticipation }}">
                                        {{ ucfirst($c->candidature_typeParticipation) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="tag tag-{{ $c->candidature_preferenceAction }}">
                                        {{ ucfirst($c->candidature_preferenceAction) }}
                                    </span>
                                </td>
                                <td style="font-size:12px; max-width:160px; white-space:normal;">{{ Str::limit($c->candidature_disponibilites, 60) }}</td>
                                <td style="white-space:nowrap; font-size:13px;">{{ $c->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <button onclick="showCandidatureDetail(this)"
                                            data-nom="{{ $c->candidature_prenom }} {{ $c->candidature_nom }}"
                                            data-email="{{ $c->candidature_email }}"
                                            data-tel="{{ $c->candidature_telephone }}"
                                            data-adresse="{{ $c->candidature_adresse }}"
                                            data-naissance="{{ \Carbon\Carbon::parse($c->candidature_dateNaissance)->format('d/m/Y') }}"
                                            data-type="{{ ucfirst($c->candidature_typeParticipation) }}"
                                            data-autreType="{{ $c->candidature_autreType }}"
                                            data-preference="{{ ucfirst($c->candidature_preferenceAction) }}"
                                            data-dispos="{{ $c->candidature_disponibilites }}"
                                            data-motivation="{{ $c->candidature_motivation }}"
                                            class="btn btn-secondary" style="font-size:12px; padding:4px 12px;">
                                        Voir
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="loading-state">Aucune candidature pour le moment.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
</div>

{{-- ── Modal modification membre ─────────────────────────────────────── --}}
<div id="modal-edit-membre"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:1100; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; border-radius:16px; max-width:540px; width:100%; max-height:90vh; overflow-y:auto; padding:32px; position:relative; box-shadow:0 20px 60px rgba(0,0,0,.2);">

        <button onclick="closeEditMembre()"
                style="position:absolute; top:16px; right:16px; background:#f3f4f6; border:none; border-radius:50%; width:34px; height:34px; font-size:18px; cursor:pointer; color:#6b7280; display:flex; align-items:center; justify-content:center;">
            ×
        </button>

        <h2 style="margin:0 0 24px; font-size:18px; font-weight:700; color:#111827;">
            ✏️ Modifier le membre
        </h2>

        <form id="form-edit-membre" method="POST" action="" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="edit-prenom">Prénom <span class="required">*</span></label>
                    <input type="text" id="edit-prenom" name="prenom" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="edit-nom">Nom <span class="required">*</span></label>
                    <input type="text" id="edit-nom" name="nom" required>
                </div>
            </div>

            <div class="form-group">
                <label for="edit-poste">Poste / Rôle <span class="required">*</span></label>
                <input type="text" id="edit-poste" name="poste" required>
            </div>

            <div class="form-group">
                <label for="edit-bio">Biographie courte</label>
                <textarea id="edit-bio" name="bio" rows="3" maxlength="500"></textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="edit-photo">Nouvelle photo
                        <small style="color:#9ca3af; font-weight:400;">(laisser vide = garder l'actuelle)</small>
                    </label>
                    <input type="file" id="edit-photo" name="photo" accept="image/jpeg,image/png,image/webp">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="edit-ordre">Ordre d'affichage</label>
                    <input type="number" id="edit-ordre" name="ordre" min="0">
                </div>
            </div>

            <div class="form-actions" style="margin-top:24px; padding-top:20px; border-top:1px solid #e5e7eb;">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" onclick="closeEditMembre()" class="btn btn-secondary">Annuler</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal détail candidature ──────────────────────────────────────── --}}
<div id="candidature-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; max-width:600px; width:90%; max-height:85vh; overflow-y:auto; padding:32px; position:relative;">
        <button onclick="document.getElementById('candidature-modal').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:24px; cursor:pointer; color:#6b7280;">×</button>
        <h2 id="modal-titre" style="margin:0 0 20px; font-size:20px; font-weight:700; color:#111827;"></h2>
        <div id="modal-body" class="candidature-detail"></div>
    </div>
</div>


<script>
// ── Navigation entre panels ──────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const navItems = document.querySelectorAll('.nav-item[data-panel]');
    const panels   = document.querySelectorAll('.admin-panel');

    navItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            navItems.forEach(n => n.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            const panel = document.getElementById(this.dataset.panel);
            if (panel) panel.classList.add('active');
        });
    });

    // Lien "Voir tous les messages" depuis le dashboard
    document.querySelectorAll('[data-panel]').forEach(el => {
        if (!el.classList.contains('nav-item')) {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector('.nav-item[data-panel="' + this.dataset.panel + '"]');
                if (target) target.click();
            });
        }
    });
});

// ── Recherche dans les tableaux ──────────────────────────────────────
document.getElementById('messages-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#messages-table tbody tr[data-search]').forEach(row => {
        row.style.display = row.dataset.search.includes(q) ? '' : 'none';
    });
});

document.getElementById('candidatures-search')?.addEventListener('input', function () {
    filterCandidatures();
});

document.getElementById('candidatures-filter')?.addEventListener('change', function () {
    filterCandidatures();
});

function filterCandidatures() {
    const q    = document.getElementById('candidatures-search').value.toLowerCase();
    const type = document.getElementById('candidatures-filter').value;
    document.querySelectorAll('#candidatures-table tbody tr[data-search]').forEach(row => {
        const matchQ    = row.dataset.search.includes(q);
        const matchType = !type || row.dataset.type === type;
        row.style.display = matchQ && matchType ? '' : 'none';
    });
}

// ── Marquer message lu / non lu (AJAX) ──────────────────────────────
function toggleRead(id, btn) {
    fetch(`/administrateur/messages/${id}/toggle-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (data.lu) {
            row.classList.replace('unread', 'read');
            btn.textContent = 'Marquer non lu';
            btn.classList.replace('unread', 'read');
            row.querySelector('td:first-child').innerHTML =
                '<span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#d1d5db;margin-right:4px;"></span>Lu';
        } else {
            row.classList.replace('read', 'unread');
            btn.textContent = 'Marquer lu';
            btn.classList.replace('read', 'unread');
            row.querySelector('td:first-child').innerHTML =
                '<span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#3b82f6;margin-right:4px;"></span>Non lu';
        }
    })
    .catch(err => console.error(err));
}

// ── Modal détail candidature ─────────────────────────────────────────
function showCandidatureDetail(btn) {
    document.getElementById('modal-titre').textContent = btn.dataset.nom;
    const body = document.getElementById('modal-body');
    body.replaceChildren();
    const table = document.createElement('table');
    table.style.cssText = 'width:100%;font-size:14px';
    const fields = [['Email','email'],['Téléphone','tel'],['Date de naissance','naissance'],['Adresse','adresse'],['Type de participation','type'],['Autre participation','autreType'],['Préférence d’action','preference'],['Disponibilités','dispos'],['Motivation','motivation']];
    fields.forEach(([label,key]) => {
        const row=table.insertRow();
        row.insertCell().textContent=label;
        const cell=row.insertCell(); cell.textContent=btn.dataset[key] || '—';
        cell.style.whiteSpace='pre-wrap';
    });
    body.appendChild(table);
    document.getElementById('candidature-modal').style.display = 'flex';
}

// Fermer modal en cliquant à l'extérieur
document.getElementById('candidature-modal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});

// ── Modal modification membre ────────────────────────────────────────
function openEditMembre(btn) {
    const id = btn.dataset.id;

    // Remplir les champs avec les données actuelles du membre
    document.getElementById('edit-prenom').value = btn.dataset.prenom || '';
    document.getElementById('edit-nom').value    = btn.dataset.nom    || '';
    document.getElementById('edit-poste').value  = btn.dataset.poste  || '';
    document.getElementById('edit-bio').value    = btn.dataset.bio    || '';
    document.getElementById('edit-ordre').value  = btn.dataset.ordre  || '0';

    // Réinitialiser le champ photo (ne peut pas être prérempli pour raisons de sécurité)
    document.getElementById('edit-photo').value  = '';

    // Mettre à jour l'action du formulaire avec l'ID correct
    document.getElementById('form-edit-membre').action = '/administrateur/membres/' + id;

    // Afficher la modal
    document.getElementById('modal-edit-membre').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeEditMembre() {
    document.getElementById('modal-edit-membre').style.display = 'none';
    document.body.style.overflow = '';
}

// Fermer la modal membre en cliquant sur le fond
document.getElementById('modal-edit-membre').addEventListener('click', function(e) {
    if (e.target === this) closeEditMembre();
});

// Fermer avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditMembre();
        document.getElementById('candidature-modal').style.display = 'none';
    }
});

// ── Graphique visiteurs ──────────────────────────────────────────────
(function () {
    const labels  = @json($graphLabels);
    const donnees = @json($graphDonnees);

    const ctx = document.getElementById('visiteurs-chart');
    if (!ctx || !window.Chart) return;

    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Visiteurs uniques',
                data: donnees,
                fill: true,
                backgroundColor: gradient,
                borderColor: '#2563eb',
                borderWidth: 2.5,
                pointBackgroundColor: '#2563eb',
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e3a8a',
                    titleColor: '#bfdbfe',
                    bodyColor: '#fff',
                    padding: 12,
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} visiteur${ctx.parsed.y > 1 ? 's' : ''}`
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { size: 12 }, color: '#6b7280' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        font: { size: 12 }, color: '#6b7280',
                        stepSize: 1,
                        callback: v => Number.isInteger(v) ? v : ''
                    }
                }
            }
        }
    });
})();
</script>
<script>document.addEventListener('DOMContentLoaded',()=>{const activate=()=>{const id=location.hash.slice(1);if(!id)return;const panel=document.getElementById(id);if(!panel?.classList.contains('admin-panel'))return;document.querySelectorAll('.admin-panel').forEach(p=>p.classList.toggle('active',p===panel));document.querySelectorAll('.nav-item[data-panel]').forEach(a=>a.classList.toggle('active',a.dataset.panel===id));};activate();window.addEventListener('hashchange',activate);document.querySelectorAll('.nav-item[data-panel]').forEach(a=>a.addEventListener('click',()=>{location.hash=a.dataset.panel}));});</script></body>
</html>
