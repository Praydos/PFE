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

    /* ── Form field styles (compatible with _form partial) ── */
    .frm-group {
        display: flex;
        flex-direction: column;
        gap: .38rem;
        margin-bottom: 1.25rem;
    }
    .frm-group:last-of-type {
        margin-bottom: 0;
    }
    .frm-label { font-size: .77rem; font-weight: 600; color: var(--text-secondary); letter-spacing: -.01em; }
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
    .frm-readonly {
        padding: .62rem .9rem;
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        font-size: .84rem;
        color: var(--text-secondary);
        font-family: var(--font);
        min-height: 2.4rem;
        display: flex;
        align-items: center;
    }
    .frm-checkbox {
        accent-color: var(--blue);
        width: 1rem;
        height: 1rem;
        cursor: pointer;
    }
    select[multiple] {
        padding: .5rem;
        min-height: 100px;
    }
    select[multiple] option {
        padding: .3rem .5rem;
        border-radius: var(--r-xs);
    }
    select[multiple] option:checked {
        background: var(--blue-light);
        color: var(--blue-dark);
    }

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
        <a href="{{ route('comptes.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('comptes.index') }}">Comptes</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Nouveau compte</span>
    </div>

    <div class="zn-header">
        <h1>Créer un compte client</h1>
        <p>Remplissez les informations ci‑dessous pour ajouter un nouvel établissement.</p>
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
        <form method="POST" action="{{ route('comptes.store') }}" novalidate>
            @csrf

            <div class="fp-section">
                <div class="fp-section-head">
                    <div class="fp-section-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M9 9h6M9 13h4"/>
                        </svg>
                    </div>
                    <div class="fp-section-meta">
                        <div class="fp-section-title">Informations du compte</div>
                        <div class="fp-section-sub">Identité, localisation et classification</div>
                    </div>
                </div>

                {{-- Include the existing _form partial (all fields) --}}
                @include('comptes._form')
            </div>

            <div class="fp-footer">
                <p class="fp-req-note"><span>*</span> Champs obligatoires</p>
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('comptes.index') }}" class="btn-zn btn-zn-ghost">
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
@endsection

@push('scripts')
<script>
    // ── JavaScript from the original _form (for filtering, etc.) ──
    // This script must be placed here because it uses DOM elements defined in the partial.
    // It is identical to the one originally in the partial, just moved to the main view.

    // Show/hide motif field based on status selection
    const statusSelect = document.getElementById('status');
    const motifGroup = document.getElementById('motif_fermeture_group');

    function toggleMotif() {
        if (motifGroup) motifGroup.style.display = statusSelect?.value === 'ferme' ? 'block' : 'none';
    }
    if (statusSelect) {
        statusSelect.addEventListener('change', toggleMotif);
        toggleMotif();
    }

    // Filter zones by selected ville
    const villeSelect = document.getElementById('ville_id');
    const zoneSelect = document.getElementById('zone_id');
    if (zoneSelect) {
        const zoneOptions = zoneSelect.querySelectorAll('option');
        function filterZones() {
            const villeId = villeSelect?.value;
            zoneOptions.forEach(opt => {
                if (opt.value === '') return;
                if (opt.dataset.ville == villeId || !villeId) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                    if (opt.selected) opt.selected = false;
                }
            });
            if (zoneSelect) zoneSelect.dispatchEvent(new Event('change'));
        }
        if (villeSelect) {
            villeSelect.addEventListener('change', filterZones);
            filterZones();
        }
    }

    // Filter quartiers by selected zone
    const quartierSelect = document.getElementById('quartier_id');
    if (quartierSelect && zoneSelect) {
        const quartierOptions = Array.from(quartierSelect.querySelectorAll('option'));
        function filterQuartiers() {
            const selectedZoneId = zoneSelect.value;
            quartierSelect.innerHTML = '<option value="">— Sélectionnez un quartier —</option>';
            quartierOptions.forEach(opt => {
                if (opt.value === '') return;
                if (!selectedZoneId || opt.dataset.zone == selectedZoneId) {
                    quartierSelect.appendChild(opt.cloneNode(true));
                }
            });
        }
        zoneSelect.addEventListener('change', filterQuartiers);
        filterQuartiers();
    }

    // Filter delegues by selected zone
    const delegateSelect = document.getElementById('delegue_id');
    if (delegateSelect && zoneSelect) {
        const delegateOptions = Array.from(delegateSelect.querySelectorAll('option'));
        function filterDelegates() {
            const selectedZoneId = zoneSelect.value;
            delegateSelect.innerHTML = '<option value="">— Sélectionnez un délégué —</option>';
            let firstValid = null;
            delegateOptions.forEach(opt => {
                if (opt.value === '') return;
                const zones = opt.dataset.zones ? JSON.parse(opt.dataset.zones) : [];
                if (!selectedZoneId || zones.includes(parseInt(selectedZoneId))) {
                    delegateSelect.appendChild(opt.cloneNode(true));
                    if (!firstValid) firstValid = opt.value;
                }
            });
            const currentSelected = delegateSelect.value;
            if (currentSelected && ![...delegateSelect.options].some(opt => opt.value === currentSelected)) {
                delegateSelect.value = '';
            }
        }
        zoneSelect.addEventListener('change', filterDelegates);
        filterDelegates();
    }
</script>
@endpush