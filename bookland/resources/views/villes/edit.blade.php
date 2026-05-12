@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ── Global design system (identical to previous forms) ── */
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
        --green:         #28c76f;
        --green-light:   #e8fbf0;
        --text-primary:   #1a1f36;
        --text-secondary: #525f7f;
        --text-muted:     #9ba8c5;
        --text-hint:      #bcc5dc;
        --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
        --shadow-xs: 0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
        --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
        --shadow-md: 0 8px 24px rgba(31,45,80,.10), 0 2px 8px rgba(31,45,80,.06);
        --shadow-blue: 0 4px 14px rgba(91,141,238,.35);
        --font: 'DM Sans', sans-serif;
        --font-mono: 'DM Mono', monospace;
        --ease: cubic-bezier(.4,0,.2,1);
        --t: .18s var(--ease);
    }

    body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1400px; margin: 0 auto; }
    @keyframes pageIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    .zn-header { margin-bottom: 2rem; }
    .zn-header h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; }
    .zn-header p { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

    .btn-zn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .56rem 1.1rem; border-radius: var(--r-sm);
        font-family: var(--font); font-size: .82rem; font-weight: 600;
        cursor: pointer; border: 1px solid transparent;
        transition: all var(--t); text-decoration: none;
        white-space: nowrap; letter-spacing: -.01em; line-height: 1;
    }
    .btn-zn svg { flex-shrink: 0; }
    .btn-zn-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue); }
    .btn-zn-primary:hover { background: var(--blue-dark); color: #fff; transform: translateY(-1px); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

    .fp-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .fp-card:last-child { margin-bottom: 0; }

    .fp-section {
        padding: 2rem 2rem 1.5rem;
        border-bottom: 1px solid var(--border);
    }
    .fp-section:last-of-type { border-bottom: none; }
    .fp-section-head {
        display: flex; align-items: center; gap: .75rem;
        margin-bottom: 1.6rem;
    }
    .fp-section-icon {
        width: 34px; height: 34px; flex-shrink: 0;
        border-radius: var(--r-md);
        display: flex; align-items: center; justify-content: center;
        background: var(--blue-light); color: var(--blue);
    }
    .fp-section-meta { flex: 1; }
    .fp-section-title { font-size: .9rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
    .fp-section-sub   { font-size: .74rem; color: var(--text-muted); margin-top: .1rem; }

    .fp-footer {
        padding: 1.25rem 2rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .fp-footer-spacer { flex: 1; }
    .fp-req-note { font-size: .74rem; color: var(--text-muted); }
    .fp-req-note span { color: var(--rose); }

    .zn-alert {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: 1rem 1.25rem; border-radius: var(--r-lg);
        border: 1px solid; margin-bottom: 1.5rem; font-size: .82rem;
    }
    .zn-alert-danger { background: var(--rose-light); border-color: rgba(232,80,106,.25); color: #b83450; }
    .zn-alert ul { padding-left: 1.2rem; margin-top: .3rem; }
    .zn-alert li { margin-bottom: .15rem; }

    /* Form field styles */
    .frm-group {
        display: flex;
        flex-direction: column;
        gap: .38rem;
        margin-bottom: 1.25rem;
    }
    .frm-group:last-of-type { margin-bottom: 0; }
    .frm-label { font-size: .77rem; font-weight: 600; color: var(--text-secondary); letter-spacing: -.01em; }
    .frm-label .req { color: var(--rose); margin-left: .18rem; }
    .frm-input-wrap { position: relative; }
    .frm-icon {
        position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); pointer-events: none;
    }
    .frm-input, .frm-select {
        width: 100%; padding: .6rem .88rem;
        border: 1px solid var(--border); border-radius: var(--r-sm);
        background: var(--bg-card); font-family: var(--font);
        font-size: .83rem; color: var(--text-primary);
        box-shadow: var(--shadow-xs);
        transition: border-color var(--t), box-shadow var(--t);
        outline: none;
    }
    .frm-input-wrap .frm-input { padding-left: 2.35rem; }
    .frm-input:focus, .frm-select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .frm-input.is-invalid, .frm-select.is-invalid {
        border-color: var(--rose);
        box-shadow: 0 0 0 3px rgba(232,80,106,.12);
    }
    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent; border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted); pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }
    .frm-error {
        font-size: .72rem; color: var(--rose); font-weight: 500;
        display: flex; align-items: center; gap: .3rem;
        margin-top: .2rem;
    }

    /* Table styles */
    .zn-table {
        width: 100%;
        border-collapse: collapse;
    }
    .zn-table thead tr {
        border-bottom: 1px solid var(--border);
    }
    .zn-table th {
        padding: .85rem 1rem;
        font-size: .69rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--text-hint);
        text-align: left;
        background: var(--bg-base);
    }
    .zn-table td {
        padding: .85rem 1rem;
        font-size: .83rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .zn-table tbody tr:hover {
        background: var(--bg-hover);
    }
    .zn-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Utilities */
    .mt-4 { margin-top: 1rem; }
    .mt-5 { margin-top: 1.5rem; }
    .text-center { text-align: center; }

    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .fp-footer { flex-direction: column; align-items: stretch; }
        .fp-footer-spacer { display: none; }
        .btn-zn { width: 100%; justify-content: center; }
        .zn-table th, .zn-table td { padding: .75rem .9rem; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('villes.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('villes.index') }}">Villes</a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('villes.index', $ville) }}">{{ $ville->nom }}</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Modifier</span>
    </div>

    <div class="zn-header">
        <h1>Modifier la ville</h1>
        <p>Mettez à jour les informations de la ville <strong>{{ $ville->nom }}</strong>.</p>
    </div>

    @if($errors->any())
    <div class="zn-alert zn-alert-danger">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:.1rem"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            <strong style="display:block;margin-bottom:.3rem;">Veuillez corriger les erreurs suivantes&nbsp;:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    </div>
    @endif

    {{-- Main edit form --}}
    <div class="fp-card">
        <form method="POST" action="{{ route('villes.update', $ville) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 2a7 7 0 0 0-7 7c0 4 7 13 7 13s7-9 7-13a7 7 0 0 0-7-7z"/>
                            <circle cx="12" cy="9" r="3"/>
                        </svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Informations de la ville</div>
                        <div class="fp-section-sub">Nom de la ville</div>
                    </div>
                </div>

                {{-- Include the existing _form partial (only the nom field) --}}
                @include('villes._form')
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('villes.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

    {{-- Zones assignées card --}}
    <div class="fp-card mt-4">
        <div class="fp-section" style="padding-bottom: 1rem;">
            <div class="fp-section-head">
                <div class="fp-section-icon" style="background: var(--blue-light);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2a7 7 0 0 0-7 7c0 4 7 13 7 13s7-9 7-13a7 7 0 0 0-7-7z"/>
                        <circle cx="12" cy="9" r="3"/>
                    </svg>
                </div>
                <div class="fp-section-meta">
                    <div class="fp-section-title">Zones assignées à cette ville</div>
                    <div class="fp-section-sub">Liste des zones qui appartiennent à {{ $ville->nom }}</div>
                </div>
            </div>

            @if($assignedZones->count())
                <div style="overflow-x: auto;">
                    <table class="zn-table">
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
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('zones.edit', $zone) }}" class="btn-zn btn-zn-ghost btn-zn-sm">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"/>
                                                <polygon points="18 2 22 6 12 16 8 16 8 12 18 2"/>
                                            </svg>
                                            Modifier
                                        </a>
                                        <form action="{{ route('zones.destroy', $zone) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-zn btn-zn-ghost btn-zn-sm" onclick="return confirm('Supprimer cette zone ?')" style="color: var(--rose);">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                </svg>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                    Aucune zone assignée à cette ville.
                </div>
            @endif
        </div>
    </div>

    {{-- Assigner une zone existante --}}
    <div class="fp-card mt-4">
        <div class="fp-section">
            <div class="fp-section-head">
                <div class="fp-section-icon" style="background: var(--teal-light); color: var(--teal);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2a7 7 0 0 0-7 7c0 4 7 13 7 13s7-9 7-13a7 7 0 0 0-7-7z"/>
                        <circle cx="12" cy="9" r="3"/>
                    </svg>
                </div>
                <div class="fp-section-meta">
                    <div class="fp-section-title">Assigner une zone existante</div>
                    <div class="fp-section-sub">Attribuer une zone déjà créée à cette ville</div>
                </div>
            </div>

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
                <div class="fp-footer" style="margin-top: 1rem; padding: 0; background: none; border: none;">
                    <div class="fp-footer-spacer"></div>
                    <button type="submit" class="btn-zn btn-zn-primary">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Assigner
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bouton pour créer une nouvelle zone --}}
    <div class="text-center mt-4">
        <a href="{{ route('zones.create', ['ville_id' => $ville->id]) }}" class="btn-zn btn-zn-primary">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
             Créer une nouvelle zone
        </a>
    </div>
</div>
@endsection