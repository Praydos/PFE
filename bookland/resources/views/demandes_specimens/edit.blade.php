@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM INDEX/SHOW VIEWS (previous answer) ===== */
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
.frm-select-wrap .zn-select,
.frm-select-wrap .frm-select {
    padding-right: 2.2rem;
}

/* ── Card ──────────────────────────────────────────── */
.zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
.zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; background: linear-gradient(to bottom, #fafbff, #fff); }
.zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em; }
.title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.zn-count { font-size: .76rem; color: var(--text-muted); font-weight: 500; }
.zn-card-body { padding: 1.5rem 1.6rem; }

/* ── Form elements ──────────────────────────────────── */
.frm-group { display: flex; flex-direction: column; gap: .45rem; margin-bottom: 1.25rem; }
.frm-group:last-of-type { margin-bottom: 0; }
.frm-label { font-size: .8rem; font-weight: 600; color: var(--text-secondary); letter-spacing: -.01em; }
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
.frm-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-mid);
}
textarea.frm-input { resize: vertical; }

/* ── Info grid (for show views) ─────────────────────── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.info-item {
    font-size: 0.84rem;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
    padding-bottom: 0.5rem;
}
.info-label {
    font-weight: 600;
    color: var(--text-primary);
    margin-right: 0.5rem;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
hr { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }

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

/* Footer (card footer) */
.card-footer {
    padding: 1.1rem 1.6rem;
    border-top: 1px solid var(--border);
    background: var(--bg-base);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .6rem;
}

/* Modal (delegates style) */
.dlg-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(26,31,54,.42);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 1000;
    display: none; align-items: center; justify-content: center; padding: 1rem;
}
.dlg-modal-overlay.visible { display: flex; animation: oIn .2s ease both; }
@keyframes oIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
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
    width: 38px; height: 38px;
    border-radius: var(--r-md);
    background: var(--blue-light);
    border: 1px solid var(--blue-mid);
    display: flex; align-items: center; justify-content: center;
    color: var(--blue); flex-shrink: 0;
}
.dlg-modal-titles { flex: 1; }
.dlg-modal-titles h2 { font-size: .95rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
.dlg-modal-titles p  { font-size: .76rem; color: var(--text-muted); margin-top: .18rem; }
.dlg-modal-close {
    width: 28px; height: 28px;
    border-radius: var(--r-xs);
    background: var(--bg-subtle);
    border: 1px solid var(--border);
    color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all var(--t);
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
    .info-grid { grid-template-columns: 1fr; }
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
        <a href="{{ route('demandes-specimens.index') }}">Demandes spéciales</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Modifier #{{ $demandes_specimen->id }}</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Modifier la demande #{{ $demandes_specimen->id }}</h1>
            <p>Mettez à jour les informations</p>
        </div>
    </div>

    {{-- Card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <div class="zn-card-title">
                <span class="title-pip"></span>
                Formulaire
            </div>
        </div>

        <form method="POST" action="{{ route('demandes-specimens.update', $demandes_specimen) }}" novalidate>
            @csrf
            @method('PUT')
            <div class="zn-card-body">

                {{-- Type --}}
                <div class="frm-group">
                    <label class="frm-label" for="type">Type <span class="req">*</span></label>
                    <div class="frm-select-wrap">
                        <select name="type" id="type" class="frm-select" required>
                            <option value="etablissement" {{ $demandes_specimen->type == 'etablissement' ? 'selected' : '' }}>Établissement</option>
                            <option value="personnelle" {{ $demandes_specimen->type == 'personnelle' ? 'selected' : '' }}>Personnelle</option>
                        </select>
                    </div>
                </div>

                {{-- Établissement fields --}}
                <div id="etablissement-fields" class="frm-group" {{ $demandes_specimen->type != 'etablissement' ? 'style=display:none' : '' }}>
                    <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
                    <div class="frm-select-wrap">
                        <select name="compte_id" id="compte_id" class="frm-select" required>
                            <option value="">— Sélectionnez —</option>
                            @foreach($comptes as $c)
                                <option value="{{ $c->id }}" {{ $demandes_specimen->compte_id == $c->id ? 'selected' : '' }}>{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Personnelle fields --}}
                <div id="personnelle-fields" class="frm-group" {{ $demandes_specimen->type == 'etablissement' ? 'style=display:none' : '' }}>
                    <div class="frm-group">
                        <label class="frm-label" for="contact_id">Contact <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="contact_id" id="contact_id" class="frm-select">
                                <option value="">— Sélectionnez —</option>
                                @foreach($contacts ?? [] as $c)
                                    <option value="{{ $c->id }}" {{ $demandes_specimen->contact_id == $c->id ? 'selected' : '' }}>{{ $c->prenom }} {{ $c->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="ville_id_perso">Ville <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="ville_id" id="ville_id_perso" class="frm-select">
                                @foreach($villes as $v)
                                    <option value="{{ $v->id }}" {{ $demandes_specimen->ville_id == $v->id ? 'selected' : '' }}>{{ $v->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="zone_id_perso">Zone</label>
                        <div class="frm-select-wrap">
                            <select name="zone_id" id="zone_id_perso" class="frm-select">
                                <option value="">— Sélectionnez —</option>
                                @foreach($zones as $z)
                                    <option value="{{ $z->id }}" {{ $demandes_specimen->zone_id == $z->id ? 'selected' : '' }}>{{ $z->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="frm-group">
                    <label class="frm-label" for="description">Description</label>
                    <textarea name="description" id="description" class="frm-input" rows="2">{{ old('description', $demandes_specimen->description) }}</textarea>
                </div>

                <hr>

                <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Produits demandés</h3>
                <div id="products-container">
                    @foreach($demandes_specimen->lignes as $idx => $ligne)
                    <div class="product-row" style="display: flex; gap: 1rem; align-items: flex-end; margin-bottom: 1rem;" data-id="{{ $ligne->id }}">
                        <div class="frm-group" style="flex: 2;">
                            <label class="frm-label">Produit <span class="req">*</span></label>
                            <div class="frm-select-wrap">
                                <select name="products[{{ $idx }}][product_id]" class="frm-select" required>
                                    <option value="">— Sélectionnez —</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" {{ $ligne->product_id == $p->id ? 'selected' : '' }}>{{ $p->titre }} ({{ $p->isbn_13 ?? $p->isbn_10 }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="frm-group" style="flex: 1;">
                            <label class="frm-label">Quantité <span class="req">*</span></label>
                            <input type="number" name="products[{{ $idx }}][quantity]" value="{{ $ligne->quantity }}" class="frm-input" required min="1">
                        </div>
                        <input type="hidden" name="products[{{ $idx }}][id]" value="{{ $ligne->id }}">
                        <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-product" style="margin-bottom: 0.5rem;">X</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-product" class="btn-zn btn-zn-sm btn-zn-ghost mt-2">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter un produit
                </button>
            </div>

            <div class="card-footer">
                <a href="{{ route('demandes-specimens.show', $demandes_specimen) }}" class="btn-zn btn-zn-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Dynamic product rows
    let nextIndex = {{ $demandes_specimen->lignes->count() }};
    const container = document.getElementById('products-container');
    const productsData = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->titre . ' (' . ($p->isbn_13 ?? $p->isbn_10) . ')']));

    function createProductRow(index) {
        const row = document.createElement('div');
        row.className = 'product-row';
        row.style.cssText = 'display: flex; gap: 1rem; align-items: flex-end; margin-bottom: 1rem;';
        row.innerHTML = `
            <div class="frm-group" style="flex: 2;">
                <label class="frm-label">Produit <span class="req">*</span></label>
                <div class="frm-select-wrap">
                    <select name="products[${index}][product_id]" class="frm-select" required>
                        <option value="">— Sélectionnez —</option>
                        ${productsData.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                    </select>
                </div>
            </div>
            <div class="frm-group" style="flex: 1;">
                <label class="frm-label">Quantité <span class="req">*</span></label>
                <input type="number" name="products[${index}][quantity]" class="frm-input" required min="1">
            </div>
            <button type="button" class="btn-zn btn-zn-danger btn-zn-sm remove-product" style="margin-bottom: 0.5rem;">X</button>
        `;
        row.querySelector('.remove-product').addEventListener('click', () => row.remove());
        return row;
    }

    document.getElementById('add-product').addEventListener('click', () => {
        const newRow = createProductRow(nextIndex++);
        container.appendChild(newRow);
    });

    document.querySelectorAll('.product-row .remove-product').forEach(btn => {
        btn.addEventListener('click', function() { this.closest('.product-row').remove(); });
    });

    // Toggle type fields
    const typeSelect = document.getElementById('type');
    const etabFields = document.getElementById('etablissement-fields');
    const persoFields = document.getElementById('personnelle-fields');
    function toggleType() {
        if (typeSelect.value === 'etablissement') {
            etabFields.style.display = 'block';
            persoFields.style.display = 'none';
        } else {
            etabFields.style.display = 'none';
            persoFields.style.display = 'block';
        }
    }
    typeSelect.addEventListener('change', toggleType);
    toggleType();

    // Load contacts for etablissement if needed (optional)
    // We can keep the existing logic if present, but not mandatory for edit.
</script>
@endsection