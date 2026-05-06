@php
    $isEdit = isset($tache);
    $defaultObjet = old('objet', $isEdit ? $tache->objet : '');
    $defaultDescription = old('description', $isEdit ? $tache->description : '');
    $defaultDatePlanif = old('date_planification', $isEdit ? $tache->date_planification->format('Y-m-d') : '');
    $defaultDateFin = old('date_fin', $isEdit && $tache->date_fin ? $tache->date_fin->format('Y-m-d') : '');
    $defaultAllDay = old('all_day', $isEdit ? $tache->all_day : false);
    $defaultLieu = old('lieu', $isEdit ? $tache->lieu : '');
    $defaultContacts = old('contacts', $isEdit ? ($tache->contacts ?? []) : []);
@endphp

<div class="ag-form-group">
    <label class="ag-form-label">Objet <span class="req">*</span></label>
    <input type="text" name="objet" class="ag-form-control" value="{{ $defaultObjet }}" required>
</div>

<div class="ag-form-group">
    <label class="ag-form-label">Description</label>
    <textarea name="description" class="ag-form-control" rows="2">{{ $defaultDescription }}</textarea>
</div>

<div style="display: flex; gap: 1rem; flex-wrap: wrap;">
    <div class="ag-form-group" style="flex:1;">
        <label class="ag-form-label">Date planification <span class="req">*</span></label>
        <input type="date" name="date_planification" class="ag-form-control" value="{{ $defaultDatePlanif }}" required>
    </div>
     <div class="ag-form-group" style="flex:1;">
        <label>Heure</label>
        <input type="time" name="heure" class="form-control" value="{{ old('heure', isset($tache) ? $tache->heure : '') }}">
    </div>
    <div class="ag-form-group" style="flex:1;">
        <label class="ag-form-label">Date fin</label>
        <input type="date" name="date_fin" class="ag-form-control" value="{{ $defaultDateFin }}">
    </div>
    <div class="ag-form-group" style="flex:1; justify-content: flex-end;">
        <label class="ag-checkbox" style="margin-top: 1.9rem;">
            <input type="checkbox" name="all_day" value="1" {{ $defaultAllDay ? 'checked' : '' }}>
            <span>Toute la journée</span>
        </label>
    </div>
</div>

<div class="ag-form-group">
    <label class="ag-form-label">Lieu</label>
    <input type="text" name="lieu" class="ag-form-control" value="{{ $defaultLieu }}">
</div>

<div class="ag-form-group">
    <label class="ag-form-label">Contacts (plusieurs)</label>
    <select name="contacts[]" multiple class="ag-form-select" size="4">
        @foreach($contacts as $c)
            <option value="{{ $c->id }}" {{ in_array($c->id, $defaultContacts) ? 'selected' : '' }}>
                {{ $c->prenom }} {{ $c->nom }}
            </option>
        @endforeach
    </select>
    <small style="font-size:0.7rem; color:var(--t3); margin-top:0.2rem;">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs contacts</small>
</div>