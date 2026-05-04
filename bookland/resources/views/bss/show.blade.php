@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
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
    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1200px; margin: 0 auto; }
    @keyframes pageIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    /* ── Breadcrumb ───────────────────────────── */
    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    /* ── Header ───────────────────────────────── */
    .zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .zn-header-left h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; }
    .zn-header-left p { font-size: .82rem; color: var(--text-muted); margin-top: .3rem; }

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
    .btn-zn-sm { padding: .34rem .65rem; font-size: .74rem; }
    .btn-zn-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.25); }
    .btn-zn-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }

    /* ── Layout ───────────────────────────────── */
    .sv-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.5rem;
        align-items: start;
    }

    /* ── Cards ────────────────────────────────── */
    .zn-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .zn-card:last-child { margin-bottom: 0; }
    .zn-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
    }
    .zn-card-header-left { display: flex; align-items: center; gap: .55rem; }
    .zn-card-pip { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
    .pip-blue   { background: var(--blue);   box-shadow: 0 0 0 3px var(--blue-mid); }
    .pip-amber  { background: var(--amber);  box-shadow: 0 0 0 3px rgba(232,160,32,.2); }
    .pip-teal   { background: var(--teal);   box-shadow: 0 0 0 3px rgba(12,184,182,.15); }
    .pip-violet { background: var(--violet); box-shadow: 0 0 0 3px rgba(124,111,205,.2); }
    .zn-card-title { font-size: .86rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }
    .zn-card-body { padding: 1.5rem; }

    /* ── Status badge (hero) ──────────────────── */
    .sv-status-hero {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .32rem .85rem; border-radius: 20px;
        font-size: .78rem; font-weight: 700; letter-spacing: -.01em;
    }
    .sv-status-hero .dot { width: 6px; height: 6px; border-radius: 50%; }

    /* ── Info grid ────────────────────────────── */
    .sv-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0;
    }
    .sv-info-item {
        padding: .9rem 0;
        border-bottom: 1px solid var(--border);
        display: flex; flex-direction: column; gap: .2rem;
    }
    .sv-info-item:last-child { border-bottom: none; }
    .sv-info-label {
        font-size: .68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em;
        color: var(--text-hint);
    }
    .sv-info-value {
        font-size: .85rem; font-weight: 500;
        color: var(--text-primary);
    }
    .sv-info-value.muted { color: var(--text-muted); font-weight: 400; font-style: italic; }

    /* ── Inline badges ────────────────────────── */
    .dr-badge {
        display: inline-flex; align-items: center; gap: .28rem;
        padding: .22rem .65rem; border-radius: 20px;
        font-size: .7rem; font-weight: 700; white-space: nowrap;
    }
    .bd-blue    { background: var(--blue-light);   color: var(--blue-dark); }
    .bd-teal    { background: var(--teal-light);   color: #087a78; }
    .bd-green   { background: var(--green-light);  color: #1a8f51; }
    .bd-amber   { background: var(--amber-light);  color: #a96f10; }
    .bd-rose    { background: var(--rose-light);   color: #b83450; }
    .bd-violet  { background: var(--violet-light); color: #5a4db0; }
    .bd-none    { background: var(--bg-subtle);    color: var(--text-muted); }

    /* ── Products table ───────────────────────── */
    .zn-table { width: 100%; border-collapse: collapse; }
    .zn-table thead tr { border-bottom: 1px solid var(--border); }
    .zn-table th {
        padding: .75rem 1rem; font-size: .68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        color: var(--text-hint); text-align: left;
        background: var(--bg-base);
    }
    .zn-table td {
        padding: .9rem 1rem; font-size: .83rem; color: var(--text-secondary);
        border-bottom: 1px solid var(--border); vertical-align: middle;
    }
    .zn-table tbody tr:last-child td { border-bottom: none; }
    .zn-table tbody tr:hover { background: var(--bg-hover); }
    .zn-table td strong { color: var(--text-primary); font-weight: 600; }
    .zn-table .qty-cell {
        font-family: var(--font-mono); font-size: .82rem;
        color: var(--text-primary); font-weight: 500;
    }
    .tbl-actions { display: flex; align-items: center; gap: .4rem; flex-wrap: wrap; }

    /* ── Side panel ───────────────────────────── */
    .sv-side-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 1.5rem;
        position: sticky;
        top: 1.5rem;
    }
    .sv-side-card:last-child { margin-bottom: 0; }
    .sv-side-head {
        padding: .85rem 1.2rem;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(to bottom, #fafbff, #fff);
        display: flex; align-items: center; gap: .5rem;
    }
    .sv-side-head span { font-size: .76rem; font-weight: 700; color: var(--text-muted); letter-spacing: -.01em; }
    .sv-side-body { padding: 1.1rem 1.2rem; }

    /* Timeline */
    .sv-timeline { list-style: none; }
    .sv-tl-item {
        display: flex; gap: .75rem;
        padding-bottom: 1.1rem;
        position: relative;
    }
    .sv-tl-item:last-child { padding-bottom: 0; }
    .sv-tl-item:not(:last-child)::before {
        content: '';
        position: absolute; left: 10px; top: 22px;
        width: 1px; bottom: 0;
        background: var(--border);
    }
    .sv-tl-dot {
        width: 20px; height: 20px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid var(--border);
        background: var(--bg-card);
        margin-top: 1px;
    }
    .sv-tl-dot.done { background: var(--green); border-color: var(--green); }
    .sv-tl-dot.active { background: var(--blue); border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
    .sv-tl-dot.pending { background: var(--bg-subtle); border-color: var(--border-md); }
    .sv-tl-dot.refused { background: var(--rose); border-color: var(--rose); }
    .sv-tl-dot svg { flex-shrink: 0; }
    .sv-tl-content { flex: 1; }
    .sv-tl-label { font-size: .8rem; font-weight: 600; color: var(--text-primary); line-height: 1.3; }
    .sv-tl-label.muted { color: var(--text-muted); font-weight: 400; }
    .sv-tl-date { font-size: .72rem; color: var(--text-muted); margin-top: .1rem; }

    /* Quick info rows */
    .sv-quick-row {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: .5rem; padding: .55rem 0;
        border-bottom: 1px solid var(--border);
        font-size: .82rem;
    }
    .sv-quick-row:last-child { border-bottom: none; }
    .sv-quick-key { color: var(--text-muted); flex-shrink: 0; }
    .sv-quick-val { color: var(--text-primary); font-weight: 500; text-align: right; }

    /* ── Page footer actions ──────────────────── */
    .sv-page-actions {
        display: flex; align-items: center; gap: .75rem;
        flex-wrap: wrap; margin-top: 1.5rem;
    }
    .sv-page-actions-spacer { flex: 1; }

    /* ── Responsive ───────────────────────────── */
    @media (max-width: 960px) {
        .sv-layout { grid-template-columns: 1fr; }
        .sv-side-card { position: static; }
    }
    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .zn-header { flex-direction: column; }
        .sv-info-grid { grid-template-columns: 1fr; }
        .zn-table th, .zn-table td { padding: .65rem .75rem; }
        .sv-page-actions { flex-direction: column-reverse; }
        .btn-zn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('bss.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('bss.index') }}">BSS</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $bss->numero }}</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>{{ $bss->numero }}</h1>
            <p>Bon de sortie spécimens</p>
        </div>
        <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
            @php
                $statutMap = [
                    'soumis'  => ['label' => 'Soumis',  'class' => 'bd-blue',  'dot' => '#5b8dee'],
                    'valide'  => ['label' => 'Validé',  'class' => 'bd-teal',  'dot' => '#0cb8b6'],
                    'livre'   => ['label' => 'Livré',   'class' => 'bd-green', 'dot' => '#28c76f'],
                    'refuse'  => ['label' => 'Refusé',  'class' => 'bd-rose',  'dot' => '#e8506a'],
                ];
                $s = $statutMap[$bss->statut] ?? ['label' => ucfirst($bss->statut), 'class' => 'bd-none', 'dot' => '#9ba8c5'];
            @endphp
            <span class="sv-status-hero {{ $s['class'] }}">
                <span class="dot" style="background:{{ $s['dot'] }};"></span>
                {{ $s['label'] }}
            </span>
        </div>
    </div>

    <div class="sv-layout">

        {{-- ── Main column ────────────────────────────── --}}
        <div>

            {{-- Informations générales --}}
            <div class="zn-card">
                <div class="zn-card-header">
                    <div class="zn-card-header-left">
                        <span class="zn-card-pip pip-blue"></span>
                        <span class="zn-card-title">Informations générales</span>
                    </div>
                </div>
                <div class="zn-card-body">
                    <div class="sv-info-grid">

                        <div class="sv-info-item">
                            <span class="sv-info-label">Compte</span>
                            <span class="sv-info-value">{{ $bss->compte->etablissement }}</span>
                        </div>

                        <div class="sv-info-item">
                            <span class="sv-info-label">Contact</span>
                            <span class="sv-info-value">{{ $bss->contact->prenom }} {{ $bss->contact->nom }}</span>
                        </div>

                        <div class="sv-info-item">
                            <span class="sv-info-label">Délégué</span>
                            <span class="sv-info-value">{{ $bss->delegate->prenom }} {{ $bss->delegate->nom }}</span>
                        </div>

                        <div class="sv-info-item">
                            <span class="sv-info-label">Moyen de contact</span>
                            <span class="sv-info-value {{ $bss->moyen_contact ? '' : 'muted' }}">{{ $bss->moyen_contact ?? '—' }}</span>
                        </div>

                        <div class="sv-info-item">
                            <span class="sv-info-label">Récupéré par</span>
                            <span class="sv-info-value">
                                {{ $bss->recupere_par_nom }}
                                <span class="dr-badge bd-none" style="margin-left:.4rem;vertical-align:middle;">{{ $bss->recupere_par_type }}</span>
                            </span>
                        </div>

                        @if($bss->controle_document)
                        <div class="sv-info-item">
                            <span class="sv-info-label">Contrôle document</span>
                            <span class="sv-info-value">{{ $bss->controle_document }}</span>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Feedback / Refus (conditionnels) --}}
            @if($bss->motif_refus || $bss->feedback)
            <div class="zn-card">
                <div class="zn-card-header">
                    <div class="zn-card-header-left">
                        <span class="zn-card-pip {{ $bss->motif_refus ? 'pip-rose' : 'pip-teal' }}" style="{{ $bss->motif_refus ? 'background:var(--rose);box-shadow:0 0 0 3px rgba(232,80,106,.15)' : '' }}"></span>
                        <span class="zn-card-title">{{ $bss->motif_refus ? 'Motif de refus' : 'Feedback' }}</span>
                    </div>
                </div>
                <div class="zn-card-body">
                    @if($bss->motif_refus)
                        <p style="font-size:.85rem;color:var(--text-secondary);line-height:1.6;">{{ $bss->motif_refus }}</p>
                    @endif
                    @if($bss->feedback)
                        <div class="{{ $bss->motif_refus ? 'mt-3' : '' }}">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-hint);margin-bottom:.5rem;">
                                Feedback
                                @if($bss->date_feedback)
                                    — <span style="font-style:normal;font-weight:500;color:var(--text-muted);">{{ \Carbon\Carbon::parse($bss->date_feedback)->format('d/m/Y') }}</span>
                                @endif
                            </div>
                            <p style="font-size:.85rem;color:var(--text-secondary);line-height:1.6;">{{ $bss->feedback }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Produits --}}
            <div class="zn-card">
                <div class="zn-card-header">
                    <div class="zn-card-header-left">
                        <span class="zn-card-pip pip-amber"></span>
                        <span class="zn-card-title">Produits</span>
                    </div>
                    <span style="font-size:.75rem;color:var(--text-muted);font-weight:500;">{{ $bss->lignes->count() }} ligne{{ $bss->lignes->count() > 1 ? 's' : '' }}</span>
                </div>
                <div style="overflow-x:auto;">
                    <table class="zn-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Qté</th>
                                <th>Source</th>
                                <th>Statut ligne</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bss->lignes as $ligne)
                            <tr>
                                <td><strong>{{ $ligne->product->titre }}</strong></td>
                                <td class="qty-cell">{{ $ligne->quantity }}</td>
                                <td>{{ $ligne->source ?? '—' }}</td>
                                <td>
                                    @if($ligne->statut_ligne)
                                        <span class="dr-badge bd-none">{{ $ligne->statut_ligne }}</span>
                                    @else
                                        <span style="color:var(--text-hint);">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="tbl-actions">
                                        @if($ligne->statut_ligne === 'livree' && !$ligne->adoption)
                                            <a href="{{ route('adoptions.convert', $ligne) }}" class="btn-zn btn-zn-sm btn-zn-primary">
                                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                                                Convertir
                                            </a>
                                        @elseif($ligne->adoption)
                                            <span class="dr-badge bd-green">
                                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                                Adopté
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- /.main column --}}

        {{-- ── Side column ─────────────────────────────── --}}
        <aside>

            {{-- Dates --}}
            <div class="sv-side-card">
                <div class="sv-side-head">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>Dates</span>
                </div>
                <div class="sv-side-body">
                    <div class="sv-quick-row">
                        <span class="sv-quick-key">Création</span>
                        <span class="sv-quick-val">{{ \Carbon\Carbon::parse($bss->date_bss)->format('d/m/Y') }}</span>
                    </div>
                    <div class="sv-quick-row">
                        <span class="sv-quick-key">Livraison prévue</span>
                        <span class="sv-quick-val {{ $bss->date_livraison_prevue ? '' : 'muted' }}" style="{{ !$bss->date_livraison_prevue ? 'color:var(--text-hint)' : '' }}">
                            {{ $bss->date_livraison_prevue ? \Carbon\Carbon::parse($bss->date_livraison_prevue)->format('d/m/Y') : '—' }}
                        </span>
                    </div>
                    @if($bss->date_feedback)
                    <div class="sv-quick-row">
                        <span class="sv-quick-key">Feedback</span>
                        <span class="sv-quick-val">{{ \Carbon\Carbon::parse($bss->date_feedback)->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Statut timeline --}}
            <div class="sv-side-card">
                <div class="sv-side-head">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Progression</span>
                </div>
                <div class="sv-side-body">
                    @php
                        $statuts = ['soumis','valide','livre'];
                        $curIdx = array_search($bss->statut, $statuts);
                        $isRefused = $bss->statut === 'refuse';
                    @endphp
                    <ul class="sv-timeline">
                        @foreach($statuts as $i => $st)
                        @php
                            if ($isRefused && $i > 0) {
                                $dotClass = 'refused'; $lblClass = 'muted';
                            } elseif ($curIdx === false) {
                                $dotClass = 'pending'; $lblClass = 'muted';
                            } elseif ($i < $curIdx) {
                                $dotClass = 'done'; $lblClass = '';
                            } elseif ($i === $curIdx) {
                                $dotClass = 'active'; $lblClass = '';
                            } else {
                                $dotClass = 'pending'; $lblClass = 'muted';
                            }
                            $labels = ['soumis' => 'Soumis', 'valide' => 'Validé', 'livre' => 'Livré'];
                        @endphp
                        <li class="sv-tl-item">
                            <div class="sv-tl-dot {{ $dotClass }}">
                                @if($dotClass === 'done')
                                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                @elseif($dotClass === 'refused' && $i > 0)
                                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                @endif
                            </div>
                            <div class="sv-tl-content">
                                <div class="sv-tl-label {{ $lblClass }}">{{ $labels[$st] }}</div>
                            </div>
                        </li>
                        @endforeach

                        @if($isRefused)
                        <li class="sv-tl-item">
                            <div class="sv-tl-dot refused">
                                <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </div>
                            <div class="sv-tl-content">
                                <div class="sv-tl-label" style="color:var(--rose);">Refusé</div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Actions --}}
            <div class="sv-side-card">
                <div class="sv-side-head">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted)"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                    <span>Actions</span>
                </div>
                <div class="sv-side-body" style="display:flex;flex-direction:column;gap:.6rem;">

                    <a href="{{ route('bss.index') }}" class="btn-zn btn-zn-ghost" style="justify-content:center;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Retour à la liste
                    </a>

                    @if(auth()->user()->role === 'delegue' && $bss->delegate_id === auth()->id() && $bss->statut === 'valide' && !$bss->feedback)
                    <a href="{{ route('bss.edit', $bss) }}" class="btn-zn btn-zn-primary" style="justify-content:center;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                        Ajouter un feedback
                    </a>
                    @endif

                    @if($bss->statut === 'livre' && auth()->user()->role === 'delegue' && $bss->delegate_id === auth()->id())
                    <a href="{{ route('retours.create', $bss) }}" class="btn-zn btn-zn-warning" style="justify-content:center;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.8"/></svg>
                        Créer un retour
                    </a>
                    @endif

                </div>
            </div>

        </aside>{{-- /.side column --}}

    </div>{{-- /.sv-layout --}}

</div>{{-- /.zn-page --}}
@endsection