@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
    /* Copy the entire <style> block from the zones index view here */
    /* (Same as previously provided – includes all zn-* classes, modals, etc.) */
    /* For brevity, we assume it's the same as in the previous answer */
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
    .zn-input {
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
    .zn-input:focus {
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

    /* List inside table */
    .product-list {
        margin: 0;
        padding-left: 1rem;
        font-size: 0.8rem;
    }
    .product-list li {
        margin-bottom: 0.2rem;
    }

    /* Clickable motif trigger */
    .motif-trigger {
        cursor: pointer;
        display: inline-block;
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        background: var(--bg-subtle);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.75rem;
        transition: background var(--t);
    }
    .motif-trigger:hover {
        background: var(--blue-mid);
        color: var(--blue);
    }

    /* ── Modal (same as delegates modal) ───────────────── */
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
        width: 100%; max-width: 500px;
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
        background: var(--amber-light); border: 1px solid var(--amber);
        display: flex; align-items: center; justify-content: center;
        color: var(--amber); flex-shrink: 0;
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

    .dlg-modal-body {
        padding: 1.5rem;
        font-size: 0.9rem;
        line-height: 1.5;
        color: var(--text-primary);
        white-space: pre-wrap;
        word-break: break-word;
    }

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
        .zn-search-bar form { flex-direction: column; align-items: stretch; }
        .filter-actions { margin-top: 0.5rem; justify-content: flex-end; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('retours.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('retours.index') }}">Stocks</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Bons de retour</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Bons de retour</h1>
            <p>Historique des retours de spécimens</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="zn-search-bar">
        <form method="GET" action="{{ route('retours.index') }}" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
            <div class="zn-filter-group">
                <label class="zn-filter-label">Recherche</label>
                <input type="text" name="search" class="zn-input" placeholder="N° retour ou n° BSS" value="{{ request('search') }}">
            </div>
            <div class="zn-filter-group">
                <label class="zn-filter-label">BSS</label>
                <div class="frm-select-wrap">
                    <select name="bss_id" class="zn-select">
                        <option value="">Tous</option>
                        @foreach($bssList as $b)
                            <option value="{{ $b->id }}" {{ request('bss_id') == $b->id ? 'selected' : '' }}>{{ $b->numero }}</option>
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
                <a href="{{ route('retours.index') }}" class="btn-zn btn-zn-sm btn-zn-danger-ghost">
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
                Liste des bons de retour
            </div>
            <span class="zn-count">{{ $retours->total() }} retour(s)</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="zn-table">
                <thead>
                    <tr>
                        <th>N° retour</th>
                        <th>BSS associé</th>
                        <th>Date retour</th>
                        <th>Créé par</th>
                        <th>Motif</th>
                        <th>Produits retournés</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($retours as $retour)
                    <tr>
                        <td><strong>{{ $retour->numero }}</strong></td>
                        <td>
                            <a href="{{ route('bss.show', $retour->bss) }}" class="btn-zn btn-zn-sm btn-zn-ghost" style="text-decoration: none;">
                                {{ $retour->bss->numero }}
                            </a>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($retour->date_retour)->format('d/m/Y') }}</td>
                        <td>{{ $retour->createdBy->prenom }} {{ $retour->createdBy->nom }}</td>
                        <td>
                            @if($retour->motif)
                                <div class="motif-trigger"
                                     role="button"
                                     tabindex="0"
                                     data-motif="{{ $retour->motif }}">
                                    {{ Str::limit($retour->motif, 50) }}
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <ul class="product-list">
                                @foreach($retour->lignes as $ligne)
                                    <li>{{ $ligne->product->titre }} : {{ $ligne->pivot->quantite_retournee }} ex.</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('bss.show', $retour->bss) }}" class="btn-zn btn-zn-sm btn-zn-info">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Voir BSS
                                </a>
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
                                    <h3>Aucun bon de retour trouvé</h3>
                                    <p>{{ request('search') ? 'Aucun résultat pour «\u00a0'.request('search').'\u00a0». Essayez un autre terme.' : 'Aucun retour enregistré pour le moment.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($retours->hasPages())
            <div class="zn-pagination">
                {{ $retours->withQueryString()->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>

{{-- Modal for motif --}}
<div class="dlg-modal-overlay" id="motifModalOverlay">
    <div class="dlg-modal" role="dialog" aria-modal="true">
        <div class="dlg-modal-hd">
            <div class="dlg-modal-icon">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
</svg>
            </div>
            <div class="dlg-modal-titles">
                <h2>Motif du retour</h2>
                <p>Détail complet</p>
            </div>
            <button class="dlg-modal-close" id="closeMotifModal" aria-label="Fermer">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="dlg-modal-body" id="motifModalBody">
            <!-- dynamic content -->
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Motif modal logic
    const motifOverlay = document.getElementById('motifModalOverlay');
    const motifBody = document.getElementById('motifModalBody');
    const closeMotif = document.getElementById('closeMotifModal');

    function closeModal() {
        motifOverlay.classList.remove('visible');
        document.body.style.overflow = '';
    }

    function openModal(motifText) {
        motifBody.innerHTML = motifText;
        motifOverlay.classList.add('visible');
        document.body.style.overflow = 'hidden';
    }

    // Attach click handlers to all motif triggers
    document.querySelectorAll('.motif-trigger').forEach(el => {
        const open = () => {
            const motif = el.dataset.motif;
            if (motif) openModal(motif);
        };
        el.addEventListener('click', open);
        el.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); } });
    });

    closeMotif.addEventListener('click', closeModal);
    motifOverlay.addEventListener('click', e => { if (e.target === motifOverlay) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endpush