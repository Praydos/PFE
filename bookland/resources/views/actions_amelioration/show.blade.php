@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:        #f5f6fa; --card:  #ffffff; --hover: #f8f9fd; --subtle: #f0f2f8;
    --border:    #e4e7f0; --border-2: #d0d5e8;
    --blue:      #5b8dee; --blue-d: #3d6fd6; --blue-l: #eef3fd; --blue-m: #dce8fb;
    --teal:      #0cb8b6; --teal-l: #e6faf9;
    --violet:    #7c6fcd; --violet-l: #f0eeff;
    --amber:     #e8a020; --amber-l: #fff8ec;
    --rose:      #e8506a; --rose-l: #fef0f2;
    --green:     #28c76f; --green-l: #e8fbf0;
    --t1: #1a1f36; --t2: #525f7f; --t3: #9ba8c5; --t4: #bcc5dc;
    --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
    --s1: 0 1px 3px rgba(31,45,80,.06); --s2: 0 2px 8px rgba(31,45,80,.08); --s3: 0 8px 24px rgba(31,45,80,.10);
    --sb: 0 4px 14px rgba(91,141,238,.32);
    --font: 'DM Sans', sans-serif; --mono: 'DM Mono', monospace;
    --ease: cubic-bezier(.4,0,.2,1); --t: .17s var(--ease);
}

body { font-family: var(--font); background: var(--bg); color: var(--t1); -webkit-font-smoothing: antialiased; }

/* ── Page ── */
.aa-page { padding: 2rem 2.5rem 3rem; animation: rise .4s var(--ease) both; max-width: 1000px; }
@keyframes rise { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

/* ── Breadcrumb ── */
.aa-bc { display: flex; align-items: center; gap: .4rem; font-size: .75rem; font-weight: 500; color: var(--t3); margin-bottom: 1.5rem; }
.aa-bc a { color: var(--t3); text-decoration: none; transition: color var(--t); }
.aa-bc a:hover { color: var(--blue); }
.aa-bc-sep { color: var(--t4); }

/* ── Header ── */
.aa-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.aa-header-left { display: flex; flex-direction: column; gap: .5rem; }
.aa-header-eyebrow { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--blue); }
.aa-header-left h1 { font-size: 1.55rem; font-weight: 800; letter-spacing: -.03em; color: var(--t1); line-height: 1.2; }
.aa-header-meta { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; margin-top: .1rem; }
.aa-num-badge { font-family: var(--mono); font-size: .75rem; color: var(--blue); font-weight: 600; background: var(--blue-l); padding: .2rem .7rem; border-radius: 20px; border: 1px solid var(--blue-m); }

/* ── Buttons ── */
.btn-aa {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .56rem 1.1rem; border-radius: var(--r-sm);
    font-family: var(--font); font-size: .82rem; font-weight: 600;
    cursor: pointer; border: 1px solid transparent;
    transition: all var(--t); text-decoration: none;
    white-space: nowrap; letter-spacing: -.01em; line-height: 1;
}
.btn-aa-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--sb); }
.btn-aa-primary:hover { background: var(--blue-d); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4); text-decoration: none; }
.btn-aa-ghost { background: var(--card); color: var(--t2); border-color: var(--border); box-shadow: var(--s1); }
.btn-aa-ghost:hover { background: var(--hover); color: var(--t1); border-color: var(--border-2); text-decoration: none; }
.btn-aa-teal { background: var(--teal-l); color: var(--teal); border-color: rgba(12,184,182,.2); }
.btn-aa-teal:hover { background: #d0f5f4; color: var(--teal); text-decoration: none; }

/* Status badge */
.aa-badge { display: inline-flex; align-items: center; gap: .25rem; padding: .2rem .65rem; border-radius: 20px; font-size: .69rem; font-weight: 600; border: 1px solid transparent; }
.badge-ouvert    { background: var(--blue-l);   color: var(--blue);   border-color: var(--blue-m); }
.badge-en_cours  { background: var(--amber-l);  color: var(--amber);  border-color: rgba(232,160,32,.2); }
.badge-cloture   { background: var(--green-l);  color: #1aaa5e;       border-color: rgba(40,199,111,.2); }
.badge-annule    { background: var(--subtle);   color: var(--t3);     border-color: var(--border); }
.badge-default   { background: var(--subtle);   color: var(--t3);     border-color: var(--border); }

/* ── Section card ── */
.aa-section {
    background: var(--card); border: 1px solid var(--border);
    border-radius: var(--r-xl); box-shadow: var(--s2);
    overflow: hidden; margin-bottom: 1.25rem;
    animation: rise .5s var(--ease) both;
}
.aa-section:nth-child(1) { animation-delay: .05s; }
.aa-section:nth-child(2) { animation-delay: .10s; }
.aa-section:nth-child(3) { animation-delay: .15s; }
.aa-section:nth-child(4) { animation-delay: .20s; }

.aa-section-hd {
    padding: .9rem 1.5rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: .55rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.aa-section-icon {
    width: 30px; height: 30px; border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.si-blue   { background: var(--blue-l);   color: var(--blue); }
.si-amber  { background: var(--amber-l);  color: var(--amber); }
.si-green  { background: var(--green-l);  color: #1aaa5e; }

.aa-section-title { font-size: .86rem; font-weight: 700; color: var(--t1); letter-spacing: -.01em; }

/* ── Info grid ── */
.aa-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 0;
}
.aa-info-item {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    border-right: 1px solid var(--border);
    transition: background var(--t);
}
.aa-info-item:hover { background: var(--hover); }
/* Remove right border on last column */
.aa-info-item:nth-child(2n) { border-right: none; }

.aa-info-label {
    font-size: .67rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: var(--t4); margin-bottom: .35rem;
    display: block;
}
.aa-info-value { font-size: .84rem; color: var(--t1); font-weight: 500; line-height: 1.45; }
.aa-info-value.muted { color: var(--t3); font-weight: 400; }
.aa-info-value.mono  { font-family: var(--mono); font-size: .8rem; }

/* Full-width item (for long text) */
.aa-info-item.full {
    grid-column: 1 / -1;
    border-right: none;
}

/* ── Footer actions ── */
.aa-footer { display: flex; align-items: center; gap: .75rem; padding-top: .5rem; flex-wrap: wrap; }

/* Responsive */
@media (max-width: 768px) {
    .aa-page { padding: 1.25rem 1rem 2rem; }
    .aa-header { flex-direction: column; gap: 1rem; }
    .aa-info-grid { grid-template-columns: 1fr; }
    .aa-info-item { border-right: none; }
    .aa-info-item:nth-child(2n) { border-right: none; }
}
</style>
@endpush

@section('content')
<div class="aa-page">

    {{-- Breadcrumb --}}
    <div class="aa-bc">
        <a href="{{ route('actions-amelioration.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <span class="aa-bc-sep">›</span>
        <a href="{{ route('actions-amelioration.index') }}">Actions d'amélioration</a>
        <span class="aa-bc-sep">›</span>
        <span style="color:var(--t2);font-weight:600;">#{{ $actions_amelioration->numero }}</span>
    </div>

    {{-- Header --}}
    <div class="aa-header">
        <div class="aa-header-left">
            <span class="aa-header-eyebrow">Action d'amélioration</span>
            <h1>{{ $actions_amelioration->type }}</h1>
            <div class="aa-header-meta">
                <span class="aa-num-badge">#{{ $actions_amelioration->numero }}</span>
                @php
                    $badgeClass = match($actions_amelioration->statut ?? 'default') {
                        'ouvert'   => 'badge-ouvert',
                        'en_cours' => 'badge-en_cours',
                        'cloture'  => 'badge-cloture',
                        'annule'   => 'badge-annule',
                        default    => 'badge-default',
                    };
                @endphp
                <span class="aa-badge {{ $badgeClass }}">{{ ucfirst($actions_amelioration->statut ?? '—') }}</span>
            </div>
        </div>
    </div>

    {{-- ── Section 1 : Informations générales ── --}}
    <div class="aa-section">
        <div class="aa-section-hd">
            <div class="aa-section-icon si-blue">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <span class="aa-section-title">Informations générales</span>
        </div>
        <div class="aa-info-grid">
            <div class="aa-info-item">
                <span class="aa-info-label">Compte</span>
                <span class="aa-info-value">{{ $actions_amelioration->compte->etablissement }}</span>
            </div>
            <div class="aa-info-item">
                <span class="aa-info-label">Émetteur</span>
                <span class="aa-info-value">{{ $actions_amelioration->emetteur->prenom }} {{ $actions_amelioration->emetteur->nom }}</span>
            </div>
            <div class="aa-info-item">
                <span class="aa-info-label">Date AA</span>
                <span class="aa-info-value mono">{{ $actions_amelioration->dateAA->format('d/m/Y') }}</span>
            </div>
            <div class="aa-info-item">
                <span class="aa-info-label">Type</span>
                <span class="aa-info-value">{{ $actions_amelioration->type }}</span>
            </div>
            <div class="aa-info-item full">
                <span class="aa-info-label">Origine</span>
                <span class="aa-info-value">{{ $actions_amelioration->origine }}</span>
            </div>
            @if($actions_amelioration->analyse_causes)
            <div class="aa-info-item full">
                <span class="aa-info-label">Analyse des causes</span>
                <span class="aa-info-value">{{ $actions_amelioration->analyse_causes }}</span>
            </div>
            @endif
            @if($actions_amelioration->sanctions)
            <div class="aa-info-item full">
                <span class="aa-info-label">Sanctions</span>
                <span class="aa-info-value">{{ $actions_amelioration->sanctions }}</span>
            </div>
            @endif
            @if($actions_amelioration->resultats_attendus)
            <div class="aa-info-item full">
                <span class="aa-info-label">Résultats attendus</span>
                <span class="aa-info-value">{{ $actions_amelioration->resultats_attendus }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- ── Section 2 : Suivi ── --}}
    @if($actions_amelioration->responsable_suivi_id)
    <div class="aa-section">
        <div class="aa-section-hd">
            <div class="aa-section-icon si-amber">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <span class="aa-section-title">Suivi de mise en œuvre</span>
        </div>
        <div class="aa-info-grid">
            <div class="aa-info-item">
                <span class="aa-info-label">Responsable suivi</span>
                <span class="aa-info-value">{{ $actions_amelioration->responsableSuivi->prenom }} {{ $actions_amelioration->responsableSuivi->nom }}</span>
            </div>
            <div class="aa-info-item">
                <span class="aa-info-label">Date de suivi</span>
                <span class="aa-info-value mono {{ $actions_amelioration->date_suivi ? '' : 'muted' }}">
                    {{ $actions_amelioration->date_suivi ? $actions_amelioration->date_suivi->format('d/m/Y') : '—' }}
                </span>
            </div>
            @if($actions_amelioration->verification_mise_en_oeuvre)
            <div class="aa-info-item full">
                <span class="aa-info-label">Vérification de mise en œuvre</span>
                <span class="aa-info-value">{{ $actions_amelioration->verification_mise_en_oeuvre }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ── Section 3 : Efficacité ── --}}
    @if($actions_amelioration->responsable_efficacite_id)
    <div class="aa-section">
        <div class="aa-section-hd">
            <div class="aa-section-icon si-green">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <span class="aa-section-title">Évaluation de l'efficacité</span>
        </div>
        <div class="aa-info-grid">
            <div class="aa-info-item">
                <span class="aa-info-label">Responsable efficacité</span>
                <span class="aa-info-value">{{ $actions_amelioration->responsableEfficacite->prenom }} {{ $actions_amelioration->responsableEfficacite->nom }}</span>
            </div>
            <div class="aa-info-item">
                <span class="aa-info-label">Date efficacité</span>
                <span class="aa-info-value mono {{ $actions_amelioration->date_efficacite ? '' : 'muted' }}">
                    {{ $actions_amelioration->date_efficacite ? $actions_amelioration->date_efficacite->format('d/m/Y') : '—' }}
                </span>
            </div>
            <div class="aa-info-item">
                <span class="aa-info-label">Mode de contrôle</span>
                <span class="aa-info-value {{ $actions_amelioration->mode_controle ? '' : 'muted' }}">
                    {{ $actions_amelioration->mode_controle ?? '—' }}
                </span>
            </div>
            <div class="aa-info-item">
                <span class="aa-info-label">Action efficace ?</span>
                @php $eff = $actions_amelioration->action_efficace; @endphp
                <span class="aa-info-value">
                    @if($eff === true)
                        <span style="display:inline-flex;align-items:center;gap:.3rem;color:#1aaa5e;font-weight:600;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Oui
                        </span>
                    @elseif($eff === false)
                        <span style="display:inline-flex;align-items:center;gap:.3rem;color:var(--rose);font-weight:600;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Non
                        </span>
                    @else
                        <span class="muted">—</span>
                    @endif
                </span>
            </div>
            <div class="aa-info-item">
                <span class="aa-info-label">Besoin d'autre action ?</span>
                @php $besoin = $actions_amelioration->besoin_action_amelioration; @endphp
                <span class="aa-info-value">
                    @if($besoin === true)
                        <span style="color:var(--amber);font-weight:600;">Oui</span>
                    @elseif($besoin === false)
                        <span style="color:var(--t3);">Non</span>
                    @else
                        <span class="muted">—</span>
                    @endif
                </span>
            </div>
            <div class="aa-info-item">
                <span class="aa-info-label">Date de clôture</span>
                <span class="aa-info-value mono {{ $actions_amelioration->date_cloture ? '' : 'muted' }}">
                    {{ $actions_amelioration->date_cloture ? $actions_amelioration->date_cloture->format('d/m/Y') : '—' }}
                </span>
            </div>
            @if($actions_amelioration->description_resultat)
            <div class="aa-info-item full">
                <span class="aa-info-label">Description du résultat</span>
                <span class="aa-info-value">{{ $actions_amelioration->description_resultat }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ── Footer actions ── --}}
    <div class="aa-footer">
        <a href="{{ route('actions-amelioration.index') }}" class="btn-aa btn-aa-ghost">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Retour à la liste
        </a>
        @if(auth()->user()->role !== 'rbo')
            @if(!$actions_amelioration->responsable_suivi_id)
            <a href="{{ route('actions-amelioration.edit-suivi', $actions_amelioration) }}" class="btn-aa btn-aa-primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Ajouter le suivi
            </a>
            @elseif(!$actions_amelioration->responsable_efficacite_id)
            <a href="{{ route('actions-amelioration.edit-efficacite', $actions_amelioration) }}" class="btn-aa btn-aa-teal">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Ajouter l'évaluation
            </a>
            @endif
        @endif
    </div>

</div>
@endsection