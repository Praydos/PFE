@php
    $isEdit = isset($reclamation);
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Compte *</label>
        <select name="compte_id" id="compte_id" class="form-select" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($comptes as $c)
                <option value="{{ $c->id }}" {{ old('compte_id', $isEdit ? $reclamation->compte_id : '') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label>Contact *</label>
        <select name="contact_id" id="contact_id" class="form-select" required>
            <option value="">-- Sélectionnez un contact --</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label>Date réclamation *</label>
        <input type="date" name="date_reclamation" class="form-control" value="{{ old('date_reclamation', $isEdit ? $reclamation->date_reclamation->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label>Type</label>
        <select name="type" class="form-select">
            <option value="">-- Sélectionnez --</option>
            @foreach($types as $t)
                <option value="{{ $t }}" {{ old('type', $isEdit ? $reclamation->type : '') == $t ? 'selected' : '' }}>{{ str_replace('_', ' ', $t) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label>Priorité</label>
        <select name="priorite" class="form-select">
            <option value="basse" {{ old('priorite', $isEdit ? $reclamation->priorite : '') == 'basse' ? 'selected' : '' }}>Basse</option>
            <option value="moyenne" {{ old('priorite', $isEdit ? $reclamation->priorite : '') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
            <option value="haute" {{ old('priorite', $isEdit ? $reclamation->priorite : '') == 'haute' ? 'selected' : '' }}>Haute</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Catégorie *</label>
        <select name="categorie" id="categorie" class="form-select" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($categories as $c)
                <option value="{{ $c }}" {{ old('categorie', $isEdit ? $reclamation->categorie : '') == $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label>Sous‑catégorie</label>
        <select name="sous_categorie" id="sous_categorie" class="form-select">
            <option value="">-- Sélectionnez --</option>
        </select>
    </div>
</div>

{{-- Linked items (only one appears based on sous‑catégorie) --}}
<div id="linked_product_container" style="display: none;" class="mb-3">
    <label>Produit lié</label>
    <select name="produit_id" class="form-select">
        <option value="">-- Sélectionnez --</option>
        @foreach($produits as $p)
            <option value="{{ $p->id }}" {{ old('produit_id', $isEdit ? $reclamation->produit_id : '') == $p->id ? 'selected' : '' }}>{{ $p->titre }} ({{ $p->isbn_13 ?? $p->isbn_10 }})</option>
        @endforeach
    </select>
</div>

<div id="linked_specimen_container" style="display: none;" class="mb-3">
    <label>Spécimen lié (BSS)</label>
    <select name="specimen_id" class="form-select">
        <option value="">-- Sélectionnez --</option>
        @foreach($specimens as $s)
            <option value="{{ $s->id }}" {{ old('specimen_id', $isEdit ? $reclamation->specimen_id : '') == $s->id ? 'selected' : '' }}>{{ $s->numero }} - {{ $s->compte->etablissement }}</option>
        @endforeach
    </select>
</div>

<div id="linked_mp_container" style="display: none;" class="mb-3">
    <label>Matériel pédagogique lié</label>
    <select name="mp_id" class="form-select">
        <option value="">-- Sélectionnez --</option>
        @foreach($mps as $mp)
            <option value="{{ $mp->id }}" {{ old('mp_id', $isEdit ? $reclamation->mp_id : '') == $mp->id ? 'selected' : '' }}>{{ $mp->nom }} ({{ $mp->code_article }})</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Description *</label>
    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $isEdit ? $reclamation->description : '') }}</textarea>
</div>

@if($isEdit)
<div class="row">
    <div class="col-md-6 mb-3">
        <label>Analyse</label>
        <textarea name="analyse" class="form-control" rows="2">{{ old('analyse', $reclamation->analyse) }}</textarea>
    </div>
    <div class="col-md-6 mb-3">
        <label>Réponse</label>
        <textarea name="reponse" class="form-control" rows="2">{{ old('reponse', $reclamation->reponse) }}</textarea>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label>Date réponse</label>
        <input type="date" name="date_reponse" class="form-control" value="{{ old('date_reponse', $reclamation->date_reponse ? $reclamation->date_reponse->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>Responsable</label>
        <select name="responsable_id" class="form-select">
            <option value="">-- Sélectionnez --</option>
            @foreach(\App\Models\User::whereIn('role', ['admin','rbo'])->get() as $u)
                <option value="{{ $u->id }}" {{ old('responsable_id', $reclamation->responsable_id) == $u->id ? 'selected' : '' }}>{{ $u->prenom }} {{ $u->nom }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label>Statut *</label>
        <select name="statut" class="form-select" required>
            @foreach($statuts as $s)
                <option value="{{ $s }}" {{ old('statut', $reclamation->statut) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label>Date clôture</label>
        <input type="date" name="date_cloture" class="form-control" value="{{ old('date_cloture', $reclamation->date_cloture ? $reclamation->date_cloture->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-check">
            <input type="checkbox" name="est_non_conformite" value="1" class="form-check-input" id="est_non_conformite" {{ old('est_non_conformite', $reclamation->est_non_conformite) ? 'checked' : '' }}>
            <label class="form-check-label" for="est_non_conformite">S'agit-il d'une non‑conformité ?</label>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-check">
            <input type="checkbox" name="besoin_action_amelioration" value="1" class="form-check-input" id="besoin_action" {{ old('besoin_action_amelioration', $reclamation->besoin_action_amelioration) ? 'checked' : '' }}>
            <label class="form-check-label" for="besoin_action">Besoin d'action d'amélioration ?</label>
        </div>
    </div>
</div>
@endif<script>
    // Load contacts when compte changes
    document.getElementById('compte_id')?.addEventListener('change', function() {
        let compteId = this.value;
        let contactSelect = document.getElementById('contact_id');
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez un contact --</option>';
                data.forEach(c => options += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`);
                contactSelect.innerHTML = options;
                @if($isEdit)
                    contactSelect.value = '{{ old('contact_id', $reclamation->contact_id) }}';
                @endif
            });
    });
    if (document.getElementById('compte_id')?.value) {
        document.getElementById('compte_id').dispatchEvent(new Event('change'));
    }

    // Load sous‑catégories dynamically based on main category
    const sousCategoriesMap = @json($sousCategoriesMap);
    const categorieSelect = document.getElementById('categorie');
    const sousSelect = document.getElementById('sous_categorie');

    function updateSousCategories() {
        let cat = categorieSelect.value;
        let options = '<option value="">-- Sélectionnez --</option>';
        if (sousCategoriesMap[cat]) {
            sousCategoriesMap[cat].forEach(sub => options += `<option value="${sub}">${sub}</option>`);
        }
        sousSelect.innerHTML = options;
    }
    categorieSelect.addEventListener('change', updateSousCategories);
    updateSousCategories();

    // Linked item selector based on main category (categorie)
    const productDiv = document.getElementById('linked_product_container');
    const specimenDiv = document.getElementById('linked_specimen_container');
    const mpDiv = document.getElementById('linked_mp_container');

    function updateLinkedSelectorByCategory() {
        const cat = categorieSelect.value;
        productDiv.style.display = 'none';
        specimenDiv.style.display = 'none';
        mpDiv.style.display = 'none';
        if (cat === 'Produit') {
            productDiv.style.display = 'block';
        } else if (cat === 'Spécimen') {
            specimenDiv.style.display = 'block';
        } else if (cat === 'Matériel pédagogique') {
            mpDiv.style.display = 'block';
        }
    }
    categorieSelect.addEventListener('change', updateLinkedSelectorByCategory);
    updateLinkedSelectorByCategory();
</script>