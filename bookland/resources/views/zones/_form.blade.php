@php
    // Determine if we are in edit mode (presence of $zone variable)
    $isEdit = isset($zone);
    $selectedVilleId = $isEdit ? $zone->ville_id : ($selectedVilleId ?? old('ville_id'));
    $selectedRboId = $isEdit ? $zone->rbo_id : old('rbo_id');
@endphp

<div class="form-group">
    <label for="name">Nom de la zone *</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $zone->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="ville_id">Ville *</label>
    <select name="ville_id" id="ville_id" class="form-control @error('ville_id') is-invalid @enderror" required>
        <option value="">-- Sélectionnez une ville --</option>
        @foreach($villes as $ville)
            <option value="{{ $ville->id }}" {{ $selectedVilleId == $ville->id ? 'selected' : '' }}>
                {{ $ville->nom }}
            </option>
        @endforeach
    </select>
    @error('ville_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="rbo_id">RBO *</label>
    <div class="form-group">
    <label for="rbo_id">RBO *</label>
    <select name="rbo_id" id="rbo_id" class="form-control @error('rbo_id') is-invalid @enderror" required>
        <option value="">-- Sélectionnez un RBO --</option>
        @foreach($rbos as $rbo)
            <option value="{{ $rbo->id }}" {{ (old('rbo_id', $zone->rbo_id ?? '') == $rbo->id) ? 'selected' : '' }}>
                {{ $rbo->prenom }} {{ $rbo->nom }} ({{ $rbo->email }})
            </option>
        @endforeach
    </select>
    @error('rbo_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
    @error('rbo_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="delegues">Délégués</label>
    <select name="delegues[]" id="delegues" class="form-control" multiple>
        @foreach($delegues as $delegue)
            <option value="{{ $delegue->id }}"
                {{ (isset($zone) && $zone->delegates->contains($delegue->id)) ? 'selected' : '' }}>
                {{ $delegue->prenom }} {{ $delegue->nom }} ({{ $delegue->email }})
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted">Sélectionnez un ou plusieurs délégués (maintenez Ctrl ou Cmd).</small>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const villeSelect = document.getElementById('ville_id');
    const rboSelect = document.getElementById('rbo_id');
    let initialRboId = {{ $selectedRboId ?? 'null' }};

    function loadRbos(villeId, callback) {
        if (!villeId) {
            rboSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord une ville --</option>';
            if (callback) callback();
            return;
        }

        // Show loading state
        rboSelect.innerHTML = '<option value="">Chargement...</option>';

        fetch(`/api/villes/${villeId}/rbos`)
            .then(response => response.json())
            .then(data => {
                rboSelect.innerHTML = '<option value="">Sélectionnez un RBO</option>';
                data.forEach(rbo => {
                    const option = document.createElement('option');
                    option.value = rbo.id;
                    option.textContent = `${rbo.prenom} ${rbo.nom} (${rbo.email})`;
                    rboSelect.appendChild(option);
                });
                if (callback) callback();
            })
            .catch(error => {
                console.error('Erreur lors du chargement des RBOs :', error);
                rboSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }

    // // When the ville changes, reload RBOs and then (optionally) select the saved RBO
    // villeSelect.addEventListener('change', function() {
    //     const villeId = this.value;
    //     loadRbos(villeId, function() {
    //         // If we have a saved RBO id (in edit mode) and it's available, select it
    //         if (initialRboId && rboSelect.querySelector(`option[value="${initialRboId}"]`)) {
    //             rboSelect.value = initialRboId;
    //         }
    //     });
    // });

    // // Initial load: if a ville is already selected (e.g., in edit mode), load RBOs
    // if (villeSelect.value) {
    //     loadRbos(villeSelect.value, function() {
    //         if (initialRboId && rboSelect.querySelector(`option[value="${initialRboId}"]`)) {
    //             rboSelect.value = initialRboId;
    //         }
    //     });
    // }
});
</script>
@endpush