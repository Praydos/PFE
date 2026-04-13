<div class="row">
    <div class="col-md-6 mb-3">
        <label>Compte *</label>
        <select name="compte_id" id="compte_id" class="form-select" required>
            @foreach($comptes as $c)
                <option value="{{ $c->id }}" {{ old('compte_id', $effectif->compte_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label>Année scolaire *</label>
        <select name="annee_scolaire_id" class="form-select" required>
            @foreach($years as $y)
                <option value="{{ $y->id }}" {{ old('annee_scolaire_id', $effectif->annee_scolaire_id ?? '') == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Niveau *</label>
        <select name="niveau" class="form-select" required>
            @foreach($niveaux as $n)
                <option value="{{ $n }}" {{ old('niveau', $effectif->niveau ?? '') == $n ? 'selected' : '' }}>{{ $n }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label>Cycle</label>
        <select name="cycle" class="form-select">
            <option value="">-- Sélectionnez --</option>
            @foreach($cycleOptions as $opt)
                <option value="{{ $opt }}" {{ old('cycle', $effectif->cycle ?? '') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Massar (nombre officiel)</label>
        <input type="number" name="massar" class="form-control" value="{{ old('massar', $effectif->massar ?? '') }}">
    </div>
</div>

{{-- Sources with nombre_classes --}}
<div class="row">
    <div class="col-md-4 mb-3">
        <label>Source 1 (contact)</label>
        <select name="source_1" class="form-select contact-select" data-target="nombre_classes_1">
            <option value="">-- Sélectionnez --</option>
        </select>
        <input type="number" name="nombre_classes_1" class="form-control mt-2" placeholder="Nombre de classes" value="{{ old('nombre_classes_1', $effectif->nombre_classes_1 ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>Source 2 (contact)</label>
        <select name="source_2" class="form-select contact-select" data-target="nombre_classes_2">
            <option value="">-- Sélectionnez --</option>
        </select>
        <input type="number" name="nombre_classes_2" class="form-control mt-2" placeholder="Nombre de classes" value="{{ old('nombre_classes_2', $effectif->nombre_classes_2 ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>Source 3 (contact)</label>
        <select name="source_3" class="form-select contact-select" data-target="nombre_classes_3">
            <option value="">-- Sélectionnez --</option>
        </select>
        <input type="number" name="nombre_classes_3" class="form-control mt-2" placeholder="Nombre de classes" value="{{ old('nombre_classes_3', $effectif->nombre_classes_3 ?? '') }}">
    </div>
</div>

{{-- effectif_valide – visible only to RBO/Admin --}}
@if(in_array(auth()->user()->role, ['admin', 'rbo']))
<div class="row">
    <div class="col-md-6 mb-3">
        <label>Effectif validé (par RBO/Admin)</label>
        <input type="number" name="effectif_valide" class="form-control" value="{{ old('effectif_valide', $effectif->effectif_valide ?? '') }}">
        <small class="text-muted">Laissez vide si non encore validé.</small>
    </div>
</div>
@endif

@push('scripts')
<script>
    document.getElementById('compte_id').addEventListener('change', function() {
        const compteId = this.value;
        const selects = document.querySelectorAll('.contact-select');
        if (!compteId) {
            selects.forEach(sel => sel.innerHTML = '<option value="">-- Sélectionnez --</option>');
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                const options = '<option value="">-- Sélectionnez --</option>' + data.map(c => `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`).join('');
                selects.forEach(sel => sel.innerHTML = options);
                // Pre‑select existing values when editing
                @isset($effectif)
                    @if($effectif->source_1) document.querySelector('select[name="source_1"]').value = {{ $effectif->source_1 }}; @endif
                    @if($effectif->source_2) document.querySelector('select[name="source_2"]').value = {{ $effectif->source_2 }}; @endif
                    @if($effectif->source_3) document.querySelector('select[name="source_3"]').value = {{ $effectif->source_3 }}; @endif
                @endisset
            });
    });
    if (document.getElementById('compte_id').value) {
        document.getElementById('compte_id').dispatchEvent(new Event('change'));
    }
</script>
@endpush