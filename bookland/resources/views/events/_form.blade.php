@php
    $isEdit = isset($event);
    $defaultVilleId = old('ville_id', $isEdit ? $event->ville_id : '');
    $defaultType = old('type', $isEdit ? $event->type : '');
    $defaultEditeur = old('editeur', $isEdit ? $event->editeur : '');
    $defaultDate = old('date_event', $isEdit ? $event->date_event->format('Y-m-d') : now()->format('Y-m-d'));
    $defaultYearId = old('annee_scolaire_id', $isEdit ? $event->annee_scolaire_id : ($currentYear->id ?? ''));
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Ville *</label>
        <select name="ville_id" class="form-select" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($villes as $v)
                <option value="{{ $v->id }}" {{ $defaultVilleId == $v->id ? 'selected' : '' }}>{{ $v->nom }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label>Année scolaire *</label>
        <select name="annee_scolaire_id" class="form-select" required>
            @foreach($years as $y)
                <option value="{{ $y->id }}" {{ $defaultYearId == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Type d'événement *</label>
        <select name="type" class="form-select" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($types as $t)
                <option value="{{ $t }}" {{ $defaultType == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label>Éditeur *</label>
        <select name="editeur" class="form-select" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($editeurs as $e)
                <option value="{{ $e }}" {{ $defaultEditeur == $e ? 'selected' : '' }}>{{ $e }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Date de l'événement *</label>
        <input type="date" name="date_event" class="form-control" value="{{ $defaultDate }}" required>
    </div>
</div>