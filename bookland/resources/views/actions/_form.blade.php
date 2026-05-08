@php
    $isEdit                  = isset($action);
    $defaultObjet            = old('objet',                  $isEdit ? $action->objet                                    : '');
    $defaultCompteId         = old('compte_id', $isEdit ? $action->compte_id : ($selectedCompteId ?? ''));
    // $defaultDate             = old('date_planification',     $isEdit ? $action->date_planification->format('Y-m-d')       : '');
    $defaultHeure            = old('heure',                  $isEdit ? $action->heure                                    : '');
    $defaultDuree            = old('duree',                  $isEdit ? $action->duree                                    : '');
    $defaultLieu             = old('lieu',                   $isEdit ? $action->lieu                                     : '');
    $defaultRappel           = old('rappel',                 $isEdit ? $action->rappel                                   : false);
    $defaultRappelAvant      = old('rappel_avant',           $isEdit ? $action->rappel_avant                             : '');
    $defaultRecurrenceFreq   = old('recurrence_frequence',   $isEdit ? $action->recurrence_frequence                     : '');
    $defaultRecurrenceInt    = old('recurrence_intervalle',  $isEdit ? $action->recurrence_intervalle                    : '');
    $defaultRecurrenceFin    = old('recurrence_fin',         $isEdit && $action->recurrence_fin ? $action->recurrence_fin->format('Y-m-d') : '');
    $defaultDate = old(
    'date_planification',
    $isEdit
        ? $action->date_planification->format('Y-m-d')
        : ($prefilledDate ?? now()->toDateString())
);
@endphp

{{-- ── Objet & Compte ─────────────────────────────── --}}
<div class="ac-sec">Informations générales</div>
<div class="ac-row ac-row-2">

    <div class="ac-group">
        <label class="ac-label" for="objet">
            Objet <span class="req">*</span>
        </label>
        <input type="text" name="objet" id="objet"
               class="ac-input {{ $errors->has('objet') ? 'err' : '' }}"
               value="{{ $defaultObjet }}"
               placeholder="Ex : Visite client, présentation catalogue…"
               required>
        @error('objet')
            <span class="ac-error">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $message }}
            </span>
        @enderror
    </div>

    <div class="ac-group">
        <label class="ac-label" for="compte_id">
            Compte <span class="req">*</span>
        </label>
        <div class="ac-sel-wrap">
            <select name="compte_id" id="compte_id"
                    class="ac-select {{ $errors->has('compte_id') ? 'err' : '' }}"
                    required>
                <option value="">— Sélectionnez un compte —</option>
                @foreach($comptes as $c)
                    <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>
                        {{ $c->etablissement }}
                        @if($c->ville) · {{ $c->ville->nom }} @endif
                    </option>
                @endforeach
            </select>
        </div>
        @error('compte_id')
            <span class="ac-error">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $message }}
            </span>
        @enderror
    </div>

</div>

{{-- ── Planification ───────────────────────────────── --}}
<div class="ac-sec">Planification</div>
<div class="ac-row ac-row-3">

    <div class="ac-group">
        <label class="ac-label" for="date_planification">
            Date <span class="req">*</span>
        </label>
        <input type="date" name="date_planification" id="date_planification"
               class="ac-input {{ $errors->has('date_planification') ? 'err' : '' }}"
               value="{{ old('date_planification', $defaultDate) }}" required>
        @error('date_planification')
            <span class="ac-error"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
        @enderror
    </div>

    <div class="ac-group" style="grid-column:span 2;">
        <label class="ac-label" for="lieu">
            Lieu <span class="opt">(optionnel)</span>
        </label>
        <div style="position:relative;">
            <span style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:var(--t3);pointer-events:none;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </span>
            <input type="text" name="lieu" id="lieu"
                   class="ac-input"
                   style="padding-left:2.3rem;"
                   value="{{ $defaultLieu }}"
                   placeholder="Ex : Siège client, Casablanca…">
        </div>
    </div>

</div>

{{-- ── Rappel ──────────────────────────────────────── --}}
<div class="ac-sec">Rappel</div>
<div class="ac-row ac-row-2" style="align-items:start;">

    <div class="ac-group">
        <label class="ac-check-row" for="rappel" style="padding:.7rem 1rem;background:var(--subtle);border:1px solid var(--border);border-radius:var(--r2);transition:all var(--t);">
            <input type="checkbox" name="rappel" id="rappel" value="1"
                   {{ $defaultRappel ? 'checked' : '' }}>
            <div>
                <div style="font-size:.82rem;font-weight:600;color:var(--t1);">Activer le rappel</div>
                <div style="font-size:.73rem;color:var(--t3);margin-top:.1rem;">Envoyer une notification avant l'action</div>
            </div>
        </label>
    </div>

    <div class="ac-group" id="rappel_avant_group" style="{{ $defaultRappel ? '' : 'display:none;' }}">
        <label class="ac-label" for="rappel_avant">Minutes avant</label>
        <div style="position:relative;">
            <span style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:var(--t3);pointer-events:none;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </span>
            <input type="number" name="rappel_avant" id="rappel_avant"
                   class="ac-input"
                   style="padding-left:2.3rem;"
                   value="{{ $defaultRappelAvant }}"
                   min="1" placeholder="Ex : 30">
        </div>
        <span class="ac-hint">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Nombre de minutes avant la date planifiée
        </span>
    </div>

</div>

{{-- ── Récurrence ──────────────────────────────────── --}}
<div class="ac-sec">Récurrence <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.72rem;color:var(--t4);">(optionnel)</span></div>
<div class="ac-row ac-row-3">

    <div class="ac-group">
        <label class="ac-label" for="recurrence_frequence">Fréquence</label>
        <div class="ac-sel-wrap">
            <select name="recurrence_frequence" id="recurrence_frequence" class="ac-select">
                <option value="">Aucune</option>
                <option value="daily"   {{ $defaultRecurrenceFreq == 'daily'   ? 'selected' : '' }}>Journalière</option>
                <option value="weekly"  {{ $defaultRecurrenceFreq == 'weekly'  ? 'selected' : '' }}>Hebdomadaire</option>
                <option value="monthly" {{ $defaultRecurrenceFreq == 'monthly' ? 'selected' : '' }}>Mensuelle</option>
                <option value="yearly"  {{ $defaultRecurrenceFreq == 'yearly'  ? 'selected' : '' }}>Annuelle</option>
            </select>
        </div>
    </div>

    <div class="ac-group">
        <label class="ac-label" for="recurrence_intervalle">
            Intervalle <span class="opt">(tous les N)</span>
        </label>
        <input type="number" name="recurrence_intervalle" id="recurrence_intervalle"
               class="ac-input"
               value="{{ $defaultRecurrenceInt }}"
               min="1" placeholder="1">
    </div>

    <div class="ac-group">
        <label class="ac-label" for="recurrence_fin">Date de fin</label>
        <input type="date" name="recurrence_fin" id="recurrence_fin"
               class="ac-input"
               value="{{ $defaultRecurrenceFin }}">
    </div>

</div>






<script>
(function () {
    const rappelCb    = document.getElementById('rappel');
    const rappelGroup = document.getElementById('rappel_avant_group');
    if (rappelCb && rappelGroup) {
        rappelCb.addEventListener('change', function () {
            rappelGroup.style.display = this.checked ? '' : 'none';
        });
    }
})();

// Auto-fill lieu when compte is selected
document.addEventListener('DOMContentLoaded', function() {
    const compteSelect = document.getElementById('compte_id');
    const lieuInput = document.getElementById('lieu');

    if (!compteSelect || !lieuInput) return;

    function setLieuFromCompte(compteId) {
        if (!compteId) return;
        fetch(`/api/comptes/${compteId}/details`)
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                const zoneName = data.zone_name || '—';
                const villeName = data.ville_nom || '—';
                lieuInput.value = `Zone: ${zoneName} - Ville: ${villeName}`;
            })
            .catch(err => console.error('Error fetching compte details:', err));
    }

    // When compte changes, update lieu
    compteSelect.addEventListener('change', function() {
        if (this.value) {
            setLieuFromCompte(this.value);
        } else {
            lieuInput.value = '';
        }
    });

    // If a compte is already pre‑selected (e.g., from shortcut), trigger change
    if (compteSelect.value) {
        setLieuFromCompte(compteSelect.value);
    }
});
</script>