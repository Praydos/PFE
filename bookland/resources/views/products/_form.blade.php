@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ========== FULL CSS FROM THE WORKING COMPTE FORM ========== */
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
        --text-primary:   #1a1f36;
        --text-secondary: #525f7f;
        --text-muted:     #9ba8c5;
        --text-hint:      #bcc5dc;
        --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
        --shadow-xs: 0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
        --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
        --shadow-blue: 0 4px 14px rgba(91,141,238,.35);
        --font: 'DM Sans', sans-serif;
        --font-mono: 'DM Mono', monospace;
        --ease: cubic-bezier(.4,0,.2,1);
        --t: .18s var(--ease);
    }

    body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

    /* Page */
    .form-page {
        padding: 2rem 2.5rem 3rem;
        max-width: 1100px;  /* wider to accommodate multi-column layout */
        animation: pageIn .4s var(--ease) both;
    }
    @keyframes pageIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Breadcrumb */
    .fp-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .fp-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .fp-bc a:hover { color: var(--blue); }
    .fp-bc-sep { color: var(--text-hint); }
    .fp-bc-cur { color: var(--text-secondary); }

    /* Header */
    .fp-header { margin-bottom: 2rem; }
    .fp-header h1 { font-size: 1.55rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.2; }
    .fp-header p  { font-size: .83rem; color: var(--text-muted); margin-top: .35rem; }

    /* Card */
    .fp-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .fp-card-header {
        padding: 1.1rem 1.6rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: .55rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
    }
    .fp-card-pip {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--amber); box-shadow: 0 0 0 3px rgba(232,160,32,.2);
    }
    .fp-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }
    .fp-card-body { padding: 1.75rem 1.6rem; }

    /* Form Group */
    .frm-group {
        display: flex; flex-direction: column; gap: .45rem;
        margin-bottom: 1.25rem;
    }
    .frm-group:last-of-type { margin-bottom: 0; }
    .frm-label {
        font-size: .8rem; font-weight: 600;
        color: var(--text-secondary); letter-spacing: -.01em;
    }
    .frm-label .req { color: var(--rose); margin-left: .2rem; }

    /* Inputs & Selects */
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
    .frm-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
    .frm-input.is-invalid,
    .frm-select.is-invalid { border-color: var(--rose); box-shadow: 0 0 0 3px rgba(232,80,106,.12); }

    .frm-error {
        font-size: .76rem; color: var(--rose); font-weight: 500;
        display: flex; align-items: center; gap: .3rem;
    }

    /* Select wrapper with custom arrow */
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

    /* Input with left icon */
    .frm-input-wrap { position: relative; }
    .frm-input-wrap .frm-icon {
        position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); pointer-events: none;
    }
    .frm-input-wrap .frm-input { padding-left: 2.35rem; }

    /* Multi-column grid (replaces Bootstrap row/col) */
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

    @media (max-width: 768px) {
        .grid-cols-2, .grid-cols-3, .grid-cols-4-special, .grid-cols-2-isbn {
            grid-template-columns: 1fr;
        }
        .form-page { padding: 1.25rem 1rem 2rem; }
    }
    .grid-row .frm-group {
        margin-bottom: 0;
    }

    /* Footer */
    .fp-footer {
        padding: 1.1rem 1.6rem;
        border-top: 1px solid var(--border);
        background: var(--bg-base);
        display: flex; align-items: center; justify-content: flex-end;
        gap: .6rem;
    }
    .btn-fp {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .58rem 1.2rem; border-radius: var(--r-sm);
        font-family: var(--font); font-size: .83rem; font-weight: 600;
        cursor: pointer; border: 1px solid transparent;
        transition: all var(--t); text-decoration: none;
        white-space: nowrap; letter-spacing: -.01em; line-height: 1;
    }
    .btn-fp svg { flex-shrink: 0; }
    .btn-fp-primary {
        background: var(--blue); color: #fff;
        border-color: var(--blue); box-shadow: var(--shadow-blue);
    }
    .btn-fp-primary:hover {
        background: var(--blue-dark); color: #fff; text-decoration: none;
        transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4);
    }
    .btn-fp-ghost {
        background: var(--bg-card); color: var(--text-secondary);
        border-color: var(--border); box-shadow: var(--shadow-xs);
    }
    .btn-fp-ghost:hover {
        background: var(--bg-hover); color: var(--text-primary);
        border-color: var(--border-md); text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="form-page">

    {{-- Breadcrumb --}}
    <div class="fp-bc">
        <a href="{{ route('products.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="fp-bc-sep">›</span>
        <a href="{{ route('products.index') }}">Produits</a>
        <span class="fp-bc-sep">›</span>
        <span class="fp-bc-cur">{{ isset($product) ? 'Modifier' : 'Nouveau produit' }}</span>
    </div>

    {{-- Page header --}}
    <div class="fp-header">
        <h1>{{ isset($product) ? 'Modifier le produit' : 'Créer un produit' }}</h1>
        <p>{{ isset($product) ? 'Mettez à jour les informations du produit.' : 'Remplissez les informations ci‑dessous pour ajouter un nouveau produit.' }}</p>
    </div>

    {{-- Form card --}}
    <div class="fp-card">
        <div class="fp-card-header">
            <span class="fp-card-pip"></span>
            <span class="fp-card-title">Informations du produit</span>
        </div>

        <form method="POST" action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" novalidate>
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="fp-card-body">
                <div class="form-grid">
                    {{-- Row 1: Source + Titre --}}
                    <div class="grid-row grid-cols-2">
                        {{-- Source --}}
                        <div class="frm-group">
                            <label class="frm-label">Source <span class="req">*</span></label>
                            <div class="frm-select-wrap">
                                <select name="source" class="frm-select @error('source') is-invalid @enderror" required>
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="bookland" {{ old('source', $product->source ?? '') == 'bookland' ? 'selected' : '' }}>Bookland</option>
                                    <option value="esprit_du_livre" {{ old('source', $product->source ?? '') == 'esprit_du_livre' ? 'selected' : '' }}>Esprit du livre</option>
                                </select>
                            </div>
                            @error('source')
                                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Titre --}}
                        <div class="frm-group">
                            <label class="frm-label">Titre <span class="req">*</span></label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
                                <input type="text" name="titre" class="frm-input @error('titre') is-invalid @enderror" value="{{ old('titre', $product->titre ?? '') }}" required>
                            </div>
                            @error('titre')
                                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
                            @enderror
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
                                    <option value="CP" {{ old('niveau', $product->niveau ?? '') == 'CP' ? 'selected' : '' }}>CP</option>
                                    <option value="CE1" {{ old('niveau', $product->niveau ?? '') == 'CE1' ? 'selected' : '' }}>CE1</option>
                                    <option value="CE2" {{ old('niveau', $product->niveau ?? '') == 'CE2' ? 'selected' : '' }}>CE2</option>
                                    <option value="CM1" {{ old('niveau', $product->niveau ?? '') == 'CM1' ? 'selected' : '' }}>CM1</option>
                                    <option value="CM2" {{ old('niveau', $product->niveau ?? '') == 'CM2' ? 'selected' : '' }}>CM2</option>
                                    <option value="6ème" {{ old('niveau', $product->niveau ?? '') == '6ème' ? 'selected' : '' }}>6ème</option>
                                    <option value="5ème" {{ old('niveau', $product->niveau ?? '') == '5ème' ? 'selected' : '' }}>5ème</option>
                                    <option value="4ème" {{ old('niveau', $product->niveau ?? '') == '4ème' ? 'selected' : '' }}>4ème</option>
                                    <option value="3ème" {{ old('niveau', $product->niveau ?? '') == '3ème' ? 'selected' : '' }}>3ème</option>
                                    <option value="Seconde" {{ old('niveau', $product->niveau ?? '') == 'Seconde' ? 'selected' : '' }}>Seconde</option>
                                    <option value="Première" {{ old('niveau', $product->niveau ?? '') == 'Première' ? 'selected' : '' }}>Première</option>
                                    <option value="Terminale" {{ old('niveau', $product->niveau ?? '') == 'Terminale' ? 'selected' : '' }}>Terminale</option>
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Type <span class="req">*</span></label>
                            <div class="frm-select-wrap">
                                <select name="type" class="frm-select @error('type') is-invalid @enderror" required>
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="Livre de l'élève" {{ old('type', $product->type ?? '') == "Livre de l'élève" ? 'selected' : '' }}>Livre de l'élève</option>
                                    <option value="Guide pédagogique" {{ old('type', $product->type ?? '') == 'Guide pédagogique' ? 'selected' : '' }}>Guide pédagogique</option>
                                    <option value="Cahier d'activités" {{ old('type', $product->type ?? '') == "Cahier d'activités" ? 'selected' : '' }}>Cahier d'activités</option>
                                    <option value="Fichier ressources" {{ old('type', $product->type ?? '') == 'Fichier ressources' ? 'selected' : '' }}>Fichier ressources</option>
                                    <option value="Manuel numérique" {{ old('type', $product->type ?? '') == 'Manuel numérique' ? 'selected' : '' }}>Manuel numérique</option>
                                </select>
                            </div>
                            @error('type')
                                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
                            @enderror
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
                                    <option value="Français" {{ old('langue', $product->langue ?? '') == 'Français' ? 'selected' : '' }}>Français</option>
                                    <option value="Anglais" {{ old('langue', $product->langue ?? '') == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                                    <option value="Arabe" {{ old('langue', $product->langue ?? '') == 'Arabe' ? 'selected' : '' }}>Arabe</option>
                                    <option value="Espagnol" {{ old('langue', $product->langue ?? '') == 'Espagnol' ? 'selected' : '' }}>Espagnol</option>
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
                                    <option value="Scolaire" {{ old('rayon', $product->rayon ?? '') == 'Scolaire' ? 'selected' : '' }}>Scolaire</option>
                                    <option value="Parascolaire" {{ old('rayon', $product->rayon ?? '') == 'Parascolaire' ? 'selected' : '' }}>Parascolaire</option>
                                    <option value="Universitaire" {{ old('rayon', $product->rayon ?? '') == 'Universitaire' ? 'selected' : '' }}>Universitaire</option>
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Sous-rayon</label>
                            <div class="frm-select-wrap">
                                <select name="sous_rayon" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="Primaire" {{ old('sous_rayon', $product->sous_rayon ?? '') == 'Primaire' ? 'selected' : '' }}>Primaire</option>
                                    <option value="Collège" {{ old('sous_rayon', $product->sous_rayon ?? '') == 'Collège' ? 'selected' : '' }}>Collège</option>
                                    <option value="Lycée" {{ old('sous_rayon', $product->sous_rayon ?? '') == 'Lycée' ? 'selected' : '' }}>Lycée</option>
                                    <option value="Supérieur" {{ old('sous_rayon', $product->sous_rayon ?? '') == 'Supérieur' ? 'selected' : '' }}>Supérieur</option>
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Catégorie</label>
                            <div class="frm-select-wrap">
                                <select name="categorie" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="Méthode" {{ old('categorie', $product->categorie ?? '') == 'Méthode' ? 'selected' : '' }}>Méthode</option>
                                    <option value="Lecture" {{ old('categorie', $product->categorie ?? '') == 'Lecture' ? 'selected' : '' }}>Lecture</option>
                                    <option value="Grammaire" {{ old('categorie', $product->categorie ?? '') == 'Grammaire' ? 'selected' : '' }}>Grammaire</option>
                                    <option value="Mathématiques" {{ old('categorie', $product->categorie ?? '') == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
                                    <option value="Sciences" {{ old('categorie', $product->categorie ?? '') == 'Sciences' ? 'selected' : '' }}>Sciences</option>
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
                                    <option value="Français" {{ old('sous_categorie', $product->sous_categorie ?? '') == 'Français' ? 'selected' : '' }}>Français</option>
                                    <option value="Anglais" {{ old('sous_categorie', $product->sous_categorie ?? '') == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                                    <option value="Maths" {{ old('sous_categorie', $product->sous_categorie ?? '') == 'Maths' ? 'selected' : '' }}>Maths</option>
                                    <option value="SVT" {{ old('sous_categorie', $product->sous_categorie ?? '') == 'SVT' ? 'selected' : '' }}>SVT</option>
                                    <option value="Histoire-Géo" {{ old('sous_categorie', $product->sous_categorie ?? '') == 'Histoire-Géo' ? 'selected' : '' }}>Histoire-Géo</option>
                                </select>
                            </div>
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">Éditeur</label>
                            <div class="frm-select-wrap">
                                <select name="editeur" class="frm-select">
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="Bookland" {{ old('editeur', $product->editeur ?? '') == 'Bookland' ? 'selected' : '' }}>Bookland</option>
                                    <option value="Hachette" {{ old('editeur', $product->editeur ?? '') == 'Hachette' ? 'selected' : '' }}>Hachette</option>
                                    <option value="Nathan" {{ old('editeur', $product->editeur ?? '') == 'Nathan' ? 'selected' : '' }}>Nathan</option>
                                    <option value="Magnard" {{ old('editeur', $product->editeur ?? '') == 'Magnard' ? 'selected' : '' }}>Magnard</option>
                                    <option value="Bordas" {{ old('editeur', $product->editeur ?? '') == 'Bordas' ? 'selected' : '' }}>Bordas</option>
                                    <option value="Hatier" {{ old('editeur', $product->editeur ?? '') == 'Hatier' ? 'selected' : '' }}>Hatier</option>
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
                                    <option value="Papier" {{ old('support', $product->support ?? '') == 'Papier' ? 'selected' : '' }}>Papier</option>
                                    <option value="Numérique" {{ old('support', $product->support ?? '') == 'Numérique' ? 'selected' : '' }}>Numérique</option>
                                    <option value="Papier + Numérique" {{ old('support', $product->support ?? '') == 'Papier + Numérique' ? 'selected' : '' }}>Papier + Numérique</option>
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
                            @error('isbn_13')
                                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="frm-group">
                            <label class="frm-label">ISBN-10</label>
                            <div class="frm-input-wrap">
                                <span class="frm-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 3h5v5M8 3H3v5M16 21h5v-5M8 21H3v-5"/><line x1="7" y1="12" x2="17" y2="12"/><polyline points="12 7 17 12 12 17"/></svg></span>
                                <input type="text" name="isbn_10" class="frm-input @error('isbn_10') is-invalid @enderror" value="{{ old('isbn_10', $product->isbn_10 ?? '') }}">
                            </div>
                            @error('isbn_10')
                                <span class="frm-error"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>
                            @enderror
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
                <a href="{{ route('products.index') }}" class="btn-fp btn-fp-ghost">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-fp btn-fp-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ isset($product) ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection