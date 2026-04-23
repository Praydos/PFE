@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== EXACT STYLE PROVIDED (same as previous views) ===== */
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

    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; }
    @keyframes pageIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    .zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .zn-header-left h1 { font-size: 1.55rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.2; margin: 0; }
    .zn-header-left p  { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

    .zn-card {
        max-width: 100%;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .zn-card-header {
        padding: 1.1rem 1.6rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: .55rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
    }
    .zn-card-title {
        font-size: .88rem; font-weight: 700;
        color: var(--text-primary); letter-spacing: -.01em;
        display: flex; align-items: center; gap: .55rem;
    }
    .title-pip {
        width: 7px; height: 7px; border-radius: 50%;
        background: var(--amber);
        box-shadow: 0 0 0 3px rgba(232,160,32,.2);
        flex-shrink: 0;
    }
    .zn-card-body { padding: 1.75rem 1.6rem; }

    .card-footer {
        padding: 1.1rem 1.6rem;
        border-top: 1px solid var(--border);
        background: var(--bg-base);
        display: flex; align-items: center;
        gap: .6rem;
        flex-wrap: wrap;
    }

    .btn-zn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .58rem 1.2rem; border-radius: var(--r-sm);
        font-family: var(--font); font-size: .83rem; font-weight: 600;
        cursor: pointer; border: 1px solid transparent;
        transition: all var(--t); text-decoration: none;
        white-space: nowrap; letter-spacing: -.01em; line-height: 1;
    }
    .btn-zn svg { flex-shrink: 0; }
    .btn-zn-primary {
        background: var(--blue); color: #fff;
        border-color: var(--blue); box-shadow: var(--shadow-blue);
    }
    .btn-zn-primary:hover {
        background: var(--blue-dark); color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(91,141,238,.4);
        text-decoration: none;
    }
    .btn-zn-ghost {
        background: var(--bg-card); color: var(--text-secondary);
        border-color: var(--border); box-shadow: var(--shadow-xs);
    }
    .btn-zn-ghost:hover {
        background: var(--bg-hover); color: var(--text-primary);
        border-color: var(--border-md); text-decoration: none;
    }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .info-item {
        font-size: 0.84rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.5rem;
    }
    .info-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-right: 0.5rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .zn-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    .zn-table th {
        padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
        color: var(--text-hint); text-align: left;
        background: var(--bg-base);
        border-bottom: 1px solid var(--border);
    }
    .zn-table td { padding: .95rem 1.2rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
    .zn-table tbody tr:hover { background: #f8f9fd; }

    .dr-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .22rem .65rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .bd-teal { background: var(--teal-light); color: var(--teal); }
    .bd-blue { background: var(--blue-light); color: var(--blue); }
    .bd-green { background: var(--green-light); color: var(--green); }
    .bd-amber { background: var(--amber-light); color: var(--amber); }
    .bd-none { background: var(--bg-subtle); color: var(--text-muted); }

    .frm-select-wrap { position: relative; display: inline-block; width: auto; }
    .frm-select-wrap .frm-select {
        padding: .38rem 2rem .38rem .9rem;
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        background: var(--bg-card);
        font-family: var(--font);
        font-size: .8rem;
        color: var(--text-primary);
        cursor: pointer;
    }
    .frm-select-wrap::after {
        content: '';
        position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted);
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .zn-table th, .zn-table td { padding: .75rem .9rem; }
        .card-footer { flex-direction: column; align-items: stretch; }
        .btn-zn { justify-content: center; }
        .frm-select-wrap { width: 100%; }
        .frm-select-wrap .frm-select { width: 100%; }
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
        <span class="zn-bc-cur">{{ $event->type }}</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <div class="zn-header-left">
            <h1>{{ $event->type }}</h1>
            <p>{{ $event->date_event->format('d/m/Y') }} - {{ $event->ville->nom }}</p>
        </div>
    </div>

    {{-- Card --}}
    <div class="zn-card">
        <div class="zn-card-header">
            <div class="zn-card-title">
                <span class="title-pip"></span>
                Détails de l'événement
            </div>
        </div>
        <div class="zn-card-body">
            {{-- Informations générales --}}
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Éditeur</span> {{ $event->editeur }}</div>
                <div class="info-item"><span class="info-label">Zone</span> {{ $event->zone->name }}</div>
                <div class="info-item"><span class="info-label">Délégué</span> {{ $event->delegate->prenom }} {{ $event->delegate->nom }}</div>
            </div>

            {{-- Contacts invités --}}
            <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Contacts invités</h3>
            <div class="table-responsive">
                <table class="zn-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Ville</th>
                            <th>Fonction</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->contacts as $contact)
                        <tr>
                            <td>{{ $contact->prenom }} {{ $contact->nom }}</td>
                            <td>{{ $contact->ville->nom }}</td>
                            <td>{{ $contact->fonction ?? '-' }}</td>
                            <td><span class="dr-badge bd-blue">{{ $statuts[$contact->pivot->statut] }}</span></td>
                            <td>
                                @if(auth()->user()->role === 'delegue' && $event->delegue_id === auth()->id())
                                    <form method="POST" action="{{ route('events.update-status', [$event, $contact]) }}" style="display:inline;">
                                        @csrf
                                        <div class="frm-select-wrap">
                                            <select name="statut" class="frm-select" onchange="this.form.submit()">
                                                @foreach($statuts as $key => $label)
                                                    <option value="{{ $key }}" {{ $contact->pivot->statut == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer actions --}}
        @if(auth()->user()->role === 'delegue' && $event->delegue_id === auth()->id())
        <div class="card-footer">
            <a href="{{ route('events.index') }}" class="btn-zn btn-zn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            <a href="{{ route('events.invite', $event) }}" class="btn-zn btn-zn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Inviter plus de contacts
            </a>
            <a href="{{ route('events.edit', $event) }}" class="btn-zn btn-zn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                </svg>
                Modifier
            </a>
            <a href="{{ route('events.statistics', $event) }}" class="btn-zn btn-zn-info" style="background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2);">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.66 0 3-4 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4-3-9s1.34-9 3-9"/>
                </svg>
                Statistiques
            </a>
        </div>
        @else
        <div class="card-footer" style="justify-content: flex-end;">
            <a href="{{ route('events.index') }}" class="btn-zn btn-zn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
        </div>
        @endif
    </div>
</div>
@endsection