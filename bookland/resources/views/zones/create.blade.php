@extends('layouts.app')

@push('styles')
<style>
/* ─────────────────────────────────────────────────────────────
   Google Fonts (Sora + DM Sans)
   Add this once in your layout <head> if not already present:
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
──────────────────────────────────────────────────────────────── */

/* ── Reset & base ── */
.tch-pg *  { box-sizing: border-box; margin: 0; padding: 0; }
.tch-pg    {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg, #f5f6fa);
    min-height: 100vh;
    padding: 2rem 1.75rem;
    color: var(--text, #1a1d23);
}

/* ── Breadcrumb ── */
.tch-bc               { display: flex; align-items: center; gap: .4rem; font-size: .78rem; color: #6b7280; margin-bottom: 2rem; }
.tch-bc a             { color: #6b7280; text-decoration: none; display: flex; align-items: center; gap: .3rem; transition: color .15s; }
.tch-bc a:hover       { color: #111827; }
.tch-bc-sep           { opacity: .35; }
.tch-bc-cur           { font-family: 'Sora', sans-serif; font-weight: 600; font-size: .78rem; color: #111827; }

/* ── Page header ── */
.tch-hdr              { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.75rem; gap: 1rem; flex-wrap: wrap; }
.tch-hdr-l h1         { font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1.55rem; letter-spacing: -.02em; line-height: 1.2; color: #111827; }
.tch-hdr-l p          { font-size: .82rem; color: #6b7280; margin-top: .3rem; }
.tch-hdr-actions      { display: flex; gap: .5rem; flex-wrap: wrap; }

/* ── Buttons ── */
.btn-tch              { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; border-radius: 8px; font-size: .8rem; font-weight: 500; font-family: 'DM Sans', sans-serif; cursor: pointer; border: none; transition: all .15s; text-decoration: none; }
.btn-tch-ghost        { background: #fff; border: 1px solid #e5e7eb; color: #374151; }
.btn-tch-ghost:hover  { background: #f9fafb; }
.btn-tch-primary      { background: #185FA5; color: #fff; }
.btn-tch-primary:hover{ background: #0c447c; }
.btn-tch-success      { background: #3B6D11; color: #fff; }
.btn-tch-success:hover{ background: #27500A; }
.btn-tch-danger       { background: #A32D2D; color: #fff; font-size: .78rem; padding: .42rem .9rem; }
.btn-tch-danger:hover { background: #791F1F; }

/* ── Main card ── */
.tch-card             { background: #fff; border: 1px solid #e9eaee; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.05); }

/* ── Card header ── */
.tch-card-hd          { padding: 1.1rem 1.5rem; border-bottom: 1px solid #f0f1f5; display: flex; align-items: center; justify-content: space-between; }
.tch-card-title       { display: flex; align-items: center; gap: .55rem; font-family: 'Sora', sans-serif; font-weight: 600; font-size: .88rem; color: #111827; }
.tch-pip              { width: 6px; height: 6px; border-radius: 50%; background: #185FA5; flex-shrink: 0; }

/* ── Card body (form container) ── */
.tch-card-body {
    padding: 1.5rem;
}

/* ── Form elements (extended for the zone form) ── */
.frm-group {
    margin-bottom: 1.25rem;
}

.frm-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 0.4rem;
    color: #1f2937;
}

.frm-label .req {
    color: #dc2626;
    margin-left: 0.2rem;
}

.frm-input-wrap,
.frm-select-wrap {
    position: relative;
}

.frm-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
    display: flex;
    align-items: center;
}

.frm-input,
.frm-select {
    width: 100%;
    padding: 0.6rem 0.75rem;
    font-size: 0.85rem;
    font-family: 'DM Sans', sans-serif;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    transition: all 0.15s;
    color: #1a1d23;
}

.frm-input:focus,
.frm-select:focus {
    outline: none;
    border-color: #185FA5;
    box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.1);
}

.frm-input.is-invalid,
.frm-select.is-invalid {
    border-color: #dc2626;
    background-color: #fef2f2;
}

.frm-input {
    padding-left: 2rem;
}

.frm-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%236b7280' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
}

select[multiple] {
    background-image: none;
    padding: 0.5rem;
}

.frm-error {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.7rem;
    color: #dc2626;
    margin-top: 0.3rem;
}

.frm-hint {
    display: block;
    font-size: 0.7rem;
    color: #6b7280;
    margin-top: 0.3rem;
}

/* Two‑column grid for form fields */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
}

/* Better styling for multiple select */
select[multiple] option {
    padding: 0.4rem 0.5rem;
    border-bottom: 1px solid #f0f1f5;
}

/* ── Card footer ── */
.tch-card-ft          { padding: .9rem 1.5rem; border-top: 1px solid #f0f1f5; background: #fafbfc; display: flex; justify-content: flex-end; gap: .5rem; align-items: center; flex-wrap: wrap; }

/* ── SVG icons (inline helpers) ── */
.tch-icon             { display: inline-block; vertical-align: middle; flex-shrink: 0; }

/* ── Responsive adjustments ── */
@media (max-width: 480px) {
    .tch-pg { padding: 1rem; }
    .tch-info-row { padding-inline: 1rem; }
    .tch-card-body { padding: 1rem; }
}

/* ── Focus styles for accessibility ── */
.btn-tch:focus-visible,
.tch-bc a:focus-visible,
.frm-input:focus-visible,
.frm-select:focus-visible {
    outline: 2px solid #185FA5;
    outline-offset: 2px;
}
</style>
@endpush

@section('content')
<div class="tch-pg">

    {{-- Breadcrumb --}}
    <nav class="tch-bc" aria-label="Fil d'Ariane">
        <a href="{{ route('zones.index') }}">
            <svg class="tch-icon" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="tch-bc-sep">›</span>
        <a href="{{ route('zones.index') }}">Zones</a>
        <span class="tch-bc-sep">›</span>
        <span class="tch-bc-cur">Nouvelle zone</span>
    </nav>

    {{-- Page header --}}
    <div class="tch-hdr">
        <div class="tch-hdr-l">
            <h1>Créer une zone</h1>
            <p>Remplissez les informations ci‑dessous pour ajouter une nouvelle zone.</p>
        </div>
        <div class="tch-hdr-actions">
            <a href="{{ route('zones.index') }}" class="btn-tch btn-tch-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Annuler
            </a>
        </div>
    </div>

    {{-- Form card --}}
    <div class="tch-card">
        <div class="tch-card-hd">
            <div class="tch-card-title">
                <span class="tch-pip"></span>
                Informations de la zone
            </div>
        </div>

        <form method="POST" action="{{ route('zones.store') }}" novalidate>
            @csrf

            <div class="tch-card-body">
                {{-- Optional: two‑column grid for better use of space --}}
                <div class="form-grid">
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
                                    <option value="{{ $ville->id }}" {{ (old('ville_id', $selectedVilleId ?? '') == $ville->id) ? 'selected' : '' }}>
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
                                    <option value="{{ $rbo->id }}" {{ (old('rbo_id', $selectedRboId ?? '') == $rbo->id) ? 'selected' : '' }}>
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
                        <div class="frm-select-wrap">
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
                        </div>
                        <small class="frm-hint">Sélectionnez un ou plusieurs délégués (maintenez Ctrl ou Cmd).</small>
                    </div>
                </div>{{-- /.form-grid --}}
            </div>{{-- /.tch-card-body --}}

            <div class="tch-card-ft">
                <a href="{{ route('zones.index') }}" class="btn-tch btn-tch-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-tch btn-tch-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Créer
                </button>
            </div>
        </form>
    </div>{{-- /.tch-card --}}

</div>{{-- /.tch-pg --}}
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const villeSelect = document.getElementById('ville_id');
    const rboSelect = document.getElementById('rbo_id');
    const initialRboId = {{ isset($zone) ? $zone->rbo_id : 'null' }};

    function loadRbos(villeId, callback) {
        if (!villeId) {
            rboSelect.innerHTML = '<option value="">— Sélectionnez d\'abord une ville —</option>';
            if (callback) callback();
            return;
        }
        rboSelect.innerHTML = '<option value="">Chargement...</option>';
        fetch(`/api/villes/${villeId}/rbos`)
            .then(response => response.json())
            .then(data => {
                rboSelect.innerHTML = '<option value="">— Sélectionnez un RBO —</option>';
                data.forEach(rbo => {
                    const option = document.createElement('option');
                    option.value = rbo.id;
                    option.textContent = `${rbo.prenom} ${rbo.nom} (${rbo.email})`;
                    rboSelect.appendChild(option);
                });
                if (callback) callback();
            })
            .catch(error => {
                console.error('Erreur :', error);
                rboSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }

    villeSelect.addEventListener('change', function() {
        const villeId = this.value;
        loadRbos(villeId, function() {
            if (initialRboId && rboSelect.querySelector(`option[value="${initialRboId}"]`)) {
                rboSelect.value = initialRboId;
            }
        });
    });

    if (villeSelect.value) {
        loadRbos(villeSelect.value, function() {
            if (initialRboId && rboSelect.querySelector(`option[value="${initialRboId}"]`)) {
                rboSelect.value = initialRboId;
            }
        });
    }
});
</script>
@endpush