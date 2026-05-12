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
        background: var(--blue-light); color: var(--blue);
    }
    .fp-section-meta { flex: 1; }
    .fp-section-title { font-size: .9rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
    .fp-section-sub   { font-size: .74rem; color: var(--text-muted); margin-top: .1rem; }

    /* ── Form grid (compatible with existing product form) ── */
    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .grid-row {
        display: grid;
        gap: 1rem;
        margin-bottom: 0.25rem;
    }
    .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
    .grid-cols-4-special { grid-template-columns: 1fr 0.6fr 0.8fr 0.8fr; }
    .grid-cols-2-isbn { grid-template-columns: 1fr 1fr; }
    .grid-cols-1 { grid-template-columns: 1fr; }

    @media (max-width: 768px) {
        .grid-cols-2, .grid-cols-3, .grid-cols-4-special, .grid-cols-2-isbn {
            grid-template-columns: 1fr;
        }
    }

    .frm-group {
        display: flex;
        flex-direction: column;
        gap: .38rem;
        margin-bottom: 1.25rem;
    }
    .frm-group:last-of-type {
        margin-bottom: 0;
    }
    .frm-label {
        font-size: .77rem;
        font-weight: 600;
        color: var(--text-secondary);
        letter-spacing: -.01em;
    }
    .frm-label .req { color: var(--rose); margin-left: .18rem; }
    .frm-input-wrap { position: relative; }
    .frm-icon {
        position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); pointer-events: none;
    }
    .frm-input, .frm-select, textarea.frm-input {
        width: 100%; padding: .6rem .88rem;
        border: 1px solid var(--border); border-radius: var(--r-sm);
        background: var(--bg-card); font-family: var(--font);
        font-size: .83rem; color: var(--text-primary);
        box-shadow: var(--shadow-xs);
        transition: border-color var(--t), box-shadow var(--t);
        outline: none;
    }
    .frm-input-wrap .frm-input { padding-left: 2.35rem; }
    .frm-input:focus, .frm-select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .frm-input.is-invalid, .frm-select.is-invalid {
        border-color: var(--rose);
        box-shadow: 0 0 0 3px rgba(232,80,106,.12);
    }
    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent; border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted); pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }
    .frm-error {
        font-size: .72rem; color: var(--rose); font-weight: 500;
        display: flex; align-items: center; gap: .3rem;
        margin-top: .2rem;
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

    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
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
        <a href="{{ route('products.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('products.index') }}">Produits</a>
        <span class="zn-bc-sep">›</span>
        @if(isset($product))
            <a href="{{ route('products.show', $product) }}">{{ $product->titre }}</a>
            <span class="zn-bc-sep">›</span>
            <span class="zn-bc-cur">Modifier</span>
        @else
            <span class="zn-bc-cur">Nouveau produit</span>
        @endif
    </div>

    <div class="zn-header">
        <h1>{{ isset($product) ? 'Modifier le produit' : 'Créer un produit' }}</h1>
        <p>{{ isset($product) ? 'Mettez à jour les informations du produit.' : 'Remplissez les informations ci‑dessous pour ajouter un nouveau produit.' }}</p>
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
        <form method="POST" action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" novalidate>
            @csrf
            @if(isset($product)) @method('PUT') @endif

            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M9 9h6M9 13h4"/>
                        </svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Informations du produit</div>
                        <div class="fp-section-sub">Identité, classification et caractéristiques</div>
                    </div>
                </div>

                <div class="form-grid">
                    {{-- Row 1: Source + Titre --}}
                    <div class="grid-row grid-cols-2">
                        <div class="frm-group">
                            <label class="frm-label">Source <span class="req">*</span></label>
                            <div class="frm-select-wrap">
                                <select name="source" class="frm-select @error('source') is-invalid @enderror" required>
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="bookland" {{ old('source', $product->source ?? '') == 'bookland' ? 'selected' : '' }}>Bookland</option>
                                    <option value="esprit_du_livre" {{ old('source', $product->source ?? '') == 'esprit_du_livre' ? 'selected' : '' }}>Esprit du livre</option>
                                </select>
                            </div>
                            @error('source')<span class="frm-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Titre <span class="req">*</span></label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
                                <input type="text" name="titre" class="frm-input @error('titre') is-invalid @enderror" value="{{ old('titre', $product->titre ?? '') }}" required>
                            </div>
                            @error('titre')<span class="frm-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    {{-- Row 2: Sous-titre + Niveau + Type --}}
                    <div class="grid-row grid-cols-3">
                        <div class="frm-group">
                            <label class="frm-label">Sous-titre</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h10"/></svg></span>
                                <input type="text" name="sous_titre" class="frm-input" value="{{ old('sous_titre', $product->sous_titre ?? '') }}">
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Niveau</label>
                            <div class="frm-select-wrap">
                                <select name="niveau" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    @php $levels = ['CP','CE1','CE2','CM1','CM2','6ème','5ème','4ème','3ème','2ème','1ère']; @endphp
                                    @foreach($levels as $level)
                                        <option value="{{ $level }}" {{ old('niveau', $product->niveau ?? '') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Type <span class="req">*</span></label>
                            <div class="frm-select-wrap">
                                <select name="type" class="frm-select @error('type') is-invalid @enderror" required>
                                    <option value="">-- Sélectionnez --</option>
                                    @php $types = ["Livre de l'élève",'Guide pédagogique',"Cahier d'activités",'Fichier ressources','Manuel numérique']; @endphp
                                    @foreach($types as $typeOption)
                                        <option value="{{ $typeOption }}" {{ old('type', $product->type ?? '') == $typeOption ? 'selected' : '' }}>{{ $typeOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('type')<span class="frm-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    {{-- Row 3: Édition + Auteur + Langue --}}
                    <div class="grid-row grid-cols-3">
                        <div class="frm-group">
                            <label class="frm-label">Édition</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><line x1="8" y1="9" x2="16" y2="9"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="12" y2="17"/></svg></span>
                                <input type="text" name="edition" class="frm-input" value="{{ old('edition', $product->edition ?? '') }}">
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Auteur</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                                <input type="text" name="auteur" class="frm-input" value="{{ old('auteur', $product->auteur ?? '') }}">
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Langue</label>
                            <div class="frm-select-wrap">
                                <select name="langue" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    @php $langues = ['Français','Anglais','Arabe','Espagnol']; @endphp
                                    @foreach($langues as $lang)
                                        <option value="{{ $lang }}" {{ old('langue', $product->langue ?? '') == $lang ? 'selected' : '' }}>{{ $lang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Row 4: Rayon + Sous-rayon + Catégorie --}}
                    <div class="grid-row grid-cols-3">
                        <div class="frm-group">
                            <label class="frm-label">Rayon</label>
                            <div class="frm-select-wrap">
                                <select name="rayon" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    @php $rayons = ['Scolaire','Parascolaire','Universitaire']; @endphp
                                    @foreach($rayons as $rayon)
                                        <option value="{{ $rayon }}" {{ old('rayon', $product->rayon ?? '') == $rayon ? 'selected' : '' }}>{{ $rayon }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Sous-rayon</label>
                            <div class="frm-select-wrap">
                                <select name="sous_rayon" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    @php $srayons = ['Primaire','Collège','Lycée','Supérieur','Maternelle']; @endphp
                                    @foreach($srayons as $srayon)
                                        <option value="{{ $srayon }}" {{ old('sous_rayon', $product->sous_rayon ?? '') == $srayon ? 'selected' : '' }}>{{ $srayon }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Catégorie</label>
                            <div class="frm-select-wrap">
                                <select name="categorie" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    @php $cats = ['Méthode','Lecture','Grammaire','Mathématiques','Sciences']; @endphp
                                    @foreach($cats as $cat)
                                        <option value="{{ $cat }}" {{ old('categorie', $product->categorie ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Row 5: Sous-catégorie + Éditeur + Collection --}}
                    <div class="grid-row grid-cols-3">
                        <div class="frm-group">
                            <label class="frm-label">Sous-catégorie</label>
                            <div class="frm-select-wrap">
                                <select name="sous_categorie" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    @php $scats = ['Français','Anglais','Maths','SVT','Histoire-Géo','Physique-Chimie']; @endphp
                                    @foreach($scats as $scat)
                                        <option value="{{ $scat }}" {{ old('sous_categorie', $product->sous_categorie ?? '') == $scat ? 'selected' : '' }}>{{ $scat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Éditeur</label>
                            <div class="frm-select-wrap">
                                <select name="editeur" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    @php $editeurs = ['Bookland','Hachette','Nathan','Magnard','Bordas','Hatier']; @endphp
                                    @foreach($editeurs as $ed)
                                        <option value="{{ $ed }}" {{ old('editeur', $product->editeur ?? '') == $ed ? 'selected' : '' }}>{{ $ed }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Collection</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="18" rx="2"/><line x1="8" y1="7" x2="16" y2="7"/><line x1="8" y1="11" x2="16" y2="11"/><line x1="8" y1="15" x2="12" y2="15"/></svg></span>
                                <input type="text" name="collection" class="frm-input" value="{{ old('collection', $product->collection ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Row 6: Support + Nbr pages + Prix + Date parution --}}
                    <div class="grid-row grid-cols-4-special">
                        <div class="frm-group">
                            <label class="frm-label">Support</label>
                            <div class="frm-select-wrap">
                                <select name="support" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    @php $supports = ['Papier','Numérique','Papier + Numérique']; @endphp
                                    @foreach($supports as $sup)
                                        <option value="{{ $sup }}" {{ old('support', $product->support ?? '') == $sup ? 'selected' : '' }}>{{ $sup }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Nombre de pages</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                                <input type="number" name="nbr_pages" class="frm-input" value="{{ old('nbr_pages', $product->nbr_pages ?? 0) }}">
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Prix (€)</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="1" x2="12" y2="23"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/></svg></span>
                                <input type="number" step="0.01" name="prix" class="frm-input" value="{{ old('prix', $product->prix ?? '') }}">
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Date de parution</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                                <input type="date" name="date_parution" class="frm-input" value="{{ old('date_parution', optional($product->date_parution ?? null)->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Row 7: ISBN-13 + ISBN-10 --}}
                    <div class="grid-row grid-cols-2-isbn">
                        <div class="frm-group">
                            <label class="frm-label">ISBN-13</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 3h5v5M8 3H3v5M16 21h5v-5M8 21H3v-5"/><line x1="7" y1="12" x2="17" y2="12"/><polyline points="12 7 17 12 12 17"/></svg></span>
                                <input type="text" name="isbn_13" class="frm-input @error('isbn_13') is-invalid @enderror" value="{{ old('isbn_13', $product->isbn_13 ?? '') }}">
                            </div>
                            @error('isbn_13')<span class="frm-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">ISBN-10</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 3h5v5M8 3H3v5M16 21h5v-5M8 21H3v-5"/><line x1="7" y1="12" x2="17" y2="12"/><polyline points="12 7 17 12 12 17"/></svg></span>
                                <input type="text" name="isbn_10" class="frm-input @error('isbn_10') is-invalid @enderror" value="{{ old('isbn_10', $product->isbn_10 ?? '') }}">
                            </div>
                            @error('isbn_10')<span class="frm-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    {{-- Row 8: Référence interne --}}
                    <div class="grid-row grid-cols-1">
                        <div class="frm-group">
                            <label class="frm-label">Référence interne</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg></span>
                                <input type="text" name="reference_interne" class="frm-input" value="{{ old('reference_interne', $product->reference_interne ?? '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Row 9: Description --}}
                    <div class="grid-row grid-cols-1">
                        <div class="frm-group">
                            <label class="frm-label">Description</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                                <textarea name="description" class="frm-input" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('products.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ isset($product) ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection