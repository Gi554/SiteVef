<section class="editorial-section">
<h2>Présentation et publication</h2>
<div class="editorial-grid">
<div class="form-group">
<label for="p-titre">Titre</label>
<input id="p-titre" name="titre" value="{{ old('titre', $projet->titre) }}" maxlength="255" required>
</div>
<div class="form-group">
<label for="p-slug">Adresse de la page (slug)</label>
<input id="p-slug" name="slug" value="{{ old('slug', $projet->slug) }}" maxlength="220" >
</div>
<div class="form-group">
<label for="p-description">Résumé</label>
<input id="p-description" name="description" value="{{ old('description', $projet->description) }}" maxlength="255" >
</div>
<div class="form-group">
<label for="p-localisation">Localisation</label>
<input id="p-localisation" name="localisation" value="{{ old('localisation', $projet->localisation) }}" maxlength="255" >
</div>
<div class="form-group">
<label for="p-seo_title">Titre SEO</label>
<input id="p-seo_title" name="seo_title" value="{{ old('seo_title', $projet->seo_title) }}" maxlength="255" >
</div>
<div class="form-group">
<label for="p-meta_description">Méta-description</label>
<input id="p-meta_description" name="meta_description" value="{{ old('meta_description', $projet->meta_description) }}" maxlength="255" >
</div>
<div>
<label for="p-statut">Statut du projet</label>
<select id="p-statut" name="statut">
@foreach(['a_venir'=>'À venir','en_cours'=>'En cours','realise'=>'Réalisé'] as $v=>$label)<option value="{{ $v }}" @selected(old('statut',$projet->statut ?? 'en_cours')===$v)>{{ $label }}</option>
@endforeach
</select>
</div>
<div>
<label for="p-publication">Visibilité</label>
<select id="p-publication" name="publication">
@foreach(['brouillon'=>'Brouillon','publie'=>'Publié','archive'=>'Archivé (retiré du site public)'] as $v=>$label)<option value="{{ $v }}" @selected(old('publication',$projet->publication ?? 'brouillon')===$v)>{{ $label }}</option>
@endforeach
</select>
</div>
</div>
</section>
<section class="editorial-section">
<h2>Comprendre le projet</h2>
<div class="form-group">
<label for="p-description_longue">Description complète</label>
<textarea id="p-description_longue" name="description_longue" rows="4">{{ old('description_longue', $projet->description_longue) }}</textarea>
</div>
<div class="form-group">
<label for="p-contexte">Pourquoi ce projet ?</label>
<textarea id="p-contexte" name="contexte" rows="4">{{ old('contexte', $projet->contexte) }}</textarea>
</div>
<div class="form-group">
<label for="p-solution">Notre réponse</label>
<textarea id="p-solution" name="solution" rows="4">{{ old('solution', $projet->solution) }}</textarea>
</div>
<div class="form-group">
<label for="p-objectifs">Objectifs</label>
<textarea id="p-objectifs" name="objectifs" rows="4">{{ old('objectifs', $projet->objectifs) }}</textarea>
</div>
<div class="form-group">
<label for="p-beneficiaires">Bénéficiaires</label>
<textarea id="p-beneficiaires" name="beneficiaires" rows="4">{{ old('beneficiaires', $projet->beneficiaires) }}</textarea>
</div>
<div class="form-group">
<label for="p-equipe">Équipe et partenaires</label>
<textarea id="p-equipe" name="equipe" rows="4">{{ old('equipe', $projet->equipe) }}</textarea>
</div>
<div class="form-group">
<label for="p-financement_note">Utilisation des contributions si l’objectif est dépassé ou non atteint</label>
<textarea id="p-financement_note" name="financement_note" rows="4">{{ old('financement_note', $projet->financement_note) }}</textarea>
</div>
</section>
<section class="editorial-section">
<h2>Indicateurs, étapes et questions fréquentes</h2>
<p class="editorial-help">Champs facultatifs : laissez vides les informations non disponibles. Saisissez une entrée par ligne, avec le séparateur indiqué.</p>
<div class="form-group">
<label for="p-indicateurs">Indicateurs</label>
<p class="editorial-help" id="help-indicateurs">Libellé | Valeur</p>
<textarea id="p-indicateurs" name="indicateurs_texte" aria-describedby="help-indicateurs" rows="4">{{ old('indicateurs_texte', collect($projet->indicateurs ?? [])->map(fn($row)=>implode(' | ', $row))->implode("\n")) }}</textarea>
</div>
<div class="form-group">
<label for="p-etapes">Étapes de réalisation</label>
<p class="editorial-help" id="help-etapes">Nom de l’étape | Description</p>
<textarea id="p-etapes" name="etapes_texte" aria-describedby="help-etapes" rows="4">{{ old('etapes_texte', collect($projet->etapes ?? [])->map(fn($row)=>implode(' | ', $row))->implode("\n")) }}</textarea>
</div>
<div class="form-group">
<label for="p-depenses">Répartition du budget</label>
<p class="editorial-help" id="help-depenses">Poste de dépense | Montant en euros (ex. 125.50)</p>
<textarea id="p-depenses" name="depenses_texte" aria-describedby="help-depenses" rows="4">{{ old('depenses_texte', collect($projet->depenses ?? [])->map(fn($row)=>implode(' | ', $row))->implode("\n")) }}</textarea>
</div>
<div class="form-group">
<label for="p-faq">Questions fréquentes</label>
<p class="editorial-help" id="help-faq">Question | Réponse</p>
<textarea id="p-faq" name="faq_texte" aria-describedby="help-faq" rows="4">{{ old('faq_texte', collect($projet->faq ?? [])->map(fn($row)=>implode(' | ', $row))->implode("\n")) }}</textarea>
</div>
</section>
<section class="editorial-section">
<h2>Financement</h2>
<div class="editorial-grid">
<div>
<label for="p-budget">Budget prévisionnel / objectif (€)</label>
<input id="p-budget" type="number" name="budget" min="0" max="100000000" step="0.01" value="{{ old('budget', $projet->budget_centimes !== null ? $projet->budget_centimes / 100 : '') }}">
</div>
<div>
<label for="p-montants">Montants suggérés (€), séparés par des virgules</label>
<input id="p-montants" name="montants_texte" value="{{ old('montants_texte', implode(', ', $projet->montants_suggeres ?? [])) }}">
</div>
</div>
<label>
<input type="checkbox" name="dons_actifs" value="1" @checked(old('dons_actifs', $projet->dons_actifs))> Autoriser les dons pour ce projet</label>
<p class="editorial-help">Le montant collecté provient exclusivement des paiements confirmés. Les nouveaux dons sont vérifiés auprès de Kkiapay. Il n’est pas modifiable manuellement.</p>
</section>
<section class="editorial-section">
<h2>Image principale et galerie</h2>
<label for="p-images">Ajouter des photos</label>
<input id="p-images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
<p class="editorial-help">JPEG, PNG ou WebP ; 3 Mo par photo, 20 photos par envoi. La première photo sert de couverture par défaut.</p>
@if($projet->exists)<div class="editorial-gallery">
@foreach($projet->images as $photo)<div>
<img src="{{ asset($photo->chemin) }}" alt="Photo du projet {{ $projet->titre }}">
<label>
<input type="radio" name="couverture_id" value="{{ $photo->id }}" @checked($projet->image===$photo->chemin)> Couverture</label>
<label>
<input type="checkbox" name="supprimer_images[]" value="{{ $photo->id }}"> Retirer</label>
</div>
@endforeach
</div>
@endif
</section>
<div class="editorial-actions">
<button class="btn btn-primary">Enregistrer les modifications</button>
@if($projet->exists)<a class="btn btn-secondary" target="_blank" rel="noopener" href="{{ route('admin.projets.preview',$projet) }}">Prévisualiser la version enregistrée</a>
@endif
<a class="btn btn-secondary" href="{{ route('admin.projets.index') }}#projets">Retour aux projets</a>
</div>
