<div class="mb-3">
    <label>Objet *</label>
    <input type="text" name="objet" class="form-control" value="{{ old('objet', $tache->objet ?? '') }}" required>
</div>
<div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control" rows="2">{{ old('description', $tache->description ?? '') }}</textarea>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label>Date planification *</label>
        <input type="date" name="date_planification" class="form-control" value="{{ old('date_planification', isset($tache) ? $tache->date_planification->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label>Date fin</label>
        <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin', isset($tache) && $tache->date_fin ? $tache->date_fin->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-check mt-4">
            <input type="checkbox" name="all_day" class="form-check-input" id="all_day" value="1" {{ old('all_day', $tache->all_day ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="all_day">Toute la journée</label>
        </div>
    </div>
</div>
<div class="mb-3">
    <label>Lieu</label>
    <input type="text" name="lieu" class="form-control" value="{{ old('lieu', $tache->lieu ?? '') }}">
</div>
<div class="mb-3">
    <label>Contacts (plusieurs)</label>
    <select name="contacts[]" multiple class="form-select">
        @foreach($contacts as $c)
            <option value="{{ $c->id }}" {{ in_array($c->id, old('contacts', isset($tache) ? ($tache->contacts ?? []) : [])) ? 'selected' : '' }}>{{ $c->prenom }} {{ $c->nom }}</option>
        @endforeach
    </select>
</div>