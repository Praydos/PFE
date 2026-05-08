@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ── exact same CSS as previous forms (exam, formation) ── */
    /* (copied from the user's provided style block – kept identical) */
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
    .btn-zn-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; transform: translateY(-1px); }
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
    .frm-error { font-size: .72rem; color: var(--rose); font-weight: 500; margin-top: .2rem; }

    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent; border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted); pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }

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
        .fp-row-2 { grid-template-columns: 1fr; }
        .fp-footer { flex-wrap: wrap; }
        .fp-footer-spacer { display: none; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('events.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('events.index') }}">Événements</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ isset($event) ? 'Modifier l\'événement' : 'Nouvel événement' }}</span>
    </div>

    <div class="zn-header">
        <h1>{{ isset($event) ? 'Modifier l\'événement' : 'Nouvel événement' }}</h1>
        <p>{{ isset($event) ? 'Mettez à jour les informations ci-dessous.' : 'Créez un nouvel événement.' }}</p>
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
        <form method="POST" action="{{ isset($event) ? route('events.update', $event) : route('events.store') }}" id="event-form">
            @csrf
            @if(isset($event)) @method('PUT') @endif

            @php
                $isEdit = isset($event);
                $defaultVilleId = old('ville_id', $isEdit ? $event->ville_id : '');
                $defaultType = old('type', $isEdit ? $event->type : '');
                $defaultEditeur = old('editeur', $isEdit ? $event->editeur : '');
                // $defaultDate = old('date_event', $isEdit ? $event->date_event->format('Y-m-d') : now()->format('Y-m-d'));
                $defaultYearId = old('annee_scolaire_id', $isEdit ? $event->annee_scolaire_id : ($currentYear->id ?? ''));
              


                $defaultDate = old(
                    'date_planification',
                    $isEdit
                        ? $tache->date_planification->format('Y-m-d')
                        : $defaultDate
                );

            @endphp

            {{-- Section unique : Détails de l'événement --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Détails de l'événement</div>
                        <div class="fp-section-sub">Ville, année scolaire, type, éditeur et date</div>
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
                        <label class="frm-label" for="annee_scolaire_id">Année scolaire <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="annee_scolaire_id" id="annee_scolaire_id" class="frm-select {{ $errors->has('annee_scolaire_id') ? 'is-invalid' : '' }}" required>
                                @foreach($years as $y)
                                    <option value="{{ $y->id }}" {{ $defaultYearId == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('annee_scolaire_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="type">Type d'événement <span class="req">*</span></label>
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
                        <label class="frm-label" for="editeur">Éditeur <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="editeur" id="editeur" class="frm-select {{ $errors->has('editeur') ? 'is-invalid' : '' }}" required>
                                <option value="">— Sélectionnez —</option>
                                @foreach($editeurs as $e)
                                    <option value="{{ $e }}" {{ $defaultEditeur == $e ? 'selected' : '' }}>{{ $e }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('editeur')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="fp-row">
                    <div class="frm-group">
                        <label class="frm-label" for="date_planification">Date de l'événement <span class="req">*</span></label>
                       <input type="date"
                                name="date_planification"
                                id="date_planification"
                                class="frm-input {{ $errors->has('date_planification') ? 'is-invalid' : '' }}"
                                value="{{ $defaultDate }}"
                                required>
                        @error('date_planification')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('events.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ isset($event) ? 'Enregistrer les modifications' : 'Créer l\'événement' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection