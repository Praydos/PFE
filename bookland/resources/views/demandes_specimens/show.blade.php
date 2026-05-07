@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
:root{
    --bg:#f4f7fb;
    --card:#ffffff;
    --card-2:#fafcff;
    --line:#e7ecf5;

    --text:#1b2432;
    --text-soft:#6b7280;
    --text-muted:#9ca3af;

    --blue:#4f7cff;
    --blue-soft:#edf3ff;

    --green:#22c55e;
    --green-soft:#ecfdf3;

    --amber:#f59e0b;
    --amber-soft:#fff7e8;

    --red:#ef4444;
    --red-soft:#fef2f2;

    --purple:#7c3aed;
    --purple-soft:#f5f3ff;

    --shadow-sm:0 2px 8px rgba(15,23,42,.05);
    --shadow-md:0 10px 30px rgba(15,23,42,.08);

    --radius-sm:10px;
    --radius-md:16px;
    --radius-lg:22px;

    --transition:.2s cubic-bezier(.4,0,.2,1);
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:var(--bg);
    font-family:'DM Sans',sans-serif;
    color:var(--text);
}

.ds-page{
    max-width:1450px;
    margin:auto;
    padding:2rem;
    animation:fadeIn .35s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(8px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* ================= HEADER ================= */

.ds-topbar{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:1rem;
    margin-bottom:1.8rem;
    flex-wrap:wrap;
}

.ds-breadcrumb{
    display:flex;
    align-items:center;
    gap:.45rem;
    font-size:.78rem;
    color:var(--text-muted);
    margin-bottom:.9rem;
}

.ds-breadcrumb a{
    color:var(--text-muted);
    text-decoration:none;
    transition:var(--transition);
}

.ds-breadcrumb a:hover{
    color:var(--blue);
}

.ds-title{
    display:flex;
    align-items:center;
    gap:1rem;
}

.ds-title-icon{
    width:58px;
    height:58px;
    border-radius:18px;
    background:linear-gradient(135deg,#4f7cff,#6ea0ff);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 12px 28px rgba(79,124,255,.28);
    flex-shrink:0;
}

.ds-title h1{
    font-size:1.9rem;
    font-weight:700;
    letter-spacing:-.03em;
}

.ds-title p{
    margin-top:.25rem;
    color:var(--text-soft);
    font-size:.88rem;
}

/* ================= BUTTONS ================= */

.ds-actions{
    display:flex;
    gap:.7rem;
    flex-wrap:wrap;
}

.ds-btn{
    border:none;
    outline:none;
    cursor:pointer;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:.55rem;
    padding:.82rem 1.15rem;
    border-radius:14px;
    font-weight:600;
    font-size:.84rem;
    transition:var(--transition);
}

.ds-btn svg{
    flex-shrink:0;
}

.ds-btn-primary{
    background:var(--blue);
    color:#fff;
    box-shadow:0 10px 20px rgba(79,124,255,.25);
}

.ds-btn-primary:hover{
    transform:translateY(-2px);
    background:#3e6df7;
}

.ds-btn-warning{
    background:var(--amber-soft);
    color:var(--amber);
}

.ds-btn-warning:hover{
    background:#ffefcc;
}

.ds-btn-danger{
    background:var(--red-soft);
    color:var(--red);
}

.ds-btn-danger:hover{
    background:#ffe5e5;
}

.ds-btn-ghost{
    background:#fff;
    border:1px solid var(--line);
    color:var(--text-soft);
}

.ds-btn-ghost:hover{
    background:#fafcff;
}

/* ================= LAYOUT ================= */

.ds-layout{
    display:grid;
    grid-template-columns:340px 1fr;
    gap:1.5rem;
}

@media(max-width:1100px){
    .ds-layout{
        grid-template-columns:1fr;
    }
}

/* ================= CARDS ================= */

.ds-card{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:var(--radius-lg);
    box-shadow:var(--shadow-sm);
    overflow:hidden;
}

.ds-card-header{
    padding:1.2rem 1.4rem;
    border-bottom:1px solid var(--line);
    display:flex;
    align-items:center;
    justify-content:space-between;
    background:linear-gradient(to bottom,#fbfcff,#fff);
}

.ds-card-title{
    display:flex;
    align-items:center;
    gap:.7rem;
    font-weight:700;
    font-size:.92rem;
}

.ds-card-title-dot{
    width:10px;
    height:10px;
    border-radius:50%;
    background:var(--blue);
    box-shadow:0 0 0 5px rgba(79,124,255,.12);
}

.ds-card-body{
    padding:1.4rem;
}

/* ================= STATUS ================= */

.ds-status{
    display:inline-flex;
    align-items:center;
    gap:.45rem;
    padding:.5rem .9rem;
    border-radius:999px;
    font-size:.75rem;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.04em;
}

.ds-status-demande{
    background:var(--amber-soft);
    color:var(--amber);
}

.ds-status-valide{
    background:var(--green-soft);
    color:var(--green);
}

.ds-status-decline{
    background:var(--red-soft);
    color:var(--red);
}

.ds-status-annule{
    background:#f3f4f6;
    color:#6b7280;
}

/* ================= INFO ================= */

.ds-meta{
    display:flex;
    flex-direction:column;
    gap:1rem;
}

.ds-meta-item{
    padding-bottom:1rem;
    border-bottom:1px dashed var(--line);
}

.ds-meta-item:last-child{
    border-bottom:none;
    padding-bottom:0;
}

.ds-meta-label{
    font-size:.72rem;
    font-weight:700;
    color:var(--text-muted);
    text-transform:uppercase;
    letter-spacing:.06em;
    margin-bottom:.45rem;
}

.ds-meta-value{
    font-size:.93rem;
    color:var(--text);
    font-weight:600;
    line-height:1.5;
}

.ds-meta-sub{
    margin-top:.2rem;
    font-size:.78rem;
    color:var(--text-soft);
}

/* ================= DESCRIPTION ================= */

.ds-description{
    background:var(--card-2);
    border:1px solid var(--line);
    border-radius:18px;
    padding:1rem 1.1rem;
    line-height:1.7;
    color:var(--text-soft);
    font-size:.9rem;
}

/* ================= TABLE ================= */

.ds-table-wrap{
    overflow:auto;
}

.ds-table{
    width:100%;
    border-collapse:collapse;
}

.ds-table thead th{
    text-align:left;
    padding:1rem;
    font-size:.73rem;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:var(--text-muted);
    background:#f9fbff;
    border-bottom:1px solid var(--line);
    white-space:nowrap;
}

.ds-table tbody td{
    padding:1rem;
    border-bottom:1px solid var(--line);
    vertical-align:middle;
}

.ds-table tbody tr:hover{
    background:#fafcff;
}

.ds-product{
    display:flex;
    align-items:flex-start;
    gap:.9rem;
}

.ds-product-cover{
    width:48px;
    height:48px;
    border-radius:14px;
    background:var(--blue-soft);
    color:var(--blue);
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}

.ds-product-title{
    font-weight:700;
    font-size:.9rem;
    color:var(--text);
}

.ds-product-isbn{
    margin-top:.2rem;
    font-size:.76rem;
    color:var(--text-muted);
    font-family:'DM Mono',monospace;
}

.ds-qty{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:38px;
    height:38px;
    border-radius:12px;
    background:var(--blue-soft);
    color:var(--blue);
    font-weight:700;
}

/* ================= BSS LINKS ================= */

.ds-link-card{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:1rem;
    padding:1rem 1.1rem;
    border:1px solid var(--line);
    border-radius:18px;
    background:#fcfdff;
    margin-bottom:.9rem;
}

.ds-link-card:last-child{
    margin-bottom:0;
}

.ds-link-card-left{
    display:flex;
    align-items:center;
    gap:.9rem;
}

.ds-link-icon{
    width:46px;
    height:46px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.ds-link-icon.blue{
    background:var(--blue-soft);
    color:var(--blue);
}

.ds-link-icon.purple{
    background:var(--purple-soft);
    color:var(--purple);
}

.ds-link-label{
    font-size:.74rem;
    font-weight:700;
    color:var(--text-muted);
    text-transform:uppercase;
    letter-spacing:.06em;
}

.ds-link-value{
    margin-top:.18rem;
    font-size:.92rem;
    font-weight:700;
    color:var(--text);
}

.ds-link-btn{
    text-decoration:none;
    font-size:.8rem;
    font-weight:700;
    color:var(--blue);
}

.ds-link-btn:hover{
    text-decoration:underline;
}

/* ================= RESPONSIVE ================= */

@media(max-width:768px){

    .ds-page{
        padding:1rem;
    }

    .ds-title{
        align-items:flex-start;
    }

    .ds-title h1{
        font-size:1.45rem;
    }

    .ds-actions{
        width:100%;
    }

    .ds-btn{
        flex:1;
        justify-content:center;
    }
}
</style>
@endpush

@section('content')

<div class="ds-page">

    {{-- Breadcrumb --}}
    <div class="ds-breadcrumb">
        <a href="{{ route('demandes-specimens.index') }}">Demandes spéciales</a>
        <span>›</span>
        <span>#{{ $demandes_specimen->id }}</span>
    </div>

    {{-- Header --}}
    <div class="ds-topbar">

        <div>
            <div class="ds-title">
                <div class="ds-title-icon">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                </div>

                <div>
                    <h1>Demande spéciale #{{ $demandes_specimen->id }}</h1>

                    <p>
                        {{ ucfirst($demandes_specimen->type) }}
                        •
                        {{ $demandes_specimen->date_demande->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="ds-actions">

            <a href="{{ route('demandes-specimens.index') }}" class="ds-btn ds-btn-ghost">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>

            @if($demandes_specimen->statut === 'demande' && auth()->user()->role === 'delegue' && $demandes_specimen->delegue_id === auth()->id())
                <a href="{{ route('demandes-specimens.edit', $demandes_specimen) }}" class="ds-btn ds-btn-warning">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/>
                    </svg>
                    Modifier
                </a>
            @endif

            @if($demandes_specimen->statut === 'demande' && in_array(auth()->user()->role, ['admin','rbo']))

                <form method="POST" action="{{ route('demandes-specimens.validate', $demandes_specimen) }}">
                    @csrf

                    <button
                        type="submit"
                        name="action"
                        value="approve"
                        class="ds-btn ds-btn-primary"
                        onclick="return confirm('Générer le BSS correspondant ?')"
                    >
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>

                        Approuver
                    </button>
                </form>

                <form method="POST" action="{{ route('demandes-specimens.validate', $demandes_specimen) }}">
                    @csrf

                    <button
                        type="submit"
                        name="action"
                        value="decline"
                        class="ds-btn ds-btn-danger"
                        onclick="return confirm('Refuser cette demande ?')"
                    >
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>

                        Refuser
                    </button>
                </form>

            @endif

        </div>
    </div>

    {{-- Layout --}}
    <div class="ds-layout">

        {{-- LEFT SIDEBAR --}}
        <div>

            {{-- General info --}}
            <div class="ds-card" style="margin-bottom:1.5rem;">

                <div class="ds-card-header">
                    <div class="ds-card-title">
                        <span class="ds-card-title-dot"></span>
                        Informations
                    </div>
                </div>

                <div class="ds-card-body">

                    <div class="ds-meta">

                        <div class="ds-meta-item">
                            <div class="ds-meta-label">Statut</div>

                            <div class="ds-meta-value">
                                <span class="ds-status ds-status-{{ $demandes_specimen->statut }}">
                                    {{ ucfirst($demandes_specimen->statut) }}
                                </span>
                            </div>
                        </div>

                        <div class="ds-meta-item">
                            <div class="ds-meta-label">Compte</div>

                            <div class="ds-meta-value">
                                {{ $demandes_specimen->compte->etablissement ?? '-' }}
                            </div>
                        </div>

                        <div class="ds-meta-item">
                            <div class="ds-meta-label">Contact</div>

                            <div class="ds-meta-value">
                                {{ optional($demandes_specimen->contact)->prenom }}
                                {{ optional($demandes_specimen->contact)->nom ?? '-' }}
                            </div>
                        </div>

                        <div class="ds-meta-item">
                            <div class="ds-meta-label">Délégué</div>

                            <div class="ds-meta-value">
                                {{ $demandes_specimen->delegate->prenom }}
                                {{ $demandes_specimen->delegate->nom }}
                            </div>
                        </div>

                        <div class="ds-meta-item">
                            <div class="ds-meta-label">Ville / Zone</div>

                            <div class="ds-meta-value">
                                {{ $demandes_specimen->ville->nom ?? '-' }}
                            </div>

                            <div class="ds-meta-sub">
                                {{ $demandes_specimen->zone->name ?? '-' }}
                            </div>
                        </div>

                        <div class="ds-meta-item">
                            <div class="ds-meta-label">Date de demande</div>

                            <div class="ds-meta-value">
                                {{ $demandes_specimen->date_demande->format('d/m/Y') }}
                            </div>
                        </div>

                        @if($demandes_specimen->valide_par)

                            <div class="ds-meta-item">
                                <div class="ds-meta-label">Validation</div>

                                <div class="ds-meta-value">
                                    {{ $demandes_specimen->validePar->prenom }}
                                    {{ $demandes_specimen->validePar->nom }}
                                </div>

                                <div class="ds-meta-sub">
                                    {{ $demandes_specimen->date_validation->format('d/m/Y H:i') }}
                                </div>
                            </div>

                        @endif

                    </div>

                </div>

            </div>

            {{-- BSS links --}}
            <div class="ds-card">

                <div class="ds-card-header">
                    <div class="ds-card-title">
                        <span class="ds-card-title-dot"></span>
                        Liens BSS
                    </div>
                </div>

                <div class="ds-card-body">

                    @if($demandes_specimen->originalBss)

                        <div class="ds-link-card">

                            <div class="ds-link-card-left">

                                <div class="ds-link-icon purple">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                    </svg>
                                </div>

                                <div>
                                    <div class="ds-link-label">BSS original</div>

                                    <div class="ds-link-value">
                                        {{ $demandes_specimen->originalBss->numero }}
                                    </div>
                                </div>

                            </div>

                            <a href="{{ route('bss.show', $demandes_specimen->originalBss) }}" class="ds-link-btn">
                                Voir
                            </a>

                        </div>

                    @endif

                    @if($demandes_specimen->generatedBss)

                        <div class="ds-link-card">

                            <div class="ds-link-card-left">

                                <div class="ds-link-icon blue">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 5v14"/>
                                        <path d="M5 12h14"/>
                                    </svg>
                                </div>

                                <div>
                                    <div class="ds-link-label">BSS généré</div>

                                    <div class="ds-link-value">
                                        {{ $demandes_specimen->generatedBss->numero }}
                                    </div>
                                </div>

                            </div>

                            <a href="{{ route('bss.show', $demandes_specimen->generatedBss) }}" class="ds-link-btn">
                                Voir
                            </a>

                        </div>

                    @endif

                    @if(!$demandes_specimen->originalBss && !$demandes_specimen->generatedBss)

                        <div style="text-align:center;padding:.7rem 0;color:var(--text-muted);font-size:.85rem;">
                            Aucun BSS lié.
                        </div>

                    @endif

                </div>

            </div>

        </div>

        {{-- RIGHT CONTENT --}}
        <div>

            {{-- Description --}}
            @if($demandes_specimen->description)

                <div class="ds-card" style="margin-bottom:1.5rem;">

                    <div class="ds-card-header">
                        <div class="ds-card-title">
                            <span class="ds-card-title-dot"></span>
                            Description
                        </div>
                    </div>

                    <div class="ds-card-body">

                        <div class="ds-description">
                            {{ $demandes_specimen->description }}
                        </div>

                    </div>

                </div>

            @endif

            {{-- Products --}}
            <div class="ds-card">

                <div class="ds-card-header">
                    <div class="ds-card-title">
                        <span class="ds-card-title-dot"></span>
                        Produits demandés
                    </div>

                    <div style="font-size:.8rem;color:var(--text-muted);font-weight:600;">
                        {{ $demandes_specimen->lignes->count() }} produit(s)
                    </div>
                </div>

                <div class="ds-table-wrap">

                    <table class="ds-table">

                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th width="120">Quantité</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($demandes_specimen->lignes as $ligne)

                                <tr>

                                    <td>

                                        <div class="ds-product">

                                            <div class="ds-product-cover">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                                </svg>
                                            </div>

                                            <div>

                                                <div class="ds-product-title">
                                                    {{ $ligne->product->titre }}
                                                </div>

                                                <div class="ds-product-isbn">
                                                    {{ $ligne->product->isbn_13 ?? $ligne->product->isbn_10 ?? 'Aucun ISBN' }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                    <td>
                                        <span class="ds-qty">
                                            {{ $ligne->quantity }}
                                        </span>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection