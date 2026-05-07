{{-- resources/views/contacts/show.blade.php --}}
@extends('layouts.app')

@section('title', $contact->prenom . ' ' . $contact->nom . ' — Fiche Contact')

@push('styles')
<style>
    :root {
        --c-bg:        #f5f6fa;
        --c-surface:   #ffffff;
        --c-border:    #e4e7ef;
        --c-text:      #1a1d2e;
        --c-muted:     #6b7280;
        --c-accent:    #4f46e5;
        --c-accent-lt: #ede9fe;
        --c-green:     #059669;
        --c-green-lt:  #d1fae5;
        --c-amber:     #d97706;
        --c-amber-lt:  #fef3c7;
        --c-red:       #dc2626;
        --c-red-lt:    #fee2e2;
        --c-blue:      #2563eb;
        --c-blue-lt:   #dbeafe;
        --c-teal:      #0d9488;
        --c-teal-lt:   #ccfbf1;
        --c-shadow:    0 1px 3px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
        --radius:      12px;
    }

    .cs-page { background: var(--c-bg); min-height: 100vh; padding: 2rem 1rem 4rem; }
    .cs-wrap { max-width: 960px; margin: 0 auto; display: grid; gap: 1.5rem; }

    .cs-breadcrumb { display: flex; align-items: center; gap: .5rem; font-size: .85rem; color: var(--c-muted); }
    .cs-breadcrumb a { color: var(--c-accent); text-decoration: none; }
    .cs-breadcrumb a:hover { text-decoration: underline; }

    .cs-card { background: var(--c-surface); border: 1px solid var(--c-border); border-radius: var(--radius); box-shadow: var(--c-shadow); overflow: hidden; }
    .cs-card-header { padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--c-border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .cs-card-title { font-size: .75rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--c-muted); }
    .cs-card-body { padding: 1.5rem; }

    .cs-hero { display: flex; align-items: flex-start; gap: 1.5rem; flex-wrap: wrap; }
    .cs-avatar { width: 72px; height: 72px; border-radius: 50%; background: var(--c-accent-lt); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 700; color: var(--c-accent); flex-shrink: 0; border: 3px solid var(--c-accent); }
    .cs-identity { flex: 1; min-width: 200px; }
    .cs-full-name { font-size: 1.5rem; font-weight: 700; color: var(--c-text); line-height: 1.2; }
    .cs-sub { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; font-size: .875rem; color: var(--c-muted); margin-top: .5rem; }

    .cs-badge { display: inline-flex; align-items: center; gap: .3rem; padding: .2rem .65rem; border-radius: 999px; font-size: .75rem; font-weight: 600; white-space: nowrap; }
    .badge-accent { background: var(--c-accent-lt); color: var(--c-accent); }
    .badge-green  { background: var(--c-green-lt);  color: var(--c-green);  }
    .badge-amber  { background: var(--c-amber-lt);  color: var(--c-amber);  }
    .badge-red    { background: var(--c-red-lt);    color: var(--c-red);    }
    .badge-blue   { background: var(--c-blue-lt);   color: var(--c-blue);   }
    .badge-teal   { background: var(--c-teal-lt);   color: var(--c-teal);   }
    .badge-gray   { background: #f3f4f6;             color: #374151;         }

    .cs-actions { display: flex; gap: .5rem; flex-wrap: wrap; margin-left: auto; }
    .cs-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .45rem 1rem; border-radius: 8px; font-size: .85rem; font-weight: 600; text-decoration: none; border: 1.5px solid transparent; cursor: pointer; transition: all .15s; }
    .cs-btn-secondary { background: #fff; color: var(--c-text); border-color: var(--c-border); }
    .cs-btn-secondary:hover { background: var(--c-bg); }

    .cs-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem; }
    .cs-info-label { font-size: .7rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: var(--c-muted); margin-bottom: .3rem; }
    .cs-info-value { font-size: .925rem; color: var(--c-text); font-weight: 500; }
    .cs-info-value a { color: var(--c-accent); text-decoration: none; }
    .cs-info-empty { color: var(--c-muted); font-style: italic; font-weight: 400; }
    .cs-tag-list { display: flex; flex-wrap: wrap; gap: .4rem; }

    .cs-compte-list { display: grid; gap: .6rem; }
    .cs-compte-item { display: flex; align-items: center; gap: .75rem; padding: .65rem 1rem; border: 1px solid var(--c-border); border-radius: 8px; background: var(--c-bg); }
    .cs-compte-icon { width: 32px; height: 32px; background: var(--c-accent-lt); color: var(--c-accent); border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .cs-compte-name { font-weight: 600; font-size: .9rem; color: var(--c-text); }
    .cs-compte-meta { font-size: .8rem; color: var(--c-muted); }

    .cs-stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 1rem; }
    .cs-stat { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 1rem .5rem; border-radius: 10px; border: 1px solid var(--c-border); }
    .cs-stat-num { font-size: 1.75rem; font-weight: 800; line-height: 1; margin-bottom: .35rem; }
    .cs-stat-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--c-muted); }

    /* ── Timeline ─────────────────────────────────────────────────── */
    .cs-timeline { position: relative; padding-left: 1.75rem; }
    .cs-timeline::before { content: ''; position: absolute; left: .6rem; top: .5rem; bottom: .5rem; width: 2px; background: var(--c-border); border-radius: 2px; }
    .cs-tl-item { position: relative; padding: 0 0 1.5rem 1.25rem; }
    .cs-tl-item:last-child { padding-bottom: 0; }
    .cs-tl-dot { position: absolute; left: -1.75rem; top: .25rem; width: 12px; height: 12px; border-radius: 50%; border: 2px solid var(--c-surface); }

    .dot-present { background: var(--c-green);  box-shadow: 0 0 0 2px var(--c-green);  }
    .dot-accepte { background: var(--c-blue);   box-shadow: 0 0 0 2px var(--c-blue);   }
    .dot-decline { background: var(--c-red);    box-shadow: 0 0 0 2px var(--c-red);    }
    .dot-invite  { background: var(--c-amber);  box-shadow: 0 0 0 2px var(--c-amber);  }
    .dot-active  { background: var(--c-teal);   box-shadow: 0 0 0 2px var(--c-teal);   }
    .dot-closed  { background: #d1d5db;          box-shadow: 0 0 0 2px #d1d5db;          }

    .cs-tl-header { display: flex; align-items: flex-start; gap: .75rem; flex-wrap: wrap; }
    .cs-tl-date  { font-size: .78rem; font-weight: 700; color: var(--c-muted); white-space: nowrap; padding-top: .15rem; }
    .cs-tl-type  { font-weight: 700; font-size: .95rem; color: var(--c-text); margin-top: .15rem; }
    .cs-tl-meta  { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; margin-top: .35rem; font-size: .82rem; color: var(--c-muted); }
    .cs-tl-sep   { opacity: .4; }

    /* Period pill shown under each compte history entry */
    .cs-period { display: inline-flex; align-items: center; gap: .4rem; margin-top: .45rem; font-size: .78rem; color: var(--c-muted); background: var(--c-bg); border: 1px solid var(--c-border); border-radius: 6px; padding: .25rem .6rem; }
    .cs-period strong { color: var(--c-text); }

    .cs-empty { text-align: center; padding: 2.5rem 1rem; color: var(--c-muted); }
    .cs-empty-icon { font-size: 2.5rem; margin-bottom: .75rem; opacity: .4; }

    @media (max-width: 640px) { .two-col { grid-template-columns: 1fr !important; } }
</style>
@endpush

@section('content')
<div class="cs-page">
<div class="cs-wrap">

    {{-- Breadcrumb --}}
    <nav class="cs-breadcrumb">
        <a href="{{ route('contacts.index') }}">Contacts</a>
        <span style="opacity:.4">/</span>
        <span>{{ $contact->civilite ? $contact->civilite . ' ' : '' }}{{ $contact->prenom }} {{ $contact->nom }}</span>
    </nav>

    {{-- ── Hero ── --}}
    <div class="cs-card">
        <div class="cs-card-body">
            <div class="cs-hero">
                <div class="cs-avatar">
                    {{ strtoupper(substr($contact->prenom ?? $contact->nom, 0, 1)) }}{{ strtoupper(substr($contact->nom, 0, 1)) }}
                </div>
                <div class="cs-identity">
                    <div class="cs-full-name">
                        {{ $contact->civilite ? $contact->civilite . ' ' : '' }}{{ $contact->prenom }} {{ $contact->nom }}
                    </div>
                    <div class="cs-sub">
                        @if($contact->fonction)
                            <span>{{ $contact->fonction }}</span>
                            <span style="opacity:.3">·</span>
                        @endif
                        <span>📍 {{ $contact->ville->nom }}</span>
                        @if($contact->status)
                            <span class="cs-badge badge-green">✓ Actif</span>
                        @else
                            <span class="cs-badge badge-gray">Inactif</span>
                        @endif
                    </div>
                </div>
                @if(in_array(Auth::user()->role, ['delegue', 'admin']))
                <div class="cs-actions">
                    <a href="{{ route('contacts.edit', $contact) }}" class="cs-btn cs-btn-secondary">
                        ✏️ Modifier
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Info + Current comptes ── --}}
    <div class="two-col" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; align-items:start;">

        <div class="cs-card">
            <div class="cs-card-header"><span class="cs-card-title">Informations</span></div>
            <div class="cs-card-body">
                <div class="cs-info-grid">
                    <div>
                        <div class="cs-info-label">Email</div>
                        <div class="cs-info-value">
                            @if($contact->email) <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            @else <span class="cs-info-empty">—</span> @endif
                        </div>
                    </div>
                    <div>
                        <div class="cs-info-label">Téléphone</div>
                        <div class="cs-info-value">
                            @if($contact->telephone) <a href="tel:{{ $contact->telephone }}">{{ $contact->telephone }}</a>
                            @else <span class="cs-info-empty">—</span> @endif
                        </div>
                    </div>
                    <div>
                        <div class="cs-info-label">Ville</div>
                        <div class="cs-info-value">{{ $contact->ville->nom }}</div>
                    </div>
                    <div>
                        <div class="cs-info-label">Civilité</div>
                        <div class="cs-info-value">{{ $contact->civilite ?? '—' }}</div>
                    </div>
                    @if(!empty($contact->categories))
                    <div style="grid-column:1/-1;">
                        <div class="cs-info-label">Catégories</div>
                        <div class="cs-tag-list" style="margin-top:.3rem;">
                            @foreach($contact->categories as $cat)
                                <span class="cs-badge badge-accent">{{ $cat }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if(!empty($contact->cycles))
                    <div style="grid-column:1/-1;">
                        <div class="cs-info-label">Cycles</div>
                        <div class="cs-tag-list" style="margin-top:.3rem;">
                            @foreach($contact->cycles as $cycle)
                                <span class="cs-badge badge-blue">{{ $cycle }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="cs-card">
            <div class="cs-card-header">
                <span class="cs-card-title">Établissements actuels</span>
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
                            <span class="cs-badge badge-teal" style="margin-left:auto;">Actif</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="cs-empty">
                        <div class="cs-empty-icon">🏫</div>
                        <p style="font-size:.9rem;">Aucun établissement associé.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════
         COMPTE ASSIGNMENT HISTORY
    ══════════════════════════════════════════════════════════════ --}}
    <div class="cs-card">
        <div class="cs-card-header">
            <span class="cs-card-title">Historique des Affectations aux Établissements</span>
            <span class="cs-badge badge-teal">
                {{ $contact->compteHistory->count() }}
                entrée{{ $contact->compteHistory->count() > 1 ? 's' : '' }}
            </span>
        </div>

        @if($contact->compteHistory->isNotEmpty())
        <div class="cs-card-body">
            <div class="cs-timeline">
                @foreach($contact->compteHistory as $history)
                    @php $isActive = is_null($history->unassigned_at); @endphp

                    <div class="cs-tl-item">
                        <div class="cs-tl-dot {{ $isActive ? 'dot-active' : 'dot-closed' }}"></div>

                        <div class="cs-tl-header">
                            <div class="cs-tl-date">
                                {{ $history->date_debut->locale('fr')->isoFormat('D MMM YYYY') }}
                            </div>
                            @if($isActive)
                                <span class="cs-badge badge-teal">● Actif</span>
                            @else
                                <span class="cs-badge badge-gray">Terminé</span>
                            @endif
                        </div>

                        <div class="cs-tl-type">{{ $history->compte->etablissement ?? '—' }}</div>

                        <div class="cs-tl-meta">
                            <span>📍 {{ $history->compte->ville->nom ?? '—' }}</span>
                            <span class="cs-tl-sep">·</span>
                            <span>⏱ {{ $history->duration }}</span>
                        </div>

                        <div class="cs-period">
                            <strong>Début :</strong>
                            {{ $history->date_debut->locale('fr')->isoFormat('D MMM YYYY') }}
                            @if($isActive)
                                &nbsp;→&nbsp;<em>aujourd'hui</em>
                            @else
                                &nbsp;→&nbsp;<strong>Fin :</strong>
                                {{ $history->date_fin->locale('fr')->isoFormat('D MMM YYYY') }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="cs-card-body">
            <div class="cs-empty">
                <div class="cs-empty-icon">📋</div>
                <p style="font-size:.9rem;">Aucune affectation enregistrée pour ce contact.</p>
            </div>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         EVENT HISTORY
    ══════════════════════════════════════════════════════════════ --}}
    <div class="cs-card">
        <div class="cs-card-header">
            <span class="cs-card-title">Historique des Événements</span>
            <span class="cs-badge badge-accent">
                {{ $eventStats['total'] }} événement{{ $eventStats['total'] > 1 ? 's' : '' }}
            </span>
        </div>

        @if($eventStats['total'] > 0)
        <div style="padding:1rem 1.5rem; border-bottom:1px solid var(--c-border);">
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

        <div class="cs-card-body">
            <div class="cs-timeline">
                @foreach($contact->events as $event)
                    @php
                        $statut     = $event->pivot->statut;
                        $badgeClass = match($statut) {
                            'present' => 'badge-green',
                            'accepte' => 'badge-blue',
                            'decline' => 'badge-red',
                            default   => 'badge-amber',
                        };
                    @endphp
                    <div class="cs-tl-item">
                        <div class="cs-tl-dot dot-{{ $statut }}"></div>
                        <div class="cs-tl-header">
                            <div class="cs-tl-date">
                                {{ \Carbon\Carbon::parse($event->date_event)->locale('fr')->isoFormat('D MMM YYYY') }}
                            </div>
                            <span class="cs-badge {{ $badgeClass }}">{{ $statuts[$statut] }}</span>
                        </div>
                        <div class="cs-tl-type">{{ $event->type }}</div>
                        <div class="cs-tl-meta">
                            <span>📚 {{ $event->editeur }}</span>
                            <span class="cs-tl-sep">·</span>
                            <span>📍 {{ $event->ville->nom }}</span>
                            <span class="cs-tl-sep">·</span>
                            <span>🎓 {{ $event->anneeScolaire->libelle ?? $event->anneeScolaire->annee ?? 'N/A' }}</span>
                            @if($event->delegate)
                                <span class="cs-tl-sep">·</span>
                                <span>👤 {{ $event->delegate->name }}</span>
                            @endif
                        </div>
                        @if(in_array(Auth::user()->role, ['delegue', 'admin']))
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
        <div class="cs-card-body">
            <div class="cs-empty">
                <div class="cs-empty-icon">📅</div>
                <p style="font-size:.9rem;">Ce contact n'a encore participé à aucun événement.</p>
            </div>
        </div>
        @endif
    </div>

</div>
</div>
@endsection