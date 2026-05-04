@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ── same CSS as the create view (already provided) ── */
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
    .btn-zn-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; transform: translateY(-1px); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
    .btn-zn-sm { padding: .34rem .65rem; font-size: .74rem; }

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
    .fp-section-icon.violet{ background: var(--violet-light); color: var(--violet); }
    .fp-section-icon.amber { background: var(--amber-light);  color: var(--amber); }
    .fp-section-meta { flex: 1; }
    .fp-section-title { font-size: .9rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.02em; }
    .fp-section-sub   { font-size: .74rem; color: var(--text-muted); margin-top: .1rem; }

    .fp-row { display: grid; gap: 1rem; margin-bottom: 1rem; }
    .fp-row:last-child { margin-bottom: 0; }
    .fp-row-2 { grid-template-columns: repeat(2, 1fr); }
    .fp-row-3 { grid-template-columns: repeat(3, 1fr); }
    .fp-row-title { grid-template-columns: 2fr 1fr; }
    .fp-row-21 { grid-template-columns: 2fr 1fr; }

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
    .frm-textarea { resize: vertical; min-height: 76px; }

    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent; border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted); pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }

    .frm-input.is-invalid, .frm-select.is-invalid, .frm-textarea.is-invalid {
        border-color: var(--rose);
        box-shadow: 0 0 0 3px rgba(232,80,106,.12);
    }
    .frm-error { font-size: .72rem; color: var(--rose); font-weight: 500; margin-top: .2rem; }

    .ep-table-wrap { margin-top: .25rem; }
    .ep-table-head {
        display: grid;
        grid-template-columns: 2fr 110px 160px 36px;
        gap: .75rem;
        padding: 0 .5rem .5rem;
        border-bottom: 1px solid var(--border);
        margin-bottom: .75rem;
    }
    .ep-col-label {
        font-size: .68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em;
        color: var(--text-hint);
    }
    #epreuves-container .epreuve-row {
        display: grid;
        grid-template-columns: 2fr 110px 160px 36px;
        gap: .75rem;
        align-items: center;
        margin-bottom: .6rem;
        animation: rowIn .22s var(--ease) both;
    }
    @keyframes rowIn { from { opacity: 0; transform: translateX(-6px); } to { opacity: 1; transform: translateX(0); } }

    .ep-del-btn {
        width: 36px; height: 36px; flex-shrink: 0;
        border-radius: var(--r-sm);
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        color: var(--text-muted);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all var(--t);
        padding: 0;
    }
    .ep-del-btn:hover { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }

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
        .fp-row-2, .fp-row-3, .fp-row-title, .fp-row-21 { grid-template-columns: 1fr; }
        .ep-table-head { display: none; }
        #epreuves-container .epreuve-row { grid-template-columns: 1fr 1fr; grid-template-rows: auto auto; }
        #epreuves-container .epreuve-row > :first-child { grid-column: 1 / -1; }
        .fp-footer { flex-wrap: wrap; }
        .fp-footer-spacer { display: none; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('examens.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('examens.index') }}">Examens</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Modifier la demande</span>
    </div>

    <div class="zn-header">
        <h1>Modifier la demande d'examen</h1>
        <p>Mettez à jour les informations ci-dessous.</p>
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
        <form method="POST" action="{{ route('examens.update', $examen) }}" id="exam-form">
            @csrf
            @method('PUT')

            @php
                $defaultCompteId    = old('compte_id', $examen->compte_id ?? '');
                $defaultContactId   = old('contact_id', $examen->contact_id ?? '');
                $defaultYearId      = old('annee_scolaire_id', $examen->annee_scolaire_id ?? ($currentYear->id ?? null));
                $defaultDateDemande = old('date_demande', $examen->date_demande ?? now()->format('Y-m-d'));
                $defaultLangue      = old('langue', $examen->langue ?? '');
                $defaultOrganisme   = old('organisme', $examen->organisme ?? '');
                $defaultNiveauCECR  = old('niveau_cecr', $examen->niveau_cecr ?? '');
                $defaultTitre       = old('titre', $examen->titre ?? '');
                $defaultAbreviation = old('abreviation', $examen->abreviation ?? '');
                $defaultNiveauScolaire = old('niveau_scolaire', $examen->niveau_scolaire ?? '');
                $defaultDateExamen  = old('date_examen', $examen->date_examen ?? '');
                $defaultDescription = old('description', $examen->description ?? '');
                $defaultObservations= old('observations', $examen->observations ?? '');
            @endphp

            {{-- Section 1: Identification --}}
            <div class="fp-section" id="sec-identification">
                <div class="fp-section-head">
                    <div class="fp-section-icon blue">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 13h4"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Identification</div>
                        <div class="fp-section-sub">Compte, contact et période scolaire</div>
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
                    <div class="frm-group">
                        <label class="frm-label" for="date_demande">Date de demande <span class="req">*</span></label>
                        <input type="date" name="date_demande" id="date_demande"
                            class="frm-input {{ $errors->has('date_demande') ? 'is-invalid' : '' }}"
                            value="{{ $defaultDateDemande }}" required>
                        @error('date_demande')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Examen --}}
            <div class="fp-section" id="sec-examen">
                <div class="fp-section-head">
                    <div class="fp-section-icon teal">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Examen</div>
                        <div class="fp-section-sub">Langue, organisme certificateur et intitulé</div>
                    </div>
                </div>

                <div class="fp-row fp-row-3">
                    <div class="frm-group">
                        <label class="frm-label" for="langue">Langue</label>
                        <div class="frm-select-wrap">
                            <select name="langue" id="langue" class="frm-select">
                                <option value="">—</option>
                                @foreach($langues as $l)
                                    <option value="{{ $l }}" {{ $defaultLangue == $l ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="organisme">Organisme</label>
                        <div class="frm-select-wrap">
                            <select name="organisme" id="organisme" class="frm-select">
                                <option value="">—</option>
                                @foreach($organismes as $o)
                                    <option value="{{ $o }}" {{ $defaultOrganisme == $o ? 'selected' : '' }}>{{ $o }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="niveau_cecr">Niveau CECR</label>
                        <div class="frm-select-wrap">
                            <select name="niveau_cecr" id="niveau_cecr" class="frm-select">
                                <option value="">—</option>
                                @foreach($niveauxCECR as $n)
                                    <option value="{{ $n }}" {{ $defaultNiveauCECR == $n ? 'selected' : '' }}>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="fp-row fp-row-title">
                    <div class="frm-group">
                        <label class="frm-label" for="titre">Titre de l'examen <span class="req">*</span></label>
                        <input type="text" name="titre" id="titre"
                            class="frm-input {{ $errors->has('titre') ? 'is-invalid' : '' }}"
                            value="{{ $defaultTitre }}" placeholder="ex : DELF B2 Session Printemps" required>
                        @error('titre')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="abreviation">Abréviation</label>
                        <input type="text" name="abreviation" id="abreviation"
                            class="frm-input" value="{{ $defaultAbreviation }}" placeholder="ex : DELF B2">
                    </div>
                </div>
            </div>

            {{-- Section 3: Détails --}}
            <div class="fp-section" id="sec-details">
                <div class="fp-section-head">
                    <div class="fp-section-icon violet">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Détails</div>
                        <div class="fp-section-sub">Niveau, date et informations complémentaires</div>
                    </div>
                </div>

                <div class="fp-row fp-row-21">
                    <div class="frm-group">
                        <label class="frm-label" for="niveau_scolaire">Niveau scolaire</label>
                        <div class="frm-select-wrap">
                            <select name="niveau_scolaire" id="niveau_scolaire" class="frm-select">
                                <option value="">— Sélectionnez un niveau —</option>
                                @foreach($niveauxScolaires as $ns)
                                    <option value="{{ $ns }}" {{ $defaultNiveauScolaire == $ns ? 'selected' : '' }}>{{ $ns }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="frm-group">
                        <label class="frm-label" for="date_examen">Date d'examen (prévue)</label>
                        <input type="date" name="date_examen" id="date_examen"
                            class="frm-input" value="{{ $defaultDateExamen }}">
                    </div>
                </div>

                <div class="fp-row">
                    <div class="frm-group">
                        <label class="frm-label" for="description">Description</label>
                        <textarea name="description" id="description" class="frm-input frm-textarea"
                            rows="2" placeholder="Description de l'examen…">{{ $defaultDescription }}</textarea>
                    </div>
                </div>

                <div class="fp-row">
                    <div class="frm-group">
                        <label class="frm-label" for="observations">Observations</label>
                        <textarea name="observations" id="observations" class="frm-input frm-textarea"
                            rows="2" placeholder="Remarques ou observations particulières…">{{ $defaultObservations }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Section 4: Épreuves --}}
            <div class="fp-section" id="sec-epreuves">
                <div class="fp-section-head">
                    <div class="fp-section-icon amber">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Épreuves</div>
                        <div class="fp-section-sub">Liste des épreuves de cet examen</div>
                    </div>
                </div>

                <div class="ep-table-wrap">
                    <div class="ep-table-head">
                        <span class="ep-col-label">Intitulé de l'épreuve</span>
                        <span class="ep-col-label">Durée (min)</span>
                        <span class="ep-col-label">Date de réalisation</span>
                        <span></span>
                    </div>

                    <div id="epreuves-container">
                        @if($examen->epreuves->count())
                            @foreach($examen->epreuves as $index => $ep)
                            <div class="epreuve-row">
                                <input type="text"   name="epreuves[{{ $index }}][epreuve]"          class="frm-input" value="{{ $ep->epreuve }}"         placeholder="ex : Compréhension écrite">
                                <input type="number" name="epreuves[{{ $index }}][duree]"             class="frm-input" value="{{ $ep->duree }}"           placeholder="60" min="0">
                                <input type="date"   name="epreuves[{{ $index }}][date_realisation]"  class="frm-input" value="{{ $ep->date_realisation }}">
                                <button type="button" class="ep-del-btn remove-epreuve" title="Supprimer l'épreuve">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                                <input type="hidden" name="epreuves[{{ $index }}][id]" value="{{ $ep->id }}">
                            </div>
                            @endforeach
                        @else
                            <div class="epreuve-row">
                                <input type="text"   name="epreuves[0][epreuve]"         class="frm-input" placeholder="ex : Compréhension écrite">
                                <input type="number" name="epreuves[0][duree]"            class="frm-input" placeholder="60" min="0">
                                <input type="date"   name="epreuves[0][date_realisation]" class="frm-input">
                                <button type="button" class="ep-del-btn remove-epreuve" title="Supprimer l'épreuve">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <button type="button" id="add-epreuve" class="btn-zn btn-zn-ghost btn-zn-sm" style="margin-top:.75rem;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Ajouter une épreuve
                    </button>
                </div>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('examens.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>

        </form>
    </div>
</div>

<script>
(function () {
    const compteSelect   = document.getElementById('compte_id');
    const contactSelect  = document.getElementById('contact_id');
    const defaultContact = '{{ $defaultContactId }}';

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

    compteSelect?.addEventListener('change', () => loadContacts(compteSelect.value));
    if (compteSelect?.value) loadContacts(compteSelect.value);

    let epIdx = {{ $examen->epreuves->count() }};
    const container = document.getElementById('epreuves-container');

    function attachRowEvents(row) {
        row.querySelector('.remove-epreuve')?.addEventListener('click', () => {
            if (container.querySelectorAll('.epreuve-row').length > 1) {
                row.remove();
            } else {
                row.querySelectorAll('input[type="text"], input[type="number"], input[type="date"]')
                   .forEach(i => i.value = '');
            }
        });
    }

    document.getElementById('add-epreuve')?.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'epreuve-row';
        row.innerHTML = `
            <input type="text"   name="epreuves[${epIdx}][epreuve]"         class="frm-input" placeholder="ex : Expression orale">
            <input type="number" name="epreuves[${epIdx}][duree]"            class="frm-input" placeholder="60" min="0">
            <input type="date"   name="epreuves[${epIdx}][date_realisation]" class="frm-input">
            <button type="button" class="ep-del-btn remove-epreuve" title="Supprimer l'épreuve">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>`;
        container.appendChild(row);
        attachRowEvents(row);
        epIdx++;
        row.querySelector('input')?.focus();
    });

    document.querySelectorAll('.epreuve-row').forEach(attachRowEvents);
})();
</script>
@endsection