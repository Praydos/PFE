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

    /* Checkbox styling */
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-top: 0.25rem;
    }
    .checkbox-group input[type="checkbox"] {
        width: 1rem;
        height: 1rem;
        margin: 0;
        accent-color: var(--blue);
        cursor: pointer;
    }
    .checkbox-group label {
        font-size: .83rem;
        color: var(--text-secondary);
        cursor: pointer;
    }

    /* Multi-select specific */
    .frm-select[size] {
        padding: 0.4rem 0.2rem;
    }
    .frm-select[size] option {
        padding: 0.4rem 0.6rem;
        border-radius: var(--r-xs);
    }
    .frm-select[size] option:checked {
        background: var(--blue-light);
        color: var(--blue-dark);
    }

    .frm-hint {
        font-size: .72rem;
        color: var(--text-muted);
        margin-top: .2rem;
        display: flex;
        align-items: center;
        gap: .3rem;
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
        <a href="{{ route('taches.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('taches.index') }}">Tâches</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ isset($tache) ? 'Modifier la tâche' : 'Nouvelle tâche' }}</span>
    </div>

    <div class="zn-header">
        <h1>{{ isset($tache) ? 'Modifier la tâche' : 'Nouvelle tâche' }}</h1>
        <p>{{ isset($tache) ? 'Mettez à jour les informations de la tâche.' : 'Créez une tâche planifiée.' }}</p>
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
        <form method="POST" action="{{ isset($tache) ? route('taches.update', $tache) : (isset($targetDelegate) ? route('taches.storeForDelegate', $targetDelegate) : route('taches.store')) }}">
            @csrf
            @if(isset($tache)) @method('PUT') @endif

            @php
                $isEdit = isset($tache);
                $defaultObjet = old('objet', $isEdit ? $tache->objet : '');
                $defaultDescription = old('description', $isEdit ? $tache->description : '');
                // $defaultDatePlanif = old('date_planification', $isEdit ? $tache->date_planification->format('Y-m-d') : '');
                $defaultDateFin = old('date_fin', $isEdit && $tache->date_fin ? $tache->date_fin->format('Y-m-d') : '');
                $defaultAllDay = old('all_day', $isEdit ? $tache->all_day : false);
                $defaultLieu = old('lieu', $isEdit ? $tache->lieu : '');
                $defaultContacts = old('contacts', $isEdit ? ($tache->contacts->pluck('id')->toArray() ?? []) : []);
                $defaultHeure = old('heure', $isEdit ? $tache->heure : '');
                $defaultRecurrenceFreq = old('recurrence_frequence', $isEdit ? $tache->recurrence_frequence : '');
                $defaultRecurrenceInterval = old('recurrence_intervalle', $isEdit ? $tache->recurrence_interval : 1);
                $defaultRecurrenceEnd = old(
                    'recurrence_fin',
                    $isEdit && $tache->recurrence_end_date ? $tache->recurrence_end_date->format('Y-m-d') : ''
                );
                $defaultDatePlanif = old(
                    'date_planification',
                    $isEdit
                        ? $tache->date_planification->format('Y-m-d')
                        : ($defaultDate ?? now()->toDateString())
                );
            @endphp

            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Détails de la tâche</div>
                        <div class="fp-section-sub">Objet, dates, lieu et contacts</div>
                    </div>
                </div>

                {{-- Objet --}}
                <div class="frm-group">
                    <label class="frm-label" for="objet">Objet <span class="req">*</span></label>
                    <input type="text" name="objet" id="objet"
                           class="frm-input {{ $errors->has('objet') ? 'is-invalid' : '' }}"
                           value="{{ $defaultObjet }}" required>
                    @error('objet')<span class="frm-error">{{ $message }}</span>@enderror
                </div>
                <br>

                {{-- Description --}}
                <div class="frm-group">
                    <label class="frm-label" for="description">Description</label>
                    <textarea name="description" id="description" class="frm-input" rows="2">{{ $defaultDescription }}</textarea>
                </div>
<br>
                {{-- Dates + Heure --}}
<div class="fp-row fp-row-3">
    {{-- Date --}}
    <div class="frm-group">
        <label class="frm-label" for="date_planification">
            Date planification <span class="req">*</span>
        </label>
        <input type="date"
                name="date_planification"
                id="date_planification"
                class="frm-input {{ $errors->has('date_planification') ? 'is-invalid' : '' }}"
                value="{{ $defaultDatePlanif }}"
                required>
        @error('date_planification')<span class="frm-error">{{ $message }}</span>@enderror
    </div>

    {{-- Heure --}}
    <div class="frm-group">
        <label class="frm-label" for="heure">Heure</label>
        <input type="time" name="heure" id="heure"
               class="frm-input"
               value="{{ $defaultHeure }}">
    </div>

    {{-- All day --}}
    <div class="frm-group" style="justify-content: flex-end;">
        <div class="checkbox-group">
            <input type="checkbox" name="all_day" id="all_day" value="1"
                   {{ $defaultAllDay ? 'checked' : '' }}>
            <label for="all_day">Toute la journée</label>
        </div>
    </div>
</div>

                {{-- Lieu --}}
                <div class="frm-group">
                    <label class="frm-label" for="lieu">Lieu</label>
                    <input type="text" name="lieu" id="lieu"
                           class="frm-input {{ $errors->has('lieu') ? 'is-invalid' : '' }}"
                           value="{{ $defaultLieu }}">
                    @error('lieu')<span class="frm-error">{{ $message }}</span>@enderror
                </div>

                <br>

                <div class="fp-section">
    <div class="fp-section-head">
        <div class="fp-section-icon" style="background: var(--violet-light); color: var(--violet);">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="23 4 23 10 17 10"/>
                <polyline points="1 20 1 14 7 14"/>
                <path d="M3.5 9a9 9 0 0114.5-3.5L23 10M1 14l5 4.5A9 9 0 0020.5 15"/>
            </svg>
        </div>
        <div class="fp-section-meta">
            <div class="fp-section-title">Récurrence</div>
            <div class="fp-section-sub">Configurer une répétition automatique</div>
        </div>
    </div>

    <div class="fp-row fp-row-3">
        {{-- Frequence --}}
        <div class="frm-group">
            <label class="frm-label">Répéter</label>
            <div class="frm-select-wrap">
                <select name="recurrence_frequence" class="frm-select">
                    <option value="">Aucune</option>
                    <option value="daily" {{ $defaultRecurrenceFreq == 'daily' ? 'selected' : '' }}>Quotidienne</option>
                    <option value="weekly" {{ $defaultRecurrenceFreq == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                    <option value="monthly" {{ $defaultRecurrenceFreq == 'monthly' ? 'selected' : '' }}>Mensuelle</option>
                    <option value="yearly" {{ $defaultRecurrenceFreq == 'yearly' ? 'selected' : '' }}>Annuelle</option>
                </select>
            </div>
        </div>

        {{-- Interval --}}
        <div class="frm-group">
            <label class="frm-label">Tous les N</label>
            <input type="number" name="recurrence_intervalle"
                   class="frm-input"
                   value="{{ $defaultRecurrenceInterval }}" min="1" type="number">
        </div>

        {{-- End date --}}
        {{-- Date fin --}}
        <div class="frm-group" style="margin-top: 1rem;">
            <label class="frm-label" for="date_fin">
                Date de fin
                <span class="opt">(optionnelle)</span>
            </label>
            <input type="date" name="date_fin" id="date_fin"
                class="frm-input {{ $errors->has('date_fin') ? 'is-invalid' : '' }}"
                value="{{ $defaultDateFin }}">
            @error('date_fin')<span class="frm-error">{{ $message }}</span>@enderror
        </div>
    </div>
</div>
                

                {{-- Contacts (multi-select) --}}
                <div class="frm-group">
                    <label class="frm-label" for="contacts">Contacts (plusieurs)</label>
                    <select name="contacts[]" id="contacts" class="frm-select" multiple size="4">
                        @foreach($contacts as $c)
                            <option value="{{ $c->id }}" {{ in_array($c->id, $defaultContacts) ? 'selected' : '' }}>
                                {{ $c->prenom }} {{ $c->nom }}
                            </option>
                        @endforeach
                    </select>
                    <div class="frm-hint">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs contacts
                    </div>
                    @error('contacts')<span class="frm-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('taches.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ isset($tache) ? 'Enregistrer les modifications' : 'Créer la tâche' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection