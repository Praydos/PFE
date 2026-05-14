@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:       #f5f6fa;
    --card:     #ffffff;
    --hover:    #f8f9fd;
    --subtle:   #f0f2f8;
    --border:   #e4e7f0;
    --border-2: #d0d5e8;
    --blue:     #5b8dee;
    --blue-d:   #3d6fd6;
    --blue-l:   #eef3fd;
    --blue-m:   #dce8fb;
    --teal:     #0cb8b6;
    --teal-l:   #e6faf9;
    --violet:   #7c6fcd;
    --violet-l: #f0eeff;
    --amber:    #e8a020;
    --amber-l:  #fff8ec;
    --rose:     #e8506a;
    --rose-l:   #fef0f2;
    --green:    #28c76f;
    --green-l:  #e8fbf0;
    --t1: #1a1f36; --t2: #525f7f; --t3: #9ba8c5; --t4: #bcc5dc;
    --r1:6px; --r2:8px; --r3:12px; --r4:16px; --r5:20px;
    --s1: 0 1px 3px rgba(31,45,80,.06);
    --s2: 0 2px 8px rgba(31,45,80,.08);
    --s3: 0 8px 24px rgba(31,45,80,.10);
    --sb: 0 4px 14px rgba(91,141,238,.32);
    --font: 'DM Sans', sans-serif;
    --mono: 'DM Mono', monospace;
    --ease: cubic-bezier(.4,0,.2,1);
    --t: .17s var(--ease);
}

body { font-family: var(--font); background: var(--bg); color: var(--t1); -webkit-font-smoothing: antialiased; }

/* ── Page ─────────────────────────────────────────── */
.ac-page { padding: 2rem 2.5rem 3rem; animation: rise .4s var(--ease) both; }
@keyframes rise { from{opacity:0;transform:translateY(12px);} to{opacity:1;transform:translateY(0);} }

/* ── Breadcrumb ────────────────────────────────────── */
.ac-bc { display:flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:500; color:var(--t3); margin-bottom:1.5rem; }
.ac-bc a { color:var(--t3); text-decoration:none; transition:color var(--t); }
.ac-bc a:hover { color:var(--blue); }
.ac-bc-s { color:var(--t4); }

/* ── Header ─────────────────────────────────────────  */
.ac-header { margin-bottom:2rem; }
.ac-header h1 { font-size:1.6rem; font-weight:800; letter-spacing:-.03em; color:var(--t1); }
.ac-header p  { font-size:.83rem; color:var(--t3); margin-top:.3rem; }

