@php
    $isEdit = isset($zone);
    $selectedVilleId = $isEdit ? $zone->ville_id : ($selectedVilleId ?? old('ville_id'));
    $selectedRboId = $isEdit ? $zone->rbo_id : old('rbo_id');
@endphp

{{-- Nom de la zone --}}
<div class="frm-group">
    <label class="frm-label" for="name">
        Nom de la zone <span class="req">*</span>
    </label>
    <div class="frm-input-wrap">
        <span class="frm-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 2a7 7 0 0 0-7 7c0 4 7 13 7 13s7-9 7-13a7 7 0 0 0-7-7z"/>
                <circle cx="12" cy="9" r="3"/>
            </svg>
        </span>
        <input type="text" id="name" name="name"
               class="frm-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
               value="{{ old('name', $zone->name ?? '') }}"
               placeholder="Ex : Centre-ville, Zone industrielle…"
               required autocomplete="off">
    </div>
    @error('name')
        <span class="frm-error">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $message }}
        </span>
    @enderror
</div>

{{-- Ville --}}
<div class="frm-group">
    <label class="frm-label" for="ville_id">
        Ville <span class="req">*</span>
    </label>
    <div class="frm-select-wrap">
        <select id="ville_id" name="ville_id"
                class="frm-select {{ $errors->has('ville_id') ? 'is-invalid' : '' }}"
                required>
            <option value="">— Sélectionnez une ville —</option>
            @foreach($villes as $ville)
                <option value="{{ $ville->id }}" {{ $selectedVilleId == $ville->id ? 'selected' : '' }}>
                    {{ $ville->nom }}
                </option>
            @endforeach
        </select>
    </div>
    @error('ville_id')
        <span class="frm-error">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $message }}
        </span>
    @enderror
</div>

{{-- RBO (dynamique) --}}
<div class="frm-group">
    <label class="frm-label" for="rbo_id">
        RBO <span class="req">*</span>
    </label>
    <div class="frm-select-wrap">
        <select id="rbo_id" name="rbo_id"
                class="frm-select {{ $errors->has('rbo_id') ? 'is-invalid' : '' }}"
                required>
            <option value="">— Sélectionnez un RBO —</option>
            @foreach($rbos as $rbo)
                <option value="{{ $rbo->id }}" {{ (old('rbo_id', $zone->rbo_id ?? '') == $rbo->id) ? 'selected' : '' }}>
                    {{ $rbo->prenom }} {{ $rbo->nom }} ({{ $rbo->email }})
                </option>
            @endforeach
        </select>
    </div>
    @error('rbo_id')
        <span class="frm-error">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $message }}
        </span>
    @enderror
</div>

{{-- Délégués (multiple) --}}
<div class="frm-group">
    <label class="frm-label" for="delegues">
        Délégués
    </label>
    <select id="delegues" name="delegues[]"
            class="frm-select" multiple size="4"
            style="height: auto; min-height: 100px;">
        @foreach($delegues as $delegue)
            <option value="{{ $delegue->id }}"
                {{ (isset($zone) && $zone->delegates->contains($delegue->id)) ? 'selected' : '' }}>
                {{ $delegue->prenom }} {{ $delegue->nom }} ({{ $delegue->email }})
            </option>
        @endforeach
    </select>
    <small class="frm-hint">Sélectionnez un ou plusieurs délégués (maintenez Ctrl ou Cmd).</small>
</div>

<style>
    .frm-hint {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.3rem;
    }
</style>