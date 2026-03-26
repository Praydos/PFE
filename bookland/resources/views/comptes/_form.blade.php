<div class="mb-3">
    <label for="etablissement" class="form-label">Nom de l'établissement</label>
    <input type="text" class="form-control @error('etablissement') is-invalid @enderror" id="etablissement" name="etablissement" value="{{ old('etablissement', $compte->etablissement ?? '') }}" required>
    @error('etablissement') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="type" class="form-label">Type</label>
        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
            @foreach($types as $typeOption)
                <option value="{{ $typeOption }}" {{ (old('type', $compte->type ?? '') == $typeOption) ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $typeOption)) }}
                </option>
            @endforeach
        </select>
        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="cycle" class="form-label">Cycle</label>
        <select class="form-select @error('cycle') is-invalid @enderror" id="cycle" name="cycle">
            <option value="">-- Non spécifié --</option>
            @foreach($cycles as $cycleOption)
                <option value="{{ $cycleOption }}" {{ (old('cycle', $compte->cycle ?? '') == $cycleOption) ? 'selected' : '' }}>
                    {{ $cycleOption }}
                </option>
            @endforeach
        </select>
        @error('cycle') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="ville_id" class="form-label">Ville</label>
        <select class="form-select @error('ville_id') is-invalid @enderror" id="ville_id" name="ville_id" required>
            <option value="">Sélectionnez une ville</option>
            @foreach($villes as $ville)
                <option value="{{ $ville->id }}" {{ (old('ville_id', $compte->ville_id ?? '') == $ville->id) ? 'selected' : '' }}>
                    {{ $ville->nom }}
                </option>
            @endforeach
        </select>
        @error('ville_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="zone_id" class="form-label">Zone</label>
        <select class="form-select @error('zone_id') is-invalid @enderror" id="zone_id" name="zone_id" required>
            <option value="">Sélectionnez une zone</option>
            @foreach($zones as $zone)
                <option value="{{ $zone->id }}" data-ville="{{ $zone->ville_id }}" {{ (old('zone_id', $compte->zone_id ?? '') == $zone->id) ? 'selected' : '' }}>
                    {{ $zone->name }}
                </option>
            @endforeach
        </select>
        @error('zone_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label for="quartier_id" class="form-label">Quartier</label>
    <select class="form-select @error('quartier_id') is-invalid @enderror" id="quartier_id" name="quartier_id" required>
        <option value="">Sélectionnez un quartier</option>
        @foreach($quartiers as $quartier)
            <option value="{{ $quartier->id }}" data-zone="{{ $quartier->zone_id }}" {{ (old('quartier_id', $compte->quartier_id ?? '') == $quartier->id) ? 'selected' : '' }}>
                {{ $quartier->nom }} ({{ $quartier->zone->nom }})
            </option>
        @endforeach
    </select>
    @error('quartier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="adresse" class="form-label">Adresse</label>
    <textarea class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" rows="2" required>{{ old('adresse', $compte->adresse ?? '') }}</textarea>
    @error('adresse') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
    <label for="delegue_id" class="form-label">Délégué pédagogique</label>
    <select class="form-select @error('delegue_id') is-invalid @enderror" id="delegue_id" name="delegue_id" >
        <option value="">Sélectionnez un délégué</option>
        @foreach($delegues as $delegue)
            @php
                $zoneIds = $delegue->zones->pluck('id')->toArray();
            @endphp
            <option value="{{ $delegue->id }}" data-zones='@json($zoneIds)' {{ (old('delegue_id', $compte->delegue_id ?? '') == $delegue->id) ? 'selected' : '' }}>
                {{ $delegue->prenom }} {{ $delegue->nom }}
            </option>
        @endforeach
    </select>
    @error('delegue_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
    <div class="col-md-6 mb-3">
        <label for="status" class="form-label">Statut</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            @foreach($statuses as $statusOption)
                <option value="{{ $statusOption }}" {{ (old('status', $compte->status ?? 'actif') == $statusOption) ? 'selected' : '' }}>
                    {{ ucfirst($statusOption) }}
                </option>
            @endforeach
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3" id="motif_fermeture_group" style="{{ old('status', $compte->status ?? 'actif') == 'ferme' ? '' : 'display:none;' }}">
    <label for="motif_fermeture" class="form-label">Motif de fermeture</label>
    <textarea class="form-control @error('motif_fermeture') is-invalid @enderror" id="motif_fermeture" name="motif_fermeture" rows="2">{{ old('motif_fermeture', $compte->motif_fermeture ?? '') }}</textarea>
    @error('motif_fermeture') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="tel_bureau_1" class="form-label">Téléphone bureau</label>
        <input type="text" class="form-control @error('tel_bureau_1') is-invalid @enderror" id="tel_bureau_1" name="tel_bureau_1" value="{{ old('tel_bureau_1', $compte->tel_bureau_1 ?? '') }}">
        @error('tel_bureau_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $compte->email ?? '') }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

@push('scripts')
<script>
    // Show/hide motif field based on status selection
    document.getElementById('status').addEventListener('change', function() {
        document.getElementById('motif_fermeture_group').style.display = this.value === 'ferme' ? 'block' : 'none';
    });

    // Filter zones by selected ville
    document.getElementById('ville_id').addEventListener('change', function() {
        const villeId = this.value;
        const zoneSelect = document.getElementById('zone_id');
        const options = zoneSelect.querySelectorAll('option');
        options.forEach(option => {
            if (option.value === '') return;
            if (option.dataset.ville == villeId || villeId === '') {
                option.style.display = '';
            } else {
                option.style.display = 'none';
                if (option.selected) option.selected = false;
            }
        });
    });
    // Trigger on load
    document.getElementById('ville_id').dispatchEvent(new Event('change'));

    // Filter quartiers by selected zone
const zoneSelect = document.getElementById('zone_id');
const quartierSelect = document.getElementById('quartier_id');
const allQuartierOptions = Array.from(quartierSelect.querySelectorAll('option'));

function filterQuartiers() {
    const selectedZoneId = zoneSelect.value;
    quartierSelect.innerHTML = '<option value="">Sélectionnez un quartier</option>';
    allQuartierOptions.forEach(opt => {
        if (opt.value === '') return;
        if (selectedZoneId === '' || opt.dataset.zone === selectedZoneId) {
            quartierSelect.appendChild(opt.cloneNode(true));
        }
    });
}

zoneSelect.addEventListener('change', filterQuartiers);
filterQuartiers(); // initial filter


// Filter delegues by selected zone
document.addEventListener('DOMContentLoaded', function() {
    const zoneSelect = document.getElementById('zone_id');
    const delegateSelect = document.getElementById('delegue_id');
    const allDelegateOptions = Array.from(delegateSelect.querySelectorAll('option'));

    function filterDelegates() {
        const selectedZoneId = zoneSelect.value;
        delegateSelect.innerHTML = '<option value="">Sélectionnez un délégué</option>';
        let firstValidOption = null;

        allDelegateOptions.forEach(opt => {
            if (opt.value === '') return;
            const zones = opt.dataset.zones ? JSON.parse(opt.dataset.zones) : [];
            if (selectedZoneId === '' || zones.includes(parseInt(selectedZoneId))) {
                delegateSelect.appendChild(opt.cloneNode(true));
                if (!firstValidOption) firstValidOption = opt.value;
            }
        });

        // If the currently selected delegate is no longer valid, reset it
        const currentSelected = delegateSelect.value;
        if (currentSelected && ![...delegateSelect.options].some(opt => opt.value === currentSelected)) {
            delegateSelect.value = '';
        }
    }

    zoneSelect.addEventListener('change', filterDelegates);
    filterDelegates(); // run once on page load
});
</script>
@endpush