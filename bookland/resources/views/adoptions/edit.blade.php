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

    /* Product row – custom flex layout (more than 3 columns) */
    .product-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }
    .product-row .frm-group {
        flex: 1;
        min-width: 150px;
        margin-bottom: 0;
    }

    /* Readonly fields styling */
    .frm-input[readonly] {
        background: var(--bg-subtle);
        cursor: not-allowed;
    }

    hr {
        border: none;
        border-top: 1px solid var(--border);
        margin: 1rem 0;
    }

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
        .product-row { flex-direction: column; align-items: stretch; }
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
        <a href="{{ route('adoptions.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('adoptions.index') }}">Adoptions</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Modifier l'adoption</span>
    </div>

    <div class="zn-header">
        <h1>Modifier l'adoption</h1>
        <p>Mettez à jour les informations de l'adoption manuelle</p>
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
        <form method="POST" action="{{ route('adoptions.update', $adoption) }}" id="adoption-form">
            @csrf
            @method('PUT')

            {{-- Section 1 : Informations de l'adoption --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon blue">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 13h4"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Informations de l'adoption</div>
                        <div class="fp-section-sub">Compte, contact, année scolaire, date et méthode</div>
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    {{-- Compte --}}
                    <div class="frm-group">
                        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="compte_id" id="compte_id" class="frm-select {{ $errors->has('compte_id') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Sélectionnez --</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->id }}" {{ old('compte_id', $adoption->compte_id) == $c->id ? 'selected' : '' }}>{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                                @endforeach
                            </select>
                        </div>
                        @error('compte_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- Année scolaire --}}
                    <div class="frm-group">
                        <label class="frm-label" for="annee_scolaire_id">Année scolaire <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="annee_scolaire_id" id="annee_scolaire_id" class="frm-select {{ $errors->has('annee_scolaire_id') ? 'is-invalid' : '' }}" required>
                                @foreach($years as $y)
                                    <option value="{{ $y->id }}" {{ old('annee_scolaire_id', $adoption->annee_scolaire_id) == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('annee_scolaire_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    {{-- Date adoption --}}
                    <div class="frm-group">
                        <label class="frm-label" for="date_adoption">Date adoption <span class="req">*</span></label>
                        <input type="date" name="date_adoption" id="date_adoption"
                               class="frm-input {{ $errors->has('date_adoption') ? 'is-invalid' : '' }}"
                               value="{{ old('date_adoption', $adoption->date_adoption->format('Y-m-d')) }}" required>
                        @error('date_adoption')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- Contact --}}
                    <div class="frm-group">
                        <label class="frm-label" for="contact_id">Contact <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="contact_id" id="contact_id" class="frm-select {{ $errors->has('contact_id') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Sélectionnez --</option>
                                @foreach($contacts as $co)
                                    <option value="{{ $co->id }}" {{ old('contact_id', $adoption->contact_id) == $co->id ? 'selected' : '' }}>
                                        {{ $co->prenom }} {{ $co->nom }} ({{ $co->fonction ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('contact_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="fp-row">
                    {{-- Méthode --}}
                    <div class="frm-group">
                        <label class="frm-label" for="methode">Méthode <span class="req">*</span></label>
                        <input type="text" name="methode" id="methode"
                               class="frm-input {{ $errors->has('methode') ? 'is-invalid' : '' }}"
                               value="{{ old('methode', $adoption->methode) }}" required>
                        @error('methode')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 2 : Produit adopté --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon amber">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Produit adopté</div>
                        <div class="fp-section-sub">Sélectionnez le produit et renseignez le niveau/cycle</div>
                    </div>
                </div>

                <div class="product-row">
                    {{-- Product --}}
                    <div class="frm-group">
                        <label class="frm-label" for="product_id">Produit <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="product_id" id="product_id" class="frm-select {{ $errors->has('product_id') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Sélectionnez --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" 
                                            data-isbn="{{ $p->isbn_13 ?? $p->isbn_10 ?? '' }}" 
                                            data-sous-categorie="{{ $p->sous_categorie ?? '' }}"
                                            {{ old('product_id', $adoption->product_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->titre }} ({{ $p->isbn_13 ?? $p->isbn_10 }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('product_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- Type adoption --}}
                    <div class="frm-group">
                        <label class="frm-label" for="type_adoption">Type adoption <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="type_adoption" id="type_adoption" class="frm-select {{ $errors->has('type_adoption') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="BOOKLAND" {{ old('type_adoption', $adoption->type_adoption) == 'BOOKLAND' ? 'selected' : '' }}>BOOKLAND</option>
                                <option value="ESPRIT_DU_LIVRE" {{ old('type_adoption', $adoption->type_adoption) == 'ESPRIT_DU_LIVRE' ? 'selected' : '' }}>ESPRIT DU LIVRE</option>
                                <option value="CONCURRENT" {{ old('type_adoption', $adoption->type_adoption) == 'CONCURRENT' ? 'selected' : '' }}>CONCURRENT</option>
                            </select>
                        </div>
                        @error('type_adoption')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- ISBN --}}
                    <div class="frm-group">
                        <label class="frm-label" for="isbn">ISBN</label>
                        <input type="text" name="isbn" id="isbn" class="frm-input" value="{{ old('isbn', $adoption->isbn) }}" readonly>
                    </div>

                    {{-- Sous-catégorie --}}
                    <div class="frm-group">
                        <label class="frm-label" for="sous_categorie">Sous-catégorie</label>
                        <input type="text" name="sous_categorie" id="sous_categorie" class="frm-input" value="{{ old('sous_categorie', $adoption->sous_categorie) }}" readonly>
                    </div>

                    {{-- Niveau --}}
                    <div class="frm-group">
                        <label class="frm-label" for="niveau">Niveau <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="niveau" id="niveau" class="frm-select niveau-select {{ $errors->has('niveau') ? 'is-invalid' : '' }}" required>
                                <option value="{{ $adoption->niveau }}" selected>{{ $adoption->niveau }}</option>
                            </select>
                        </div>
                        @error('niveau')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- Cycle --}}
                    <div class="frm-group">
                        <label class="frm-label" for="cycle">Cycle <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="cycle" id="cycle" class="frm-select cycle-select {{ $errors->has('cycle') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Cycle --</option>
                                <option value="primaire" {{ old('cycle', $adoption->cycle) == 'primaire' ? 'selected' : '' }}>Primaire</option>
                                <option value="college" {{ old('cycle', $adoption->cycle) == 'college' ? 'selected' : '' }}>Collège</option>
                                <option value="Lycée" {{ old('cycle', $adoption->cycle) == 'Lycée' ? 'selected' : '' }}>Lycée</option>
                                <option value="Learners" {{ old('cycle', $adoption->cycle) == 'Learners' ? 'selected' : '' }}>Learners</option>
                                <option value="Pre-teens" {{ old('cycle', $adoption->cycle) == 'Pre-teens' ? 'selected' : '' }}>Pre-teens</option>
                                <option value="Teens" {{ old('cycle', $adoption->cycle) == 'Teens' ? 'selected' : '' }}>Teens</option>
                                <option value="Adults" {{ old('cycle', $adoption->cycle) == 'Adults' ? 'selected' : '' }}>Adults</option>
                            </select>
                        </div>
                        @error('cycle')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- Quantité --}}
                    <div class="frm-group">
                        <label class="frm-label" for="quantity">Quantité <span class="req">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="frm-input quantity-input {{ $errors->has('quantity') ? 'is-invalid' : '' }}" value="{{ old('quantity', $adoption->quantity) }}" readonly required>
                        @error('quantity')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('adoptions.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const compteSelect = document.getElementById('compte_id');
        const yearSelect = document.getElementById('annee_scolaire_id');
        const contactSelect = document.getElementById('contact_id');
        const productSelect = document.getElementById('product_id');
        const niveauSelect = document.getElementById('niveau');
        const cycleSelect = document.getElementById('cycle');
        const quantityInput = document.getElementById('quantity');
        
        let currentNiveaux = [];

        // ── Load contacts based on compte ──
        function loadContacts(callback) {
            const compteId = compteSelect.value;
            if (!compteId) {
                contactSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
                if (callback) callback();
                return;
            }
            fetch(`/api/comptes/${compteId}/contacts`)
                .then(r => r.json())
                .then(data => {
                    let html = '<option value="">-- Sélectionnez un contact --</option>';
                    data.forEach(c => {
                        html += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`;
                    });
                    contactSelect.innerHTML = html;
                    const defaultContactId = '{{ old('contact_id', $adoption->contact_id) }}';
                    if (defaultContactId) contactSelect.value = defaultContactId;
                    if (callback) callback();
                })
                .catch(err => {
                    console.error('Erreur chargement contacts:', err);
                    if (callback) callback();
                });
        }

        // ── Load niveaux for the compte ──
        function loadNiveaux(callback) {
            const compteId = compteSelect.value;
            if (!compteId) {
                niveauSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
                if (callback) callback();
                return;
            }
            fetch(`/api/comptes/${compteId}/niveaux`)
                .then(r => r.json())
                .then(data => {
                    currentNiveaux = data;
                    let html = '<option value="">-- Sélectionnez un niveau --</option>';
                    data.forEach(n => {
                        html += `<option value="${n}">${n}</option>`;
                    });
                    niveauSelect.innerHTML = html;
                    const defaultNiveau = '{{ old('niveau', $adoption->niveau) }}';
                    if (defaultNiveau) niveauSelect.value = defaultNiveau;
                    if (callback) callback();
                })
                .catch(err => {
                    console.error('Erreur chargement niveaux:', err);
                    if (callback) callback();
                });
        }

        // ── Fetch quantity ──
        function fetchQuantity() {
            const compteId = compteSelect.value;
            const yearId = yearSelect.value;
            const niveau = niveauSelect.value;
            const cycle = cycleSelect.value;

            if (!compteId || !yearId || !niveau || !cycle) {
                return;
            }

            fetch(`/api/comptes/${compteId}/effectif?annee_scolaire_id=${yearId}&niveau=${encodeURIComponent(niveau)}&cycle=${encodeURIComponent(cycle)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.effectif_valide !== null && data.effectif_valide > 0) {
                        quantityInput.value = data.effectif_valide;
                    } else {
                        quantityInput.value = '';
                    }
                })
                .catch(err => console.error('Erreur chargement effectif:', err));
        }

        // Product select change: fill ISBN and sous-catégorie
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const isbn = selectedOption.dataset.isbn || '';
            const sousCategorie = selectedOption.dataset.sousCategorie || '';
            document.getElementById('isbn').value = isbn;
            document.getElementById('sous_categorie').value = sousCategorie;
        });

        // Event listeners
        compteSelect.addEventListener('change', function() {
            loadContacts();
            loadNiveaux();
        });
        yearSelect.addEventListener('change', fetchQuantity);
        niveauSelect.addEventListener('change', fetchQuantity);
        cycleSelect.addEventListener('change', fetchQuantity);

        // Initial load if compte already selected
        if (compteSelect.value) {
            // Load contacts and levels, keeping initial selections intact
            loadContacts();
            loadNiveaux();
        }
    });
</script>
@endsection