/* ── Buttons ──────────────────────────────────────── */
.btn-ac { display:inline-flex; align-items:center; gap:.4rem; padding:.56rem 1.2rem; border-radius:var(--r2); font-family:var(--font); font-size:.82rem; font-weight:600; cursor:pointer; border:1px solid transparent; transition:all var(--t); text-decoration:none; white-space:nowrap; letter-spacing:-.01em; line-height:1; }
.btn-ac svg { flex-shrink:0; }
.btn-ac-primary { background:var(--blue); color:#fff; border-color:var(--blue); box-shadow:var(--sb); }
.btn-ac-primary:hover { background:var(--blue-d); color:#fff; text-decoration:none; transform:translateY(-1px); }
.btn-ac-ghost { background:var(--card); color:var(--t2); border-color:var(--border); box-shadow:var(--s1); }
.btn-ac-ghost:hover { background:var(--hover); color:var(--t1); border-color:var(--border-2); text-decoration:none; }
.btn-ac-danger { background:var(--rose-l); color:var(--rose); border-color:rgba(232,80,106,.2); }
.btn-ac-danger:hover { background:#fddde2; color:var(--rose); text-decoration:none; }
.btn-ac-teal { background:var(--teal-l); color:var(--teal); border-color:rgba(12,184,182,.2); }
.btn-ac-teal:hover { background:#d1f5f4; color:var(--teal); text-decoration:none; }
.btn-ac-sm { padding:.34rem .7rem; font-size:.75rem; }

/* ── Main card ─────────────────────────────────────── */
.ac-card { background:var(--card); border:1px solid var(--border); border-radius:var(--r5); box-shadow:var(--s2); overflow:hidden; }
.ac-card-hd { padding:1rem 1.6rem; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:.5rem; background:linear-gradient(to bottom,#fafbff,#fff); }
.ac-card-pip { width:7px; height:7px; border-radius:50%; background:var(--blue); box-shadow:0 0 0 3px var(--blue-m); flex-shrink:0; }
.ac-card-pip.amber { background:var(--amber); box-shadow:0 0 0 3px rgba(232,160,32,.2); }
.ac-card-title { font-size:.87rem; font-weight:700; color:var(--t1); letter-spacing:-.01em; }

.ac-card-body { padding:1.75rem 1.6rem; }

/* ── Form primitives ───────────────────────────────── */
.ac-sec { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.11em; color:var(--t4); display:flex; align-items:center; gap:.5rem; margin-bottom:1rem; }
.ac-sec::after { content:''; flex:1; height:1px; background:var(--border); }

.ac-row   { display:grid; gap:1rem; margin-bottom:1.1rem; }
.ac-row-2 { grid-template-columns:1fr 1fr; }
.ac-row-3 { grid-template-columns:1fr 1fr 1fr; }
.ac-row-21 { grid-template-columns:2fr 1fr; }
.ac-row-12 { grid-template-columns:1fr 2fr; }
.ac-row-1 { grid-template-columns:1fr; }

.ac-group { display:flex; flex-direction:column; gap:.42rem; }
.ac-label { font-size:.78rem; font-weight:600; color:var(--t2); letter-spacing:-.01em; display:flex; align-items:center; gap:.3rem; }
.ac-label .req { color:var(--rose); }
.ac-label .opt { font-size:.7rem; font-weight:400; color:var(--t4); }

.ac-input, .ac-select, .ac-textarea {
    width:100%; padding:.62rem .9rem;
    border:1px solid var(--border); border-radius:var(--r2);
    background:var(--card); font-family:var(--font);
    font-size:.84rem; color:var(--t1);
    box-shadow:var(--s1);
    transition:border-color var(--t), box-shadow var(--t);
    outline:none; appearance:none; -webkit-appearance:none;
}
.ac-input::placeholder, .ac-textarea::placeholder { color:var(--t4); }
.ac-input:focus, .ac-select:focus, .ac-textarea:focus { border-color:var(--blue); box-shadow:0 0 0 3px var(--blue-m); }
.ac-textarea { resize:vertical; min-height:72px; }
.ac-input.err, .ac-select.err { border-color:var(--rose); box-shadow:0 0 0 3px rgba(232,80,106,.1); }
.ac-input[type="date"], .ac-input[type="time"] { font-family:var(--mono); font-size:.8rem; }
.ac-input[type="number"] { font-family:var(--mono); font-size:.82rem; -moz-appearance:textfield; }
.ac-input[type="number"]::-webkit-inner-spin-button,
.ac-input[type="number"]::-webkit-outer-spin-button { -webkit-appearance:none; }

.ac-sel-wrap { position:relative; }
.ac-sel-wrap::after { content:''; position:absolute; right:.9rem; top:50%; transform:translateY(-50%); width:0; height:0; border-left:4px solid transparent; border-right:4px solid transparent; border-top:5px solid var(--t3); pointer-events:none; }
.ac-select { padding-right:2.2rem; cursor:pointer; }
.ac-select[multiple] { padding-right:.9rem; }

.ac-hint  { font-size:.72rem; color:var(--t3); margin-top:.2rem; display:flex; align-items:center; gap:.3rem; }
.ac-error { font-size:.75rem; color:var(--rose); font-weight:500; display:flex; align-items:center; gap:.3rem; margin-top:.2rem; }

/* Checkbox row */
.ac-check-row { display:flex; align-items:center; gap:.55rem; cursor:pointer; }
.ac-check-row input[type="checkbox"] { width:15px; height:15px; accent-color:var(--blue); cursor:pointer; flex-shrink:0; }
.ac-check-row span { font-size:.82rem; font-weight:600; color:var(--t2); }

/* ── Lines section ─────────────────────────────────── */
.ac-lines-hd {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:1rem;
}
.ac-lines-title { font-size:.9rem; font-weight:700; color:var(--t1); display:flex; align-items:center; gap:.5rem; }
.ac-lines-title-pip { width:7px; height:7px; border-radius:50%; background:var(--amber); box-shadow:0 0 0 3px rgba(232,160,32,.2); }

/* Individual line card */
.line-item {
    background:var(--subtle);
    border:1px solid var(--border);
    border-radius:var(--r3);
    margin-bottom:.85rem;
    overflow:hidden;
    transition:border-color var(--t), box-shadow var(--t);
}
.line-item:hover { border-color:var(--border-2); box-shadow:var(--s2); }
.line-item:last-child { margin-bottom:0; }

.line-item-hd {
    padding:.65rem 1.2rem;
    background:var(--card);
    border-bottom:1px solid var(--border);
    display:flex; align-items:center; justify-content:space-between;
    gap:.5rem;
}
.line-item-num {
    display:flex; align-items:center; gap:.5rem;
    font-size:.78rem; font-weight:700; color:var(--t2);
}
.line-num-badge {
    width:22px; height:22px; border-radius:50%;
    background:var(--amber-l); border:1px solid rgba(232,160,32,.25);
    color:var(--amber); font-size:.68rem; font-weight:800;
    display:flex; align-items:center; justify-content:center;
}
.line-item-body { padding:1.1rem 1.2rem; display:flex; flex-direction:column; gap:.9rem; }

/* Multi-select list */
.ac-multiselect {
    width:100%; border:1px solid var(--border);
    border-radius:var(--r2); background:var(--card);
    font-family:var(--font); font-size:.82rem; color:var(--t1);
    outline:none; box-shadow:var(--s1);
    transition:border-color var(--t), box-shadow var(--t);
    padding:.3rem 0;
}
.ac-multiselect:focus { border-color:var(--blue); box-shadow:0 0 0 3px var(--blue-m); }
.ac-multiselect option { padding:.4rem .8rem; }
.ac-multiselect option:checked { background:var(--blue-l); color:var(--blue); }

/* Conditional field reveal */
.cond-field { display:none; }
.cond-field.visible { display:flex; flex-direction:column; gap:.42rem; }

/* ── Footer ─────────────────────────────────────────── */
.ac-footer { padding:1.1rem 1.6rem; border-top:1px solid var(--border); background:var(--bg); display:flex; align-items:center; justify-content:flex-end; gap:.6rem; }

/* ── Responsive ─────────────────────────────────────── */
@media(max-width:900px) { .ac-row-3 { grid-template-columns:1fr 1fr; } }
@media(max-width:768px) {
    .ac-page { padding:1.25rem 1rem 2rem; }
    .ac-row-2, .ac-row-3, .ac-row-21, .ac-row-12 { grid-template-columns:1fr; }
    .ac-footer { flex-direction:column-reverse; }
    .btn-ac { width:100%; justify-content:center; }
}
</style>
@endpush

@section('content')
<div class="ac-page">

    {{-- Breadcrumb --}}
    <div class="ac-bc">
        <a href="{{ route('actions.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="ac-bc-s">›</span>
        <a href="{{ route('actions.index') }}">Actions</a>
        <span class="ac-bc-s">›</span>
        <span style="color:var(--t2);font-weight:600;">Nouvelle action</span>
        @isset($targetDelegate)
            <span class="ac-bc-s">›</span>
            <span style="color:var(--blue);font-weight:600;">Pour {{ $targetDelegate->prenom }} {{ $targetDelegate->nom }}</span>
        @endisset
    </div>

    {{-- Header --}}
    <div class="ac-header">
        @isset($targetDelegate)
            <h1>Nouvelle action</h1>
            <p>Création au nom de <strong>{{ $targetDelegate->prenom }} {{ $targetDelegate->nom }}</strong></p>
        @else
            <h1>Nouvelle action</h1>
            <p>Créez une action commerciale ou une tâche planifiée</p>
        @endisset
    </div>

    @isset($targetDelegate)
    <div style="display:flex;align-items:center;gap:.6rem;padding:.75rem 1.1rem;background:var(--blue-l);border:1px solid var(--blue-m);border-radius:var(--r3);margin-bottom:1.25rem;">
        <svg width="15" height="15" fill="none" stroke="var(--blue)" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span style="font-size:.82rem;font-weight:600;color:var(--blue-d);">Vous créez cette action au nom de <strong>{{ $targetDelegate->prenom }} {{ $targetDelegate->nom }}</strong>. Elle lui sera attribuée automatiquement.</span>
    </div>
    @endisset

    <div class="ac-card">
        <div class="ac-card-hd">
            <span class="ac-card-pip"></span>
            <span class="ac-card-title">Formulaire d'action</span>
        </div>

        <form method="POST"
              action="{{ isset($targetDelegate) ? route('actions.storeForDelegate', $targetDelegate) : route('actions.store') }}"
              id="actionForm" novalidate>
            @csrf

            {{-- Main fields --}}
            <div class="ac-card-body">
                @include('actions._form')
            </div>

            {{-- Lines section --}}
            <div style="padding:0 1.6rem 1.6rem;">
                <div class="ac-lines-hd">
                    <div class="ac-lines-title">
                        <span class="ac-lines-title-pip"></span>
                        Lignes d'action
                    </div>
                    <button type="button" id="add-line" class="btn-ac btn-ac-sm btn-ac-teal">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Ajouter une ligne
                    </button>
                </div>

                <div id="lines-container">
                    <div class="line-item" data-line-index="0">
                        @include('actions._line_form', ['lineIndex' => 0, 'line' => null, 'products' => $products, 'examens' => $examens])
                    </div>
                </div>
            </div>

            <div class="ac-footer">
                <a href="{{ route('actions.index') }}" class="btn-ac btn-ac-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Annuler
                </a>
                <button type="submit" class="btn-ac btn-ac-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Créer l'action
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    let lineCounter = 1;
    const categories      = @json($categories);
    const requiresProduct = @json($requiresProduct);
    const requiresBss     = @json($requiresBss);
    const requiresRetour  = @json($requiresRetour);
    const requiresExamen  = @json($requiresExamen);

    /* ── API helpers ───────────────────────── */
    function loadContacts(compteId, selectEl) {
        if (!compteId) { selectEl.innerHTML = '<option value="">— Sélectionnez —</option>'; return; }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                selectEl.innerHTML = '<option value="">— Sélectionnez —</option>' +
                    data.map(c => `<option value="${c.id}">${c.prenom} ${c.nom}${c.fonction ? ' · '+c.fonction : ''}</option>`).join('');
            });
    }

    function loadActionTypes(categorie, selectEl) {
        selectEl.innerHTML = '<option value="">Chargement…</option>';
        fetch(`/api/action-types-by-categorie?categorie=${encodeURIComponent(categorie)}`)
            .then(r => r.json())
            .then(data => {
                selectEl.innerHTML = '<option value="">— Sélectionnez —</option>' +
                    data.map(at => `<option value="${at}">${at}</option>`).join('');
                selectEl.dispatchEvent(new Event('change'));
            });
    }

    function loadMoyens(actionType, selectEl) {
        fetch(`/api/moyens-by-action-type?action_type=${encodeURIComponent(actionType)}`)
            .then(r => r.json())
            .then(data => {
                selectEl.innerHTML = '<option value="">— Sélectionnez —</option>' +
                    data.map(m => `<option value="${m}">${m}</option>`).join('');
            });
    }

    /* ── Show/hide conditional fields ──────── */
    function updateConditional(lineEl) {
        const actionType  = lineEl.querySelector('.action-type-select')?.value || '';
        const idx         = lineEl.dataset.lineIndex;
        const productGrp  = lineEl.querySelector('.cond-product');
        const examenGrp   = lineEl.querySelector('.cond-examen');
        const bssGrp      = lineEl.querySelector(`#bss-group-${idx}`);
        const retourGrp   = lineEl.querySelector(`#retour-group-${idx}`);

        [productGrp, examenGrp, bssGrp, retourGrp].forEach(g => { if (g) g.classList.remove('visible'); });

        if (requiresProduct.includes(actionType) && productGrp) productGrp.classList.add('visible');
        else if (requiresBss.includes(actionType) && bssGrp)    bssGrp.classList.add('visible');
        else if (requiresRetour.includes(actionType) && retourGrp) retourGrp.classList.add('visible');
        else if (requiresExamen.includes(actionType) && examenGrp) examenGrp.classList.add('visible');
    }

    /* ── Wire up a single line ──────────────── */
    function attachLineEvents(lineEl) {
        const compteSelect    = document.getElementById('compte_id');
        const categorieSelect = lineEl.querySelector('.categorie-select');
        const actionTypeSelect= lineEl.querySelector('.action-type-select');
        const moyenSelect     = lineEl.querySelector('.moyen-select');
        const contactSelect   = lineEl.querySelector('.contact-multiselect');

        if (compteSelect && contactSelect) {
            compteSelect.addEventListener('change', () => loadContacts(compteSelect.value, contactSelect));
            if (compteSelect.value) loadContacts(compteSelect.value, contactSelect);
        }
        if (categorieSelect && actionTypeSelect) {
            categorieSelect.addEventListener('change', () => loadActionTypes(categorieSelect.value, actionTypeSelect));
            if (categorieSelect.value) loadActionTypes(categorieSelect.value, actionTypeSelect);
        }
        if (actionTypeSelect) {
            actionTypeSelect.addEventListener('change', () => {
                if (moyenSelect) loadMoyens(actionTypeSelect.value, moyenSelect);
                updateConditional(lineEl);
            });
            if (actionTypeSelect.value) {
                if (moyenSelect) loadMoyens(actionTypeSelect.value, moyenSelect);
                updateConditional(lineEl);
            }
        }
    }

    /* ── Clone a new line ───────────────────── */
    document.getElementById('add-line')?.addEventListener('click', () => {
        const container = document.getElementById('lines-container');
        const template  = container.querySelector('.line-item').cloneNode(true);
        const idx       = lineCounter++;

        template.dataset.lineIndex = idx;

        template.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${idx}]`);
            if (el.tagName === 'SELECT') {
                if (el.name.includes('categorie'))   el.selectedIndex = 0;
                if (el.name.includes('action_type')) el.innerHTML = '<option value="">— D\'abord choisir catégorie —</option>';
                if (el.name.includes('moyen'))       el.innerHTML = '<option value="">— Sélectionnez —</option>';
                if (el.name.includes('contact_ids')) el.innerHTML = '';
                if (el.name.includes('product_ids') || el.name.includes('examen_ids'))
                    Array.from(el.options).forEach(o => o.selected = false);
                if (el.name.includes('bss_id') || el.name.includes('retour_id')) el.value = '';
            }
            if (el.tagName === 'INPUT') el.value = '';
        });

        template.querySelectorAll('[id^="bss-group-"]').forEach(el => el.id = `bss-group-${idx}`);
        template.querySelectorAll('[id^="retour-group-"]').forEach(el => el.id = `retour-group-${idx}`);
        template.querySelectorAll('.cond-field').forEach(el => el.classList.remove('visible'));

        // Update line number badge
        const badge = template.querySelector('.line-num-badge');
        if (badge) badge.textContent = idx + 1;
        const numLabel = template.querySelector('.line-num-label');
        if (numLabel) numLabel.textContent = `Ligne ${idx + 1}`;

        container.appendChild(template);
        attachLineEvents(template);
    });

    /* ── Remove line ────────────────────────── */
    document.addEventListener('click', e => {
        if (e.target.closest('.remove-line')) {
            const container = document.getElementById('lines-container');
            if (container.children.length > 1) {
                e.target.closest('.line-item').remove();
            }
        }
    });

    /* ── Init existing lines ────────────────── */
    document.querySelectorAll('.line-item').forEach(attachLineEvents);
})();
</script>
@endpush