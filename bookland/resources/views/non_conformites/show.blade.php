@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL DESIGN SYSTEM CSS (same as previous forms) ===== */
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
    .btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
    .btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
    .btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

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

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1rem;
    }
    .info-item {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 0.5rem;
        padding: 0.6rem 0;
        border-bottom: 1px solid var(--border);
    }
    .info-item.full {
        grid-column: 1 / -1;
    }
    .info-label {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted);
        min-width: 130px;
    }
    .info-value {
        font-size: .85rem;
        color: var(--text-primary);
        font-weight: 500;
    }
    .info-value a {
        color: var(--blue);
        text-decoration: none;
    }
    .info-value a:hover {
        text-decoration: underline;
    }

    .dr-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .22rem .65rem; border-radius: 20px;
        font-size: .7rem; font-weight: 600; white-space: nowrap;
    }
    .bd-blue { background: var(--blue-light); color: var(--blue); }
    .bd-teal { background: var(--teal-light); color: var(--teal); }
    .bd-green { background: var(--green-light); color: var(--green); }
    .bd-amber { background: var(--amber-light); color: var(--amber); }
    .bd-none { background: var(--bg-subtle); color: var(--text-muted); }

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

    hr {
        border: none;
        border-top: 1px solid var(--border);
        margin: 1rem 0;
    }

    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .info-grid { grid-template-columns: 1fr; }
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
        <span class="zn-bc-cur">{{ $non_conformite->numero }}</span>
    </div>

    <div class="zn-header">
        <h1>Non‑conformité {{ $non_conformite->numero }}</h1>
        <p>{{ $non_conformite->objet }} – 
            @if($non_conformite->statut === 'brouillon')
                <span class="dr-badge bd-amber">Brouillon</span>
            @elseif($non_conformite->statut === 'en_cours')
                <span class="dr-badge bd-blue">En cours</span>
            @elseif($non_conformite->statut === 'resolu')
                <span class="dr-badge bd-green">Résolu</span>
            @elseif($non_conformite->statut === 'ferme')
                <span class="dr-badge bd-none">Fermé</span>
            @else
                <span class="dr-badge bd-none">{{ ucfirst($non_conformite->statut) }}</span>
            @endif
        </p>
    </div>

    <div class="fp-card">

        {{-- Section 1 : Informations générales --}}
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
                    <div class="fp-section-sub">Détails de la non‑conformité</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Compte</span>
                    <span class="info-value">{{ $non_conformite->compte->etablissement }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Contact</span>
                    <span class="info-value">{{ $non_conformite->contact->prenom }} {{ $non_conformite->contact->nom }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date NC</span>
                    <span class="info-value">{{ $non_conformite->date_nc->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Catégorie</span>
                    <span class="info-value">{{ $non_conformite->categorie }} @if($non_conformite->sous_categorie) / {{ $non_conformite->sous_categorie }} @endif</span>
                </div>
                @if($non_conformite->evaluation)
                <div class="info-item">
                    <span class="info-label">Évaluation</span>
                    <span class="info-value">{{ $non_conformite->evaluation }}</span>
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Objet</span>
                    <span class="info-value">{{ $non_conformite->objet }}</span>
                </div>
                <div class="info-item full">
                    <span class="info-label">Description</span>
                    <span class="info-value">{{ $non_conformite->description }}</span>
                </div>
                @if($non_conformite->module_type && $non_conformite->module_id)
                <div class="info-item">
                    <span class="info-label">Élément lié</span>
                    <span class="info-value">
                        @php
                            $linked = $non_conformite->linkedModule;
                            $url = null;
                            $label = '';
                            switch($non_conformite->module_type) {
                                case 'product':
                                    $url = route('products.show', $non_conformite->module_id);
                                    $label = optional($linked)->titre ?? 'Produit #'.$non_conformite->module_id;
                                    break;
                                case 'specimen':
                                    $url = route('bss.show', $non_conformite->module_id);
                                    $label = optional($linked)->numero ?? 'BSS #'.$non_conformite->module_id;
                                    break;
                                case 'mp':
                                    $url = route('mp-deliveries.show', $non_conformite->module_id);
                                    $label = optional($linked)->numero ?? 'MP #'.$non_conformite->module_id;
                                    break;
                                case 'examen':
                                    $url = route('examens.show', $non_conformite->module_id);
                                    $label = optional($linked)->titre ?? 'Examen #'.$non_conformite->module_id;
                                    break;
                                case 'formation':
                                    $url = route('formations.show', $non_conformite->module_id);
                                    $label = optional($linked)->type ?? 'Formation #'.$non_conformite->module_id;
                                    break;
                                case 'event':
                                    $url = route('events.show', $non_conformite->module_id);
                                    $label = optional($linked)->type ?? 'Événement #'.$non_conformite->module_id;
                                    break;
                            }
                        @endphp
                        @if($url)
                            <a href="{{ $url }}">{{ $label }}</a>
                        @else
                            {{ $label }}
                        @endif
                    </span>
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Statut</span>
                    <span class="info-value">
                        @if($non_conformite->statut === 'brouillon')
                            <span class="dr-badge bd-amber">Brouillon</span>
                        @elseif($non_conformite->statut === 'en_cours')
                            <span class="dr-badge bd-blue">En cours</span>
                        @elseif($non_conformite->statut === 'resolu')
                            <span class="dr-badge bd-green">Résolu</span>
                        @elseif($non_conformite->statut === 'ferme')
                            <span class="dr-badge bd-none">Fermé</span>
                        @else
                            <span class="dr-badge bd-none">{{ ucfirst($non_conformite->statut) }}</span>
                        @endif
                    </span>
                </div>
                @if($non_conformite->date_cloture)
                <div class="info-item">
                    <span class="info-label">Date clôture</span>
                    <span class="info-value">{{ $non_conformite->date_cloture->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Section 2 : Efficacité (si renseignée) --}}
        @if($non_conformite->mode_controle || $non_conformite->description_resultat || $non_conformite->action_efficace !== null)
        <div class="fp-section">
            <div class="fp-section-head">
                <div class="fp-section-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2a7 7 0 0 0-7 7c0 4 7 13 7 13s7-9 7-13a7 7 0 0 0-7-7z"/>
                        <circle cx="12" cy="9" r="3"/>
                    </svg>
                </div>
                <div class="fp-section-meta">
                    <div class="fp-section-title">Efficacité</div>
                    <div class="fp-section-sub">Contrôle et résultats</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Mode de contrôle</span>
                    <span class="info-value">{{ $non_conformite->mode_controle ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Description résultat</span>
                    <span class="info-value">{{ $non_conformite->description_resultat ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Action efficace</span>
                    <span class="info-value">{{ $non_conformite->action_efficace === true ? 'Oui' : ($non_conformite->action_efficace === false ? 'Non' : '-') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Besoin autre action</span>
                    <span class="info-value">{{ $non_conformite->besoin_action_amelioration === true ? 'Oui' : ($non_conformite->besoin_action_amelioration === false ? 'Non' : '-') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Responsable efficacité</span>
                    <span class="info-value">{{ optional($non_conformite->responsableEfficacite)->prenom ?? '' }} {{ optional($non_conformite->responsableEfficacite)->nom ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date efficacité</span>
                    <span class="info-value">{{ $non_conformite->date_efficacite ? $non_conformite->date_efficacite->format('d/m/Y') : '-' }}</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Footer / actions --}}
        <div class="fp-footer">
            <div class="fp-footer-spacer"></div>
            <a href="{{ route('non-conformites.index') }}" class="btn-zn btn-zn-ghost">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
            @if($non_conformite->statut === 'brouillon' && auth()->user()->role === 'delegue' && $non_conformite->delegue_id === auth()->id())
                <a href="{{ route('non-conformites.edit', $non_conformite) }}" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif
            @if(in_array(auth()->user()->role, ['admin','rbo','delegue']))
                @if(!$non_conformite->responsable_efficacite_id)
                    <a href="{{ route('non-conformites.edit-efficacite', $non_conformite) }}" class="btn-zn btn-zn-primary">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Ajouter efficacité
                    </a>
                @else
                    <a href="{{ route('non-conformites.edit-efficacite', $non_conformite) }}" class="btn-zn btn-zn-primary">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                        </svg>
                        Modifier efficacité
                    </a>
                @endif
                @if(in_array(auth()->user()->role, ['admin','rbo']))
                    <a href="{{ route('non-conformites.edit', $non_conformite) }}" class="btn-zn btn-zn-ghost">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                        </svg>
                        Éditer
                    </a>
                @endif
            @endif
            @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('non-conformites.destroy', $non_conformite) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-zn btn-zn-danger" onclick="return confirm('Supprimer définitivement ?')">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14H6L5 6"/>
                        </svg>
                        Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection