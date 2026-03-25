<div class="mb-3">
    <label for="nom" class="form-label">Nom du quartier</label>
    <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $quartier->nom ?? '') }}" required>
    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="zone_id" class="form-label">Zone</label>
    {{-- <pre>Zones: {{ $zones->pluck('name') }}</pre>  <!-- now shows zone names --> --}}
    <select class="form-select @error('zone_id') is-invalid @enderror" id="zone_id" name="zone_id" required>
        <option value="">Sélectionnez une zone</option>
        @foreach($zones as $zone)
            <option value="{{ $zone->id }}" {{ (old('zone_id', $quartier->zone_id ?? '') == $zone->id) ? 'selected' : '' }}>
                {{ $zone->name }} ({{ $zone->ville->nom }})   <!-- fixed -->
            </option>
        @endforeach
    </select>
    @error('zone_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>