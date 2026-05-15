@php
    $isEdit = isset($event);
    $defaultVilleId = old('ville_id', $isEdit ? $event->ville_id : '');
    $defaultType = old('type', $isEdit ? $event->type : '');
    $defaultEditeur = old('editeur', $isEdit ? $event->editeur : '');
    // $defaultDate = old('date_event', $isEdit ? $event->date_event->format('Y-m-d') : now()->format('Y-m-d'));
    $defaultYearId = old('annee_scolaire_id', $isEdit ? $event->annee_scolaire_id : ($currentYear->id ?? ''));
    $defaultDate = old(
    'date_event',
    $isEdit
        ? $action->date_planification->format('Y-m-d')
        : ($prefilledDate ?? now()->toDateString())
);
@endphp

{{-- Ville and Année scolaire --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="ville_id">Ville <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="ville_id" id="ville_id" class="frm-select" required>
                <option value="">— Sélectionnez —</option>
                @foreach($villes as $v)
                    <option value="{{ $v->id }}" {{ $defaultVilleId == $v->id ? 'selected' : '' }}>{{ $v->nom }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="annee_scolaire_id">Année scolaire <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="annee_scolaire_id" id="annee_scolaire_id" class="frm-select" required>
                @foreach($years as $y)
                    <option value="{{ $y->id }}" {{ $defaultYearId == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- Type and Éditeur --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="type">Type d'événement <span class="req">*</span></label>
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
        <label class="frm-label" for="editeur">Éditeur <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="editeur" id="editeur" class="frm-select" required>
                <option value="">— Sélectionnez —</option>
                @foreach($editeurs as $e)
                    <option value="{{ $e }}" {{ $defaultEditeur == $e ? 'selected' : '' }}>{{ $e }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- Date de l'événement --}}
<div class="form-row" style="display: flex; gap: 1rem; flex-wrap: wrap;">
    <div class="frm-group" style="flex: 1; min-width: 200px;">
        <label class="frm-label" for="date_event">Date de l'événement <span class="req">*</span></label>
        <input type="date" name="date_event" id="date_event" class="frm-input" value="{{ $defaultDate }}" required>
    </div>
</div>