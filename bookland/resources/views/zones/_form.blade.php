<div class="mb-3">
    <label for="nom" class="form-label">Nom de la zone</label>
    <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="name" value="{{ old('nom', $zone->nom ?? '') }}" required>
    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="ville_id" class="form-label">Ville</label>
    <select class="form-select @error('ville_id') is-invalid @enderror" id="ville_id" name="ville_id" required>
        <option value="">Sélectionnez une ville</option>
        @foreach($villes as $ville)
            <option value="{{ $ville->id }}"{{ (old('ville_id', $zone->ville_id ?? $selectedVilleId ?? '') == $ville->id) ? 'selected' : '' }}>
            {{ $ville->nom }}
            </option>
        @endforeach
    </select>
    @error('ville_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="rbo_id" class="form-label">Responsable Back Office (RBO)</label>
    <select class="form-select @error('rbo_id') is-invalid @enderror" id="rbo_id" name="rbo_id" required>
        <option value="">Sélectionnez un RBO</option>
        @foreach($rbos as $rbo)
            <option value="{{ $rbo->id }}" {{ (old('rbo_id', $zone->rbo_id ?? '') == $rbo->id) ? 'selected' : '' }}>
                {{ $rbo->prenom }} {{ $rbo->nom }} ({{ $rbo->email }})
            </option>
        @endforeach
    </select>
    @error('rbo_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="delegues" class="form-label">Délégués assignés</label>
    <select multiple class="form-select @error('delegues') is-invalid @enderror" id="delegues" name="delegues[]">
        @foreach($delegues as $delegue)
            @php
                // Determine selected IDs from old input or from existing zone (if any)
                $selectedIds = old('delegues', isset($zone) ? $zone->delegates->pluck('id')->toArray() : []);
            @endphp
            <option value="{{ $delegue->id }}" 
                {{ (collect($selectedIds)->contains($delegue->id)) ? 'selected' : '' }}>
                {{ $delegue->prenom }} {{ $delegue->nom }}
            </option>
        @endforeach
    </select>
    @error('delegues') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="form-text text-muted">Maintenez Ctrl pour sélection multiple</small>
</div>