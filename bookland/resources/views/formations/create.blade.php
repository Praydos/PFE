@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ── same global CSS as the exam form (no sidebar) ── */
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
        --ease: cubic-bezier(.4,0,.2,1);
        --t: .18s var(--ease);
    }

    body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

    /* ── Page ─────────────────────────────────── */
    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1400px; margin: 0 auto; }
    @keyframes pageIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    /* ── Breadcrumb ───────────────────────────── */
    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    /* ── Page Header ──────────────────────────── */
    .zn-header { margin-bottom: 2rem; }
    .zn-header h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; }
    .zn-header p { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

    /* ── Buttons ──────────────────────────────── */
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
    .btn-zn-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; transform: translateY(-1px); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
    .btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
    .btn-zn-danger:hover { background: #fddde2; color: var(--rose); }
    .btn-zn-sm { padding: .34rem .65rem; font-size: .74rem; }

    /* ── Main form card ───────────────────────── */
    .fp-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    /* ── Form sections ────────────────────────── */
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
    .fp-section-meta { flex: 1; }
    .fp-section-title { font-size: .9rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
    .fp-section-sub   { font-size: .74rem; color: var(--text-muted); margin-top: .1rem; }

    /* ── Form grid rows ───────────────────────── */
    .fp-row { display: grid; gap: 1rem; margin-bottom: 1rem; }
    .fp-row:last-child { margin-bottom: 0; }
    .fp-row-2 { grid-template-columns: repeat(2, 1fr); }
    .fp-row-3 { grid-template-columns: repeat(3, 1fr); }

    /* ── Form fields ──────────────────────────── */
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
    .frm-input.is-invalid, .frm-select.is-invalid {
        border-color: var(--rose);
        box-shadow: 0 0 0 3px rgba(232,80,106,.12);
    }
    .frm-error { font-size: .72rem; color: var(--rose); font-weight: 500; margin-top: .2rem; }

    /* Select arrow */
    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent; border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted); pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }

    /* ── Dynamic date rows (like épreuves) ───── */
    .dates-container {
        margin-top: .25rem;
    }
    .date-row {
        display: grid;
        grid-template-columns: 1fr 36px;
        gap: .75rem;
        align-items: center;
        margin-bottom: .6rem;
    }
    .date-del-btn {
        width: 36px; height: 36px; flex-shrink: 0;
        border-radius: var(--r-sm);
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        color: var(--text-muted);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all var(--t);
        padding: 0;
    }
    .date-del-btn:hover { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }

    /* ── Footer ───────────────────────────────── */
    .fp-footer {
        padding: 1.25rem 2rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
        border-top: 1px solid var(--border);
        display: flex; align-items: center; gap: .75rem;
    }
    .fp-footer-spacer { flex: 1; }
    .fp-req-note { font-size: .74rem; color: var(--text-muted); }
    .fp-req-note span { color: var(--rose); }

    /* ── Validation alert ─────────────────────── */
    .zn-alert {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: 1rem 1.25rem; border-radius: var(--r-lg);
        border: 1px solid; margin-bottom: 1.5rem; font-size: .82rem;
    }
    .zn-alert-danger { background: var(--rose-light); border-color: rgba(232,80,106,.25); color: #b83450; }
    .zn-alert ul { padding-left: 1.2rem; margin-top: .3rem; }
    .zn-alert li { margin-bottom: .15rem; }

    /* ── Responsive ───────────────────────────── */
    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .fp-row-2, .fp-row-3 { grid-template-columns: 1fr; }
        .date-row { grid-template-columns: 1fr 36px; }
        .fp-footer { flex-wrap: wrap; }
        .fp-footer-spacer { display: none; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('formations.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('formations.index') }}">Formations</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ isset($formation) ? 'Modifier la demande' : 'Nouvelle demande' }}</span>
    </div>

    <div class="zn-header">
        <h1>{{ isset($formation) ? 'Modifier la demande de formation' : 'Nouvelle demande de formation' }}</h1>
        <p>{{ isset($formation) ? 'Mettez à jour les informations ci-dessous.' : 'Créez une demande de formation pour un établissement.' }}</p>
    </div>

    {{-- Validation errors --}}
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
        <form method="POST" action="{{ isset($formation) ? route('formations.update', $formation) : (isset($targetDelegate) ? route('formations.storeForDelegate', $targetDelegate) : route('formations.store')) }}" id="formation-form">
            @csrf
            @if(isset($formation)) @method('PUT') @endif

            @php
                $isEdit = isset($formation);
                $defaultCompteId = old('compte_id', $isEdit ? $formation->compte_id : ($selectedCompteId ?? ''));
                $defaultContactId = old('contact_id', $isEdit ? $formation->contact_id : '');
                $defaultVilleId = old('ville_id', $isEdit ? $formation->ville_id : ($defaultVilleId ?? ''));
                $defaultZoneId = old('zone_id', $isEdit ? $formation->zone_id : ($defaultZoneId ?? ''));
                $defaultType = old('type', $isEdit ? $formation->type : '');
                $defaultCible = old('cible', $isEdit ? $formation->cible : '');
                $defaultDatesEcole = old('dates_ecole', $isEdit ? ($formation->dates_ecole ?? []) : []);
                $defaultDatesProposees = old('dates_proposees', $isEdit ? ($formation->dates_proposees ?? []) : []);
                $defaultStatut = old('statut', $isEdit ? $formation->statut : '');
            @endphp

            {{-- Section 1 : Identification --}}
            <div class="fp-section" id="sec-identification">
                <div class="fp-section-head">
                    <div class="fp-section-icon blue">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 13h4"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Identification</div>
                        <div class="fp-section-sub">Compte, contact, ville et zone</div>
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="compte_id" id="compte_id" class="frm-select {{ $errors->has('compte_id') ? 'is-invalid' : '' }}" required>
                                <option value="">— Sélectionnez un compte —</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>
                                        {{ $c->etablissement }} ({{ $c->ville->nom }})
                                    </option>
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

                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="ville_id">Ville <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="ville_id" id="ville_id" class="frm-select {{ $errors->has('ville_id') ? 'is-invalid' : '' }}" required>
                                <option value="">— Sélectionnez —</option>
                                @foreach($villes as $v)
                                    <option value="{{ $v->id }}" {{ $defaultVilleId == $v->id ? 'selected' : '' }}>{{ $v->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('ville_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="zone_id">Zone <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="zone_id" id="zone_id" class="frm-select {{ $errors->has('zone_id') ? 'is-invalid' : '' }}" required>
                                <option value="">— Sélectionnez —</option>
                                @foreach($zones as $z)
                                    <option value="{{ $z->id }}" {{ $defaultZoneId == $z->id ? 'selected' : '' }}>{{ $z->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('zone_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 2 : Détails de la formation --}}
            <div class="fp-section" id="sec-details">
                <div class="fp-section-head">
                    <div class="fp-section-icon teal">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Détails de la formation</div>
                        <div class="fp-section-sub">Type, cible et statut</div>
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="type">Type de formation <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="type" id="type" class="frm-select {{ $errors->has('type') ? 'is-invalid' : '' }}" required>
                                <option value="">— Sélectionnez —</option>
                                @foreach($types as $t)
                                    <option value="{{ $t }}" {{ $defaultType == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('type')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="cible">Cible</label>
                        <div class="frm-select-wrap">
                            <select name="cible" id="cible" class="frm-select">
                                <option value="">— Sélectionnez —</option>
                                @foreach($cibles as $c)
                                    <option value="{{ $c }}" {{ $defaultCible == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                @if($isEdit)
                <div class="fp-row">
                    <div class="frm-group">
                        <label class="frm-label" for="statut">Statut</label>
                        <div class="frm-select-wrap">
                            <select name="statut" id="statut" class="frm-select">
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ $defaultStatut == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Section 3 : Dates --}}
            <div class="fp-section" id="sec-dates">
                <div class="fp-section-head">
                    <div class="fp-section-icon amber">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Dates</div>
                        <div class="fp-section-sub">Dates proposées par l’école et dates proposées à l’école</div>
                    </div>
                </div>

                {{-- Dates école --}}
                <div class="frm-group">
                    <label class="frm-label">Dates proposées par l'école</label>
                    <div id="dates-ecole-container" class="dates-container">
                        @if(count($defaultDatesEcole))
                            @foreach($defaultDatesEcole as $date)
                            <div class="date-row">
                                <input type="date" name="dates_ecole[]" class="frm-input" value="{{ $date }}">
                                <button type="button" class="date-del-btn remove-date" title="Supprimer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                            @endforeach
                        @else
                            <div class="date-row">
                                <input type="date" name="dates_ecole[]" class="frm-input">
                                <button type="button" class="date-del-btn remove-date" title="Supprimer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-zn btn-zn-ghost btn-zn-sm add-date" data-target="dates-ecole-container" data-name="dates_ecole[]" style="margin-top: .5rem;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Ajouter une date
                    </button>
                </div>

                {{-- Dates proposées à l'école --}}
                <div class="frm-group" style="margin-top: 1.5rem;">
                    <label class="frm-label">Dates proposées à l'école</label>
                    <div id="dates-proposees-container" class="dates-container">
                        @if(count($defaultDatesProposees))
                            @foreach($defaultDatesProposees as $date)
                            <div class="date-row">
                                <input type="date" name="dates_proposees[]" class="frm-input" value="{{ $date }}">
                                <button type="button" class="date-del-btn remove-date" title="Supprimer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                            @endforeach
                        @else
                            <div class="date-row">
                                <input type="date" name="dates_proposees[]" class="frm-input">
                                <button type="button" class="date-del-btn remove-date" title="Supprimer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-zn btn-zn-ghost btn-zn-sm add-date" data-target="dates-proposees-container" data-name="dates_proposees[]" style="margin-top: .5rem;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Ajouter une date
                    </button>
                </div>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('formations.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ isset($formation) ? 'Enregistrer les modifications' : 'Créer la demande' }}
                </button>
            </div>

        </form>
    </div>
</div>

<script>
(function () {
    // ── Contact & ville/zone loading ──────────────
    const compteSelect   = document.getElementById('compte_id');
    const contactSelect  = document.getElementById('contact_id');
    const villeSelect    = document.getElementById('ville_id');
    const zoneSelect     = document.getElementById('zone_id');
    const defaultContact = '{{ $defaultContactId }}';

    function loadContactsForCompte(compteId) {
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

    function loadCompteDetails(compteId) {
        if (!compteId) return;
        fetch(`/api/comptes/${compteId}/details`)
            .then(r => r.json())
            .then(data => {
                if (data.ville_id) villeSelect.value = data.ville_id;
                if (data.zone_id) zoneSelect.value = data.zone_id;
                loadContactsForCompte(compteId);
            })
            .catch(err => console.error('Error loading compte details:', err));
    }

    compteSelect?.addEventListener('change', () => {
        const compteId = compteSelect.value;
        if (compteId) {
            loadCompteDetails(compteId);
        } else {
            contactSelect.innerHTML = '<option value="">— Sélectionnez d\'abord un compte —</option>';
            villeSelect.value = '';
            zoneSelect.value = '';
        }
    });

    if (compteSelect?.value) loadCompteDetails(compteSelect.value);

    // ── Dynamic date rows (generic) ───────────────
    function attachDateRowEvents(row) {
        const delBtn = row.querySelector('.remove-date');
        if (delBtn) {
            delBtn.addEventListener('click', () => {
                const container = row.parentNode;
                if (container.querySelectorAll('.date-row').length > 1) {
                    row.remove();
                } else {
                    row.querySelector('input[type="date"]').value = '';
                }
            });
        }
    }

    document.querySelectorAll('.date-row').forEach(attachDateRowEvents);

    document.querySelectorAll('.add-date').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const inputName = this.dataset.name;
            const container = document.getElementById(targetId);
            if (!container) return;

            const newRow = document.createElement('div');
            newRow.className = 'date-row';
            newRow.innerHTML = `
                <input type="date" name="${inputName}" class="frm-input">
                <button type="button" class="date-del-btn remove-date" title="Supprimer">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            `;
            container.appendChild(newRow);
            attachDateRowEvents(newRow);
            newRow.querySelector('input')?.focus();
        });
    });
})();
</script>
@endsection