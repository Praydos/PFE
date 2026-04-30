@php
    $isEdit = isset($actions_amelioration);
@endphp

<div class="mb-3">
    <label>Compte *</label>
    <select name="compte_id" id="compte_id" class="form-select" required>
        <option value="">-- Sélectionnez --</option>
        @foreach($comptes as $c)
            <option value="{{ $c->id }}" {{ old('compte_id', $isEdit ? $actions_amelioration->compte_id : '') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Émetteur (contact) *</label>
    <select name="emetteur_id" id="emetteur_id" class="form-select" required>
        <option value="">-- Sélectionnez d'abord un compte --</option>
    </select>
</div>

<div class="row">
    <div class="col-md-4 mb-3"><label>Date AA *</label><input type="date" name="dateAA" class="form-control" value="{{ old('dateAA', $isEdit ? $actions_amelioration->dateAA->format('Y-m-d') : '') }}" required></div>
    <div class="col-md-4 mb-3"><label>Type *</label><select name="type" class="form-select" required>@foreach($types as $t)<option value="{{ $t }}" {{ old('type', $isEdit ? $actions_amelioration->type : '') == $t ? 'selected' : '' }}>{{ $t }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>Origine *</label><select name="origine" class="form-select" required>@foreach($origines as $o)<option value="{{ $o }}" {{ old('origine', $isEdit ? $actions_amelioration->origine : '') == $o ? 'selected' : '' }}>{{ $o }}</option>@endforeach</select></div>
</div>

<div class="mb-3"><label>Analyse des causes</label><textarea name="analyse_causes" class="form-control" rows="2">{{ old('analyse_causes', $isEdit ? $actions_amelioration->analyse_causes : '') }}</textarea></div>
<div class="mb-3"><label>Sanctions</label><textarea name="sanctions" class="form-control" rows="2">{{ old('sanctions', $isEdit ? $actions_amelioration->sanctions : '') }}</textarea></div>
<div class="mb-3"><label>Résultats attendus</label><textarea name="resultats_attendus" class="form-control" rows="2">{{ old('resultats_attendus', $isEdit ? $actions_amelioration->resultats_attendus : '') }}</textarea></div>

<script>
    document.getElementById('compte_id')?.addEventListener('change', function() {
        const compteId = this.value;
        const emetteurSelect = document.getElementById('emetteur_id');
        if (!compteId) {
            emetteurSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez un contact --</option>';
                data.forEach(c => options += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`);
                emetteurSelect.innerHTML = options;
                const defaultEmetteurId = '{{ old('emetteur_id', $isEdit ? $actions_amelioration->emetteur_id : '') }}';
                if (defaultEmetteurId) emetteurSelect.value = defaultEmetteurId;
            });
    });
    if (document.getElementById('compte_id')?.value) {
        document.getElementById('compte_id').dispatchEvent(new Event('change'));
    }
</script>