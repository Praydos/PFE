@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ── Global design system (identical to previous forms) ── */
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
    .btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
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
        scroll-margin-top: 1.5rem;
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
    .fp-section-icon.teal  { background: var(--teal-light);   color: var(--teal); }
    .fp-section-icon.amber { background: var(--amber-light);  color: var(--amber); }
    .fp-section-icon.violet{ background: var(--violet-light); color: var(--violet); }
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
    .frm-label .opt { font-size: .7rem; font-weight: 400; color: var(--text-muted); }
    .frm-input, .frm-select {
        width: 100%; padding: .6rem .88rem;
        border: 1px solid var(--border); border-radius: var(--r-sm);
        background: var(--bg-card); font-family: var(--font);
        font-size: .83rem; color: var(--text-primary);
        box-shadow: var(--shadow-xs);
        transition: border-color var(--t), box-shadow var(--t);
        outline: none;
    }
    .frm-input:focus, .frm-select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .frm-input.is-invalid, .frm-select.is-invalid {
        border-color: var(--rose);
        box-shadow: 0 0 0 3px rgba(232,80,106,.12);
    }
    .frm-error { font-size: .72rem; color: var(--rose); font-weight: 500; margin-top: .2rem; }

    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent; border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted); pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }

    /* Radio group */
    .radio-group {
        display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;
        margin-top: .25rem;
    }
    .radio-option {
        display: flex; align-items: center; gap: 0.5rem;
        cursor: pointer;
    }
    .radio-option input { margin: 0; cursor: pointer; width: 1rem; height: 1rem; accent-color: var(--blue); }
    .radio-option label { font-size: .83rem; color: var(--text-secondary); cursor: pointer; }

    /* Produits rows */
    .product-row {
        display: grid;
        grid-template-columns: 2fr 1fr 36px;
        gap: 1rem;
        align-items: center;
        margin-bottom: .75rem;
    }
    .product-row .remove-product {
        width: 36px; height: 36px;
        border-radius: var(--r-sm);
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        color: var(--text-muted);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all var(--t);
        padding: 0;
    }
    .product-row .remove-product:hover { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }

    .frm-hint { font-size: .72rem; color: var(--text-muted); margin-top: .2rem; display: flex; align-items: center; gap: .3rem; }

    hr { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }

    .fp-footer {
        padding: 1.25rem 2rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
        border-top: 1px solid var(--border);
        display: flex; align-items: center; gap: .75rem;
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

    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .fp-row-2, .fp-row-3 { grid-template-columns: 1fr; }
        .product-row { grid-template-columns: 1fr 1fr 36px; grid-template-rows: auto auto; }
        .product-row > :first-child { grid-column: 1 / -1; }
        .fp-footer { flex-wrap: wrap; }
        .fp-footer-spacer { display: none; }
        .btn-zn { width: 100%; justify-content: center; }
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
        <span class="zn-bc-cur">{{ isset($bss) ? 'Modifier BSS' : 'Nouveau BSS' }}</span>
    </div>

    <div class="zn-header">
        <h1>{{ isset($bss) ? 'Modifier BSS' : 'Nouveau BSS' }}</h1>
        <p>{{ isset($bss) ? 'Mettez à jour le bon de sortie spécimens.' : 'Créez un bon de sortie spécimens.' }}</p>
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
        <form method="POST" action="{{ isset($bss) ? route('bss.update', $bss) : route('bss.store') }}" id="bssForm">
            @csrf
            @if(isset($bss)) @method('PUT') @endif

            <input type="hidden" name="numero" value="{{ $numero ?? ($bss->numero ?? '') }}">

            @php
                $isEdit = isset($bss);
                $defaultCompteId = old('compte_id', $isEdit ? $bss->compte_id : ($selectedCompteId ?? ''));
                $defaultContactId = old('contact_id', $isEdit ? $bss->contact_id : '');
                // $defaultDateLivraison = old('date_livraison_prevue', $isEdit ? ($bss->date_livraison_prevue ? $bss->date_livraison_prevue->format('Y-m-d') : '') : '');
                $defaultMoyenContact = old('moyen_contact', $isEdit ? $bss->moyen_contact : '');
                $defaultRecupereParType = old('recupere_par_type', $isEdit ? $bss->recupere_par_type : '');
                $defaultRecupereParNom = old('recupere_par_nom_contact', $isEdit ? $bss->recupere_par_nom_contact : '');
                $defaultNumeroExpedition = old('numero_expedition', $isEdit ? $bss->numero_expedition : '');
                $defaultControleDocument = old('controle_document', $isEdit ? $bss->controle_document : '');
                $products = old('products', $isEdit ? ($bss->items ?? []) : []);
                $defaultDateLivraison = old(
                    'date_livraison_prevue',
                    $isEdit
                        ? ($bss->date_livraison_prevue
                            ? $bss->date_livraison_prevue->format('Y-m-d')
                            : '')
                        : ($defaultDate ?? now()->toDateString())
                );
            @endphp

            {{-- Section 1 : Identification --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon blue">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 13h4"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Identification</div>
                        <div class="fp-section-sub">Compte, contact et dates</div>
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="compte_id" id="compte_id" class="frm-select {{ $errors->has('compte_id') ? 'is-invalid' : '' }}" required>
                                <option value="">— Sélectionnez un compte —</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                                @endforeach
                            </select>
                        </div>
                        @error('compte_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="contact_id">Contact <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="contact_id" id="contact_id" class="frm-select {{ $errors->has('contact_id') ? 'is-invalid' : '' }}" required>
                                <option value="">— Sélectionnez d'abord un compte —</option>
                            </select>
                        </div>
                        @error('contact_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="fp-row fp-row-3">
                    <div class="frm-group">
                        <label class="frm-label" for="date_livraison_prevue">Date livraison prévue</label>
                        <input type="date" name="date_livraison_prevue" id="date_livraison_prevue"
                               class="frm-input" value="{{ $defaultDateLivraison }}">
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="moyen_contact">Moyen de contact</label>
                        <div class="frm-select-wrap">
                            <select name="moyen_contact" id="moyen_contact" class="frm-select">
                                <option value="">—</option>
                                <option value="telephone" {{ $defaultMoyenContact == 'telephone' ? 'selected' : '' }}>Téléphone</option>
                                <option value="email" {{ $defaultMoyenContact == 'email' ? 'selected' : '' }}>Email</option>
                            </select>
                        </div>
                    </div>
                    <div class="frm-group">
                        <label class="frm-label">Date de création</label>
                        <input type="text" class="frm-input" value="{{ now()->format('d/m/Y') }}" readonly disabled>
                        <span class="frm-hint">Date automatique</span>
                    </div>
                </div>
            </div>

            {{-- Section 2 : Récupération --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon teal">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Récupération</div>
                        <div class="fp-section-sub">Par qui et comment</div>
                    </div>
                </div>

                <div class="frm-group">
                    <label class="frm-label">Récupéré par <span class="req">*</span></label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="recupere_par_type" value="contact" {{ $defaultRecupereParType == 'contact' ? 'checked' : '' }} required> Contact (nom)
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="recupere_par_type" value="transport" {{ $defaultRecupereParType == 'transport' ? 'checked' : '' }}> Transport (numéro d'expédition)
                        </label>
                    </div>
                </div>

                <div id="contact_field" class="frm-group" style="{{ $defaultRecupereParType == 'contact' ? 'display:block' : 'display:none' }}">
                    <label class="frm-label" for="recupere_par_nom_contact">Nom du contact <span class="req">*</span></label>
                    <input type="text" name="recupere_par_nom_contact" id="recupere_par_nom_contact"
                           class="frm-input {{ $errors->has('recupere_par_nom_contact') ? 'is-invalid' : '' }}"
                           value="{{ $defaultRecupereParNom }}" placeholder="Nom complet">
                    @error('recupere_par_nom_contact')<span class="frm-error">{{ $message }}</span>@enderror
                </div>

                <div id="transport_field" class="frm-group" style="{{ $defaultRecupereParType == 'transport' ? 'display:block' : 'display:none' }}">
                    <label class="frm-label" for="numero_expedition">Numéro d'expédition <span class="req">*</span></label>
                    <input type="text" name="numero_expedition" id="numero_expedition"
                           class="frm-input {{ $errors->has('numero_expedition') ? 'is-invalid' : '' }}"
                           value="{{ $defaultNumeroExpedition }}" placeholder="Ex: EXP-12345">
                    @error('numero_expedition')<span class="frm-error">{{ $message }}</span>@enderror
                </div>

                <br>
                <br>

                <div class="frm-group">
                    <label class="frm-label" for="controle_document">Contrôle document physique</label>
                    <div class="frm-select-wrap">
                        <select name="controle_document" id="controle_document" class="frm-select">
                            <option value="">— Non spécifié —</option>
                            <option value="OK" {{ $defaultControleDocument == 'OK' ? 'selected' : '' }}>OK</option>
                            <option value="Absence signature" {{ $defaultControleDocument == 'Absence signature' ? 'selected' : '' }}>Absence signature</option>
                            <option value="Absence cachet" {{ $defaultControleDocument == 'Absence cachet' ? 'selected' : '' }}>Absence cachet</option>
                            <option value="Absence Document" {{ $defaultControleDocument == 'Absence Document' ? 'selected' : '' }}>Absence Document</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section 3 : Produits --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon amber">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Produits</div>
                        <div class="fp-section-sub">Spécimens commandés</div>
                    </div>
                </div>

                <div id="products-container">
                    @if(count($products))
                        @foreach($products as $index => $item)
                        <div class="product-row">
                            <div class="frm-select-wrap">
                                <select name="products[{{ $index }}][product_id]" class="frm-select product-select" required>
                                    <option value="">— Produit —</option>
                                    @foreach($consignations as $cons)
                                        <option value="{{ $cons->product_id }}" data-stock="{{ $cons->quantity }}"
                                            {{ (old("products.$index.product_id", $item['product_id'] ?? $item->product_id ?? '') == $cons->product_id) ? 'selected' : '' }}>
                                            {{ $cons->product->titre }} (stock: {{ $cons->quantity }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="number" name="products[{{ $index }}][quantity]"
                                   class="frm-input" placeholder="Quantité" value="{{ old("products.$index.quantity", $item['quantity'] ?? $item->quantity ?? 1) }}" min="1" required>
                            <button type="button" class="remove-product btn-zn-danger" {{ $loop->first ? 'style="display:none"' : '' }}>X</button>
                        </div>
                        @endforeach
                    @else
                        <div class="product-row">
                            <div class="frm-select-wrap">
                                <select name="products[0][product_id]" class="frm-select product-select" required>
                                    <option value="">— Produit —</option>
                                    @foreach($consignations as $cons)
                                        <option value="{{ $cons->product_id }}" data-stock="{{ $cons->quantity }}">{{ $cons->product->titre }} (stock: {{ $cons->quantity }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="number" name="products[0][quantity]" class="frm-input" placeholder="Quantité" value="1" min="1" required>
                            <button type="button" class="remove-product" style="display:none;">X</button>
                        </div>
                    @endif
                </div>

                <button type="button" id="add-product" class="btn-zn btn-zn-ghost btn-zn-sm" style="margin-top: .75rem;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Ajouter un produit
                </button>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('bss.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ isset($bss) ? 'Enregistrer les modifications' : 'Soumettre BSS' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const compteSelect = document.getElementById('compte_id');
    const contactSelect = document.getElementById('contact_id');
    const defaultContact = '{{ $defaultContactId ?? '' }}';

    function loadContacts(compteId) {
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">— Sélectionnez d\'abord un compte —</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let html = '<option value="">— Sélectionnez un contact —</option>';
                data.forEach(c => {
                    html += `<option value="${c.id}">${c.prenom} ${c.nom}${c.fonction ? ' · ' + c.fonction : ''}</option>`;
                });
                contactSelect.innerHTML = html;
                if (defaultContact) contactSelect.value = defaultContact;
            });
    }

    compteSelect.addEventListener('change', () => loadContacts(compteSelect.value));
    if (compteSelect.value) loadContacts(compteSelect.value);

    // ── Récupéré par toggle ────────────────────
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

    radioContact?.addEventListener('change', toggleRecuperePar);
    radioTransport?.addEventListener('change', toggleRecuperePar);
    toggleRecuperePar();

    // ── Dynamic product rows ───────────────────
    let productIndex = {{ count($products) }};
    const container = document.getElementById('products-container');
    const addBtn = document.getElementById('add-product');

    function attachRemoveEvent(row) {
        const removeBtn = row.querySelector('.remove-product');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => row.remove());
        }
    }

    addBtn.addEventListener('click', () => {
        const firstRow = container.children[0];
        const newRow = firstRow.cloneNode(true);
        // Reset values
        newRow.querySelectorAll('select, input').forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/, `[${productIndex}]`);
                if (el.tagName === 'SELECT') el.value = '';
                else if (el.type === 'number') el.value = 1;
                else el.value = '';
            }
        });
        const removeBtn = newRow.querySelector('.remove-product');
        if (removeBtn) removeBtn.style.display = 'inline-block';
        container.appendChild(newRow);
        attachRemoveEvent(newRow);
        productIndex++;
    });

    document.querySelectorAll('.product-row').forEach(row => attachRemoveEvent(row));
})();
</script>
@endpush