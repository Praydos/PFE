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

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Objet *</label>
        <input type="text" name="objet" class="form-control" value="{{ $defaultObjet }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label>Compte *</label>
        <select name="compte_id" id="compte_id" class="form-select" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($comptes as $c)
                <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <label>Date *</label>
        <input type="date" name="date_planification" class="form-control" value="{{ $defaultDate }}" required>
    </div>
    <div class="col-md-2 mb-3">
        <label>Heure</label>
        <input type="time" name="heure" class="form-control" value="{{ $defaultHeure }}" step="60">
    </div>
    <div class="col-md-2 mb-3">
        <label>Durée (min)</label>
        <input type="number" name="duree" class="form-control" value="{{ $defaultDuree }}" min="0">
    </div>
    <div class="col-md-5 mb-3">
        <label>Lieu</label>
        <input type="text" name="lieu" class="form-control" value="{{ $defaultLieu }}">
    </div>
    <div class="row mb-3">
    <div class="col-md-6">
        <label>Lier à un BSS (spécimen)</label>
        <select name="bss_id" class="form-select">
            <option value="">-- Aucun --</option>
            @foreach($bssOptions ?? [] as $b)
                <option value="{{ $b['id'] }}" {{ old('bss_id', $action->bss_id ?? '') == $b['id'] ? 'selected' : '' }}>{{ $b['label'] }}</option>
            @endforeach
        </select>
    </div>
</div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="form-check">
            <input type="checkbox" name="rappel" class="form-check-input" id="rappel" value="1" {{ $defaultRappel ? 'checked' : '' }}>
            <label class="form-check-label" for="rappel">Rappel</label>
        </div>
    </div>
    <div class="col-md-3" id="rappel_avant_group" style="{{ $defaultRappel ? '' : 'display:none;' }}">
        <label>Minutes avant</label>
        <input type="number" name="rappel_avant" class="form-control" value="{{ $defaultRappelAvant }}" min="1">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <label>Récurrence</label>
        <select name="recurrence_frequence" class="form-select">
            <option value="">Aucune</option>
            <option value="daily" {{ $defaultRecurrenceFreq == 'daily' ? 'selected' : '' }}>Journalière</option>
            <option value="weekly" {{ $defaultRecurrenceFreq == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
            <option value="monthly" {{ $defaultRecurrenceFreq == 'monthly' ? 'selected' : '' }}>Mensuelle</option>
            <option value="yearly" {{ $defaultRecurrenceFreq == 'yearly' ? 'selected' : '' }}>Annuelle</option>
        </select>
    </div>
    <div class="col-md-2">
        <label>Intervalle</label>
        <input type="number" name="recurrence_intervalle" class="form-control" value="{{ $defaultRecurrenceInterval }}" min="1">
    </div>
    <div class="col-md-3">
        <label>Date fin</label>
        <input type="date" name="recurrence_fin" class="form-control" value="{{ $defaultRecurrenceFin }}">
    </div>
</div>

<script>
    document.getElementById('rappel')?.addEventListener('change', function() {
        document.getElementById('rappel_avant_group').style.display = this.checked ? '' : 'none';
    });
</script>