@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM THE EXAMENS SHOW VIEW (same as before) ===== */
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

/* ── Page ──────────────────────────────────────────── */
.zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1400px; margin: 0 auto; }
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ────────────────────────────────────── */
.zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
.zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.zn-bc a:hover { color: var(--blue); }
.zn-bc-sep { color: var(--text-hint); }
.zn-bc-cur { color: var(--text-secondary); }

/* ── Header ────────────────────────────────────────── */
.zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.zn-header-left h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
.zn-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

/* ── Buttons ───────────────────────────────────────── */
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
.btn-zn-success { background: var(--green-light); color: var(--green); border-color: rgba(40,199,111,.2); }
.btn-zn-success:hover { background: #d4f5e2; color: var(--green); text-decoration: none; }
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
.frm-select-wrap {
    position: relative;
}
.frm-select-wrap::after {
    content: '';
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid var(--text-muted);
    pointer-events: none;
}
.frm-select-wrap .zn-select {
    padding-right: 2.2rem;
}

/* ── Card ──────────────────────────────────────────── */
.zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
.zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; background: linear-gradient(to bottom, #fafbff, #fff); }
.zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em; }
.title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.zn-count { font-size: .76rem; color: var(--text-muted); font-weight: 500; }
.zn-card-body { padding: 1.5rem 1.6rem; }

/* ── Table ─────────────────────────────────────────── */
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
.id-pill { font-family: var(--font-mono); font-size: .75rem; color: var(--text-muted); font-weight: 500; background: var(--bg-subtle); border-radius: var(--r-xs); padding: .18rem .5rem; display: inline-block; }

/* Badges */
.dr-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 20px;
    font-size: .7rem; font-weight: 600; white-space: nowrap;
}
.bd-teal { background: var(--teal-light); color: var(--teal); }
.bd-blue { background: var(--blue-light); color: var(--blue); }
.bd-green { background: var(--green-light); color: var(--green); }
.bd-amber { background: var(--amber-light); color: var(--amber); }
.bd-none { background: var(--bg-subtle); color: var(--text-muted); }

/* Actions cell */
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

/* General footer (card footer) */
.card-footer {
    padding: 1.1rem 1.6rem;
    border-top: 1px solid var(--border);
    background: var(--bg-base);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .6rem;
}

/* Responsive */
@media (max-width: 768px) {
    .zn-page { padding: 1.25rem 1rem 2rem; }
    .zn-header { flex-direction: column; gap: 1rem; }
    .zn-table th, .zn-table td { padding: .75rem .9rem; }
    .zn-search-bar form { flex-direction: column; align-items: stretch; }
    .filter-actions { margin-top: 0.5rem; justify-content: flex-end; }
    .actions-cell { flex-direction: column; align-items: flex-start; }
    .card-footer { flex-direction: column-reverse; }
    .btn-zn { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('demandes-specimens.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="#">Spécimens</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Demandes spéciales</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Demandes spéciales – Spécimens</h1>
            <p>Demandes hors consignation (magasin)</p>
        </div>
        @if(auth()->user()->role === 'delegue' || auth()->user()->role === 'admin')
        <div class="zn-header-actions">
            <a href="{{ route('demandes-specimens.create') }}" class="btn-zn btn-zn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nouvelle demande
            </a>
        </div>
        @endif
    </div>

    {{-- Filters --}}
    <div class="zn-search-bar">
        <form method="GET" action="{{ route('demandes-specimens.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            <div class="zn-filter-group">
                <label class="zn-filter-label">Statut</label>
                <div class="frm-select-wrap">
                    <select name="statut" class="zn-select">
                        <option value="">Tous statuts</option>
                        @foreach($statuts as $s)
                            <option value="{{ $s }}" {{ request('statut') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="zn-filter-group">
                <label class="zn-filter-label">Type</label>
                <div class="frm-select-wrap">
                    <select name="type" class="zn-select">
                        <option value="">Tous types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="zn-filter-group">
                <label class="zn-filter-label">Compte</label>
                <div class="frm-select-wrap">
                    <select name="compte_id" class="zn-select">
                        <option value="">Tous comptes</option>
                        @foreach($comptes as $c)
                            <option value="{{ $c->id }}" {{ request('compte_id') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-zn btn-zn-sm btn-zn-ghost">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    Filtrer
                </button>
                <a href="{{ route('demandes-specimens.index') }}" class="btn-zn btn-zn-sm btn-zn-danger-ghost">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
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
                Liste des demandes
            </div>
            <span class="zn-count">{{ $demandes->total() }} demande(s)</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="zn-table">
                <thead>
                    <tr>
                       
                        <th>Type</th>
                        <th>Compte / Contact</th>
                        <th>Délégué</th>
                        <th>Date demande</th>
                        <th>Statut</th>
                        <th>Old BSS associé</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($demandes as $d)
                    <tr>
                   
                        <td>{{ ucfirst($d->type) }}</td>
                        <td>
                            @if($d->type == 'etablissement')
                                {{ $d->compte->etablissement ?? '-' }}
                            @else
                                {{ $d->contact->prenom ?? '' }} {{ $d->contact->nom ?? '-' }}
                            @endif
                        </td>
                        <td>{{ $d->delegate->prenom }} {{ $d->delegate->nom }}</td>
                        <td>{{ $d->date_demande->format('d/m/Y') }}</td>
                        <td>
                            <span class="dr-badge bd-{{ $d->statut }}">{{ ucfirst($d->statut) }}</span>
                        </td>
                        <td>
                            @if($d->originalBss)
                                <a href="{{ route('bss.show', $d->originalBss) }}" class="btn-zn btn-zn-sm btn-zn-info">{{ $d->originalBss->numero }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('demandes-specimens.show', $d) }}" class="btn-zn btn-zn-sm btn-zn-info">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Voir
                                </a>
                                @if($d->statut === 'demande')
                                    @if(in_array(auth()->user()->role, ['admin','rbo']))
                                        <form method="POST" action="{{ route('demandes-specimens.validate', $d) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" name="action" value="approve" class="btn-zn btn-zn-sm btn-zn-success" onclick="return confirm('Approuver cette demande ? Un BSS sera généré.')">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                                Approuver
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('demandes-specimens.validate', $d) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" name="action" value="decline" class="btn-zn btn-zn-sm btn-zn-danger" onclick="return confirm('Refuser cette demande ?')">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                                </svg>
                                                Refuser
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->user()->role === 'delegue' && $d->delegue_id === auth()->id())
                                        <a href="{{ route('demandes-specimens.edit', $d) }}" class="btn-zn btn-zn-sm btn-zn-warning">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                                            </svg>
                                            Modifier
                                        </a>
                                        <form method="POST" action="{{ route('demandes-specimens.destroy', $d) }}" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-zn btn-zn-sm btn-zn-danger" onclick="return confirm('Supprimer définitivement ?')">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6l-1 14H6L5 6"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="zn-empty">
                                    <div class="zn-empty-icon">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </div>
                                    <h3>Aucune demande trouvée</h3>
                                    <p>{{ request('statut') || request('type') || request('compte_id') ? 'Aucun résultat pour les filtres sélectionnés.' : 'Commencez par créer une nouvelle demande.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($demandes->hasPages())
            <div class="zn-pagination">
                {{ $demandes->withQueryString()->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection