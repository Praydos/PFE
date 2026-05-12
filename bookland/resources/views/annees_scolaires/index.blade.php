@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
    /* Copy the entire <style> block from the zones example here */
    /* (We'll include it fully in the final answer) */
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

    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; }
    @keyframes pageIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Breadcrumb ──────────────────────────────────────── */
    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    /* ── Header ──────────────────────────────────────────── */
    .zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .zn-header-left h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
    .zn-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

    /* ── Buttons ─────────────────────────────────────────── */
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
    .btn-zn-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
    .btn-zn-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
    .btn-zn-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
    .btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
    .btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

    /* ── Card ────────────────────────────────────────────── */
    .zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
    .zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em; }
    .title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
    .zn-count { font-size: .76rem; color: var(--text-muted); font-weight: 500; }

    /* ── Table ───────────────────────────────────────────── */
    .zn-table { width: 100%; border-collapse: collapse; }
    .zn-table thead tr { border-bottom: 1px solid var(--border); }
    .zn-table th {
        padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        color: var(--text-hint); text-align: left;
        background: var(--bg-base); white-space: nowrap;
    }
    .zn-table td { padding: .95rem 1.2rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
    .zn-table tbody tr:hover { background: #f8f9fd; }
    .zn-table tbody tr:last-child td { border-bottom: none; }

    /* Badges */
    .dr-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .22rem .65rem; border-radius: 20px;
        font-size: .7rem; font-weight: 600; white-space: nowrap;
    }
    .bd-teal { background: var(--teal-light); color: var(--teal); }
    .bd-blue { background: var(--blue-light); color: var(--blue); }
    .bd-none { background: var(--bg-subtle); color: var(--text-muted); }

    /* Actions */
    .actions-cell { display: flex; align-items: center; gap: .35rem; flex-wrap: wrap; }

    /* Empty state */
    .zn-empty { padding: 4rem 2rem; text-align: center; }
    .zn-empty-icon { width: 52px; height: 52px; border-radius: var(--r-md); background: var(--bg-subtle); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--text-hint); }
    .zn-empty h3 { font-size: .95rem; font-weight: 700; color: var(--text-secondary); }
    .zn-empty p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

    /* Pagination */
    .zn-pagination { padding: 1rem 1.5rem; border-top: 1px solid var(--border); background: var(--bg-card); display: flex; justify-content: center; gap: 0.3rem; flex-wrap: wrap; }
    .zn-pagination .page-link {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2rem; padding: 0.3rem 0.6rem; border-radius: var(--r-sm);
        background: var(--bg-card); border: 1px solid var(--border);
        color: var(--text-secondary); font-size: 0.8rem; text-decoration: none;
        transition: all var(--t);
    }
    .zn-pagination .page-link:hover { background: var(--bg-hover); border-color: var(--border-md); }
    .zn-pagination .active .page-link { background: var(--blue); border-color: var(--blue); color: white; }
    .zn-pagination .disabled .page-link { opacity: 0.5; pointer-events: none; }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .zn-header { flex-direction: column; gap: 1rem; }
        .zn-table th, .zn-table td { padding: .75rem .9rem; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('annees-scolaires.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="#">Paramètres</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Années scolaires</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Années scolaires</h1>
            <p>Gérez les périodes scolaires et verrouillez les données par année</p>
        </div>
        <div class="zn-header-actions">
            <a href="{{ route('annees-scolaires.create') }}" class="btn-zn btn-zn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nouvelle année
            </a>
        </div>
    </div>

    {{-- Card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <div class="zn-card-title">
                <span class="title-pip"></span>
                Liste des années scolaires
            </div>
            <span class="zn-count">{{ $annees->total() }} année(s)</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="zn-table">
                <thead>
                    <tr>
                        <th>Libellé</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($annees as $annee)
                    <tr>
                        <td><strong>{{ $annee->libelle }}</strong></td>
                        <td>{{ $annee->date_debut->format('d/m/Y') }}</td>
                        <td>{{ $annee->date_fin->format('d/m/Y') }}</td>
                        <td>
                            @if($annee->is_active)
                                <span class="dr-badge bd-teal">Active</span>
                            @elseif($annee->is_closed)
                                <span class="dr-badge bd-none">Fermée</span>
                            @else
                                <span class="dr-badge bd-blue">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                {{-- Activer (only for inactive, not closed) --}}
                                @if(!$annee->is_active && !$annee->is_closed)
                                    <form action="{{ route('annees-scolaires.set-active', $annee) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-zn btn-zn-sm btn-zn-primary" title="Activer">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                            Activer
                                        </button>
                                    </form>
                                @endif

                                {{-- Modifier (only if not closed and not active) --}}
                                @if(!$annee->is_closed )
                                    <a href="{{ route('annees-scolaires.edit', $annee) }}" class="btn-zn btn-zn-sm btn-zn-warning" title="Modifier">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                                        </svg>
                                        Modifier
                                    </a>
                                @endif

                                {{-- Fermer (only if not closed and active) --}}
                                @if(!$annee->is_closed && $annee->is_active)
                                    <form action="{{ route('annees-scolaires.close', $annee) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-zn btn-zn-sm btn-zn-ghost" title="Fermer l'année" onclick="return confirm('Fermer cette année ? Les données ne seront plus modifiables.')">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                <line x1="9" y1="9" x2="15" y2="15"/>
                                                <line x1="15" y1="9" x2="9" y2="15"/>
                                            </svg>
                                            Fermer
                                        </button>
                                    </form>
                                @endif

                                {{-- Supprimer (only if not active and not closed) --}}
                                @if(!$annee->is_active && !$annee->is_closed)
                                    <form action="{{ route('annees-scolaires.destroy', $annee) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-zn btn-zn-sm btn-zn-danger" title="Supprimer" onclick="return confirm('Supprimer définitivement cette année ?')">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6l-1 14H6L5 6"/>
                                                <path d="M10 11v6M14 11v6"/>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="zn-empty">
                                    <div class="zn-empty-icon">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </div>
                                    <h3>Aucune année scolaire</h3>
                                    <p>Commencez par créer une nouvelle année scolaire.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($annees->hasPages())
            <div class="zn-pagination">
                {{ $annees->withQueryString()->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection