@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
    /* Paste the entire <style> block from the zones example here */
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

    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1200px; margin: 0 auto; }
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
    .zn-header { margin-bottom: 2rem; }
    .zn-header h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
    .zn-header p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

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
    .btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
    .btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

    /* ── Card ────────────────────────────────────────────── */
    .zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
    .zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em; }
    .title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
    .zn-card-body { padding: 1.5rem 1.6rem; }

    /* ── Form Fields ───────────────────────────────────── */
    .frm-group {
        display: flex; flex-direction: column; gap: .45rem;
        margin-bottom: 1.25rem;
    }
    .frm-label {
        font-size: .8rem; font-weight: 600;
        color: var(--text-secondary); letter-spacing: -.01em;
    }
    .frm-label .req { color: var(--rose); margin-left: .2rem; }
    .frm-input, .frm-select {
        width: 100%; padding: .62rem .9rem;
        border: 1px solid var(--border); border-radius: var(--r-sm);
        background: var(--bg-card); font-family: var(--font);
        font-size: .84rem; color: var(--text-primary);
        box-shadow: var(--shadow-xs);
        transition: border-color var(--t), box-shadow var(--t);
        outline: none; appearance: none; -webkit-appearance: none;
    }
    .frm-select { cursor: pointer; }
    .frm-input:focus, .frm-select:focus {
        border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .form-row {
        display: flex; gap: 1rem; flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }
    .form-row > * { flex: 1; min-width: 180px; }

    /* Radio group styling */
    .radio-group {
        display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;
        margin-top: 0.25rem;
    }
    .radio-option {
        display: flex; align-items: center; gap: 0.5rem;
        cursor: pointer;
    }
    .radio-option input { margin: 0; cursor: pointer; width: 1rem; height: 1rem; accent-color: var(--blue); }
    .radio-option label { font-size: 0.84rem; color: var(--text-secondary); cursor: pointer; }

    /* Product row */
    .product-row {
        display: flex; gap: 1rem; align-items: center;
        margin-bottom: 0.75rem;
    }
    .product-row .frm-select, .product-row .frm-input { flex: 1; }
    .remove-product { flex-shrink: 0; }

    hr { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0; }

    .card-footer {
        padding: 1.1rem 1.6rem;
        border-top: 1px solid var(--border);
        background: var(--bg-base);
        display: flex; align-items: center; justify-content: flex-end;
        gap: .6rem;
    }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .form-row { flex-direction: column; gap: 0; }
        .product-row { flex-wrap: wrap; }
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
        <a href="{{ route('bss.index') }}">BSS</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Nouveau BSS</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <h1>Nouveau BSS</h1>
        <p>Créez un bon de sortie spécimens</p>
    </div>

    {{-- Form card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <div class="zn-card-title">
                <span class="title-pip"></span>
                Formulaire
            </div>
        </div>
        <div class="zn-card-body">
            <form method="POST" action="{{ route('bss.store') }}" id="bssForm">
                @csrf
                <input type="hidden" name="numero" value="{{ $numero }}">

                <div class="form-row">
                    <div class="frm-group">
                        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="compte_id" id="compte_id" class="frm-select" required>
                                <option value="">— Sélectionnez —</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->id }}">{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="contact_id">Contact <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="contact_id" id="contact_id" class="frm-select" required>
                                <option value="">— D'abord choisir un compte —</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="frm-group">
                        <label class="frm-label" for="date_livraison_prevue">Date livraison prévue</label>
                        <input type="date" name="date_livraison_prevue" id="date_livraison_prevue" class="frm-input">
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="moyen_contact">Moyen de contact</label>
                        <div class="frm-select-wrap">
                            <select name="moyen_contact" id="moyen_contact" class="frm-select">
                                <option value="">—</option>
                                <option value="telephone">Téléphone</option>
                                <option value="email">Email</option>
                            </select>
                        </div>
                    </div>
                    <div class="frm-group">
                        <label class="frm-label">Date de création</label>
                        <input type="text" class="frm-input" value="{{ now()->format('d/m/Y') }}" readonly disabled>
                        <small class="frm-hint">Date automatique</small>
                    </div>
                </div>

                {{-- Récupéré par --}}
                <div class="frm-group">
                    <label class="frm-label">Récupéré par <span class="req">*</span></label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="recupere_par_type" value="contact" required> Contact (nom)
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="recupere_par_type" value="transport"> Transport (numéro d'expédition)
                        </label>
                    </div>
                </div>

                <div id="contact_field" class="frm-group" style="display: none;">
                    <label class="frm-label" for="recupere_par_nom_contact">Nom du contact <span class="req">*</span></label>
                    <input type="text" name="recupere_par_nom_contact" id="recupere_par_nom_contact" class="frm-input" placeholder="Nom complet">
                </div>

                <div id="transport_field" class="frm-group" style="display: none;">
                    <label class="frm-label" for="numero_expedition">Numéro d'expédition <span class="req">*</span></label>
                    <input type="text" name="numero_expedition" id="numero_expedition" class="frm-input" placeholder="Ex: EXP-12345">
                </div>

                {{-- Contrôle document --}}
                <div class="frm-group">
                    <label class="frm-label" for="controle_document">Contrôle document physique</label>
                    <div class="frm-select-wrap">
                        <select name="controle_document" id="controle_document" class="frm-select">
                            <option value="">— Non spécifié —</option>
                            <option value="OK">OK</option>
                            <option value="Absence signature">Absence signature</option>
                            <option value="Absence cachet">Absence cachet</option>
                            <option value="Absence Document">Absence Document</option>
                        </select>
                    </div>
                </div>

                <hr>

                <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Produits</h3>
                <div id="products-container">
                    <div class="product-row">
                        <div class="frm-select-wrap" style="flex:2;">
                            <select name="products[0][product_id]" class="frm-select product-select" required>
                                <option value="">— Produit —</option>
                                @foreach($consignations as $cons)
                                    <option value="{{ $cons->product_id }}" data-stock="{{ $cons->quantity }}">{{ $cons->product->titre }} (stock: {{ $cons->quantity }})</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="number" name="products[0][quantity]" class="frm-input" placeholder="Quantité" style="flex:1;" required>
                        <button type="button" class="btn-zn btn-zn-danger remove-product" style="display:none;">X</button>
                    </div>
                </div>
                <button type="button" id="add-product" class="btn-zn btn-zn-sm btn-zn-ghost mt-2">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter un produit
                </button>

                <div class="card-footer" style="margin-top: 2rem; padding: 0; background: none; border: none;">
                    <button type="submit" class="btn-zn btn-zn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Soumettre BSS
                    </button>
                    <a href="{{ route('bss.index') }}" class="btn-zn btn-zn-ghost">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Contact dropdown based on compte
    document.getElementById('compte_id').addEventListener('change', function() {
        let compteId = this.value;
        let contactSelect = document.getElementById('contact_id');
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">— D\'abord choisir un compte —</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let html = '<option value="">— Sélectionnez —</option>';
                data.forEach(c => {
                    html += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`;
                });
                contactSelect.innerHTML = html;
            });
    });

    // Toggle fields for "Récupéré par"
    const radioContact = document.querySelector('input[name="recupere_par_type"][value="contact"]');
    const radioTransport = document.querySelector('input[name="recupere_par_type"][value="transport"]');
    const contactField = document.getElementById('contact_field');
    const transportField = document.getElementById('transport_field');
    const contactInput = document.getElementById('recupere_par_nom_contact');
    const transportInput = document.getElementById('numero_expedition');

    function toggleRecuperePar() {
        if (radioContact.checked) {
            contactField.style.display = 'block';
            transportField.style.display = 'none';
            contactInput.required = true;
            transportInput.required = false;
        } else if (radioTransport.checked) {
            contactField.style.display = 'none';
            transportField.style.display = 'block';
            contactInput.required = false;
            transportInput.required = true;
        } else {
            contactField.style.display = 'none';
            transportField.style.display = 'none';
            contactInput.required = false;
            transportInput.required = false;
        }
    }

    radioContact.addEventListener('change', toggleRecuperePar);
    radioTransport.addEventListener('change', toggleRecuperePar);

    // Dynamic product rows
    let productIndex = 1;
    const container = document.getElementById('products-container');
    const addBtn = document.getElementById('add-product');

    function attachRemoveEvent(row) {
        const removeBtn = row.querySelector('.remove-product');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => row.remove());
        }
    }

    addBtn.addEventListener('click', function() {
        const firstRow = container.children[0];
        const newRow = firstRow.cloneNode(true);
        // Reset values
        newRow.querySelectorAll('select, input').forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/, `[${productIndex}]`);
                el.value = '';
            }
        });
        const removeBtn = newRow.querySelector('.remove-product');
        if (removeBtn) removeBtn.style.display = 'inline-block';
        container.appendChild(newRow);
        attachRemoveEvent(newRow);
        productIndex++;
    });

    // Attach remove to initial row's button (if any)
    document.querySelectorAll('.product-row').forEach(row => attachRemoveEvent(row));
</script>
@endpush