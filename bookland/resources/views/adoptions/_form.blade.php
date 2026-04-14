@php
    $isEdit = isset($adoption) && $adoption;
    $defaultCompteId = $defaultCompteId ?? ($isEdit ? $adoption->compte_id : old('compte_id'));
    $defaultProductId = $defaultProductId ?? ($isEdit ? $adoption->product_id : old('product_id'));
    $defaultYearId = $defaultYearId ?? ($isEdit ? $adoption->annee_scolaire_id : old('annee_scolaire_id'));
    $defaultQuantity = $defaultQuantity ?? ($isEdit ? $adoption->quantity : old('quantity'));
    $defaultDate = $defaultDate ?? ($isEdit && $adoption->date_adoption ? $adoption->date_adoption->format('Y-m-d') : old('date_adoption', now()->format('Y-m-d')));
    $defaultNiveau = $defaultNiveau ?? ($isEdit ? $adoption->niveau : old('niveau'));
    $defaultCycle = $defaultCycle ?? ($isEdit ? $adoption->cycle : old('cycle'));
    $defaultContactId = $defaultContactId ?? ($isEdit ? $adoption->contact_id : old('contact_id'));
    $defaultMethode = $defaultMethode ?? ($isEdit ? $adoption->methode : old('methode'));
@endphp

<div class="form-row" style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem;">
    <div class="frm-group" style="flex:1; min-width:200px;">
        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="compte_id" id="compte_id" class="frm-select" required>
                <option value="">-- Sélectionnez --</option>
                @foreach($comptes as $c)
                    <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="frm-group" style="flex:1; min-width:200px;">
        <label class="frm-label" for="product_id">Produit <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="product_id" id="product_id" class="frm-select" required>
                <option value="">-- Sélectionnez --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ $defaultProductId == $p->id ? 'selected' : '' }}>{{ $p->titre }} ({{ $p->isbn_13 ?? $p->isbn_10 }})</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-row" style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem;">
    <div class="frm-group" style="flex:1; min-width:180px;">
        <label class="frm-label" for="annee_scolaire_id">Année scolaire <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="annee_scolaire_id" id="annee_scolaire_id" class="frm-select" required>
                @foreach($years as $y)
                    <option value="{{ $y->id }}" {{ $defaultYearId == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="frm-group" style="flex:1; min-width:120px;">
        <label class="frm-label" for="quantity">Quantité (effectif) <span class="req">*</span></label>
        <input type="number" name="quantity" id="quantity" class="frm-input" value="{{ $defaultQuantity }}" required readonly style="background-color:#f5f5f5; cursor:not-allowed;">
        <small class="text-muted">Automatique – basé sur l'effectif validé</small>
    </div>
    <div class="frm-group" style="flex:1; min-width:150px;">
        <label class="frm-label" for="date_adoption">Date adoption <span class="req">*</span></label>
        <input type="date" name="date_adoption" id="date_adoption" class="frm-input" value="{{ $defaultDate }}" required>
    </div>
</div>

<div class="form-row" style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem;">
    <div class="frm-group" style="flex:1; min-width:150px;">
        <label class="frm-label" for="niveau">Niveau scolaire</label>
        <select name="niveau" id="niveau" class="frm-select" required>
            <option value="">-- Sélectionnez d'abord un compte --</option>
        </select>
    </div>
    <div class="frm-group" style="flex:1; min-width:150px;">
        <label class="frm-label" for="cycle">Cycle <span class="req">*</span></label>
        <select name="cycle" id="cycle" class="frm-select" required>
            <option value="">-- Sélectionnez un cycle --</option>
            <option value="primaire" {{ $defaultCycle == 'primaire' ? 'selected' : '' }}>Primaire</option>
            <option value="college" {{ $defaultCycle == 'college' ? 'selected' : '' }}>Collège</option>
            <option value="Lycée" {{ $defaultCycle == 'Lycée' ? 'selected' : '' }}>Lycée</option>
            <option value="Learners" {{ $defaultCycle == 'Learners' ? 'selected' : '' }}>Learners</option>
            <option value="Pre-teens" {{ $defaultCycle == 'Pre-teens' ? 'selected' : '' }}>Pre-teens</option>
            <option value="Teens" {{ $defaultCycle == 'Teens' ? 'selected' : '' }}>Teens</option>
            <option value="Adults" {{ $defaultCycle == 'Adults' ? 'selected' : '' }}>Adults</option>
        </select>
    </div>
    <div class="frm-group" style="flex:1; min-width:200px;">
        <label class="frm-label">Contact *</label>
        <select name="contact_id" id="contact_id" class="frm-select" required>
            <option value="">-- Sélectionnez d'abord un compte --</option>
        </select>
    </div>
    <div class="frm-group" style="flex:1; min-width:200px;">
        <label class="frm-label">Méthode *</label>
        <input type="text" name="methode" class="frm-input" value="{{ $defaultMethode }}" required>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const compteSelect = document.getElementById('compte_id');
    const yearSelect = document.getElementById('annee_scolaire_id');
    const niveauSelect = document.getElementById('niveau');
    const cycleSelect = document.getElementById('cycle');
    const contactSelect = document.getElementById('contact_id');
    const quantityInput = document.getElementById('quantity');

    // Load niveaux based on compte
    function loadNiveaux() {
        const compteId = compteSelect.value;
        if (!compteId) {
            niveauSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/niveaux`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez un niveau --</option>';
                data.forEach(n => options += `<option value="${n}">${n}</option>`);
                niveauSelect.innerHTML = options;
                const defaultNiveau = '{{ $defaultNiveau }}';
                if (defaultNiveau) niveauSelect.value = defaultNiveau;
                fetchEffectif();
            })
            .catch(err => console.error('Erreur chargement niveaux:', err));
    }

    // Load contacts based on compte
    function loadContacts() {
        const compteId = compteSelect.value;
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
                const defaultContactId = '{{ $defaultContactId }}';
                if (defaultContactId) contactSelect.value = defaultContactId;
            })
            .catch(err => console.error('Erreur chargement contacts:', err));
    }

    // Fetch effectif and auto-fill quantity
    function fetchEffectif() {
        const compteId = compteSelect.value;
        const yearId = yearSelect.value;
        const niveau = niveauSelect.value;
        const cycle = cycleSelect.value;

        if (!compteId || !yearId || !niveau || !cycle) {
            quantityInput.value = '';
            return;
        }

        fetch(`/api/comptes/${compteId}/effectif?annee_scolaire_id=${yearId}&niveau=${encodeURIComponent(niveau)}&cycle=${encodeURIComponent(cycle)}`)
            .then(r => r.json())
            .then(data => {
                if (data.effectif_valide !== null && data.effectif_valide > 0) {
                    quantityInput.value = data.effectif_valide;
                } else {
                    quantityInput.value = '';
                }
            })
            .catch(err => console.error('Erreur chargement effectif:', err));
    }

    // Event listeners
    compteSelect?.addEventListener('change', function() {
        loadNiveaux();
        loadContacts();
    });
    yearSelect?.addEventListener('change', fetchEffectif);
    niveauSelect?.addEventListener('change', fetchEffectif);
    cycleSelect?.addEventListener('change', fetchEffectif);

    // Initial load (for edit mode)
    if (compteSelect?.value) {
        loadNiveaux();
        loadContacts();
    }
    if (niveauSelect?.value && cycleSelect?.value && compteSelect?.value && yearSelect?.value) {
        fetchEffectif();
    }
});
</script>