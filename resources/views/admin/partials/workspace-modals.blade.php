{{-- ── Modal modification membre ─────────────────────────────────────── --}}
<div id="modal-edit-membre" aria-labelledby="member-modal-title"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:1100; align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; border-radius:16px; max-width:540px; width:100%; max-height:90vh; overflow-y:auto; padding:32px; position:relative; box-shadow:0 20px 60px rgba(0,0,0,.2);">

        <button onclick="closeEditMembre()"
                style="position:absolute; top:16px; right:16px; background:#f3f4f6; border:none; border-radius:50%; width:34px; height:34px; font-size:18px; cursor:pointer; color:#6b7280; display:flex; align-items:center; justify-content:center;">
            ×
        </button>

        <h2 id="member-modal-title" style="margin:0 0 24px; font-size:18px; font-weight:700; color:#111827;">
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
<div id="candidature-modal" aria-labelledby="modal-titre" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; max-width:600px; width:90%; max-height:85vh; overflow-y:auto; padding:32px; position:relative;">
        <button onclick="document.getElementById('candidature-modal').style.display='none'"
                style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:24px; cursor:pointer; color:#6b7280;">×</button>
        <h2 id="modal-titre" style="margin:0 0 20px; font-size:20px; font-weight:700; color:#111827;"></h2>
        <div id="modal-body" class="candidature-detail"></div>
    </div>
</div>
