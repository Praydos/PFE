@extends('layouts.app')
@push('styles')
    

{{-- ─────────────────────────────────────────────────────────────
     Google Fonts (Sora + DM Sans)
     Add this once in your layout <head> if not already present:
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
──────────────────────────────────────────────────────────────── --}}

<style>/* ============================================================
   TASK SHOW VIEW – STYLED WITH THE SAME DESIGN SYSTEM
   (Matches the action view’s look & feel)
   ============================================================ */

/* ── Reuse the same root variables ────────────────────────── */
:root {
    /* Backgrounds */
    --bg-base:       #f5f6fa;
    --bg-card:       #ffffff;
    --bg-hover:      #f8f9fd;
    --bg-subtle:     #f0f2f8;

    /* Borders */
    --border:        #e4e7f0;
    --border-md:     #d0d5e8;

    /* Colors – Brand */
    --blue:          #5b8dee;
    --blue-dark:     #3d6fd6;
    --blue-light:    #eef3fd;
    --blue-mid:      #dce8fb;

    /* Colors – Accents */
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

    /* Text */
    --text-primary:   #1a1f36;
    --text-secondary: #525f7f;
    --text-muted:     #9ba8c5;
    --text-hint:      #bcc5dc;

    /* Radius */
    --r-xs: 6px;
    --r-sm: 8px;
    --r-md: 12px;
    --r-lg: 16px;
    --r-xl: 20px;

    /* Shadows */
    --shadow-xs: 0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
    --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
    --shadow-md: 0 8px 24px rgba(31,45,80,.10), 0 2px 8px rgba(31,45,80,.06);
    --shadow-blue: 0 4px 14px rgba(91,141,238,.35);

    /* Typography */
    --font: 'DM Sans', sans-serif;
    --font-mono: 'DM Mono', monospace;

    /* Transitions */
    --ease: cubic-bezier(.4,0,.2,1);
    --t: .18s var(--ease);
}

/* ── Page ──────────────────────────────────────────────────── */
.tch-pg {
    padding: 2rem 2.5rem 3rem;
    animation: pageIn .4s var(--ease) both;
    max-width: 1400px;
    margin: 0 auto;
}

/* reuse pageIn animation from the action view */
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ───────────────────────────────────────────── */
.tch-bc {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .76rem;
    color: var(--text-muted);
    font-weight: 500;
    margin-bottom: 1.4rem;
}
.tch-bc a {
    color: var(--text-muted);
    text-decoration: none;
    transition: color var(--t);
}
.tch-bc a:hover { color: var(--blue); }
.tch-bc-sep { color: var(--text-hint); }
.tch-bc-cur { color: var(--text-secondary); }

/* ── Header ────────────────────────────────────────────────── */
.tch-hdr {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1.5rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}
.tch-hdr-l h1 {
    font-size: 1.65rem;
    font-weight: 700;
    letter-spacing: -.03em;
    color: var(--text-primary);
    line-height: 1.15;
    margin: 0;
}
.tch-hdr-l p {
    font-size: .83rem;
    color: var(--text-muted);
    margin-top: .3rem;
}
.tch-hdr-actions {
    display: flex;
    gap: .6rem;
    flex-wrap: wrap;
}

/* ── Buttons ───────────────────────────────────────────────── */
.btn-tch {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .56rem 1.1rem;
    border-radius: var(--r-sm);
    font-family: var(--font);
    font-size: .82rem;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all var(--t);
    text-decoration: none;
    white-space: nowrap;
    letter-spacing: -.01em;
    line-height: 1;
}
.btn-tch svg { flex-shrink: 0; }

.btn-tch-primary {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
    box-shadow: var(--shadow-blue);
}
.btn-tch-primary:hover {
    background: var(--blue-dark);
    border-color: var(--blue-dark);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(91,141,238,.4);
}

.btn-tch-ghost {
    background: var(--bg-card);
    color: var(--text-secondary);
    border-color: var(--border);
    box-shadow: var(--shadow-xs);
}
.btn-tch-ghost:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
    border-color: var(--border-md);
    text-decoration: none;
}

.btn-tch-success {
    background: var(--green);
    color: #fff;
    border-color: var(--green);
    box-shadow: 0 4px 14px rgba(40,199,111,.35);
}
.btn-tch-success:hover {
    background: #1fa85a;
    border-color: #1fa85a;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(40,199,111,.4);
}

.btn-tch-danger {
    background: var(--rose-light);
    color: var(--rose);
    border-color: rgba(232,80,106,.18);
}
.btn-tch-danger:hover {
    background: #fddde2;
    color: var(--rose);
    text-decoration: none;
}

/* ── Main Card ────────────────────────────────────────────── */
.tch-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.tch-card-hd {
    padding: 1.1rem 1.6rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .55rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}

.tch-card-title {
    font-size: .88rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -.01em;
    display: flex;
    align-items: center;
    gap: .55rem;
}

.tch-pip {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--blue);
    box-shadow: 0 0 0 3px var(--blue-mid);
    display: inline-block;
}

/* ── Badges ────────────────────────────────────────────────── */
.tch-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .22rem .65rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 600;
    white-space: nowrap;
}
.tch-badge-green {
    background: var(--green-light);
    color: var(--green);
}
.tch-badge-amber {
    background: var(--amber-light);
    color: var(--amber);
}

/* ── Info Grid ────────────────────────────────────────────── */
.tch-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    padding: 1.5rem 1.6rem;
}

.tch-info-row {
    display: flex;
    flex-direction: column;
    gap: .2rem;
    font-size: 0.84rem;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
    padding-bottom: 0.5rem;
}
.tch-info-row.full {
    grid-column: 1 / -1;
}

.tch-info-lbl {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.tch-info-val {
    display: flex;
    align-items: center;
    gap: .4rem;
    color: var(--text-secondary);
}
.tch-info-val.muted {
    color: var(--text-muted);
}
.tch-info-val .tch-icon {
    color: var(--text-hint);
}

/* ── Avatar ────────────────────────────────────────────────── */
.tch-avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--blue-light);
    color: var(--blue);
    font-size: .7rem;
    font-weight: 600;
    flex-shrink: 0;
}
.tch-avatar.sm {
    width: 22px;
    height: 22px;
    font-size: .6rem;
}

/* ── Chips ─────────────────────────────────────────────────── */
.tch-chips {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin-top: .2rem;
}
.tch-chip {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .15rem .6rem .15rem .3rem;
    border-radius: 20px;
    background: var(--bg-subtle);
    border: 1px solid var(--border);
    font-size: .75rem;
    color: var(--text-secondary);
}

/* ── Recurrence Section ───────────────────────────────────── */
.tch-recur {
    margin: 0 1.6rem 1.5rem;
    padding: 1rem 1.2rem;
    background: var(--bg-subtle);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
}

.tch-recur-title {
    font-size: .85rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: .5rem;
    margin-bottom: .75rem;
}

.tch-radio-group {
    display: flex;
    flex-direction: column;
    gap: .4rem;
    margin-bottom: 1rem;
}
.tch-radio-lbl {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .82rem;
    color: var(--text-secondary);
    cursor: pointer;
}
.tch-radio-lbl input[type="radio"] {
    accent-color: var(--blue);
    width: 15px;
    height: 15px;
}

/* ── Card Footer ──────────────────────────────────────────── */
.tch-card-ft {
    padding: 1.1rem 1.6rem;
    border-top: 1px solid var(--border);
    background: var(--bg-base);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .6rem;
    flex-wrap: wrap;
}

/* ── Utilities ────────────────────────────────────────────── */
.tch-icon {
    flex-shrink: 0;
}
.muted {
    color: var(--text-muted);
}

