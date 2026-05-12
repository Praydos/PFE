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
    .frm-input.is-invalid, .frm-select.is-invalid, .frm-textarea.is-invalid {
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
    .frm-textarea { resize: vertical; min-height: 80px; }
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
        .fp-row-2, .fp-row-3 { grid-template-columns: 1fr; }
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
        <a href="{{ route('actions-amelioration.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('actions-amelioration.index') }}">Actions d'amélioration</a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('actions-amelioration.show', $actions_amelioration) }}">{{ $actions_amelioration->numero }}</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Évaluation de l'efficacité</span>
    </div>

    <div class="zn-header">
        <h1>Évaluation de l'efficacité</h1>
        <p>Action n° {{ $actions_amelioration->numero }} – {{ $actions_amelioration->type ?? '' }}</p>
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
        <form method="POST" action="{{ route('actions-amelioration.update-efficacite', $actions_amelioration) }}">
            @csrf
            @method('PUT')

            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 2a7 7 0 0 0-7 7c0 4 7 13 7 13s7-9 7-13a7 7 0 0 0-7-7z"/>
                            <circle cx="12" cy="9" r="3"/>
                        </svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Évaluation de l'efficacité</div>
                        <div class="fp-section-sub">Résultats, contrôle et statut</div>
                    </div>
                </div>

                {{-- Row 1: Date efficacité + Responsable efficacité (2 columns) --}}
                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="date_efficacite">Date efficacité</label>
                        <input type="date" name="date_efficacite" id="date_efficacite"
                               class="frm-input @error('date_efficacite') is-invalid @enderror"
                               value="{{ old('date_efficacite', $actions_amelioration->date_efficacite ? $actions_amelioration->date_efficacite->format('Y-m-d') : '') }}">
                        @error('date_efficacite')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="responsable_effecacite_id">Responsable efficacité <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="responsable_effecacite_id" id="responsable_effecacite_id"
                                    class="frm-select @error('responsable_effecacite_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionnez un contact --</option>
                                @foreach($contacts as $c)
                                    <option value="{{ $c->id }}" {{ old('responsable_effecacite_id', $actions_amelioration->responsable_effecacite_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->prenom }} {{ $c->nom }} ({{ $c->fonction ?? 'Contact' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('responsable_effecacite_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Mode de contrôle --}}
                <div class="frm-group">
                    <label class="frm-label" for="mode_controle">Mode de contrôle</label>
                    <textarea name="mode_controle" id="mode_controle"
                              class="frm-textarea @error('mode_controle') is-invalid @enderror"
                              rows="2">{{ old('mode_controle', $actions_amelioration->mode_controle) }}</textarea>
                    @error('mode_controle')<span class="frm-error">{{ $message }}</span>@enderror
                </div>

                {{-- Description du résultat --}}
                <div class="frm-group">
                    <label class="frm-label" for="description_resultat">Description du résultat</label>
                    <textarea name="description_resultat" id="description_resultat"
                              class="frm-textarea @error('description_resultat') is-invalid @enderror"
                              rows="2">{{ old('description_resultat', $actions_amelioration->description_resultat) }}</textarea>
                    @error('description_resultat')<span class="frm-error">{{ $message }}</span>@enderror
                </div>

                {{-- Row 3: Action efficace ? + Besoin d'action d'amélioration ? + Statut (3 columns) --}}
                <div class="fp-row fp-row-3">
                    <div class="frm-group">
                        <label class="frm-label" for="action_effecace">Action efficace ?</label>
                        <div class="frm-select-wrap">
                            <select name="action_effecace" id="action_effecace"
                                    class="frm-select @error('action_effecace') is-invalid @enderror">
                                <option value="">-- Non spécifié --</option>
                                <option value="1" {{ old('action_effecace', $actions_amelioration->action_effecace) === '1' ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ old('action_effecace', $actions_amelioration->action_effecace) === '0' ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                        @error('action_effecace')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="besoin_action_amelioration">Besoin d'action d'amélioration ?</label>
                        <div class="frm-select-wrap">
                            <select name="besoin_action_amelioration" id="besoin_action_amelioration"
                                    class="frm-select @error('besoin_action_amelioration') is-invalid @enderror">
                                <option value="">-- Non spécifié --</option>
                                <option value="1" {{ old('besoin_action_amelioration', $actions_amelioration->besoin_action_amelioration) === '1' ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ old('besoin_action_amelioration', $actions_amelioration->besoin_action_amelioration) === '0' ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                        @error('besoin_action_amelioration')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="statut">Statut <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="statut" id="statut"
                                    class="frm-select @error('statut') is-invalid @enderror" required>
                                @foreach(['brouillon','en_cours','termine','annule','en_attente'] as $s)
                                    <option value="{{ $s }}" {{ old('statut', $actions_amelioration->statut) == $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('statut')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Date clôture --}}
                <div class="frm-group">
                    <label class="frm-label" for="date_cloture">Date clôture</label>
                    <input type="date" name="date_cloture" id="date_cloture"
                           class="frm-input @error('date_cloture') is-invalid @enderror"
                           value="{{ old('date_cloture', $actions_amelioration->date_cloture ? $actions_amelioration->date_cloture->format('Y-m-d') : '') }}">
                    @error('date_cloture')<span class="frm-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('actions-amelioration.show', $actions_amelioration) }}" class="btn-zn btn-zn-ghost">
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
                    Enregistrer l'évaluation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection