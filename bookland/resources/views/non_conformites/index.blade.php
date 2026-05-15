@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL DESIGN SYSTEM CSS (same as BSS index, etc.) ===== */
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

    /* Breadcrumb */
    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    /* Header */
    .zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .zn-header-left h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
    .zn-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

    /* Buttons */
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
    .btn-zn-info { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2); }
    .btn-zn-info:hover { background: #e8e5ff; color: var(--violet); text-decoration: none; }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

    /* Search bar */
    .zn-search-bar {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-xs);
    }
    .zn-filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        min-width: 160px;
    }
    .zn-filter-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-secondary);
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .zn-select, .zn-input {
        width: 100%;
        padding: 0.55rem 0.85rem;
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        background: var(--bg-card);
        font-family: var(--font);
        font-size: 0.82rem;
        color: var(--text-primary);
        box-shadow: var(--shadow-xs);
        transition: all var(--t);
        outline: none;
    }
    .zn-select:focus, .zn-input:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .filter-actions {
        display: flex;
        align-items: flex-end;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    /* Card */
    .zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
    .zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em; }
    .title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
    .zn-count { font-size: .76rem; color: var(--text-muted); font-weight: 500; }

    /* Table */
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
    .bd-blue { background: var(--blue-light); color: var(--blue); }
    .bd-teal { background: var(--teal-light); color: var(--teal); }
    .bd-green { background: var(--green-light); color: var(--green); }
    .bd-amber { background: var(--amber-light); color: var(--amber); }
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

    /* Success alert */
    .zn-alert-success {
        background: var(--green-light);
        border: 1px solid rgba(40,199,111,.2);
        color: #166534;
        padding: .85rem 1rem;
        border-radius: var(--r-lg);
        margin-bottom: 1.5rem;
        font-size: .82rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .zn-header { flex-direction: column; gap: 1rem; }
        .zn-table th, .zn-table td { padding: .75rem .9rem; }
        .filter-actions { margin-top: 0.5rem; justify-content: flex-end; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('comptes.index') }}">Accueil</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Non‑conformités</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Non‑conformités</h1>
            <p>Liste des déclarations de non‑conformité.</p>
        </div>
        @if(auth()->user()->role === 'delegue')
            <a href="{{ route('non-conformites.create') }}" class="btn-zn btn-zn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nouvelle NC
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="zn-alert-success">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter bar --}}
    <div class="zn-search-bar">
        <form method="GET" action="{{ route('non-conformites.index') }}" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
            <div class="zn-filter-group">
                <label class="zn-filter-label">Statut</label>
                <select name="statut" class="zn-select">
                    <option value="">Tous statuts</option>
                    @foreach($statuts as $s)
                        <option value="{{ $s }}" {{ request('statut') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="zn-filter-group">
                <label class="zn-filter-label">Catégorie</label>
                <select name="categorie" class="zn-select">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c }}" {{ request('categorie') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="zn-filter-group">
                <label class="zn-filter-label">Compte</label>
                <select name="compte_id" class="zn-select">
                    <option value="">Tous comptes</option>
                    @foreach($comptes as $c)
                        <option value="{{ $c->id }}" {{ request('compte_id') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    Filtrer
                </button>
                <a href="{{ route('non-conformites.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Main card with table --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <div class="zn-card-title">
                <span class="title-pip"></span>
                Liste des non‑conformités
            </div>
            <span class="zn-count">{{ $nonConformites->total() }} NC</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="zn-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Compte</th>
                        <th>Catégorie</th>
                        <th>Objet</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nonConformites as $nc)
                        <tr>
                            <td><span style="font-family:var(--font-mono);">{{ $nc->numero }}</span></td>
                            <td>{{ $nc->compte->etablissement }}</td>
                            <td>
                                {{ $nc->categorie }}
                                @if($nc->sous_categorie)
                                    <span style="color:var(--text-muted);"> / {{ $nc->sous_categorie }}</span>
                                @endif
                            </td>
                            <td>{{ $nc->objet }}</td>
                            <td>
                                @if($nc->statut === 'brouillon')
                                    <span class="dr-badge bd-amber">Brouillon</span>
                                @elseif($nc->statut === 'en_cours')
                                    <span class="dr-badge bd-blue">En cours</span>
                                @elseif($nc->statut === 'resolu')
                                    <span class="dr-badge bd-green">Résolu</span>
                                @elseif($nc->statut === 'ferme')
                                    <span class="dr-badge bd-none">Fermé</span>
                                @else
                                    <span class="dr-badge bd-none">{{ ucfirst($nc->statut) }}</span>
                                @endif
                            </td>
                            <td class="actions-cell">
                                <a href="{{ route('non-conformites.show', $nc) }}" class="btn-zn btn-zn-sm btn-zn-info">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Voir
                                </a>
                                 @if( auth()->user()->role === 'delegue' && $nc->delegue_id === auth()->id()) {{--$nc->statut === 'brouillon' && --}}
                                    <a href="{{ route('non-conformites.edit', $nc) }}" class="btn-zn btn-zn-sm btn-zn-ghost">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                                        </svg>
                                        Modifier
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="zn-empty">
                                    <div class="zn-empty-icon">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="8" x2="12" y2="12"/>
                                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                    </div>
                                    <h3>Aucune non‑conformité</h3>
                                    <p>{{ request('statut') || request('categorie') || request('compte_id') ? 'Aucun résultat pour les filtres sélectionnés.' : 'Commencez par créer une nouvelle non‑conformité.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($nonConformites->hasPages())
            <div class="zn-pagination">
                {{ $nonConformites->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection