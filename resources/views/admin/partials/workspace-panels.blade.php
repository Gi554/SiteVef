        @include('admin.partials.editorial-panels')
        {{-- ── Dashboard ──────────────────────────────────────────── --}}
        @include('admin.partials.dashboard')
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
                                    @if($projet->image)<img src="{{ asset($projet->image) }}" alt="{{ $projet->titre }}"
                                         class="project-thumb" style="width:120px; height:80px; object-fit:cover; border-radius:8px;">@else<div class="admin-project-placeholder"><x-admin-icon name="projects" /><span>Sans couverture</span></div>@endif
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
                                        <form action="{{ route('admin.projets.destroy', $projet->id) }}" method="POST"
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
                                <td style="max-width:280px; white-space:normal; word-break:break-word;">@if(mb_strlen($msg->message) > 100)<details class="admin-message-content"><summary>{{ Str::limit($msg->message, 100) }} <span>Lire la suite</span></summary><p>{{ $msg->message }}</p></details>@else{{ $msg->message }}@endif</td>
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
                                            data-autre-type="{{ $c->candidature_autreType }}"
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