/* ── Responsive ────────────────────────────────────────────── */
@media (max-width: 768px) {
    .tch-pg {
        padding: 1.25rem 1rem 2rem;
    }
    .tch-info-grid {
        grid-template-columns: 1fr;
        padding: 1rem 1.2rem;
    }
    .tch-hdr {
        flex-direction: column;
        align-items: stretch;
    }
    .tch-hdr-actions {
        justify-content: flex-start;
    }
    .tch-card-hd {
        flex-direction: column;
        align-items: flex-start;
        gap: .5rem;
    }
    .tch-card-ft {
        flex-direction: column-reverse;
    }
    .btn-tch {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush
@section('content')
<div class="tch-pg">

    {{-- ── Breadcrumb ── --}}
    <nav class="tch-bc" aria-label="Fil d'Ariane">
        <a href="{{ route('taches.index') }}">
            <svg class="tch-icon" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="tch-bc-sep">›</span>
        <a href="{{ route('taches.index') }}">Tâches</a>
        <span class="tch-bc-sep">›</span>
        <span class="tch-bc-cur">{{ $tache->objet }}</span>
    </nav>

    {{-- ── Page header ── --}}
    <div class="tch-hdr">
        <div class="tch-hdr-l">
            <h1>{{ $tache->objet }}</h1>
            <p>Détail de la tâche · Créée le {{ $tache->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="tch-hdr-actions">
            <a href="{{ route('taches.index') }}" class="btn-tch btn-tch-ghost">
                <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>

            @if(!$tache->is_validated && (auth()->user()->role === 'delegue' && $tache->delegue_id === auth()->id()))
                <a href="{{ route('taches.edit', $tache) }}" class="btn-tch btn-tch-primary">
                    <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif

            @if(!$tache->is_validated && in_array(auth()->user()->role, ['admin','rbo']))
                <form method="POST" action="{{ route('taches.validate', $tache) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-tch btn-tch-success">
                        <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Valider
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- ── Main card ── --}}
    <div class="tch-card">

        {{-- Card header --}}
        <div class="tch-card-hd">
            <div class="tch-card-title">
                <span class="tch-pip"></span>
                Informations de la tâche
            </div>
            <span class="tch-badge {{ $tache->is_validated ? 'tch-badge-green' : 'tch-badge-amber' }}">
                @if($tache->is_validated)
                    <svg class="tch-icon" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    Validée
                @else
                    <svg class="tch-icon" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    En attente
                @endif
            </span>
        </div>

        {{-- Info grid --}}
        <div class="tch-info-grid">

            <div class="tch-info-row">
                <span class="tch-info-lbl">Objet</span>
                <span class="tch-info-val" style="font-weight:500">{{ $tache->objet }}</span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Description</span>
                @if($tache->description)
                    <span class="tch-info-val">{{ $tache->description }}</span>
                @else
                    <span class="tch-info-val muted">Aucune description</span>
                @endif
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Date de planification</span>
                <span class="tch-info-val">
                    <svg class="tch-icon" width="15" height="15" fill="none" stroke="#185FA5" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $tache->date_planification->format('d/m/Y') }}
                </span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Date de fin</span>
                <span class="tch-info-val">
                    @if($tache->date_fin)
                        <svg class="tch-icon" width="15" height="15" fill="none" stroke="#185FA5" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $tache->date_fin->format('d/m/Y') }}
                    @else
                        <span class="muted">—</span>
                    @endif
                </span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Lieu</span>
                <span class="tch-info-val">
                    @if($tache->lieu)
                        <svg class="tch-icon" width="15" height="15" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $tache->lieu }}
                    @else
                        <span class="muted">—</span>
                    @endif
                </span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Toute la journée</span>
                <span class="tch-info-val">{{ $tache->all_day ? 'Oui' : 'Non' }}</span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Statut</span>
                <span class="tch-info-val">
                    <span class="tch-badge {{ $tache->is_validated ? 'tch-badge-green' : 'tch-badge-amber' }}">
                        {{ $tache->is_validated ? 'Validée' : 'En attente' }}
                    </span>
                </span>
            </div>

            <div class="tch-info-row">
                <span class="tch-info-lbl">Délégué</span>
                <span class="tch-info-val">
                    <div class="tch-avatar" aria-hidden="true">
                        {{ strtoupper(substr($tache->delegate->prenom, 0, 1)) }}{{ strtoupper(substr($tache->delegate->nom, 0, 1)) }}
                    </div>
                    {{ $tache->delegate->prenom }} {{ $tache->delegate->nom }}
                </span>
            </div>

            <div class="tch-info-row full">
                <span class="tch-info-lbl">Contacts</span>
                <div class="tch-chips">
                    @forelse($tache->contactsList as $contact)
                        <span class="tch-chip">
                            <div class="tch-avatar sm" aria-hidden="true">
                                {{ strtoupper(substr($contact->prenom, 0, 1)) }}{{ strtoupper(substr($contact->nom, 0, 1)) }}
                            </div>
                            {{ $contact->prenom }} {{ $contact->nom }}
                        </span>
                    @empty
                        <span class="tch-info-val muted">Aucun contact</span>
                    @endforelse
                </div>
            </div>

        </div>{{-- /tch-info-grid --}}

        {{-- ── Recurring section ── --}}
        @if($tache->recurrence_frequence || $tache->parent_tache_id)
        <div class="tch-recur">
            <div class="tch-recur-title">
                <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <polyline points="23 4 23 10 17 10"/>
                    <polyline points="1 20 1 14 7 14"/>
                    <path d="M3.5 9a9 9 0 0114.5-3.5L23 10M1 14l5 4.5A9 9 0 0020.5 15"/>
                </svg>
                Tâche récurrente — Annuler la récurrence
            </div>

            <form method="POST" action="{{ route('taches.cancelRecurrence', $tache) }}"
                  onsubmit="return confirm('Confirmer la suppression des occurrences sélectionnées ?')">
                @csrf
                <div class="tch-radio-group">
                    <label class="tch-radio-lbl">
                        <input type="radio" name="scope" value="this_and_following" checked>
                        Supprimer <strong style="margin-left:.2rem">cette occurrence et les suivantes</strong>
                    </label>
                    <label class="tch-radio-lbl">
                        <input type="radio" name="scope" value="all">
                        Supprimer <strong style="margin-left:.2rem">toute la série</strong> (toutes les occurrences)
                    </label>
                </div>
                <button type="submit" class="btn-tch btn-tch-danger">
                    <svg class="tch-icon" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Annuler la récurrence
                </button>
            </form>
        </div>
        @endif

        {{-- ── Card footer ── --}}
        <div class="tch-card-ft">
            <a href="{{ route('taches.index') }}" class="btn-tch btn-tch-ghost">
                <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>

            @if(!$tache->is_validated && (auth()->user()->role === 'delegue' && $tache->delegue_id === auth()->id()))
                <a href="{{ route('taches.edit', $tache) }}" class="btn-tch btn-tch-primary">
                    <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif

            @if(!$tache->is_validated && in_array(auth()->user()->role, ['admin','rbo']))
                <form method="POST" action="{{ route('taches.validate', $tache) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-tch btn-tch-success">
                        <svg class="tch-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Valider
                    </button>
                </form>
            @endif
        </div>

    </div>{{-- /tch-card --}}

</div>{{-- /tch-pg --}}

@endsection

@push('scripts')
{{-- No specific script needed for show view --}}
@endpush