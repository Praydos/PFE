@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --bg-base:       #f5f6fa;
        --bg-card:       #ffffff;
        --bg-hover:      #f8f9fd;
        --border:        #e4e7f0;
        --border-md:     #d0d5e8;
        --blue:          #5b8dee;
        --blue-dark:     #3d6fd6;
        --blue-light:    #eef3fd;
        --blue-mid:      #dce8fb;
        --rose:          #e8506a;
        --rose-light:    #fef0f2;
        --text-primary:  #1a1f36;
        --text-secondary:#525f7f;
        --text-muted:    #9ba8c5;
        --text-hint:     #bcc5dc;
        --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
        --shadow-xs: 0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
        --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
        --shadow-blue: 0 4px 14px rgba(91,141,238,.35);
        --font: 'DM Sans', sans-serif;
        --ease: cubic-bezier(.4,0,.2,1);
        --t: .18s var(--ease);
    }

    body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

    /* ── Page ── */
    .form-page { padding: 2rem 2.5rem 3rem; max-width: 860px; margin: 0 auto; animation: pageIn .4s var(--ease) both; }
    @keyframes pageIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }

    /* ── Breadcrumb ── */
    .fp-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .fp-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .fp-bc a:hover { color: var(--blue); }
    .fp-bc-sep { color: var(--text-hint); }
    .fp-bc-cur { color: var(--text-secondary); }

    /* ── Header ── */
    .fp-header { margin-bottom: 2rem; }
    .fp-header h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -.03em; line-height: 1.15; }
    .fp-header p  { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

    /* ── Card ── */
    .fp-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }

    .fp-card-header {
        display: flex; align-items: center; gap: .65rem;
        padding: 1.1rem 1.75rem;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(to bottom, #fafbff, #fff);
    }
    .fp-card-pip { width: 8px; height: 8px; border-radius: 50%; background: var(--blue); flex-shrink: 0; }
    .fp-card-title { font-size: .82rem; font-weight: 700; color: var(--text-secondary); letter-spacing: -.01em; }

    .fp-card-body { padding: 1.75rem 1.75rem 1.5rem; display: flex; flex-direction: column; gap: 1.1rem; }

    /* ── Form groups ── */
    .frm-group { display: flex; flex-direction: column; gap: .38rem; }
    .frm-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }

    .frm-label { font-size: .77rem; font-weight: 600; color: var(--text-secondary); letter-spacing: -.01em; }
    .frm-label .req { color: var(--rose); margin-left: .18rem; }

    /* Input with icon */
    .frm-input-wrap { position: relative; }
    .frm-icon {
        position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
        color: var(--text-muted); pointer-events: none; display: flex;
    }

    .frm-input,
    .frm-select {
        width: 100%; padding: .6rem .88rem;
        border: 1px solid var(--border); border-radius: var(--r-sm);
        background: var(--bg-card); font: .83rem var(--font); color: var(--text-primary);
        box-shadow: var(--shadow-xs); outline: none;
        transition: border-color var(--t), box-shadow var(--t);
    }
    .frm-input-wrap .frm-input { padding-left: 2.35rem; }
    .frm-input:focus,
    .frm-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
    .frm-input.is-invalid,
    .frm-select.is-invalid { border-color: var(--rose); box-shadow: 0 0 0 3px rgba(232,80,106,.12); }

    /* Select arrow */
    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; pointer-events: none;
        position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        border: 4px solid transparent; border-top: 5px solid var(--text-muted); border-bottom: none;
    }
    .frm-select-wrap .frm-select { padding-right: 2.2rem; cursor: pointer; }

    /* Multi-select */
    select[multiple] { padding: .5rem; min-height: 110px; }
    select[multiple] option { padding: .3rem .5rem; border-radius: var(--r-xs); }
    select[multiple] option:checked { background: var(--blue-light); color: var(--blue-dark); }

    /* Error + hint */
    .frm-error {
        font-size: .72rem; color: var(--rose); font-weight: 500;
        display: flex; align-items: center; gap: .3rem;
    }
    .frm-hint {
        font-size: .72rem; color: var(--text-muted);
        display: flex; align-items: center; gap: .3rem; margin-top: .1rem;
    }

    /* ── Alert ── */
    .fp-alert {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: 1rem 1.25rem; border-radius: var(--r-lg);
        border: 1px solid; margin-bottom: 1.5rem; font-size: .82rem;
    }
    .fp-alert-danger { background: var(--rose-light); border-color: rgba(232,80,106,.25); color: #b83450; }
    .fp-alert ul { padding-left: 1.2rem; margin-top: .3rem; }
    .fp-alert li { margin-bottom: .15rem; }

    /* ── Footer ── */
    .fp-footer {
        display: flex; align-items: center; justify-content: flex-end; gap: .65rem;
        padding: 1.1rem 1.75rem;
        border-top: 1px solid var(--border);
        background: linear-gradient(to bottom, #fafbff, #fff);
    }

    /* ── Buttons ── */
    .btn-fp {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .55rem 1.1rem; border-radius: var(--r-sm);
        font: 600 .82rem/1 var(--font); letter-spacing: -.01em;
        cursor: pointer; border: 1px solid transparent;
        transition: all var(--t); text-decoration: none; white-space: nowrap;
    }
    .btn-fp-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue); }
    .btn-fp-primary:hover { background: var(--blue-dark); transform: translateY(-1px); }
    .btn-fp-ghost   { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-fp-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }

    /* ── Responsive ── */
    @media (max-width: 640px) {
        .form-page { padding: 1.25rem 1rem 2rem; }
        .frm-row   { grid-template-columns: 1fr; }
        .fp-footer { flex-direction: column; align-items: stretch; }
        .btn-fp    { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
@php
    $isEdit    = isset($zone);
    $action    = $isEdit ? route('zones.update', $zone) : route('zones.store');
    $selVille  = old('ville_id', $isEdit ? $zone->ville_id : ($selectedVilleId ?? ''));
    $selRbo    = old('rbo_id',   $isEdit ? $zone->rbo_id   : '');
    $selDels   = old('delegues', $isEdit ? $zone->delegates->pluck('id')->toArray() : []);
@endphp

<div class="form-page">

    {{-- Breadcrumb --}}
    <div class="fp-bc">
        <a href="{{ route('zones.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="fp-bc-sep">›</span>
        <a href="{{ route('zones.index') }}">Zones</a>
        <span class="fp-bc-sep">›</span>
        @if($isEdit)
            <a href="{{ route('zones.show', $zone) }}">{{ $zone->name }}</a>
            <span class="fp-bc-sep">›</span>
            <span class="fp-bc-cur">Modifier</span>
        @else
            <span class="fp-bc-cur">Nouvelle zone</span>
        @endif
    </div>

    {{-- Page header --}}
    <div class="fp-header">
        <h1>{{ $isEdit ? 'Modifier la zone' : 'Créer une zone' }}</h1>
        <p>{{ $isEdit ? 'Mettez à jour les informations de la zone.' : 'Remplissez les informations ci‑dessous pour ajouter une nouvelle zone.' }}</p>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="fp-alert fp-alert-danger">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:.1rem">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div>
            <strong style="display:block;margin-bottom:.3rem">Veuillez corriger les erreurs suivantes&nbsp;:</strong>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    </div>
    @endif

    {{-- Form card --}}
    <div class="fp-card">
        <div class="fp-card-header">
            <span class="fp-card-pip"></span>
            <span class="fp-card-title">Informations de la zone</span>
        </div>

        <form method="POST" action="{{ $action }}" novalidate>
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="fp-card-body">

                {{-- Nom de la zone --}}
                <div class="frm-group">
                    <label class="frm-label" for="name">
                        Nom de la zone <span class="req">*</span>
                    </label>
                    <div class="frm-input-wrap">
                        <span class="frm-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 2a7 7 0 0 0-7 7c0 4 7 13 7 13s7-9 7-13a7 7 0 0 0-7-7z"/>
                                <circle cx="12" cy="9" r="3"/>
                            </svg>
                        </span>
                        <input type="text" id="name" name="name"
                               class="frm-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $zone->name ?? '') }}"
                               placeholder="Ex : Centre-ville, Zone industrielle…"
                               required autocomplete="off">
                    </div>
                    @error('name')
                        <span class="frm-error">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Ville + RBO --}}
                <div class="frm-row">
                    <div class="frm-group">
                        <label class="frm-label" for="ville_id">
                            Ville <span class="req">*</span>
                        </label>
                        <div class="frm-select-wrap">
                            <select id="ville_id" name="ville_id"
                                    class="frm-select {{ $errors->has('ville_id') ? 'is-invalid' : '' }}"
                                    required>
                                <option value="">— Sélectionnez une ville —</option>
                                @foreach($villes as $ville)
                                    <option value="{{ $ville->id }}" @selected($selVille == $ville->id)>
                                        {{ $ville->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('ville_id')
                            <span class="frm-error">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="frm-group">
                        <label class="frm-label" for="rbo_id">
                            RBO <span class="req">*</span>
                        </label>
                        <div class="frm-select-wrap">
                            <select id="rbo_id" name="rbo_id"
                                    class="frm-select {{ $errors->has('rbo_id') ? 'is-invalid' : '' }}"
                                    required>
                                <option value="">— Sélectionnez d'abord une ville —</option>
                            </select>
                        </div>
                        @error('rbo_id')
                            <span class="frm-error">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Délégués --}}
                <div class="frm-group">
                    <label class="frm-label" for="delegues">Délégués</label>
                    <select id="delegues" name="delegues[]" class="frm-select" multiple size="4">
                        @foreach($delegues as $delegue)
                            <option value="{{ $delegue->id }}" @selected(in_array($delegue->id, $selDels))>
                                {{ $delegue->prenom }} {{ $delegue->nom }} ({{ $delegue->email }})
                            </option>
                        @endforeach
                    </select>
                    <p class="frm-hint">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Sélectionnez un ou plusieurs délégués (maintenez Ctrl ou Cmd).
                    </p>
                </div>

            </div>{{-- /.fp-card-body --}}

            <div class="fp-footer">
                <a href="{{ route('zones.index') }}" class="btn-fp btn-fp-ghost">
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
                    {{ $isEdit ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const villeEl = document.getElementById('ville_id');
    const rboEl   = document.getElementById('rbo_id');
    const initRbo = {{ $isEdit ? $zone->rbo_id : 'null' }};

    function loadRbos(villeId, done) {
        if (!villeId) {
            rboEl.innerHTML = "<option value=''>— Sélectionnez d'abord une ville —</option>";
            done?.();
            return;
        }
        rboEl.innerHTML = "<option value=''>Chargement…</option>";
        fetch(`/api/villes/${villeId}/rbos`)
            .then(r => r.json())
            .then(list => {
                rboEl.innerHTML = "<option value=''>— Sélectionnez un RBO —</option>";
                list.forEach(({ id, prenom, nom, email }) => rboEl.add(new Option(`${prenom} ${nom} (${email})`, id)));
                done?.();
            })
            .catch(() => { rboEl.innerHTML = "<option value=''>Erreur de chargement</option>"; });
    }

    function restoreRbo() {
        if (initRbo && rboEl.querySelector(`option[value="${initRbo}"]`)) rboEl.value = initRbo;
    }

    villeEl.addEventListener('change', e => loadRbos(e.target.value, restoreRbo));
    if (villeEl.value) loadRbos(villeEl.value, restoreRbo);
});
</script>
@endsection