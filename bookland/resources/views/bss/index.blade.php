@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
    /* Copy the entire <style> block from the zones example here */
    /* We'll include it fully in the final answer */
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
    .btn-zn-danger-ghost { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }
    .btn-zn-danger-ghost:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
    .btn-zn-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
    .btn-zn-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
    .btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
    .btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
    .btn-zn-info { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2); }
    .btn-zn-info:hover { background: #e8e5ff; color: var(--violet); text-decoration: none; }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

    /* ── Search / Filters ───────────────────────────────── */
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
    .zn-select {
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
        cursor: pointer;
    }
    .zn-select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .filter-actions {
        display: flex;
        align-items: flex-end;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

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
    .bd-green { background: var(--green-light); color: var(--green); }
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

    /* ── Modal (custom, like delegates modal) ───────────────── */
    .dlg-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(26,31,54,.42);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 1000;
        display: none; align-items: center; justify-content: center; padding: 1rem;
    }
    .dlg-modal-overlay.visible { display: flex; animation: oIn .2s ease both; }
    @keyframes oIn { from { opacity: 0; } to { opacity: 1; } }

    .dlg-modal {
        background: var(--bg-card);
        border: 1px solid var(--border-md);
        border-radius: var(--r-xl);
        width: 100%; max-width: 480px;
        box-shadow: 0 20px 48px rgba(31,45,80,.13), 0 6px 16px rgba(31,45,80,.07);
        overflow: hidden;
        animation: mIn .28s cubic-bezier(.34,1.4,.64,1) both;
    }
    @keyframes mIn {
        from { opacity: 0; transform: scale(.94) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .dlg-modal-hd {
        padding: 1.25rem 1.5rem 1.1rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
    }
    .dlg-modal-icon {
        width: 38px; height: 38px; border-radius: var(--r-md);
        background: var(--rose-light); border: 1px solid var(--rose);
        display: flex; align-items: center; justify-content: center;
        color: var(--rose); flex-shrink: 0;
    }
    .dlg-modal-titles { flex: 1; }
    .dlg-modal-titles h2 { font-size: .95rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
    .dlg-modal-titles p  { font-size: .76rem; color: var(--text-muted); margin-top: .18rem; }
    .dlg-modal-close {
        width: 28px; height: 28px; border-radius: var(--r-xs);
        background: var(--bg-subtle); border: 1px solid var(--border);
        color: var(--text-muted); display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all var(--t); flex-shrink: 0;
    }
    .dlg-modal-close:hover { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }

    .dlg-modal-body { padding: 1rem 1.5rem 1.5rem; }
    .textarea-custom {
        width: 100%;
        padding: 0.65rem;
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        font-family: var(--font);
        font-size: 0.85rem;
        transition: all var(--t);
        outline: none;
    }
    .textarea-custom:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.6rem;
        margin-top: 1.25rem;
    }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .zn-header { flex-direction: column; gap: 1rem; }
        .zn-table th, .zn-table td { padding: .75rem .9rem; }
        .zn-search-bar form { flex-direction: column; align-items: stretch; }
        .filter-actions { margin-top: 0.5rem; justify-content: flex-end; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('bss.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="#">Stocks</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Bons de sortie spécimens</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Bons de sortie spécimens (BSS)</h1>
            <p>Gérez vos demandes de spécimens</p>
        </div>
        @if(auth()->user()->role === 'delegue')
        <div class="zn-header-actions">
            <a href="{{ route('bss.create') }}" class="btn-zn btn-zn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nouveau BSS
            </a>
        </div>
        @endif
    </div>

    {{-- Filters --}}
    <div class="zn-search-bar">
        <form method="GET" action="{{ route('bss.index') }}" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
            @if(auth()->user()->role !== 'delegue')
            <div class="zn-filter-group">
                <label class="zn-filter-label">Délégué</label>
                <select name="delegate_id" class="zn-select">
                    <option value="">Tous</option>
                    @foreach($delegates as $del)
                        <option value="{{ $del->id }}" {{ request('delegate_id') == $del->id ? 'selected' : '' }}>{{ $del->prenom }} {{ $del->nom }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="zn-filter-group">
                <label class="zn-filter-label">Compte</label>
                <select name="compte_id" class="zn-select">
                    <option value="">Tous</option>
                    @foreach($comptes as $c)
                        <option value="{{ $c->id }}" {{ request('compte_id') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
                    @endforeach
                </select>
            </div>
            <div class="zn-filter-group">
                <label class="zn-filter-label">Statut</label>
                <select name="statut" class="zn-select">
                    <option value="">Tous</option>
                    <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                    <option value="soumis" {{ request('statut') == 'soumis' ? 'selected' : '' }}>Soumis</option>
                    <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                    <option value="livre" {{ request('statut') == 'livre' ? 'selected' : '' }}>Livré</option>
                    <option value="refuse" {{ request('statut') == 'refuse' ? 'selected' : '' }}>Refusé</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    Filtrer
                </button>
                <a href="{{ route('bss.index') }}" class="btn-zn btn-zn-danger-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Table card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <div class="zn-card-title">
                <span class="title-pip"></span>
                Liste des BSS
            </div>
            <span class="zn-count">{{ $bssList->total() }} BSS</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="zn-table">
                <thead>
                    <tr>
                        <th>N° BSS</th><th>Compte</th><th>Contact</th><th>Délégué</th><th>Date création</th><th>Statut</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bssList as $bss)
                    <tr>
                        <td><strong>{{ $bss->numero }}</strong></td>
                        <td>{{ $bss->compte->etablissement }}</td>
                        <td>{{ $bss->contact->prenom }} {{ $bss->contact->nom }}</td>
                        <td>{{ $bss->delegate->prenom }} {{ $bss->delegate->nom }}</td>
                        <td>{{ $bss->date_bss->format('d/m/Y') }}</td>
                        <td>
                            @if($bss->statut == 'soumis') <span class="dr-badge bd-blue">Soumis</span>
                            @elseif($bss->statut == 'valide') <span class="dr-badge bd-teal">Validé</span>
                            @elseif($bss->statut == 'livre') <span class="dr-badge bd-green">Livré</span>
                            @elseif($bss->statut == 'refuse') <span class="dr-badge bd-none">Refusé</span>
                            @else <span class="dr-badge bd-none">{{ $bss->statut }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('bss.show', $bss) }}" class="btn-zn btn-zn-sm btn-zn-info">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Détails
                                </a>
                                @if(auth()->user()->role === 'delegue' && $bss->delegate_id === auth()->id() && $bss->statut === 'valide' && !$bss->feedback)
                                    <a href="{{ route('bss.edit', $bss) }}" class="btn-zn btn-zn-sm btn-zn-warning">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                                        </svg>
                                        Feedback
                                    </a>
                                @endif
                                @if(in_array(auth()->user()->role, ['admin','rbo']) && $bss->statut === 'soumis')
                                    <form action="{{ route('bss.validate', $bss) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn-zn btn-zn-sm btn-zn-primary" onclick="return confirm('Valider ce BSS ?')">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                            Valider
                                        </button>
                                    </form>
                                    <button class="btn-zn btn-zn-sm btn-zn-danger" onclick="openRefuseModal({{ $bss->id }})">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                        </svg>
                                        Refuser
                                    </button>
                                @endif
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('bss.destroy', $bss) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ?')" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-zn btn-zn-sm btn-zn-danger">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6l-1 14H6L5 6"/>
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
                            <td colspan="7">
                                <div class="zn-empty">
                                    <div class="zn-empty-icon">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </div>
                                    <h3>Aucun BSS trouvé</h3>
                                    <p>{{ request('statut') ? 'Aucun BSS pour les filtres sélectionnés.' : 'Commencez par créer un nouveau BSS.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bssList->hasPages())
            <div class="zn-pagination">
                {{ $bssList->withQueryString()->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>

{{-- Modal for refuse reason (custom, no Bootstrap) --}}
<div class="dlg-modal-overlay" id="refuseModalOverlay">
    <div class="dlg-modal" role="dialog" aria-modal="true">
        <div class="dlg-modal-hd">
            <div class="dlg-modal-icon">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div class="dlg-modal-titles">
                <h2>Motif du refus</h2>
                <p>Veuillez indiquer la raison du refus</p>
            </div>
            <button class="dlg-modal-close" id="closeRefuseModal" aria-label="Fermer">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="dlg-modal-body">
            <form method="POST" id="refuseForm">
                @csrf
                <input type="hidden" name="action" value="refuse">
                <textarea name="motif_refus" class="textarea-custom" rows="3" placeholder="Expliquez pourquoi ce BSS est refusé..." required></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn-zn btn-zn-ghost" id="cancelRefuseBtn">Annuler</button>
                    <button type="submit" class="btn-zn btn-zn-danger">Refuser</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Refuse modal logic (custom, no Bootstrap)
    const refuseOverlay = document.getElementById('refuseModalOverlay');
    const refuseForm = document.getElementById('refuseForm');
    const closeRefuse = document.getElementById('closeRefuseModal');
    const cancelRefuse = document.getElementById('cancelRefuseBtn');

    function closeModal() {
        refuseOverlay.classList.remove('visible');
        document.body.style.overflow = '';
        refuseForm.reset();
    }

    function openRefuseModal(bssId) {
        refuseForm.action = `/bss/${bssId}/validate`;
        refuseOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden';
    }

    // Attach to global scope for onclick usage
    window.openRefuseModal = openRefuseModal;

    closeRefuse.addEventListener('click', closeModal);
    cancelRefuse.addEventListener('click', closeModal);
    refuseOverlay.addEventListener('click', (e) => {
        if (e.target === refuseOverlay) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && refuseOverlay.classList.contains('visible')) closeModal();
    });
</script>
@endpush