@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ==== FULL EXACT STYLE CSS ==== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg-base:        #f5f6fa;
    --bg-card:        #ffffff;
    --bg-hover:       #f8f9fd;
    --bg-subtle:      #f0f2f8;
    --border:         #e4e7f0;
    --border-md:      #d0d5e8;
    --blue:           #5b8dee;
    --blue-dark:      #3d6fd6;
    --blue-light:     #eef3fd;
    --blue-mid:       #dce8fb;
    --amber:          #e8a020;
    --amber-light:    #fff8ec;
    --rose:           #e8506a;
    --rose-light:     #fef0f2;
    --green:          #28c76f;
    --green-light:    #e8fbf0;
    --teal:           #0cb8b6;
    --teal-light:     #e6faf9;
    --violet:         #7c6fcd;
    --violet-light:   #f0eeff;
    --text-primary:   #1a1f36;
    --text-secondary: #525f7f;
    --text-muted:     #9ba8c5;
    --text-hint:      #bcc5dc;
    --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
    --shadow-xs:   0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
    --shadow-sm:   0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
    --shadow-blue: 0 4px 14px rgba(91,141,238,.35);
    --font: 'DM Sans', sans-serif;
    --ease: cubic-bezier(.4,0,.2,1);
    --t: .18s var(--ease);
}

body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

/* ── Page: full‑width, no centering ── */
.zn-page {
    padding: 2rem 2.5rem 3rem;
    animation: pageIn .4s var(--ease) both;
}
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ── */
.zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
.zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.zn-bc a:hover { color: var(--blue); }
.zn-bc-sep { color: var(--text-hint); }
.zn-bc-cur { color: var(--text-secondary); }

