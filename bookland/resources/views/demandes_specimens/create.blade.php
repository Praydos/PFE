@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ── Global design system (identical to exam/formation forms) ── */
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
    .btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
    .btn-zn-danger:hover { background: #fddde2; color: var(--rose); }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

    .fp-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

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
    }
    .fp-section-icon.blue  { background: var(--blue-light);   color: var(--blue); }
    .fp-section-icon.amber { background: var(--amber-light);  color: var(--amber); }
    .fp-section-meta { flex: 1; }
    .fp-section-title { font-size: .9rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
    .fp-section-sub   { font-size: .74rem; color: var(--text-muted); margin-top: .1rem; }

    .fp-row { display: grid; gap: 1rem; margin-bottom: 1rem; }
    .fp-row:last-child { margin-bottom: 0; }
    .fp-row-2 { grid-template-columns: repeat(2, 1fr); }
    .fp-row-3 { grid-template-columns: repeat(3, 1fr); }

    .frm-group { display: flex; flex-direction: column; gap: .38rem; }
    .frm-label { font-size: .77rem; font-weight: 600; color: var(--text-secondary); letter-spacing: -.01em; }
    .frm-label .req { color: var(--rose); margin-left: .18rem; }
    .frm-input, .frm-select, .frm-textarea {
        width: 100%; padding: .6rem .88rem;
        border: 1px solid var(--border); border-radius: var(--r-sm);
        background: var(--bg-card); font-family: var(--font);
        font-size: .83rem; color: var(--text-primary);
        box-shadow: var(--shadow-xs);
        transition: border-color var(--t), box-shadow var(--t);
        outline: none;
    }
    .frm-input:focus, .frm-select:focus, .frm-textarea:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent; border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted); pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }
    .frm-textarea { resize: vertical; min-height: 80px; }

    /* Product rows (same flex layout as original, but styled) */
    .product-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }
    .product-row .frm-group {
        flex: 1;
        min-width: 150px;
        margin-bottom: 0;
    }
    .product-row .remove-product {
        flex-shrink: 0;
        margin-bottom: 0;
    }

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

    hr { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }

    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .fp-row-2 { grid-template-columns: 1fr; }
        .product-row { flex-direction: column; align-items: stretch; }
        .product-row .remove-product { align-self: flex-start; }
        .fp-footer { flex-direction: column; align-items: stretch; }
        .fp-footer-spacer { display: none; }
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
        <a href="{{ route('demandes-specimens.index') }}">Demandes spécimens</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Nouvelle demande</span>
    </div>

    <div class="zn-header">
        <h1>Nouvelle demande spéciale</h1>
        <p>Créez une demande de spécimens (établissement ou personnelle)</p>
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

    <div class="fp-card">
        <form method="POST" action="{{ route('demandes-specimens.store') }}">
            @csrf

            {{-- Section 1 : Informations générales --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon blue">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 13h4"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Informations générales</div>
                        <div class="fp-section-sub">Type de demande, compte et adresse</div>
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    {{-- Type --}}
                    <div class="frm-group">
                        <label class="frm-label" for="type">Type <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="type" id="type" class="frm-select" required>
                                <option value="etablissement" {{ old('type', 'etablissement') == 'etablissement' ? 'selected' : '' }}>Établissement (lié à un compte)</option>
                                <option value="personnelle" {{ old('type') == 'personnelle' ? 'selected' : '' }}>Personnelle (sans compte)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Compte --}}
                    <div class="frm-group" id="compte-group">
                        <label class="frm-label" for="compte_id">Compte <span class="req" id="compte-req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="compte_id" id="compte_id" class="frm-select">
                                <option value="">-- Sélectionnez (pour type Établissement) --</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->id }}" {{ old('compte_id', $selectedCompteId ?? '') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    {{-- Contact --}}
                    <div class="frm-group">
                        <label class="frm-label" for="contact_id">Contact</label>
                        <div class="frm-select-wrap">
                            <select name="contact_id" id="contact_id" class="frm-select">
                                <option value="">-- Sélectionnez un contact --</option>
                            </select>
                        </div>
                    </div>

                    {{-- Ville --}}
                    <div class="frm-group">
                        <label class="frm-label" for="ville_id">Ville <span class="req" id="ville-req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="ville_id" id="ville_id" class="frm-select">
                                @foreach($villes as $v)
                                    <option value="{{ $v->id }}" {{ old('ville_id', $defaultVilleId ?? '') == $v->id ? 'selected' : '' }}>{{ $v->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    {{-- Zone --}}
                    <div class="frm-group">
                        <label class="frm-label" for="zone_id">Zone <span class="req" id="zone-req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="zone_id" id="zone_id" class="frm-select">
                                @foreach($zones as $z)
                                    <option value="{{ $z->id }}" {{ old('zone_id', $defaultZoneId ?? '') == $z->id ? 'selected' : '' }}>{{ $z->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div></div> {{-- placeholder for alignment --}}
                </div>

                {{-- Description --}}
                <div class="frm-group">
                    <label class="frm-label" for="description">Description</label>
                    <textarea name="description" id="description" class="frm-textarea" rows="2" placeholder="Raison de la demande, informations complémentaires...">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Section 2 : Produits demandés --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon amber">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Produits demandés</div>
                        <div class="fp-section-sub">Ajoutez les produits spécimens souhaités</div>
                    </div>
                </div>

                <div id="products-container"></div>

                <button type="button" id="add-product" class="btn-zn btn-zn-ghost btn-zn-sm" style="margin-top: .5rem;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter un produit
                </button>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('demandes-specimens.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Créer la demande
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // DOM elements - KEPT EXACTLY AS ORIGINAL
    const compteSelect = document.getElementById('compte_id');
    const contactSelect = document.getElementById('contact_id');
    const villeSelect = document.getElementById('ville_id');
    const zoneSelect = document.getElementById('zone_id');
    const typeSelect = document.getElementById('type');
    const compteReq = document.getElementById('compte-req');
    const villeReq = document.getElementById('ville-req');
    const zoneReq = document.getElementById('zone-req');

    // Helper: load contacts for a compte (using API)
    function loadContacts(compteId) {
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">-- Sélectionnez un contact --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                let options = '<option value="">-- Sélectionnez un contact --</option>';
                data.forEach(c => {
                    options += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`;
                });
                contactSelect.innerHTML = options;
                // Keep previously selected contact if any (from old input)
                @if(old('contact_id'))
                    contactSelect.value = '{{ old('contact_id') }}';
                @endif
            })
            .catch(err => console.error('Error loading contacts:', err));
    }

    // Helper: load compte details (ville_id, zone_id) and auto‑fill, then load contacts
    function loadCompteDetails(compteId) {
        if (!compteId) {
            villeSelect.value = '';
            zoneSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
            contactSelect.innerHTML = '<option value="">-- Sélectionnez un contact --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/details`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.ville_id) {
                    villeSelect.value = data.ville_id;
                    loadZonesForVille(data.ville_id, data.zone_id);
                }
                if (data.zone_id) zoneSelect.value = data.zone_id;
                loadContacts(compteId);
            })
            .catch(err => console.error('Error loading compte details:', err));
    }

    // Helper: load zones for a given ville, optionally select a specific zone
    function loadZonesForVille(villeId, selectedZoneId = null) {
        if (!villeId) {
            zoneSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
            return;
        }
        fetch(`/api/villes/${villeId}/zones`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez une zone --</option>';
                data.forEach(z => {
                    options += `<option value="${z.id}">${z.name}</option>`;
                });
                zoneSelect.innerHTML = options;
                if (selectedZoneId) zoneSelect.value = selectedZoneId;
            })
            .catch(err => console.error('Error loading zones:', err));
    }

    // Toggle required fields based on type
    function toggleTypeFields() {
        const isEtablissement = typeSelect.value === 'etablissement';
        if (isEtablissement) {
            compteSelect.setAttribute('required', 'required');
            compteReq.style.display = 'inline';
            villeSelect.removeAttribute('required');
            villeReq.style.display = 'none';
            zoneSelect.removeAttribute('required');
            zoneReq.style.display = 'none';
        } else {
            compteSelect.removeAttribute('required');
            compteReq.style.display = 'none';
            villeSelect.setAttribute('required', 'required');
            villeReq.style.display = 'inline';
            zoneSelect.setAttribute('required', 'required');
            zoneReq.style.display = 'inline';
        }
    }

    // Event listeners
    typeSelect.addEventListener('change', toggleTypeFields);
    compteSelect.addEventListener('change', () => loadCompteDetails(compteSelect.value));
    villeSelect.addEventListener('change', () => loadZonesForVille(villeSelect.value));

    // Initial load
    toggleTypeFields();
    if (compteSelect.value) loadCompteDetails(compteSelect.value);
    if (villeSelect.value) loadZonesForVille(villeSelect.value);

    // ── Dynamic product rows (EXACTLY as original, only changed class names for styling) ──
    const productsData = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->titre . ' (' . ($p->isbn_13 ?? $p->isbn_10) . ')']));
    let productIndex = 0;
    const container = document.getElementById('products-container');

    function createProductRow(index) {
        const row = document.createElement('div');
        row.className = 'product-row';
        row.innerHTML = `
            <div class="frm-group">
                <label class="frm-label">Produit <span class="req">*</span></label>
                <div class="frm-select-wrap">
                    <select name="products[${index}][product_id]" class="frm-select" required>
                        <option value="">-- Sélectionnez --</option>
                        ${productsData.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                    </select>
                </div>
            </div>
            <div class="frm-group">
                <label class="frm-label">Quantité <span class="req">*</span></label>
                <input type="number" name="products[${index}][quantity]" class="frm-input" required min="1">
            </div>
            <div class="remove-product">
                <button type="button" class="btn-zn btn-zn-danger btn-zn-sm" title="Supprimer">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        `;
        row.querySelector('.remove-product button').addEventListener('click', () => row.remove());
        return row;
    }

    function addProductRow() {
        const newRow = createProductRow(productIndex++);
        container.appendChild(newRow);
    }

    // Add initial product row
    addProductRow();
    document.getElementById('add-product').addEventListener('click', addProductRow);
</script>
@endsection