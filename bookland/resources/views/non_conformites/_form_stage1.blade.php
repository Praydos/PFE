@php
    $isEdit = isset($non_conformite);
@endphp

{{-- Compte and Contact --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label>Compte *</label>
        <select name="compte_id" id="compte_id" class="form-select" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($comptes as $c)
                <option value="{{ $c->id }}" {{ old('compte_id', $isEdit ? $non_conformite->compte_id : '') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
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

{{-- Category and Sub‑category --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label>Catégorie *</label>
        <select name="categorie" id="categorie" class="form-select" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($categories as $c)
                <option value="{{ $c }}" {{ old('categorie', $isEdit ? $non_conformite->categorie : '') == $c ? 'selected' : '' }}>{{ $c }}</option>
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

{{-- Linked item selector (appears only for relevant sous‑catégories) --}}
<div id="linked_item_container" style="display: none;" class="mb-3">
    <label id="linked_item_label"></label>
    <select name="module_id" id="module_id" class="form-select">
        <option value="">-- Sélectionnez --</option>
    </select>
    <input type="hidden" name="module_type" id="module_type">
</div>

{{-- Evaluation field (only for AUDIT category) --}}
<div class="mb-3" id="evaluation_group" style="display: none;">
    <label>Évaluation *</label>
    <select name="evaluation" class="form-select">
        <option value="">-- Sélectionnez --</option>
        <option value="observation" {{ old('evaluation', $isEdit ? $non_conformite->evaluation : '') == 'observation' ? 'selected' : '' }}>Observation</option>
        <option value="ameliorer" {{ old('evaluation', $isEdit ? $non_conformite->evaluation : '') == 'ameliorer' ? 'selected' : '' }}>Améliorer</option>
        <option value="MINEUR" {{ old('evaluation', $isEdit ? $non_conformite->evaluation : '') == 'MINEUR' ? 'selected' : '' }}>Mineur</option>
        <option value="MAJEUR" {{ old('evaluation', $isEdit ? $non_conformite->evaluation : '') == 'MAJEUR' ? 'selected' : '' }}>Majeur</option>
    </select>
    <small class="text-muted">Obligatoire pour la catégorie Audit & Contrôle Interne.</small>
</div>

{{-- Object and Description --}}
<div class="mb-3">
    <label>Objet *</label>
    <input type="text" name="objet" class="form-control" value="{{ old('objet', $isEdit ? $non_conformite->objet : '') }}" required>
</div>

<div class="mb-3">
    <label>Description *</label>
    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $isEdit ? $non_conformite->description : '') }}</textarea>
</div>

<script>
    // --------------------------------------------------------------
    // 1. Load contacts when compte changes
    // --------------------------------------------------------------
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
                    contactSelect.value = '{{ old('contact_id', $non_conformite->contact_id) }}';
                @endif
            });
    });
    if (document.getElementById('compte_id')?.value) {
        document.getElementById('compte_id').dispatchEvent(new Event('change'));
    }

    // --------------------------------------------------------------
    // 2. Load sous‑catégories based on main category
    // --------------------------------------------------------------
    const sousCategoriesMap = @json($sousCategoriesMap);
    const categorieSelect = document.getElementById('categorie');
    const sousSelect = document.getElementById('sous_categorie');
    const evaluationGroup = document.getElementById('evaluation_group');

    function updateSousCategories() {
        let cat = categorieSelect.value;
        let options = '<option value="">-- Sélectionnez --</option>';
        if (sousCategoriesMap[cat]) {
            sousCategoriesMap[cat].forEach(sub => options += `<option value="${sub}">${sub}</option>`);
        }
        sousSelect.innerHTML = options;

        // Show/hide evaluation field (only for audit)
        if (cat === 'AUDIT & CONTROLE INTERNE') {
            evaluationGroup.style.display = 'block';
        } else {
            evaluationGroup.style.display = 'none';
            document.querySelector('select[name="evaluation"]').value = '';
        }
    }
    categorieSelect.addEventListener('change', updateSousCategories);
    updateSousCategories();

    // --------------------------------------------------------------
    // 3. Linked item selector (product, specimen, MP, exam, formation, event)
    // --------------------------------------------------------------
    const linkedContainer = document.getElementById('linked_item_container');
    const linkedLabel = document.getElementById('linked_item_label');
    const linkedSelect = document.getElementById('module_id');
    const moduleTypeInput = document.getElementById('module_type');

    const linkedOptions = @json($linkedItemsOptions);

    function updateLinkedSelector() {
        const sousCat = sousSelect.value;
        let moduleType = null;
        let label = '';

        if (sousCat === 'Produit') {
            moduleType = 'product';
            label = 'Sélectionnez un produit';
        } else if (sousCat === 'Spécimen') {
            moduleType = 'specimen';
            label = 'Sélectionnez un spécimen (BSS)';
        } else if (sousCat === 'Matériel Pédagogique') {
            moduleType = 'mp';
            label = 'Sélectionnez une livraison MP';
        } else if (sousCat === 'Examen') {
            moduleType = 'examen';
            label = 'Sélectionnez un examen';
        } else if (sousCat === 'Formation') {
            moduleType = 'formation';
            label = 'Sélectionnez une formation';
        } else if (sousCat === 'Événement') {
            moduleType = 'event';
            label = 'Sélectionnez un événement';
        }

        if (moduleType && linkedOptions[moduleType]) {
            linkedContainer.style.display = 'block';
            linkedLabel.textContent = label;
            moduleTypeInput.value = moduleType;

            let options = '<option value="">-- Sélectionnez --</option>';
            const selectedId = '{{ old('module_id', $isEdit ? ($non_conformite->module_id ?? '') : '') }}';
            linkedOptions[moduleType].forEach(item => {
                const selected = (selectedId == item.id) ? 'selected' : '';
                options += `<option value="${item.id}" ${selected}>${item.label}</option>`;
            });
            linkedSelect.innerHTML = options;
        } else {
            linkedContainer.style.display = 'none';
            moduleTypeInput.value = '';
            linkedSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
        }
    }

    // Call when sous‑catégorie changes
    sousSelect.addEventListener('change', updateLinkedSelector);
    // Initial call (in case of edit mode)
    updateLinkedSelector();
</script>