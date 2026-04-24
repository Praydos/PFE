@php
    $isEdit = isset($action);
    $defaultObjet = old('objet', $isEdit ? $action->objet : '');
    $defaultCompteId = old('compte_id', $isEdit ? $action->compte_id : '');
    $defaultDate = old('date_planification', $isEdit ? $action->date_planification->format('Y-m-d') : '');
    $defaultHeure = old('heure', $isEdit ? $action->heure : '');
    $defaultDuree = old('duree', $isEdit ? $action->duree : '');
    $defaultLieu = old('lieu', $isEdit ? $action->lieu : '');
    $defaultRappel = old('rappel', $isEdit ? $action->rappel : false);
    $defaultRappelAvant = old('rappel_avant', $isEdit ? $action->rappel_avant : '');
    $defaultRecurrenceFreq = old('recurrence_frequence', $isEdit ? $action->recurrence_frequence : '');
    $defaultRecurrenceInterval = old('recurrence_intervalle', $isEdit ? $action->recurrence_intervalle : '');
    $defaultRecurrenceFin = old('recurrence_fin', $isEdit && $action->recurrence_fin ? $action->recurrence_fin->format('Y-m-d') : '');
@endphp

{{-- Objet and Compte --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="objet">Objet <span class="req">*</span></label>
        <input type="text" name="objet" id="objet" class="frm-input" value="{{ $defaultObjet }}" required>
    </div>
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="compte_id" id="compte_id" class="frm-select" required>
                <option value="">— Sélectionnez —</option>
                @foreach($comptes as $c)
                    <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>
                        {{ $c->etablissement }} ({{ $c->ville->nom }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- Date, Heure, Durée, Lieu --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1; min-width: 120px;">
        <label class="frm-label" for="date_planification">Date <span class="req">*</span></label>
        <input type="date" name="date_planification" id="date_planification" class="frm-input" value="{{ $defaultDate }}" required>
    </div>
    <div class="frm-group" style="flex: 0.8; min-width: 100px;">
        <label class="frm-label" for="heure">Heure</label>
        <input type="time" name="heure" id="heure" class="frm-input" value="{{ $defaultHeure }}" step="60">
    </div>
    <div class="frm-group" style="flex: 0.8; min-width: 100px;">
        <label class="frm-label" for="duree">Durée (min)</label>
        <input type="number" name="duree" id="duree" class="frm-input" value="{{ $defaultDuree }}" min="0">
    </div>
    <div class="frm-group" style="flex: 1.5; min-width: 150px;">
        <label class="frm-label" for="lieu">Lieu</label>
        <input type="text" name="lieu" id="lieu" class="frm-input" value="{{ $defaultLieu }}">
    </div>
</div>



{{-- Rappel --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
    <div class="frm-group" style="flex: 1;">
        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
            <input type="checkbox" name="rappel" id="rappel" value="1" style="width: 1rem; height: 1rem; accent-color: var(--blue);" {{ $defaultRappel ? 'checked' : '' }}>
            <span class="frm-label" style="margin:0;">Rappel</span>
        </label>
    </div>
    <div class="frm-group" style="flex: 2;" id="rappel_avant_group" {{ $defaultRappel ? '' : 'style=display:none;' }}>
        <label class="frm-label" for="rappel_avant">Minutes avant</label>
        <input type="number" name="rappel_avant" id="rappel_avant" class="frm-input" value="{{ $defaultRappelAvant }}" min="1">
    </div>
</div>

{{-- Récurrence --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1.5;">
        <label class="frm-label" for="recurrence_frequence">Récurrence</label>
        <div class="frm-select-wrap">
            <select name="recurrence_frequence" id="recurrence_frequence" class="frm-select">
                <option value="">Aucune</option>
                <option value="daily" {{ $defaultRecurrenceFreq == 'daily' ? 'selected' : '' }}>Journalière</option>
                <option value="weekly" {{ $defaultRecurrenceFreq == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                <option value="monthly" {{ $defaultRecurrenceFreq == 'monthly' ? 'selected' : '' }}>Mensuelle</option>
                <option value="yearly" {{ $defaultRecurrenceFreq == 'yearly' ? 'selected' : '' }}>Annuelle</option>
            </select>
        </div>
    </div>
    <div class="frm-group" style="flex: 1;">
        <label class="frm-label" for="recurrence_intervalle">Intervalle</label>
        <input type="number" name="recurrence_intervalle" id="recurrence_intervalle" class="frm-input" value="{{ $defaultRecurrenceInterval }}" min="1">
    </div>
    <div class="frm-group" style="flex: 1.5;">
        <label class="frm-label" for="recurrence_fin">Date fin</label>
        <input type="date" name="recurrence_fin" id="recurrence_fin" class="frm-input" value="{{ $defaultRecurrenceFin }}">
    </div>
</div>

<script>
    document.getElementById('rappel')?.addEventListener('change', function() {
        const group = document.getElementById('rappel_avant_group');
        if (group) group.style.display = this.checked ? 'flex' : 'none';
    });
</script>