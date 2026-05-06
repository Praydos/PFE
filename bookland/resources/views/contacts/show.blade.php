{{-- resources/views/contacts/show.blade.php --}}
@extends('layouts.app')

@section('title', $contact->prenom . ' ' . $contact->nom . ' — Fiche Contact')

@push('styles')
<style>
    /* ── Design tokens ───────────────────────────────────────────── */
    :root {
        --c-bg:          #f5f6fa;
        --c-surface:     #ffffff;
        --c-border:      #e4e7ef;
        --c-text:        #1a1d2e;
        --c-muted:       #6b7280;
        --c-accent:      #4f46e5;
        --c-accent-lt:   #ede9fe;
        --c-green:       #059669;
        --c-green-lt:    #d1fae5;
        --c-amber:       #d97706;
        --c-amber-lt:    #fef3c7;
        --c-red:         #dc2626;
        --c-red-lt:      #fee2e2;
        --c-blue:        #2563eb;
        --c-blue-lt:     #dbeafe;
        --c-shadow:      0 1px 3px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
        --radius:        12px;
    }

    /* ── Layout ──────────────────────────────────────────────────── */
    .cs-page { background: var(--c-bg); min-height: 100vh; padding: 2rem 1rem 4rem; }

    .cs-wrap {
        max-width: 960px;
        margin: 0 auto;
        display: grid;
        gap: 1.5rem;
    }

    /* ── Breadcrumb ──────────────────────────────────────────────── */
    .cs-breadcrumb {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .85rem;
        color: var(--c-muted);
    }
    .cs-breadcrumb a { color: var(--c-accent); text-decoration: none; }
    .cs-breadcrumb a:hover { text-decoration: underline; }
    .cs-breadcrumb span { opacity: .5; }

    /* ── Card base ───────────────────────────────────────────────── */
    .cs-card {
        background: var(--c-surface);
        border: 1px solid var(--c-border);
        border-radius: var(--radius);
        box-shadow: var(--c-shadow);
        overflow: hidden;
    }

    .cs-card-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--c-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .cs-card-title {
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--c-muted);
    }
    .cs-card-body { padding: 1.5rem; }

    /* ── Hero / identity block ───────────────────────────────────── */
    .cs-hero {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .cs-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--c-accent-lt);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--c-accent);
        flex-shrink: 0;
        letter-spacing: -.02em;
        border: 3px solid var(--c-accent);
    }

    .cs-identity { flex: 1; min-width: 200px; }

    .cs-full-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--c-text);
        line-height: 1.2;
        margin-bottom: .25rem;
    }

    .cs-sub {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
        font-size: .875rem;
        color: var(--c-muted);
        margin-top: .5rem;
    }
    .cs-sub-item { display: flex; align-items: center; gap: .35rem; }

    .cs-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .2rem .65rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-accent   { background: var(--c-accent-lt); color: var(--c-accent); }
    .badge-green    { background: var(--c-green-lt);  color: var(--c-green);  }
    .badge-amber    { background: var(--c-amber-lt);  color: var(--c-amber);  }
    .badge-red      { background: var(--c-red-lt);    color: var(--c-red);    }
    .badge-blue     { background: var(--c-blue-lt);   color: var(--c-blue);   }
    .badge-gray     { background: #f3f4f6;             color: #374151;         }

    .cs-actions { display: flex; gap: .5rem; flex-wrap: wrap; margin-left: auto; }
    .cs-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .45rem 1rem;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
        border: 1.5px solid transparent;
        cursor: pointer;
        transition: all .15s;
    }
    .cs-btn-primary   { background: var(--c-accent); color: #fff; border-color: var(--c-accent); }
    .cs-btn-primary:hover { filter: brightness(1.1); }
    .cs-btn-secondary { background: #fff; color: var(--c-text); border-color: var(--c-border); }
    .cs-btn-secondary:hover { background: var(--c-bg); }
    .cs-btn-danger    { background: #fff; color: var(--c-red); border-color: #fca5a5; }
    .cs-btn-danger:hover { background: var(--c-red-lt); }

    /* ── Info grid ───────────────────────────────────────────────── */
    .cs-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.25rem;
    }
    .cs-info-item {}
    .cs-info-label {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: var(--c-muted);
        margin-bottom: .3rem;
    }
    .cs-info-value {
        font-size: .925rem;
        color: var(--c-text);
        font-weight: 500;
    }
    .cs-info-value a { color: var(--c-accent); text-decoration: none; }
    .cs-info-value a:hover { text-decoration: underline; }
    .cs-info-empty { color: var(--c-muted); font-style: italic; font-weight: 400; }

    .cs-tag-list { display: flex; flex-wrap: wrap; gap: .4rem; }

    /* ── Compte list ─────────────────────────────────────────────── */
    .cs-compte-list { display: grid; gap: .6rem; }
    .cs-compte-item {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .65rem 1rem;
        border: 1px solid var(--c-border);
        border-radius: 8px;
        background: var(--c-bg);
        font-size: .9rem;
    }
    .cs-compte-icon {
        width: 32px;
        height: 32px;
        background: var(--c-accent-lt);
        color: var(--c-accent);
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        flex-shrink: 0;
    }
    .cs-compte-name { font-weight: 600; color: var(--c-text); }
    .cs-compte-meta { font-size: .8rem; color: var(--c-muted); }

    /* ── Stats row ───────────────────────────────────────────────── */
    .cs-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 1rem;
    }
    .cs-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 1rem .5rem;
        border-radius: 10px;
        border: 1px solid var(--c-border);
    }
    .cs-stat-num {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: .35rem;
    }
    .cs-stat-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--c-muted); }

    /* ── Timeline ────────────────────────────────────────────────── */
    .cs-timeline { position: relative; padding-left: 1.75rem; }
    .cs-timeline::before {
        content: '';
        position: absolute;
        left: .6rem;
        top: .5rem;
        bottom: .5rem;
        width: 2px;
        background: var(--c-border);
        border-radius: 2px;
    }

    .cs-tl-item {
        position: relative;
        padding: 0 0 1.5rem 1.25rem;
    }
    .cs-tl-item:last-child { padding-bottom: 0; }

    .cs-tl-dot {
        position: absolute;
        left: -1.75rem;
        top: .25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid var(--c-surface);
        box-shadow: 0 0 0 2px var(--c-border);
    }
    .dot-present { background: var(--c-green);  box-shadow: 0 0 0 2px var(--c-green);  }
    .dot-accepte { background: var(--c-blue);   box-shadow: 0 0 0 2px var(--c-blue);   }
    .dot-decline { background: var(--c-red);    box-shadow: 0 0 0 2px var(--c-red);    }
    .dot-invite  { background: var(--c-amber);  box-shadow: 0 0 0 2px var(--c-amber);  }

    .cs-tl-header {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .cs-tl-date {
        font-size: .78rem;
        font-weight: 700;
        color: var(--c-muted);
        white-space: nowrap;
        padding-top: .15rem;
    }
    .cs-tl-type {
        font-weight: 700;
        font-size: .95rem;
        color: var(--c-text);
    }
    .cs-tl-meta {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-wrap: wrap;
        margin-top: .35rem;
        font-size: .82rem;
        color: var(--c-muted);
    }
    .cs-tl-meta-sep { opacity: .4; }

    .cs-empty {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--c-muted);
    }
    .cs-empty-icon { font-size: 2.5rem; margin-bottom: .75rem; opacity: .4; }
    .cs-empty-text { font-size: .925rem; }

    /* ── Responsive ──────────────────────────────────────────────── */
    @media (max-width: 600px) {
        .cs-hero { gap: 1rem; }
        .cs-avatar { width: 56px; height: 56px; font-size: 1.4rem; }
        .cs-full-name { font-size: 1.2rem; }
        .cs-actions { margin-left: 0; width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="cs-page">
<div class="cs-wrap">

    {{-- Breadcrumb --}}
    <nav class="cs-breadcrumb">
        <a href="{{ route('contacts.index') }}">Contacts</a>
        <span>/</span>
        <span>{{ $contact->civilite ? $contact->civilite . ' ' : '' }}{{ $contact->prenom }} {{ $contact->nom }}</span>
    </nav>

    {{-- ── Hero card ── --}}
    <div class="cs-card">
        <div class="cs-card-body">
            <div class="cs-hero">

                {{-- Avatar with initials --}}
                <div class="cs-avatar">
                    {{ strtoupper(substr($contact->prenom ?? $contact->nom, 0, 1)) }}{{ strtoupper(substr($contact->nom, 0, 1)) }}
                </div>

                {{-- Name + sub-info --}}
                <div class="cs-identity">
                    <div class="cs-full-name">
                        {{ $contact->civilite ? $contact->civilite . ' ' : '' }}{{ $contact->prenom }} {{ $contact->nom }}
                    </div>

                    <div class="cs-sub">
                        @if($contact->fonction)
                            <span class="cs-sub-item">
                                <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10" cy="7" r="4"/><path d="M2 17c0-3.3 3.6-6 8-6s8 2.7 8 6"/></svg>
                                {{ $contact->fonction }}
                            </span>
                        @endif
                        <span class="cs-sub-item">
                            <svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 2C6.7 2 4 4.7 4 8c0 5 6 10 6 10s6-5 6-10c0-3.3-2.7-6-6-6z"/><circle cx="10" cy="8" r="2"/></svg>
                            {{ $contact->ville->nom }}
                        </span>

                        @if($contact->status)
                            <span class="cs-badge badge-green">✓ Actif</span>
                        @else
                            <span class="cs-badge badge-gray">Inactif</span>
                        @endif
                    </div>
                </div>

                {{-- Action buttons --}}
                @if(Auth::user()->role === 'delegue' || Auth::user()->role === 'admin')
                <div class="cs-actions">
                    <a href="{{ route('contacts.edit', $contact) }}" class="cs-btn cs-btn-secondary">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4l5 5-9 9H2v-5L11 4z"/></svg>
                        Modifier
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ── Two-column layout: Info | Comptes ── --}}
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; align-items: start;">

        {{-- Contact details --}}
        <div class="cs-card">
            <div class="cs-card-header">
                <span class="cs-card-title">Informations</span>
            </div>
            <div class="cs-card-body">
                <div class="cs-info-grid">

                    <div class="cs-info-item">
                        <div class="cs-info-label">Email</div>
                        <div class="cs-info-value">
                            @if($contact->email)
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            @else
                                <span class="cs-info-empty">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="cs-info-item">
                        <div class="cs-info-label">Téléphone</div>
                        <div class="cs-info-value">
                            @if($contact->telephone)
                                <a href="tel:{{ $contact->telephone }}">{{ $contact->telephone }}</a>
                            @else
                                <span class="cs-info-empty">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="cs-info-item">
                        <div class="cs-info-label">Ville</div>
                        <div class="cs-info-value">{{ $contact->ville->nom }}</div>
                    </div>

                    <div class="cs-info-item">
                        <div class="cs-info-label">Civilité</div>
                        <div class="cs-info-value">
                            {{ $contact->civilite ?? '—' }}
                        </div>
                    </div>

                    @if(!empty($contact->categories))
                    <div class="cs-info-item" style="grid-column: 1 / -1;">
                        <div class="cs-info-label">Catégories</div>
                        <div class="cs-info-value">
                            <div class="cs-tag-list">
                                @foreach($contact->categories as $cat)
                                    <span class="cs-badge badge-accent">{{ $cat }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($contact->cycles))
                    <div class="cs-info-item" style="grid-column: 1 / -1;">
                        <div class="cs-info-label">Cycles</div>
                        <div class="cs-info-value">
                            <div class="cs-tag-list">
                                @foreach($contact->cycles as $cycle)
                                    <span class="cs-badge badge-blue">{{ $cycle }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Comptes associés --}}
        <div class="cs-card">
            <div class="cs-card-header">
                <span class="cs-card-title">Établissements associés</span>
                <span class="cs-badge badge-gray">{{ $contact->comptes->count() }}</span>
            </div>
            <div class="cs-card-body">
                @if($contact->comptes->isNotEmpty())
                    <div class="cs-compte-list">
                        @foreach($contact->comptes as $compte)
                        <div class="cs-compte-item">
                            <div class="cs-compte-icon">🏫</div>
                            <div>
                                <div class="cs-compte-name">{{ $compte->etablissement }}</div>
                                <div class="cs-compte-meta">{{ $compte->ville->nom ?? '—' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="cs-empty">
                        <div class="cs-empty-icon">🏫</div>
                        <div class="cs-empty-text">Aucun établissement associé.</div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ── Event history ── --}}
    <div class="cs-card">
        <div class="cs-card-header">
            <span class="cs-card-title">Historique des Événements</span>
            <span class="cs-badge badge-accent">{{ $eventStats['total'] }} événement{{ $eventStats['total'] > 1 ? 's' : '' }}</span>
        </div>

        @if($eventStats['total'] > 0)

        {{-- Stats row --}}
        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--c-border);">
            <div class="cs-stats-row">
                <div class="cs-stat" style="background:#f0fdf4; border-color:#bbf7d0;">
                    <div class="cs-stat-num" style="color:var(--c-green);">{{ $eventStats['present'] }}</div>
                    <div class="cs-stat-label">Présent</div>
                </div>
                <div class="cs-stat" style="background:#eff6ff; border-color:#bfdbfe;">
                    <div class="cs-stat-num" style="color:var(--c-blue);">{{ $eventStats['accepte'] }}</div>
                    <div class="cs-stat-label">Accepté</div>
                </div>
                <div class="cs-stat" style="background:#fff7ed; border-color:#fed7aa;">
                    <div class="cs-stat-num" style="color:var(--c-amber);">{{ $eventStats['invite'] }}</div>
                    <div class="cs-stat-label">Invité</div>
                </div>
                <div class="cs-stat" style="background:#fef2f2; border-color:#fecaca;">
                    <div class="cs-stat-num" style="color:var(--c-red);">{{ $eventStats['decline'] }}</div>
                    <div class="cs-stat-label">Décliné</div>
                </div>
                <div class="cs-stat" style="background:var(--c-accent-lt); border-color:#c4b5fd;">
                    <div class="cs-stat-num" style="color:var(--c-accent);">{{ $eventStats['rate'] }}%</div>
                    <div class="cs-stat-label">Taux présence</div>
                </div>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="cs-card-body">
            <div class="cs-timeline">
                @foreach($contact->events as $event)
                    @php
                        $statut   = $event->pivot->statut;
                        $dotClass = 'dot-' . $statut;

                        $badgeClass = match($statut) {
                            'present' => 'badge-green',
                            'accepte' => 'badge-blue',
                            'decline' => 'badge-red',
                            default   => 'badge-amber',
                        };
                    @endphp

                    <div class="cs-tl-item">
                        {{-- Timeline dot --}}
                        <div class="cs-tl-dot {{ $dotClass }}"></div>

                        {{-- Content --}}
                        <div class="cs-tl-header">
                            <div class="cs-tl-date">
                                {{ \Carbon\Carbon::parse($event->date_event)->locale('fr')->isoFormat('D MMM YYYY') }}
                            </div>
                            <span class="cs-badge {{ $badgeClass }}">{{ $statuts[$statut] }}</span>
                        </div>

                        <div class="cs-tl-type">{{ $event->type }}</div>

                        <div class="cs-tl-meta">
                            <span>📚 {{ $event->editeur }}</span>
                            <span class="cs-tl-meta-sep">·</span>
                            <span>📍 {{ $event->ville->nom }}</span>
                            <span class="cs-tl-meta-sep">·</span>
                            <span>🎓 {{ $event->anneeScolaire->libelle ?? $event->anneeScolaire->annee ?? 'N/A' }}</span>
                            @if($event->delegate)
                            <span class="cs-tl-meta-sep">·</span>
                            <span>👤 {{ $event->delegate->name }}</span>
                            @endif
                        </div>

                        @if(Auth::user()->role === 'delegue' || Auth::user()->role === 'admin')
                        <div style="margin-top:.5rem;">
                            <a href="{{ route('events.show', $event) }}" class="cs-btn cs-btn-secondary" style="padding:.3rem .75rem; font-size:.78rem;">
                                Voir l'événement →
                            </a>
                        </div>
                        @endif
                    </div>

                @endforeach
            </div>
        </div>

        @else
        {{-- Empty state --}}
        <div class="cs-card-body">
            <div class="cs-empty">
                <div class="cs-empty-icon">📅</div>
                <div class="cs-empty-text">Ce contact n'a encore participé à aucun événement.</div>
            </div>
        </div>
        @endif

    </div>{{-- end events card --}}

</div>{{-- end cs-wrap --}}
</div>{{-- end cs-page --}}
@endsection