@php
    $isEdit = isset($examen);
    $defaultCompteId = old('compte_id', $examen->compte_id ?? '');
    $defaultContactId = old('contact_id', $examen->contact_id ?? '');
    $defaultYearId = old('annee_scolaire_id', $examen->annee_scolaire_id ?? ($currentYear->id ?? null));
    $defaultDateDemande = old('date_demande', $examen->date_demande ?? now()->format('Y-m-d'));
    $defaultLangue = old('langue', $examen->langue ?? '');
    $defaultOrganisme = old('organisme', $examen->organisme ?? '');
    $defaultNiveauCECR = old('niveau_cecr', $examen->niveau_cecr ?? '');
    $defaultTitre = old('titre', $examen->titre ?? '');
    $defaultAbreviation = old('abreviation', $examen->abreviation ?? '');
    $defaultNiveauxScolaires = old('niveaux_scolaires', $examen->niveaux_scolaires ?? []);
    $defaultDateExamen = old('date_examen', $examen->date_examen ?? '');
    $defaultDescription = old('description', $examen->description ?? '');
    $defaultObservations = old('observations', $examen->observations ?? '');
@endphp

{{-- Compte and Contact --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="compte_id" id="compte_id" class="frm-select" required>
                <option value="">-- Sélectionnez --</option>
                @foreach($comptes as $c)
                    <option value="{{ $c->id }}" {{ (old('compte_id', $selectedCompteId ?? '') == $c->id) ? 'selected' : '' }}>
                        {{ $c->etablissement }} ({{ $c->ville->nom }})
                    </option>
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

{{-- Année scolaire and Date demande --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1; min-width: 180px;">
        <label class="frm-label" for="annee_scolaire_id">Année scolaire <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="annee_scolaire_id" id="annee_scolaire_id" class="frm-select" required>
                @foreach($years as $y)
                    <option value="{{ $y->id }}" {{ $defaultYearId == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="frm-group" style="flex: 1; min-width: 150px;">
        <label class="frm-label" for="date_demande">Date demande <span class="req">*</span></label>
        <input type="date" name="date_demande" id="date_demande" class="frm-input" value="{{ $defaultDateDemande }}" required>
    </div>
</div>

{{-- Langue, Organisme, Niveau CECR --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1; min-width: 150px;">
        <label class="frm-label" for="langue">Langue</label>
        <div class="frm-select-wrap">
            <select name="langue" id="langue" class="frm-select">
                @foreach($langues as $l)
                    <option value="{{ $l }}" {{ $defaultLangue == $l ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="frm-group" style="flex: 1; min-width: 150px;">
        <label class="frm-label" for="organisme">Organisme</label>
        <div class="frm-select-wrap">
            <select name="organisme" id="organisme" class="frm-select">
                @foreach($organismes as $o)
                    <option value="{{ $o }}" {{ $defaultOrganisme == $o ? 'selected' : '' }}>{{ $o }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="frm-group" style="flex: 1; min-width: 150px;">
        <label class="frm-label" for="niveau_cecr">Niveau CECR</label>
        <div class="frm-select-wrap">
            <select name="niveau_cecr" id="niveau_cecr" class="frm-select">
                <option value="">—</option>
                @foreach($niveauxCECR as $n)
                    <option value="{{ $n }}" {{ $defaultNiveauCECR == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- Titre and Abréviation --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 2; min-width: 200px;">
        <label class="frm-label" for="titre">Titre de l'examen <span class="req">*</span></label>
        <input type="text" name="titre" id="titre" class="frm-input" value="{{ $defaultTitre }}" required>
    </div>
    <div class="frm-group" style="flex: 1; min-width: 150px;">
        <label class="frm-label" for="abreviation">Abréviation</label>
        <input type="text" name="abreviation" id="abreviation" class="frm-input" value="{{ $defaultAbreviation }}">
    </div>
</div>

{{-- Niveaux scolaires (multiple) --}}
{{-- Niveau scolaire (single dropdown) --}}
<div class="frm-group">
    <label class="frm-label" for="niveau_scolaire">Niveau scolaire</label>
    <div class="frm-select-wrap">
        <select name="niveau_scolaire" id="niveau_scolaire" class="frm-select">
            <option value="">— Sélectionnez un niveau —</option>
            @foreach($niveauxScolaires as $ns)
                <option value="{{ $ns }}" {{ old('niveau_scolaire', $examen->niveau_scolaire ?? '') == $ns ? 'selected' : '' }}>{{ $ns }}</option>
            @endforeach
        </select>
    </div>
</div>



{{-- Date examen (prévue) --}}
<div class="frm-group">
    <label class="frm-label" for="date_examen">Date examen (prévue)</label>
    <input type="date" name="date_examen" id="date_examen" class="frm-input" value="{{ $defaultDateExamen }}">
</div>

{{-- Description --}}
<div class="frm-group">
    <label class="frm-label" for="description">Description</label>
    <textarea name="description" id="description" class="frm-input" rows="2">{{ $defaultDescription }}</textarea>
</div>

{{-- Observations --}}
<div class="frm-group">
    <label class="frm-label" for="observations">Observations</label>
    <textarea name="observations" id="observations" class="frm-input" rows="2">{{ $defaultObservations }}</textarea>
</div>

<hr>

<h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Épreuves</h3>
<div id="epreuves-container">
    @if(isset($examen) && $examen->epreuves)
        @foreach($examen->epreuves as $index => $ep)
            <div class="epreuve-row" style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem;">
                <div style="flex: 2;">
                    <input type="text" name="epreuves[{{$index}}][epreuve]" class="frm-input" value="{{ $ep->epreuve }}" placeholder="Épreuve">
                </div>
                <div style="flex: 1;">
                    <input type="number" name="epreuves[{{$index}}][duree]" class="frm-input" value="{{ $ep->duree }}" placeholder="Durée (min)">
                </div>
                <div style="flex: 1;">
                    <input type="date" name="epreuves[{{$index}}][date_realisation]" class="frm-input" value="{{ $ep->date_realisation }}">
                </div>
                <div>
                    <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-epreuve">X</button>
                </div>
                <input type="hidden" name="epreuves[{{$index}}][id]" value="{{ $ep->id }}">
            </div>
        @endforeach
    @else
        <div class="epreuve-row" style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem;">
            <div style="flex: 2;">
                <input type="text" name="epreuves[0][epreuve]" class="frm-input" placeholder="Épreuve">
            </div>
            <div style="flex: 1;">
                <input type="number" name="epreuves[0][duree]" class="frm-input" placeholder="Durée (min)">
            </div>
            <div style="flex: 1;">
                <input type="date" name="epreuves[0][date_realisation]" class="frm-input">
            </div>
            <div>
                <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-epreuve">X</button>
            </div>
        </div>
    @endif
</div>
<button type="button" id="add-epreuve" class="btn-zn btn-zn-sm btn-zn-ghost mt-2">
    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Ajouter une épreuve
</button>

<style>
    .frm-hint {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.3rem;
    }
</style>

<script>
    // Dynamic contact loading based on compte
    document.getElementById('compte_id')?.addEventListener('change', function() {
        let compteId = this.value;
        let contactSelect = document.getElementById('contact_id');
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
    // Trigger on load
    if (document.getElementById('compte_id')?.value) {
        document.getElementById('compte_id').dispatchEvent(new Event('change'));
    }

    // Dynamic epreuves rows
    let epreuveIndex = {{ isset($examen) ? $examen->epreuves->count() : 1 }};
    document.getElementById('add-epreuve')?.addEventListener('click', function() {
        const container = document.getElementById('epreuves-container');
        const newRow = document.createElement('div');
        newRow.className = 'epreuve-row';
        newRow.style.cssText = 'display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem;';
        newRow.innerHTML = `
            <div style="flex: 2;">
                <input type="text" name="epreuves[${epreuveIndex}][epreuve]" class="frm-input" placeholder="Épreuve">
            </div>
            <div style="flex: 1;">
                <input type="number" name="epreuves[${epreuveIndex}][duree]" class="frm-input" placeholder="Durée (min)">
            </div>
            <div style="flex: 1;">
                <input type="date" name="epreuves[${epreuveIndex}][date_realisation]" class="frm-input">
            </div>
            <div>
                <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-epreuve">X</button>
            </div>
        `;
        container.appendChild(newRow);
        attachRemoveEvent(newRow);
        epreuveIndex++;
    });

    function attachRemoveEvent(row) {
        row.querySelector('.remove-epreuve')?.addEventListener('click', () => row.remove());
    }
    document.querySelectorAll('.epreuve-row').forEach(row => attachRemoveEvent(row));
</script>