/* ── Header ── */
.zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.zn-header-left h1 { font-size: 1.55rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.2; margin: 0; }
.zn-header-left p  { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

/* ── Card: constrained width (for forms) but can be overridden ── */
.zn-card {
    max-width: 780px;               /* constrain form width; remove for full‑width tables */
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.zn-card-header {
    padding: 1.1rem 1.6rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: .55rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.zn-card-title {
    font-size: .88rem; font-weight: 700;
    color: var(--text-primary); letter-spacing: -.01em;
    display: flex; align-items: center; gap: .55rem;
}
.title-pip {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--amber);
    box-shadow: 0 0 0 3px rgba(232,160,32,.2);
    flex-shrink: 0;
}
.zn-card-body { padding: 1.75rem 1.6rem; }

/* ── Footer ── */
.card-footer {
    padding: 1.1rem 1.6rem;
    border-top: 1px solid var(--border);
    background: var(--bg-base);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .6rem;
}

/* ── Buttons ── */
.btn-zn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .58rem 1.2rem; border-radius: var(--r-sm);
    font-family: var(--font); font-size: .83rem; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: all var(--t); text-decoration: none;
    white-space: nowrap; letter-spacing: -.01em; line-height: 1;
}
.btn-zn svg { flex-shrink: 0; }
.btn-zn-primary {
    background: var(--blue); color: #fff;
    border-color: var(--blue); box-shadow: var(--shadow-blue);
}
.btn-zn-primary:hover {
    background: var(--blue-dark); color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(91,141,238,.4);
    text-decoration: none;
}
.btn-zn-ghost {
    background: var(--bg-card); color: var(--text-secondary);
    border-color: var(--border); box-shadow: var(--shadow-xs);
}
.btn-zn-ghost:hover {
    background: var(--bg-hover); color: var(--text-primary);
    border-color: var(--border-md); text-decoration: none;
}
.btn-zn-danger {
    background: var(--rose-light); color: var(--rose);
    border-color: rgba(232,80,106,.18);
}
.btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-zn-success {
    background: var(--green-light); color: var(--green);
    border-color: rgba(40,199,111,.2);
}
.btn-zn-success:hover { background: #d4f5e2; color: var(--green); text-decoration: none; }
.btn-zn-warning {
    background: var(--amber-light); color: var(--amber);
    border-color: rgba(232,160,32,.2);
}
.btn-zn-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
.btn-zn-info {
    background: var(--violet-light); color: var(--violet);
    border-color: rgba(124,111,205,.2);
}
.btn-zn-info:hover { background: #e8e5ff; color: var(--violet); text-decoration: none; }
.btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

/* ── Form elements ── */
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

/* Input with icon (optional) */
.frm-input-wrap { position: relative; }
.frm-input-wrap .frm-icon {
    position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); pointer-events: none;
}
.frm-input-wrap .frm-input { padding-left: 2.35rem; }

/* ── Info grid (show views) ── */
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

/* ── Tables ── */
.zn-table { width: 100%; border-collapse: collapse; }
.zn-table thead tr { border-bottom: 1px solid var(--border); }
.zn-table th {
    padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-hint); text-align: left;
    background: var(--bg-base);
    white-space: nowrap;
}
.zn-table td {
    padding: .95rem 1.2rem; font-size: .83rem;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.zn-table tbody tr:hover { background: #f8f9fd; }
.zn-table tbody tr:last-child td { border-bottom: none; }

/* ── Badges ── */
.dr-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 20px;
    font-size: .7rem; font-weight: 600;
    white-space: nowrap;
}
.bd-teal   { background: var(--teal-light);   color: var(--teal); }
.bd-blue   { background: var(--blue-light);   color: var(--blue); }
.bd-green  { background: var(--green-light);  color: var(--green); }
.bd-amber  { background: var(--amber-light);  color: var(--amber); }
.bd-violet { background: var(--violet-light); color: var(--violet); }
.bd-rose   { background: var(--rose-light);   color: var(--rose); }
.bd-none   { background: var(--bg-subtle);    color: var(--text-muted); }

/* ── Actions cell ── */
.actions-cell { display: flex; align-items: center; gap: .35rem; flex-wrap: wrap; }

/* ── Empty state ── */
.zn-empty { padding: 4rem 2rem; text-align: center; }
.zn-empty-icon {
    width: 52px; height: 52px; border-radius: var(--r-md);
    background: var(--bg-subtle); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; color: var(--text-hint);
}
.zn-empty h3 { font-size: .95rem; font-weight: 700; color: var(--text-secondary); }
.zn-empty p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

/* ── Pagination ── */
.zn-pagination {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border);
    background: var(--bg-card);
    display: flex; justify-content: center;
    gap: 0.3rem; flex-wrap: wrap;
}
.zn-pagination .page-link {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 2rem; padding: 0.3rem 0.6rem; border-radius: var(--r-sm);
    background: var(--bg-card); border: 1px solid var(--border);
    color: var(--text-secondary); font-size: 0.8rem;
    text-decoration: none; transition: all var(--t);
}
.zn-pagination .page-link:hover { background: var(--bg-hover); border-color: var(--border-md); }
.zn-pagination .active .page-link { background: var(--blue); border-color: var(--blue); color: white; }
.zn-pagination .disabled .page-link { opacity: 0.5; pointer-events: none; }

/* ── Filter bar (for index views) ── */
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

/* ── Modal (delegates style) ── */
.dlg-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(26,31,54,.42);
    backdrop-filter: blur(6px);
    z-index: 1000;
    display: none; align-items: center; justify-content: center;
    padding: 1rem;
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

/* ── Responsive ── */
@media (max-width: 768px) {
    .zn-page   { padding: 1.25rem 1rem 2rem; }
    .zn-card   { max-width: 100%; }
    .card-footer { flex-direction: column-reverse; }
    .btn-zn    { width: 100%; justify-content: center; }
    .zn-table th, .zn-table td { padding: .75rem .9rem; }
    .zn-search-bar form { flex-direction: column; align-items: stretch; }
    .filter-actions { margin-top: 0.5rem; justify-content: flex-end; }
    .actions-cell { flex-direction: column; align-items: flex-start; }
    .info-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('actions.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('actions.index') }}">Actions</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Nouvelle action</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>Nouvelle action</h1>
            <p>Créer une action commerciale ou une tâche</p>
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

        <form method="POST" action="{{ route('actions.store') }}" id="actionForm" novalidate>
            @csrf
            <div class="zn-card-body">
                @include('actions._form')

                <h5 style="margin: 1.5rem 0 1rem 0;">Lignes d'action</h5>
                <div id="lines-container">
                    <div class="line-item">
                        @include('actions._line_form', ['lineIndex' => 0, 'line' => null, 'products' => $products, 'examens' => $examens])
                    </div>
                </div>
                <button type="button" id="add-line" class="btn-zn btn-zn-sm btn-zn-ghost mt-2">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter une ligne
                </button>
            </div>
            <div class="card-footer">
                <a href="{{ route('actions.index') }}" class="btn-zn btn-zn-ghost">
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
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let lineCounter = 1;
    const categories = @json($categories);
    const requiresProduct = @json($requiresProduct);
    const requiresBss = @json($requiresBss);
    const requiresRetour = @json($requiresRetour);
    const requiresExamen = @json($requiresExamen);

    function loadContacts(compteId, selectElement) {
        if (!compteId) return;
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez --</option>';
                data.forEach(c => options += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`);
                selectElement.innerHTML = options;
            });
    }

    function loadActionTypes(categorie, selectElement) {
        selectElement.innerHTML = '<option value="">Chargement...</option>';
        fetch(`/api/action-types-by-categorie?categorie=${encodeURIComponent(categorie)}`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez --</option>';
                data.forEach(at => options += `<option value="${at}">${at}</option>`);
                selectElement.innerHTML = options;
                selectElement.dispatchEvent(new Event('change'));
            });
    }

    function loadMoyens(actionType, selectElement) {
        fetch(`/api/moyens-by-action-type?action_type=${encodeURIComponent(actionType)}`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez --</option>';
                data.forEach(m => options += `<option value="${m}">${m}</option>`);
                selectElement.innerHTML = options;
            });
    }

    function updateRequiredFields(line) {
        const actionType = line.querySelector('.action-type-select').value;
        const productGroup = line.querySelector('select[name$="[product_ids][]"]')?.closest('.frm-group');
        const examenGroup = line.querySelector('select[name$="[examen_ids][]"]')?.closest('.frm-group');
        const bssGroup = line.querySelector('#bss-group-' + line.dataset.lineIndex);
        const retourGroup = line.querySelector('#retour-group-' + line.dataset.lineIndex);

        if (productGroup) productGroup.style.display = 'none';
        if (examenGroup) examenGroup.style.display = 'none';
        if (bssGroup) bssGroup.style.display = 'none';
        if (retourGroup) retourGroup.style.display = 'none';

        if (requiresProduct.includes(actionType) && productGroup) {
            productGroup.style.display = 'block';
            line.querySelector('select[name$="[product_ids][]"]').required = true;
        } else if (requiresBss.includes(actionType) && bssGroup) {
            bssGroup.style.display = 'block';
            line.querySelector('.bss-select').required = true;
        } else if (requiresRetour.includes(actionType) && retourGroup) {
            retourGroup.style.display = 'block';
            line.querySelector('.retour-select').required = true;
        } else if (requiresExamen.includes(actionType) && examenGroup) {
            examenGroup.style.display = 'block';
            line.querySelector('select[name$="[examen_ids][]"]').required = true;
        }
    }

    function attachLineEvents(lineElement) {
        const compteSelect = document.getElementById('compte_id');
        const categorieSelect = lineElement.querySelector('.categorie-select');
        const actionTypeSelect = lineElement.querySelector('.action-type-select');
        const moyenSelect = lineElement.querySelector('.moyen-select');
        const contactSelect = lineElement.querySelector('select[name$="[contact_ids][]"]');

        if (compteSelect) {
            compteSelect.addEventListener('change', () => loadContacts(compteSelect.value, contactSelect));
            if (compteSelect.value) loadContacts(compteSelect.value, contactSelect);
        }

        if (categorieSelect) {
            categorieSelect.addEventListener('change', () => loadActionTypes(categorieSelect.value, actionTypeSelect));
            if (categorieSelect.value) loadActionTypes(categorieSelect.value, actionTypeSelect);
        }
        if (actionTypeSelect) {
            actionTypeSelect.addEventListener('change', () => {
                loadMoyens(actionTypeSelect.value, moyenSelect);
                updateRequiredFields(lineElement);
            });
            if (actionTypeSelect.value) {
                loadMoyens(actionTypeSelect.value, moyenSelect);
                updateRequiredFields(lineElement);
            }
        }
    }

    document.getElementById('add-line').addEventListener('click', function() {
        const container = document.getElementById('lines-container');
        const template = container.querySelector('.line-item').cloneNode(true);
        const newIndex = lineCounter++;
        template.setAttribute('data-line-index', newIndex);
        // rename all name attributes
        template.querySelectorAll('[name]').forEach(el => {
            let name = el.name;
            if (name) {
                el.name = name.replace(/\[\d+\]/, `[${newIndex}]`);
            }
            // reset values
            if (el.tagName === 'SELECT' && name.includes('categorie')) el.selectedIndex = 0;
            if (el.tagName === 'SELECT' && name.includes('action_type')) el.innerHTML = '<option value="">-- D\'abord choisir catégorie --</option>';
            if (el.tagName === 'SELECT' && name.includes('moyen')) el.innerHTML = '<option value="">-- Sélectionnez --</option>';
            if (el.tagName === 'SELECT' && name.includes('contact_ids')) el.innerHTML = '';
            if (el.tagName === 'SELECT' && name.includes('product_ids')) Array.from(el.options).forEach(opt => opt.selected = false);
            if (el.tagName === 'SELECT' && name.includes('examen_ids')) Array.from(el.options).forEach(opt => opt.selected = false);
            if (el.tagName === 'SELECT' && name.includes('bss_id')) el.value = '';
            if (el.tagName === 'SELECT' && name.includes('retour_id')) el.value = '';
            if (el.tagName === 'INPUT') el.value = '';
        });
        // update ids for bss/retour groups
        template.querySelectorAll('[id^="bss-group-"]').forEach(el => {
            el.id = `bss-group-${newIndex}`;
        });
        template.querySelectorAll('[id^="retour-group-"]').forEach(el => {
            el.id = `retour-group-${newIndex}`;
        });
        container.appendChild(template);
        attachLineEvents(template);
    });

    document.querySelectorAll('.line-item').forEach(attachLineEvents);
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-line')) {
            const container = document.getElementById('lines-container');
            if (container.children.length > 1) {
                e.target.closest('.line-item').remove();
            } else {
                alert('Vous devez conserver au moins une ligne.');
            }
        }
    });
</script>
@endpush