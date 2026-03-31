@php
    $isEdit        = isset($compte);
    $isDelegue     = auth()->user()->role === 'delegue';
    $isAdmin       = auth()->user()->role === 'admin';
    $isRbo         = auth()->user()->role === 'rbo';

    $selectedVilleId    = $isEdit ? $compte->ville_id    : old('ville_id');
    $selectedZoneId     = $isEdit ? $compte->zone_id     : old('zone_id');
    $selectedQuartierId = $isEdit ? $compte->quartier_id : old('quartier_id');
    $selectedDelegueId  = $isEdit ? $compte->delegue_id  : old('delegue_id');
    $selectedStatus     = old('status', $isEdit ? $compte->status : 'actif');
@endphp

{{-- ══════════════════════════════════════════════════════════════════════════
     LOCKED FIELDS — shown as read-only info for délégués,
                     shown as full inputs for admin / rbo.
     The controller ignores these fields for délégués on update,
     so no hidden inputs are needed for security.
     ══════════════════════════════════════════════════════════════════════════ --}}

{{-- Établissement --}}
@if($isDelegue && $isEdit)
    {{-- Read-only display --}}
    <div class="frm-group">
        <label class="frm-label">Nom de l'établissement</label>
        <div class="frm-readonly">{{ $compte->etablissement }}</div>
    </div>
@else
    <div class="frm-group">
        <label class="frm-label" for="etablissement">
            Nom de l'établissement <span class="req">*</span>
        </label>
        <div class="frm-input-wrap">
            <span class="frm-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </span>
            <input type="text" id="etablissement" name="etablissement"
                   class="frm-input {{ $errors->has('etablissement') ? 'is-invalid' : '' }}"
                   value="{{ old('etablissement', $compte->etablissement ?? '') }}"
                   placeholder="Ex : Lycée Mohammed V"
                   required autocomplete="off">
        </div>
        @error('etablissement')
            <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
        @enderror
    </div>
@endif

{{-- Type --}}
@if($isDelegue && $isEdit)
    <div class="frm-group">
        <label class="frm-label">Type</label>
        <div class="frm-readonly">{{ ucfirst(str_replace('_', ' ', $compte->type)) }}</div>
    </div>
@else
    <div class="frm-group">
        <label class="frm-label" for="type">Type <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select id="type" name="type" class="frm-select {{ $errors->has('type') ? 'is-invalid' : '' }}" required>
                @foreach($types as $typeOption)
                    <option value="{{ $typeOption }}" {{ (old('type', $compte->type ?? '') == $typeOption) ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $typeOption)) }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('type')
            <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
        @enderror
    </div>
@endif

{{-- Cycle --}}
@if($isDelegue && $isEdit)
    <div class="frm-group">
        <label class="frm-label">Cycle</label>
        <div class="frm-readonly">{{ $compte->cycle ?? '—' }}</div>
    </div>
