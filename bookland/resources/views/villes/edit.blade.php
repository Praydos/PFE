@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* Paste the full CSS from the quartier example here (same as before) */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg-base:       #f5f6fa;
    --bg-card:       #ffffff;
    --bg-hover:      #f8f9fd;
    --bg-subtle:     #f0f2f8;
    --border:        #e4e7f0;
    --border-md:     #d0d5e8;
    --blue:          #5b8dee;
    --blue-dark:     #3d6fd6;
    --blue-light:    #eef3fd;
    --blue-mid:      #dce8fb;
    --teal:          #0cb8b6;
    --teal-light:    #e6faf9;
    --violet:        #7c6fcd;
    --violet-light:  #f0eeff;
    --amber:         #e8a020;
    --amber-light:   #fff8ec;
    --rose:          #e8506a;
    --rose-light:    #fef0f2;
    --text-primary:   #1a1f36;
    --text-secondary: #525f7f;
    --text-muted:     #9ba8c5;
    --text-hint:      #bcc5dc;
    --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
    --shadow-xs: 0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
    --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
    --shadow-blue: 0 4px 14px rgba(91,141,238,.35);
    --font: 'DM Sans', sans-serif;
    --font-mono: 'DM Mono', monospace;
    --ease: cubic-bezier(.4,0,.2,1);
    --t: .18s var(--ease);
}

body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

/* ── Page ──────────────────────────────────────────── */
.form-page {
    padding: 2rem 2.5rem 3rem;
    max-width: 720px;
    animation: pageIn .4s var(--ease) both;
}
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ────────────────────────────────────── */
.fp-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
.fp-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.fp-bc a:hover { color: var(--blue); }
.fp-bc-sep { color: var(--text-hint); }
.fp-bc-cur { color: var(--text-secondary); }

/* ── Header ────────────────────────────────────────── */
.fp-header { margin-bottom: 2rem; }
.fp-header h1 { font-size: 1.55rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.2; }
.fp-header p  { font-size: .83rem; color: var(--text-muted); margin-top: .35rem; }

/* ── Card ──────────────────────────────────────────── */
.fp-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.fp-card-header {
    padding: 1.1rem 1.6rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: .55rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.fp-card-pip {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--amber); box-shadow: 0 0 0 3px rgba(232,160,32,.2);
}
.fp-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }

.fp-card-body { padding: 1.75rem 1.6rem; }

/* ── Form Fields ───────────────────────────────────── */
.frm-group {
    display: flex; flex-direction: column; gap: .45rem;
    margin-bottom: 1.25rem;
}
.frm-group:last-of-type { margin-bottom: 0; }

.frm-label {
    font-size: .8rem; font-weight: 600;
    color: var(--text-secondary); letter-spacing: -.01em;
}
.frm-label .req { color: var(--rose); margin-left: .2rem; }

.frm-input,
.frm-select {
    width: 100%; padding: .62rem .9rem;
    border: 1px solid var(--border); border-radius: var(--r-sm);
    background: var(--bg-card); font-family: var(--font);
    font-size: .84rem; color: var(--text-primary);
    box-shadow: var(--shadow-xs);
    transition: border-color var(--t), box-shadow var(--t);
    outline: none; appearance: none; -webkit-appearance: none;
}
.frm-input::placeholder { color: var(--text-hint); }
.frm-input:focus,
.frm-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.frm-input.is-invalid,
.frm-select.is-invalid { border-color: var(--rose); box-shadow: 0 0 0 3px rgba(232,80,106,.12); }

.frm-error {
    font-size: .76rem; color: var(--rose); font-weight: 500;
    display: flex; align-items: center; gap: .3rem;
}

/* Select wrapper with custom arrow */
.frm-select-wrap { position: relative; }
.frm-select-wrap::after {
    content: '';
    position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid var(--text-muted);
    pointer-events: none;
}
.frm-select { padding-right: 2.2rem; cursor: pointer; }

/* Input with left icon */
.frm-input-wrap { position: relative; }
.frm-input-wrap .frm-icon {
    position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); pointer-events: none;
}
.frm-input-wrap .frm-input { padding-left: 2.35rem; }

/* ── Divider ────────────────────────────────────────── */
.fp-divider {
    height: 1px; background: var(--border);
    margin: 1.5rem 0;
}

/* ── Footer actions ─────────────────────────────────── */
.fp-footer {
    padding: 1.1rem 1.6rem;
    border-top: 1px solid var(--border);
    background: var(--bg-base);
    display: flex; align-items: center; justify-content: flex-end;
    gap: .6rem;
}

/* ── Buttons ─────────────────────────────────────────── */
.btn-fp {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .58rem 1.2rem; border-radius: var(--r-sm);
    font-family: var(--font); font-size: .83rem; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: all var(--t); text-decoration: none;
    white-space: nowrap; letter-spacing: -.01em; line-height: 1;
}
.btn-fp svg { flex-shrink: 0; }

.btn-fp-primary {
    background: var(--blue); color: #fff;
    border-color: var(--blue); box-shadow: var(--shadow-blue);
}
.btn-fp-primary:hover {
    background: var(--blue-dark); color: #fff; text-decoration: none;
    transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4);
}

.btn-fp-ghost {
    background: var(--bg-card); color: var(--text-secondary);
    border-color: var(--border); box-shadow: var(--shadow-xs);
}
.btn-fp-ghost:hover {
    background: var(--bg-hover); color: var(--text-primary);
    border-color: var(--border-md); text-decoration: none;
}

/* ── Responsive ─────────────────────────────────────── */
@media (max-width: 768px) {
    .form-page { padding: 1.25rem 1rem 2rem; }
    .fp-footer { flex-direction: column-reverse; }
    .btn-fp { width: 100%; justify-content: center; }
}
/* Additional styles for the table and assign form */
.zone-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}
.zone-table th,
.zone-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border);
    text-align: left;
}
.zone-table th {
    font-weight: 600;
    color: var(--text-secondary);
    background: var(--bg-subtle);
}
.zone-table tr:hover {
    background: var(--bg-hover);
}
.btn-sm {
    padding: 0.3rem 0.7rem;
    font-size: 0.75rem;
}
</style>
@endpush

@section('content')
<div class="form-page">

    {{-- Breadcrumb --}}
    <div class="fp-bc">
        <a href="{{ route('villes.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="fp-bc-sep">›</span>
        <a href="{{ route('villes.index') }}">Villes</a>
        <span class="fp-bc-sep">›</span>
        <span class="fp-bc-cur">Modifier</span>
    </div>

    {{-- Page header --}}
    <div class="fp-header">
        <h1>Modifier la ville</h1>
        <p>Mettez à jour les informations de la ville <strong>{{ $ville->nom }}</strong>.</p>
    </div>

    {{-- Form card --}}
    <div class="fp-card">
        <div class="fp-card-header">
            <span class="fp-card-pip"></span>
            <span class="fp-card-title">Informations de la ville</span>
        </div>

        <form method="POST" action="{{ route('villes.update', $ville) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="fp-card-body">
                @include('villes._form')
            </div>

            <div class="fp-footer">
                <a href="{{ route('villes.index') }}" class="btn-fp btn-fp-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-fp btn-fp-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

    {{-- Zones assignées card --}}
    <div class="fp-card mt-5">
        <div class="fp-card-header">
            <span class="fp-card-pip" style="background: var(--blue);"></span>
            <span class="fp-card-title">Zones assignées à cette ville</span>
        </div>
        <div class="fp-card-body">
            @if($assignedZones->count())
                <table class="zone-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>RBO</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedZones as $zone)
                        <tr>
                            <td>{{ $zone->name }}</td>
                            <td>{{ $zone->rbo->prenom ?? '' }} {{ $zone->rbo->nom ?? '-' }}</td>
                            <td>
                                <a href="{{ route('zones.edit', $zone) }}" class="btn-fp btn-fp-ghost btn-sm">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"/>
                                        <polygon points="18 2 22 6 12 16 8 16 8 12 18 2"/>
                                    </svg>
                                    Modifier
                                </a>
                                <form action="{{ route('zones.destroy', $zone) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-fp btn-fp-ghost btn-sm" onclick="return confirm('Supprimer cette zone ?')" style="color: var(--rose);">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted" style="text-align: center; padding: 1rem;">Aucune zone assignée à cette ville.</p>
            @endif
        </div>
    </div>

    {{-- Assigner une zone card --}}
    <div class="fp-card mt-4">
        <div class="fp-card-header">
            <span class="fp-card-pip" style="background: var(--teal);"></span>
            <span class="fp-card-title">Assigner une zone existante</span>
        </div>
        <div class="fp-card-body">
            <form method="POST" action="{{ route('villes.assignZone', $ville) }}">
                @csrf
                <div class="frm-group">
                    <label class="frm-label" for="zone_id">Zone à assigner</label>
                    <div class="frm-select-wrap">
                        <select name="zone_id" id="zone_id" class="frm-select" required>
                            <option value="">— Sélectionnez une zone —</option>
                            @foreach($availableZones as $zone)
                                <option value="{{ $zone->id }}">
                                    {{ $zone->name }}
                                    (actuellement dans {{ $zone->ville->nom ?? 'aucune ville' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="fp-footer" style="margin-top: 1rem; padding: 0;">
                    <button type="submit" class="btn-fp btn-fp-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        Assigner
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Créer une nouvelle zone button (outside cards) --}}
    <div class="mt-4 text-center">
        <a href="{{ route('zones.create', ['ville_id' => $ville->id]) }}" class="btn-fp btn-fp-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            + Créer une nouvelle zone
        </a>
    </div>

</div>
@endsection