@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL DESIGN SYSTEM CSS ===== */
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
    .frm-hint {
        font-size: .72rem;
        color: var(--text-muted);
        margin-top: .25rem;
        display: flex;
        align-items: center;
        gap: .3rem;
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
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('non-conformites.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('non-conformites.index') }}">Non‑conformités</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Nouvelle</span>
    </div>

    <div class="zn-header">
        <h1>Nouvelle non‑conformité</h1>
        <p>Enregistrez une nouvelle non‑conformité.</p>
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
        <form method="POST" action="{{ route('non-conformites.store') }}">
            @csrf

            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Informations générales</div>
                        <div class="fp-section-sub">Compte, catégorie, objet et description</div>
                    </div>
                </div>

                {{-- Compte & Contact --}}
                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="compte_id" id="compte_id" class="frm-select @error('compte_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionnez --</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->id }}" {{ old('compte_id') == $c->id ? 'selected' : '' }}>{{ $c->etablissement }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('compte_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="contact_id">Contact <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="contact_id" id="contact_id" class="frm-select @error('contact_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionnez un contact --</option>
                            </select>
                        </div>
                        @error('contact_id')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Catégorie & Sous‑catégorie --}}
                <div class="fp-row fp-row-2">
                    <div class="frm-group">
                        <label class="frm-label" for="categorie">Catégorie <span class="req">*</span></label>
                        <div class="frm-select-wrap">
                            <select name="categorie" id="categorie" class="frm-select @error('categorie') is-invalid @enderror" required>
                                <option value="">-- Sélectionnez --</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c }}" {{ old('categorie') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('categorie')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="sous_categorie">Sous‑catégorie</label>
                        <div class="frm-select-wrap">
                            <select name="sous_categorie" id="sous_categorie" class="frm-select @error('sous_categorie') is-invalid @enderror">
                                <option value="">-- Sélectionnez --</option>
                            </select>
                        </div>
                        @error('sous_categorie')<span class="frm-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Linked item selector (dynamic) --}}
                <div id="linked_item_container" class="frm-group" style="display: none;">
                    <label id="linked_item_label" class="frm-label"></label>
                    <div class="frm-select-wrap">
                        <select name="module_id" id="module_id" class="frm-select">
                            <option value="">-- Sélectionnez --</option>
                        </select>
                    </div>
                    <input type="hidden" name="module_type" id="module_type">
                </div>

                {{-- Evaluation field (only for AUDIT category) --}}
                <div id="evaluation_group" class="frm-group" style="display: none;">
                    <label class="frm-label" for="evaluation">Évaluation <span class="req">*</span></label>
                    <div class="frm-select-wrap">
                        <select name="evaluation" id="evaluation" class="frm-select @error('evaluation') is-invalid @enderror">
                            <option value="">-- Sélectionnez --</option>
                            <option value="observation" {{ old('evaluation') == 'observation' ? 'selected' : '' }}>Observation</option>
                            <option value="ameliorer" {{ old('evaluation') == 'ameliorer' ? 'selected' : '' }}>Améliorer</option>
                            <option value="MINEUR" {{ old('evaluation') == 'MINEUR' ? 'selected' : '' }}>Mineur</option>
                            <option value="MAJEUR" {{ old('evaluation') == 'MAJEUR' ? 'selected' : '' }}>Majeur</option>
                        </select>
                    </div>
                    <div class="frm-hint">Obligatoire pour la catégorie Audit & Contrôle Interne.</div>
                    @error('evaluation')<span class="frm-error">{{ $message }}</span>@enderror
                </div>

                {{-- Objet --}}
                <div class="frm-group">
                    <label class="frm-label" for="objet">Objet <span class="req">*</span></label>
                    <input type="text" name="objet" id="objet"
                           class="frm-input @error('objet') is-invalid @enderror"
                           value="{{ old('objet') }}" required>
                    @error('objet')<span class="frm-error">{{ $message }}</span>@enderror
                </div>

                {{-- Description --}}
                <div class="frm-group">
                    <label class="frm-label" for="description">Description <span class="req">*</span></label>
                    <textarea name="description" id="description" rows="3"
                              class="frm-textarea @error('description') is-invalid @enderror"
                              required>{{ old('description') }}</textarea>
                    @error('description')<span class="frm-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('non-conformites.index') }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
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

<script>
    // ========== PRESERVED ORIGINAL JAVASCRIPT (only class adjustments) ==========

    // 1. Load contacts when compte changes
    document.getElementById('compte_id')?.addEventListener('change', function() {
        let compteId = this.value;
        let contactSelect = document.getElementById('contact_id');
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez un contact --</option>';
                data.forEach(c => options += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`);
                contactSelect.innerHTML = options;
                @if(old('contact_id'))
                    contactSelect.value = '{{ old('contact_id') }}';
                @endif
            });
    });
    if (document.getElementById('compte_id')?.value) {
        document.getElementById('compte_id').dispatchEvent(new Event('change'));
    }

    // 2. Load sous‑catégories based on main category
    const sousCategoriesMap = @json($sousCategoriesMap);
    const categorieSelect = document.getElementById('categorie');
    const sousSelect = document.getElementById('sous_categorie');
    const evaluationGroup = document.getElementById('evaluation_group');

    function updateSousCategories() {
        let cat = categorieSelect.value;
        let options = '<option value="">-- Sélectionnez --</option>';
        if (sousCategoriesMap[cat]) {
            sousCategoriesMap[cat].forEach(sub => options += `<option value="${sub}">${sub}</option>`);
        }
        sousSelect.innerHTML = options;

        // Show/hide evaluation field (only for audit)
        if (cat === 'AUDIT & CONTROLE INTERNE') {
            evaluationGroup.style.display = 'block';
        } else {
            evaluationGroup.style.display = 'none';
            document.querySelector('select[name="evaluation"]').value = '';
        }
    }
    categorieSelect.addEventListener('change', updateSousCategories);
    updateSousCategories();

    // 3. Linked item selector (product, specimen, MP, exam, formation, event)
    const linkedContainer = document.getElementById('linked_item_container');
    const linkedLabel = document.getElementById('linked_item_label');
    const linkedSelect = document.getElementById('module_id');
    const moduleTypeInput = document.getElementById('module_type');

    const linkedOptions = @json($linkedItemsOptions);

    function updateLinkedSelector() {
        const sousCat = sousSelect.value;
        let moduleType = null;
        let label = '';

        if (sousCat === 'Produit') {
            moduleType = 'product';
            label = 'Sélectionnez un produit';
        } else if (sousCat === 'Spécimen') {
            moduleType = 'specimen';
            label = 'Sélectionnez un spécimen (BSS)';
        } else if (sousCat === 'Matériel Pédagogique') {
            moduleType = 'mp';
            label = 'Sélectionnez une livraison MP';
        } else if (sousCat === 'Examen') {
            moduleType = 'examen';
            label = 'Sélectionnez un examen';
        } else if (sousCat === 'Formation') {
            moduleType = 'formation';
            label = 'Sélectionnez une formation';
        } else if (sousCat === 'Événement') {
            moduleType = 'event';
            label = 'Sélectionnez un événement';
        }

        if (moduleType && linkedOptions[moduleType]) {
            linkedContainer.style.display = 'block';
            linkedLabel.textContent = label;
            moduleTypeInput.value = moduleType;

            let options = '<option value="">-- Sélectionnez --</option>';
            const selectedId = '{{ old('module_id') }}';
            linkedOptions[moduleType].forEach(item => {
                const selected = (selectedId == item.id) ? 'selected' : '';
                options += `<option value="${item.id}" ${selected}>${item.label}</option>`;
            });
            linkedSelect.innerHTML = options;
        } else {
            linkedContainer.style.display = 'none';
            moduleTypeInput.value = '';
            linkedSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
        }
    }

    sousSelect.addEventListener('change', updateLinkedSelector);
    updateLinkedSelector();
</script>
@endsection