@else
    <div class="frm-group">
        <label class="frm-label" for="cycle">Cycle</label>
        <div class="frm-select-wrap">
            <select id="cycle" name="cycle" class="frm-select {{ $errors->has('cycle') ? 'is-invalid' : '' }}">
                <option value="">— Non spécifié —</option>
                @foreach($cycles as $cycleOption)
                    <option value="{{ $cycleOption }}" {{ (old('cycle', $compte->cycle ?? '') == $cycleOption) ? 'selected' : '' }}>
                        {{ $cycleOption }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('cycle')
            <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
        @enderror
    </div>
@endif

{{-- Ville --}}
@if($isDelegue && $isEdit)
    <div class="frm-group">
        <label class="frm-label">Ville</label>
        <div class="frm-readonly">{{ $compte->ville?->nom ?? '—' }}</div>
    </div>
@else
    <div class="frm-group">
        <label class="frm-label" for="ville_id">Ville <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select id="ville_id" name="ville_id" class="frm-select {{ $errors->has('ville_id') ? 'is-invalid' : '' }}" required>
                <option value="">— Sélectionnez une ville —</option>
                @foreach($villes as $ville)
                    <option value="{{ $ville->id }}" {{ $selectedVilleId == $ville->id ? 'selected' : '' }}>
                        {{ $ville->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('ville_id')
            <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
        @enderror
    </div>
@endif

{{-- Zone --}}
@if($isDelegue && $isEdit)
    <div class="frm-group">
        <label class="frm-label">Zone</label>
        <div class="frm-readonly">{{ $compte->zone?->name ?? '—' }}</div>
    </div>
@else
    <div class="frm-group">
        <label class="frm-label" for="zone_id">Zone <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select id="zone_id" name="zone_id" class="frm-select {{ $errors->has('zone_id') ? 'is-invalid' : '' }}" required>
                <option value="">— Sélectionnez une zone —</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}" data-ville="{{ $zone->ville_id }}" {{ $selectedZoneId == $zone->id ? 'selected' : '' }}>
                        {{ $zone->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('zone_id')
            <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
        @enderror
    </div>
@endif

{{-- Quartier --}}
@if($isDelegue && $isEdit)
    <div class="frm-group">
        <label class="frm-label">Quartier</label>
        <div class="frm-readonly">{{ $compte->quartier?->nom ?? '—' }}</div>
    </div>
@else
    <div class="frm-group">
        <label class="frm-label" for="quartier_id">Quartier</label>
        <div class="frm-select-wrap">
            <select id="quartier_id" name="quartier_id" class="frm-select {{ $errors->has('quartier_id') ? 'is-invalid' : '' }}">
                <option value="">— Sélectionnez un quartier —</option>
                @foreach($quartiers as $quartier)
                    <option value="{{ $quartier->id }}" data-zone="{{ $quartier->zone_id }}" {{ $selectedQuartierId == $quartier->id ? 'selected' : '' }}>
                        {{ $quartier->nom }} ({{ $quartier->zone->name ?? '' }})
                    </option>
                @endforeach
            </select>
        </div>
        @error('quartier_id')
            <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
        @enderror
    </div>
@endif

{{-- ══════════════════════════════════════════════════════════════════════════
     EDITABLE FIELDS — available to all roles
     ══════════════════════════════════════════════════════════════════════════ --}}

{{-- Adresse --}}
<div class="frm-group">
    <label class="frm-label" for="adresse">
        Adresse <span class="req">*</span>
    </label>
    <div class="frm-input-wrap">
        <span class="frm-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 2a7 7 0 0 0-7 7c0 4 7 13 7 13s7-9 7-13a7 7 0 0 0-7-7z"/>
                <circle cx="12" cy="9" r="3"/>
            </svg>
        </span>
        <textarea id="adresse" name="adresse" rows="2"
                  class="frm-input {{ $errors->has('adresse') ? 'is-invalid' : '' }}"
                  placeholder="Ex : 123 Avenue Mohammed V, ..."
                  required>{{ old('adresse', $compte->adresse ?? '') }}</textarea>
    </div>
    @error('adresse')
        <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
    @enderror
</div>

{{-- Délégué pédagogique --}}
@if($isDelegue && $isEdit)
    <div class="frm-group">
        <label class="frm-label">Délégué pédagogique</label>
        <div class="frm-readonly">
            {{ $compte->delegue ? $compte->delegue->prenom . ' ' . $compte->delegue->nom : '—' }}
        </div>
    </div>
@else
    <div class="frm-group">
        <label class="frm-label" for="delegue_id">Délégué pédagogique</label>
        <div class="frm-select-wrap">
            <select id="delegue_id" name="delegue_id"
                    class="frm-select {{ $errors->has('delegue_id') ? 'is-invalid' : '' }}">
                <option value="">— Sélectionnez un délégué —</option>
                @foreach($delegues as $delegue)
                    @php $zoneIds = $delegue->zones->pluck('id')->toArray(); @endphp
                    <option value="{{ $delegue->id }}" data-zones='@json($zoneIds)'
                            {{ $selectedDelegueId == $delegue->id ? 'selected' : '' }}>
                        {{ $delegue->prenom }} {{ $delegue->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('delegue_id')
            <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
        @enderror
    </div>
@endif

{{-- Statut --}}
<div class="frm-group">
    <label class="frm-label" for="status">
        Statut <span class="req">*</span>
    </label>
    <div class="frm-select-wrap">
        <select id="status" name="status"
                class="frm-select {{ $errors->has('status') ? 'is-invalid' : '' }}"
                required>
            @foreach($statuses as $statusOption)
                <option value="{{ $statusOption }}" {{ $selectedStatus == $statusOption ? 'selected' : '' }}>
                    {{ ucfirst($statusOption) }}
                </option>
            @endforeach
        </select>
    </div>
    @error('status')
        <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
    @enderror
</div>

{{-- Motif de fermeture (conditional) --}}
<div class="frm-group" id="motif_fermeture_group" style="{{ $selectedStatus == 'ferme' ? '' : 'display:none;' }}">
    <label class="frm-label" for="motif_fermeture">Motif de fermeture</label>
    <div class="frm-input-wrap">
        <span class="frm-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </span>
        <textarea id="motif_fermeture" name="motif_fermeture" rows="2"
                  class="frm-input {{ $errors->has('motif_fermeture') ? 'is-invalid' : '' }}"
                  placeholder="Indiquez la raison de la fermeture...">{{ old('motif_fermeture', $compte->motif_fermeture ?? '') }}</textarea>
    </div>
    @error('motif_fermeture')
        <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
    @enderror
</div>

{{-- Téléphone bureau --}}
<div class="frm-group">
    <label class="frm-label" for="tel_bureau_1">Téléphone bureau</label>
    <div class="frm-input-wrap">
        <span class="frm-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
        </span>
        <input type="text" id="tel_bureau_1" name="tel_bureau_1"
               class="frm-input {{ $errors->has('tel_bureau_1') ? 'is-invalid' : '' }}"
               value="{{ old('tel_bureau_1', $compte->tel_bureau_1 ?? '') }}"
               placeholder="Ex : 05 37 68 12 34"
               autocomplete="off">
    </div>
    @error('tel_bureau_1')
        <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
    @enderror
</div>

{{-- Email --}}
<div class="frm-group">
    <label class="frm-label" for="email">Email</label>
    <div class="frm-input-wrap">
        <span class="frm-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </span>
        <input type="email" id="email" name="email"
               class="frm-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
               value="{{ old('email', $compte->email ?? '') }}"
               placeholder="contact@etablissement.ma"
               autocomplete="off">
    </div>
    @error('email')
        <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
    @enderror
</div>

{{-- Read-only field style (injected once per form render) --}}
@once
<style>
.frm-readonly {
    padding: .62rem .9rem;
    background: var(--bg-subtle);
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .84rem;
    color: var(--text-secondary);
    font-family: var(--font);
    min-height: 2.4rem;
    display: flex;
    align-items: center;
}
</style>
@endonce