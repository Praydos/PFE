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
        <label>Module lié</label>
        <select name="module_lie" class="form-select" id="module_lie">
            <option value="">-- Aucun --</option>
            @foreach($modules as $m)
                <option value="{{ $m }}" {{ old('module_lie', $isEdit ? $reclamation->module_lie : '') == $m ? 'selected' : '' }}>{{ ucfirst($m) }}</option>
            @endforeach
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
        <label>Sous-catégorie</label>
        <select name="sous_categorie" id="sous_categorie" class="form-select">
            <option value="">-- Sélectionnez --</option>
        </select>
    </div>
</div>

<div class="mb-3">
    <label>Description *</label>
    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $isEdit ? $reclamation->description : '') }}</textarea>
</div>

@if($isEdit)
<div class="row">
    <div class="col-md-4 mb-3">
        <label>Date échéance</label>
        <input type="date" name="date_echeance" class="form-control" value="{{ old('date_echeance', $isEdit && $reclamation->date_echeance ? $reclamation->date_echeance->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>Priorité</label>
        <select name="priorite" class="form-select">
            <option value="basse" {{ old('priorite', $isEdit ? $reclamation->priorite : '') == 'basse' ? 'selected' : '' }}>Basse</option>
            <option value="moyenne" {{ old('priorite', $isEdit ? $reclamation->priorite : '') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
            <option value="haute" {{ old('priorite', $isEdit ? $reclamation->priorite : '') == 'haute' ? 'selected' : '' }}>Haute</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label>Statut *</label>
        <select name="statut" class="form-select" required>
            @foreach($statuts as $s)
                <option value="{{ $s }}" {{ old('statut', $isEdit ? $reclamation->statut : '') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <label>Analyse</label>
    <textarea name="analyse" class="form-control" rows="2">{{ old('analyse', $isEdit ? $reclamation->analyse : '') }}</textarea>
</div>
<div class="mb-3">
    <label>Réponse</label>
    <textarea name="reponse" class="form-control" rows="2">{{ old('reponse', $isEdit ? $reclamation->reponse : '') }}</textarea>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label>Responsable</label>
        <select name="responsable_id" class="form-select">
            <option value="">-- Sélectionnez --</option>
            @foreach(\App\Models\User::whereIn('role', ['admin','rbo'])->get() as $u)
                <option value="{{ $u->id }}" {{ old('responsable_id', $isEdit ? $reclamation->responsable_id : '') == $u->id ? 'selected' : '' }}>{{ $u->prenom }} {{ $u->nom }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label>Date clôture</label>
        <input type="date" name="date_cloture" class="form-control" value="{{ old('date_cloture', $isEdit && $reclamation->date_cloture ? $reclamation->date_cloture->format('Y-m-d') : '') }}">
    </div>
</div>
@endif

<script>
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

    // Load sous-catégories dynamically
    const categoriesMap = @json($sousCategories);
    document.getElementById('categorie')?.addEventListener('change', function() {
        let cat = this.value;
        let sousSelect = document.getElementById('sous_categorie');
        let options = '<option value="">-- Sélectionnez --</option>';
        if (categoriesMap[cat]) {
            categoriesMap[cat].forEach(sub => options += `<option value="${sub}">${sub}</option>`);
        }
        sousSelect.innerHTML = options;
    });
    if (document.getElementById('categorie')?.value) {
        document.getElementById('categorie').dispatchEvent(new Event('change'));
    }
</script>