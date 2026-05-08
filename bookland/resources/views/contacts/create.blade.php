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

    .fp-row { display: grid; gap: 1rem; margin-bottom: 1rem; }
    .fp-row:last-child { margin-bottom: 0; }
    .fp-row-2 { grid-template-columns: repeat(2, 1fr); }

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
    .frm-error { font-size: .72rem; color: var(--rose); font-weight: 500; margin-top: .2rem; display: flex; align-items: center; gap: .3rem; }

    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent; border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted); pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }

    /* Input with left icon */
    .frm-input-wrap { position: relative; }
    .frm-input-wrap .frm-icon {
        position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); pointer-events: none;
    }
    .frm-input-wrap .frm-input { padding-left: 2.35rem; }

    /* Checkbox styling */
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: .5rem;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .checkbox-item input[type="checkbox"] {
        width: 1rem;
        height: 1rem;
        margin: 0;
        accent-color: var(--blue);
        cursor: pointer;
    }
    .checkbox-item label {
        font-size: .83rem;
        color: var(--text-secondary);
        cursor: pointer;
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
        .fp-row-2 { grid-template-columns: 1fr; }
        .fp-footer { flex-direction: column; align-items: stretch; }
        .fp-footer-spacer { display: none; }
        .btn-zn { width: 100%; justify-content: center; }
        .checkbox-group { flex-direction: column; gap: .5rem; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('contacts.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('contacts.index') }}">Contacts</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Nouveau contact</span>
    </div>

    <div class="zn-header">
        <h1>Créer un contact</h1>
        <p>Remplissez les informations ci‑dessous pour ajouter un nouveau contact.</p>
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

    @php
        $contact = $contact ?? new \App\Models\Contact();
    @endphp

    <div class="fp-card">
        <form method="POST" action="{{ route('contacts.store') }}">
            @csrf

            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Informations du contact</div>
                        <div class="fp-section-sub">Identité, coordonnées et classification</div>
                    </div>
                </div>

                {{-- Ligne 1 : Nom et Prénom --}}
                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="nom">Nom <span class="req">*</span></label>
                        <div class="frm-input-wrap">
                            <span class="frm-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input type="text" id="nom" name="nom"
                                   class="frm-input @error('nom') is-invalid @enderror"
                                   value="{{ old('nom', $contact->nom) }}"
                                   placeholder="Ex : Dupont" required autocomplete="off">
                        </div>
                        @error('nom')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="prenom">Prénom</label>
                        <div class="frm-input-wrap">
                            <span class="frm-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input type="text" id="prenom" name="prenom"
                                   class="frm-input @error('prenom') is-invalid @enderror"
                                   value="{{ old('prenom', $contact->prenom) }}"
                                   placeholder="Ex : Jean" autocomplete="off">
                        </div>
                        @error('prenom')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Ligne 2 : Email et Téléphone --}}
                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="email">Email</label>
                        <div class="frm-input-wrap">
                            <span class="frm-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </span>
                            <input type="email" id="email" name="email"
                                   class="frm-input @error('email') is-invalid @enderror"
                                   value="{{ old('email', $contact->email) }}"
                                   placeholder="contact@exemple.com" autocomplete="off">
                        </div>
                        @error('email')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="telephone">Téléphone</label>
                        <div class="frm-input-wrap">
                            <span class="frm-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </span>
                            <input type="text" id="telephone" name="telephone"
                                   class="frm-input @error('telephone') is-invalid @enderror"
                                   value="{{ old('telephone', $contact->telephone) }}"
                                   placeholder="05 37 68 12 34" autocomplete="off">
                        </div>
                        @error('telephone')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Ligne 3 : Ville et Civilité --}}
                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="ville_id">Ville <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select id="ville_id" name="ville_id" class="frm-select @error('ville_id') is-invalid @enderror" required>
                                <option value="">— Sélectionnez une ville —</option>
                                @foreach($villes as $ville)
                                    <option value="{{ $ville->id }}" {{ old('ville_id', $contact->ville_id) == $ville->id ? 'selected' : '' }}>
                                        {{ $ville->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('ville_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="civilite">Civilité</label>
                        <div class="frm-select-wrap">
                            <select id="civilite" name="civilite" class="frm-select @error('civilite') is-invalid @enderror">
                                <option value="">— Sélectionnez —</option>
                                @foreach(['M.', 'Mme', 'Mlle'] as $civ)
                                    <option value="{{ $civ }}" {{ old('civilite', $contact->civilite) == $civ ? 'selected' : '' }}>
                                        {{ $civ }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('civilite')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Ligne 4 : Fonction et Catégories --}}
                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="fonction">Fonction</label>
                        <div class="frm-select-wrap">
                            <select id="fonction" name="fonction" class="frm-select @error('fonction') is-invalid @enderror">
                                <option value="">— Sélectionnez —</option>
                                @foreach(['Directeur', 'Responsable', 'Enseignant', 'Autre'] as $func)
                                    <option value="{{ $func }}" {{ old('fonction', $contact->fonction) == $func ? 'selected' : '' }}>
                                        {{ $func }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('fonction')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="frm-group">
                        <label class="frm-label">Catégories</label>
                        <div class="checkbox-group">
                            @foreach($categoriesOptions as $cat)
                                <div class="checkbox-item">
                                    <input type="checkbox" name="categories[]" value="{{ $cat }}" id="cat_{{ $loop->index }}"
                                           {{ in_array($cat, old('categories', $contact->categories ?? [])) ? 'checked' : '' }}>
                                    <label for="cat_{{ $loop->index }}">{{ $cat }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('categories')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Ligne 5 : Cycles (full width) --}}
                <div class="frm-group">
                    <label class="frm-label">Cycles</label>
                    <div class="checkbox-group">
                        @foreach($cyclesOptions as $cycle)
                            <div class="checkbox-item">
                                <input type="checkbox" name="cycles[]" value="{{ $cycle }}" id="cycle_{{ $loop->index }}"
                                       {{ in_array($cycle, old('cycles', $contact->cycles ?? [])) ? 'checked' : '' }}>
                                <label for="cycle_{{ $loop->index }}">{{ $cycle }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('cycles')<span class="frm-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('contacts.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection