@php
    $isEdit = isset($formation);
    $defaultCompteId = old('compte_id', $isEdit ? $formation->compte_id : '');
    $defaultContactId = old('contact_id', $isEdit ? $formation->contact_id : '');
    $defaultType = old('type', $isEdit ? $formation->type : '');
    $defaultCible = old('cible', $isEdit ? $formation->cible : '');
    $defaultDatesEcole = old('dates_ecole', $isEdit ? ($formation->dates_ecole ?? []) : []);
    $defaultDatesProposees = old('dates_proposees', $isEdit ? ($formation->dates_proposees ?? []) : []);
@endphp

{{-- Compte and Contact --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="compte_id" id="compte_id" class="frm-select" required>
                <option value="">— Sélectionnez —</option>
                @foreach($comptes as $c)
                    <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="contact_id">Contact <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="contact_id" id="contact_id" class="frm-select" required>
                <option value="">— Sélectionnez d'abord un compte —</option>
            </select>
        </div>
    </div>
</div>

{{-- Type and Cible --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="type">Type de formation <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="type" id="type" class="frm-select" required>
                <option value="">— Sélectionnez —</option>
                @foreach($types as $t)
                    <option value="{{ $t }}" {{ $defaultType == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="cible">Cible</label>
        <div class="frm-select-wrap">
            <select name="cible" id="cible" class="frm-select">
                <option value="">— Sélectionnez —</option>
                @foreach($cibles as $c)
                    <option value="{{ $c }}" {{ $defaultCible == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- Dates école (multiple) --}}
<div class="frm-group">
    <label class="frm-label">Dates proposées par l'école (plusieurs)</label>
    <div id="dates-ecole-container">
        @if(count($defaultDatesEcole))
            @foreach($defaultDatesEcole as $date)
            <div class="date-row" style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                <input type="date" name="dates_ecole[]" class="frm-input" style="flex: 1;" value="{{ $date }}">
                <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-date">X</button>
            </div>
            @endforeach
        @else
            <div class="date-row" style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                <input type="date" name="dates_ecole[]" class="frm-input" style="flex: 1;">
                <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-date">X</button>
            </div>
        @endif
    </div>
    <button type="button" class="btn-zn btn-zn-sm btn-zn-ghost add-date mt-1" data-target="dates-ecole-container">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Ajouter une date
    </button>
</div>

{{-- Dates proposées à l'école (multiple) --}}
<div class="frm-group">
    <label class="frm-label">Dates proposées à l'école (plusieurs)</label>
    <div id="dates-proposees-container">
        @if(count($defaultDatesProposees))
            @foreach($defaultDatesProposees as $date)
            <div class="date-row" style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                <input type="date" name="dates_proposees[]" class="frm-input" style="flex: 1;" value="{{ $date }}">
                <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-date">X</button>
            </div>
            @endforeach
        @else
            <div class="date-row" style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                <input type="date" name="dates_proposees[]" class="frm-input" style="flex: 1;">
                <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-date">X</button>
            </div>
        @endif
    </div>
    <button type="button" class="btn-zn btn-zn-sm btn-zn-ghost add-date mt-1" data-target="dates-proposees-container">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Ajouter une date
    </button>
</div>

@if($isEdit)
<div class="frm-group">
    <label class="frm-label" for="statut">Statut</label>
    <div class="frm-select-wrap">
        <select name="statut" id="statut" class="frm-select">
            @foreach($statuts as $key => $label)
                <option value="{{ $key }}" {{ old('statut', $formation->statut) == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif

<script>
    // Load contacts dynamically
    const compteSelect = document.getElementById('compte_id');
    const contactSelect = document.getElementById('contact_id');
    if (compteSelect) {
        compteSelect.addEventListener('change', function() {
            let compteId = this.value;
            if (!compteId) {
                contactSelect.innerHTML = '<option value="">— Sélectionnez d\'abord un compte —</option>';
                return;
            }
            fetch(`/api/comptes/${compteId}/contacts`)
                .then(r => r.json())
                .then(data => {
                    let html = '<option value="">— Sélectionnez un contact —</option>';
                    data.forEach(c => html += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`);
                    contactSelect.innerHTML = html;
                    const defaultContactId = '{{ $defaultContactId }}';
                    if (defaultContactId) contactSelect.value = defaultContactId;
                });
        });
        if (compteSelect.value) {
            compteSelect.dispatchEvent(new Event('change'));
        }
    }

    // Remove date row
    function attachRemoveEvent(row) {
        const removeBtn = row.querySelector('.remove-date');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => row.remove());
        }
    }

    // Add date row (generic)
    document.querySelectorAll('.add-date').forEach(button => {
        button.addEventListener('click', function() {
            const containerId = this.dataset.target;
            const container = document.getElementById(containerId);
            const isEcole = containerId === 'dates-ecole-container';
            const inputName = isEcole ? 'dates_ecole[]' : 'dates_proposees[]';
            const newRow = document.createElement('div');
            newRow.className = 'date-row';
            newRow.style.cssText = 'display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;';
            newRow.innerHTML = `
                <input type="date" name="${inputName}" class="frm-input" style="flex: 1;">
                <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-date">X</button>
            `;
            container.appendChild(newRow);
            attachRemoveEvent(newRow);
        });
    });

    // Attach to existing rows
    document.querySelectorAll('.date-row').forEach(row => attachRemoveEvent(row));
</script>