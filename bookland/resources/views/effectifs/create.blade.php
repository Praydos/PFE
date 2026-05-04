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
    .btn-zn-primary:hover { background: var(--blue-dark); color: #fff; transform: translateY(-1px); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }

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
    .fp-section-icon.violet{ background: var(--violet-light); color: var(--violet); }
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

    /* Source card (3 sources) */
    .source-card {
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        border-radius: var(--r-md);
        padding: 1rem 1.2rem;
        transition: border-color var(--t);
    }
    .source-card:hover { border-color: var(--border-md); }
    .source-header {
        display: flex; align-items: center; gap: .6rem;
        margin-bottom: .9rem;
        font-size: .72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: var(--text-muted);
    }
    .source-num {
        width: 24px; height: 24px;
        background: var(--blue-light); border: 1px solid var(--blue-mid);
        color: var(--blue); border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .7rem; font-weight: 800;
    }

    /* Admin section */
    .admin-section {
        background: var(--violet-light);
        border: 1px solid rgba(124,111,205,.2);
        border-radius: var(--r-md);
        padding: 1.2rem 1.4rem;
        margin-top: 0.5rem;
    }
    .admin-badge {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .2rem .7rem; border-radius: 20px;
        background: var(--violet-light); color: var(--violet);
        border: 1px solid rgba(124,111,205,.25);
        font-size: .7rem; font-weight: 700; letter-spacing: .04em;
        margin-bottom: 1rem;
    }

    /* Footer */
    .fp-footer {
        padding: 1.25rem 2rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
        border-top: 1px solid var(--border);
        display: flex; align-items: center; gap: .75rem;
    }
    .fp-footer-spacer { flex: 1; }
    .fp-req-note { font-size: .74rem; color: var(--text-muted); }
    .fp-req-note span { color: var(--rose); }

    /* Alert */
    .zn-alert {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: 1rem 1.25rem; border-radius: var(--r-lg);
        border: 1px solid; margin-bottom: 1.5rem; font-size: .82rem;
    }
    .zn-alert-danger { background: var(--rose-light); border-color: rgba(232,80,106,.25); color: #b83450; }
    .zn-alert ul { padding-left: 1.2rem; margin-top: .3rem; }
    .zn-alert li { margin-bottom: .15rem; }

    .frm-hint {
        font-size: .72rem; color: var(--text-muted);
        display: flex; align-items: center; gap: .3rem;
        margin-top: .3rem;
    }

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
        <a href="{{ route('effectifs.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('effectifs.index') }}">Effectifs</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ isset($effectif) ? 'Modifier l\'effectif' : 'Nouvel effectif' }}</span>
    </div>

    <div class="zn-header">
        <h1>{{ isset($effectif) ? 'Modifier l\'effectif' : 'Nouvel effectif' }}</h1>
        <p>{{ isset($effectif) ? 'Mettez à jour les informations de l\'effectif.' : 'Enregistrez un nouvel effectif scolaire pour un compte.' }}</p>
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
        <form method="POST" action="{{ isset($effectif) ? route('effectifs.update', $effectif) : route('effectifs.store') }}" id="effectif-form">
            @csrf
            @if(isset($effectif)) @method('PUT') @endif

            @php
                $isEdit = isset($effectif);
                $defaultCompteId = old('compte_id', $isEdit ? $effectif->compte_id : '');
                $defaultYearId = old('annee_scolaire_id', $isEdit ? $effectif->annee_scolaire_id : ($currentYear->id ?? ''));
                $defaultNiveau = old('niveau', $isEdit ? $effectif->niveau : '');
                $defaultCycle = old('cycle', $isEdit ? $effectif->cycle : '');
                $defaultMassar = old('massar', $isEdit ? $effectif->massar : '');
                $defaultEffectifValide = old('effectif_valide', $isEdit ? $effectif->effectif_valide : '');
                $defaultSource1 = old('source_1', $isEdit ? $effectif->source_1 : '');
                $defaultSource2 = old('source_2', $isEdit ? $effectif->source_2 : '');
                $defaultSource3 = old('source_3', $isEdit ? $effectif->source_3 : '');
                $defaultNbClasses1 = old('nombre_classes_1', $isEdit ? $effectif->nombre_classes_1 : '');
                $defaultNbClasses2 = old('nombre_classes_2', $isEdit ? $effectif->nombre_classes_2 : '');
                $defaultNbClasses3 = old('nombre_classes_3', $isEdit ? $effectif->nombre_classes_3 : '');
            @endphp

            {{-- Section 1 : Compte & Période --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon blue">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 13h4"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Compte & période</div>
                        <div class="fp-section-sub">Compte concerné et année scolaire</div>
                    </div>
                </div>

                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="compte_id" id="compte_id" class="frm-select {{ $errors->has('compte_id') ? 'is-invalid' : '' }}" required>
                                <option value="">— Sélectionnez un compte —</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('compte_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="annee_scolaire_id">Année scolaire <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="annee_scolaire_id" id="annee_scolaire_id" class="frm-select {{ $errors->has('annee_scolaire_id') ? 'is-invalid' : '' }}" required>
                                <option value="">— Sélectionnez une année —</option>
                                @foreach($years as $y)
                                    <option value="{{ $y->id }}" {{ $defaultYearId == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('annee_scolaire_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 2 : Niveau & Cycle & Massar --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon teal">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Niveau scolaire</div>
                        <div class="fp-section-sub">Niveau, cycle et effectif Massar</div>
                    </div>
                </div>

                <div class="fp-row fp-row-3">
                    <div class="frm-group">
                        <label class="frm-label" for="niveau">Niveau <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="niveau" id="niveau" class="frm-select {{ $errors->has('niveau') ? 'is-invalid' : '' }}" required>
                                <option value="">— Niveau —</option>
                                @foreach($niveaux as $n)
                                    <option value="{{ $n }}" {{ $defaultNiveau == $n ? 'selected' : '' }}>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('niveau')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="cycle">Cycle <span class="opt">(facultatif)</span></label>
                        <div class="frm-select-wrap">
                            <select name="cycle" id="cycle" class="frm-select">
                                <option value="">— Cycle —</option>
                                @foreach($cycleOptions as $opt)
                                    <option value="{{ $opt }}" {{ $defaultCycle == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="massar">Massar <span class="opt">(officiel)</span></label>
                        <input type="number" name="massar" id="massar"
                               class="frm-input {{ $errors->has('massar') ? 'is-invalid' : '' }}"
                               value="{{ $defaultMassar }}" placeholder="Ex: 340" min="0">
                        @error('massar')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 3 : Sources de contact (3 cards) --}}
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon amber">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Sources de contact</div>
                        <div class="fp-section-sub">Jusqu'à trois sources avec leur nombre de classes</div>
                    </div>
                </div>

                <div class="fp-row fp-row-3">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="source-card">
                        <div class="source-header">
                            <span class="source-num">{{ $i }}</span> Source {{ $i }}
                        </div>
                        <div class="frm-group">
                            <label class="frm-label" for="source_{{ $i }}">Contact</label>
                            <div class="frm-select-wrap">
                                <select name="source_{{ $i }}" id="source_{{ $i }}"
                                        class="frm-select contact-select"
                                        data-target="nombre_classes_{{ $i }}">
                                    <option value="">— Contact —</option>
                                </select>
                            </div>
                        </div>
                        <div class="frm-group" style="margin-top: .6rem;">
                            <label class="frm-label" for="nombre_classes_{{ $i }}">Nombre de classes</label>
                            <input type="number" name="nombre_classes_{{ $i }}" id="nombre_classes_{{ $i }}"
                                   class="frm-input" placeholder="0" min="0"
                                   value="{{ ${'defaultNbClasses'.$i} }}">
                        </div>
                    </div>
                    @endfor
                </div>
                <div class="frm-hint">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Sélectionnez d'abord un compte pour charger les contacts disponibles.
                </div>
            </div>

            {{-- Section 4 : Validation (admin / rbo only) --}}
            @if(in_array(auth()->user()->role, ['admin', 'rbo']))
            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon violet">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Validation</div>
                        <div class="fp-section-sub">Réservé à l'administration</div>
                    </div>
                </div>

                <div class="admin-section" style="margin-top:0;">
                    <div class="admin-badge">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        RBO / Admin
                    </div>
                    <div class="frm-group" style="max-width: 320px;">
                        <label class="frm-label" for="effectif_valide">Effectif validé</label>
                        <input type="number" name="effectif_valide" id="effectif_valide"
                               class="frm-input {{ $errors->has('effectif_valide') ? 'is-invalid' : '' }}"
                               value="{{ $defaultEffectifValide }}" placeholder="Laissez vide si non validé" min="0">
                        <span class="frm-hint">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Laissez vide si l’effectif n’est pas encore validé.
                        </span>
                        @error('effectif_valide')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            @endif

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('effectifs.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ isset($effectif) ? 'Enregistrer les modifications' : 'Créer l\'effectif' }}
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

    function loadContacts(compteId) {
        const selects = document.querySelectorAll('.contact-select');
        if (!compteId) {
            selects.forEach(s => s.innerHTML = '<option value="">— Contact —</option>');
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                const base = '<option value="">— Contact —</option>';
                const opts = data.map(c =>
                    `<option value="${c.id}">${c.prenom} ${c.nom}${c.fonction ? ' · '+c.fonction : ''}</option>`
                ).join('');
                selects.forEach(s => { s.innerHTML = base + opts; });

                // Re‑select existing values in edit mode
                @isset($effectif)
                    @if($effectif->source_1 ?? false) document.querySelector('select[name="source_1"]').value = {{ $effectif->source_1 }}; @endif
                    @if($effectif->source_2 ?? false) document.querySelector('select[name="source_2"]').value = {{ $effectif->source_2 }}; @endif
                    @if($effectif->source_3 ?? false) document.querySelector('select[name="source_3"]').value = {{ $effectif->source_3 }}; @endif
                @endisset
            })
            .catch(() => {
                document.querySelectorAll('.contact-select').forEach(s => {
                    s.innerHTML = '<option value="">Erreur de chargement</option>';
                });
            });
    }

    compteSelect.addEventListener('change', function () {
        loadContacts(this.value);
    });

    // On page load – if a compte is already selected (edit mode), load contacts
    if (compteSelect.value) loadContacts(compteSelect.value);
})();
</script>
@endpush