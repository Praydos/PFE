@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM THE EXAMENS SHOW VIEW (provided) ===== */
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

/* ── Page ──────────────────────────────────────────── */
.zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1400px; margin: 0 auto; }
@keyframes pageIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Breadcrumb ────────────────────────────────────── */
.zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
.zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
.zn-bc a:hover { color: var(--blue); }
.zn-bc-sep { color: var(--text-hint); }
.zn-bc-cur { color: var(--text-secondary); }

/* ── Header ────────────────────────────────────────── */
.zn-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
.zn-header-left h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
.zn-header-left p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

/* ── Buttons ───────────────────────────────────────── */
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
.btn-zn-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(91,141,238,.4); }
.btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
.btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
.btn-zn-danger-ghost { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.2); }
.btn-zn-danger-ghost:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-zn-warning { background: var(--amber-light); color: var(--amber); border-color: rgba(232,160,32,.2); }
.btn-zn-warning:hover { background: #ffefd4; color: var(--amber); text-decoration: none; }
.btn-zn-danger { background: var(--rose-light); color: var(--rose); border-color: rgba(232,80,106,.18); }
.btn-zn-danger:hover { background: #fddde2; color: var(--rose); text-decoration: none; }
.btn-zn-info { background: var(--violet-light); color: var(--violet); border-color: rgba(124,111,205,.2); }
.btn-zn-info:hover { background: #e8e5ff; color: var(--violet); text-decoration: none; }
.btn-zn-sm { padding: .38rem .72rem; font-size: .75rem; }

/* ── Card ──────────────────────────────────────────── */
.zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; margin-bottom: 1.5rem; }
.zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: .55rem; background: linear-gradient(to bottom, #fafbff, #fff); }
.title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); }
.zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }
.zn-card-body { padding: 1.5rem 1.6rem; }

/* ── Info grid ──────────────────────────────────────── */
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
hr { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }

/* ── Table ─────────────────────────────────────────── */
.zn-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
.zn-table thead tr { border-bottom: 1px solid var(--border); }
.zn-table th {
    padding: .85rem 1.2rem; font-size: .69rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-hint); text-align: left;
    background: var(--bg-base); white-space: nowrap;
}
.zn-table td { padding: .95rem 1.2rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
.zn-table tbody tr:hover { background: #f8f9fd; }

/* Badges */
.dr-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 20px;
    font-size: .7rem; font-weight: 600; white-space: nowrap;
}
.bd-teal { background: var(--teal-light); color: var(--teal); }
.bd-blue { background: var(--blue-light); color: var(--blue); }
.bd-green { background: var(--green-light); color: var(--green); }
.bd-amber { background: var(--amber-light); color: var(--amber); }
.bd-none { background: var(--bg-subtle); color: var(--text-muted); }

/* Status update box */
.status-update-box {
    margin-top: 1.5rem;
    padding: 1rem;
    background: var(--bg-subtle);
    border-radius: var(--r-lg);
}
.frm-select-wrap {
    position: relative;
    display: inline-block;
    width: auto;
    min-width: 200px;
}
.frm-select-wrap .frm-select {
    width: 100%;
    padding: .62rem .9rem;
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    background: var(--bg-card);
    font-family: var(--font);
    font-size: .84rem;
    color: var(--text-primary);
    box-shadow: var(--shadow-xs);
    transition: all var(--t);
    outline: none;
    cursor: pointer;
    padding-right: 2.2rem;
}
.frm-select-wrap::after {
    content: '';
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid var(--text-muted);
    pointer-events: none;
}
.frm-label {
    font-size: .8rem;
    font-weight: 600;
    color: var(--text-secondary);
    letter-spacing: -.01em;
}

/* Footer */
.card-footer {
    padding: 1.1rem 1.6rem;
    border-top: 1px solid var(--border);
    background: var(--bg-base);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: .6rem;
}

/* Responsive */
@media (max-width: 768px) {
    .zn-page { padding: 1.25rem 1rem 2rem; }
    .info-grid { grid-template-columns: 1fr; }
    .zn-table th, .zn-table td { padding: .75rem .9rem; }
    .card-footer { flex-direction: column-reverse; }
    .btn-zn { width: 100%; justify-content: center; }
    .status-form { flex-direction: column; align-items: stretch; }
    .status-form .frm-select-wrap { width: 100%; }
}
</style>
@endpush


@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('actions.index') }}">Actions</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $action->objet }}</span>
    </div>

    {{-- Header --}}
    <div class="zn-header">
        <h1>{{ $action->objet }}</h1>
        <p>Action du {{ $action->date_planification->format('d/m/Y') }} – {{ $action->compte->etablissement }}</p>
    </div>

    <div class="zn-card">
        <div class="zn-card-header">
            <span class="title-pip"></span>
            <span class="zn-card-title">Détails de l'action</span>
        </div>
        <div class="zn-card-body">
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Compte</span> {{ $action->compte->etablissement }}</div>
                <div class="info-item"><span class="info-label">Date</span> {{ $action->date_planification->format('d/m/Y') }}</div>
                {{-- <div class="info-item"><span class="info-label">Heure</span> {{ $action->heure ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Durée</span> {{ $action->duree ? $action->duree.' min' : '-' }}</div> --}}
                <div class="info-item"><span class="info-label">Lieu</span> {{ $action->lieu ?? '-' }}</div>
                <div class="info-item"><span class="info-label">Statut</span> <span class="dr-badge bd-{{ $action->statut }}">{{ ucfirst($action->statut) }}</span></div>
                <div class="info-item"><span class="info-label">Type</span> {{ ucfirst($action->type) }}</div>
                @if($action->statut == 'realise')
                    <div class="info-item"><span class="info-label">Réalisée le</span> {{ $action->date_realisation->format('d/m/Y H:i') }}</div>
                @endif
                @if($action->statut == 'valide')
                    <div class="info-item"><span class="info-label">Validée le</span> {{ $action->date_validation->format('d/m/Y H:i') }}</div>
                @endif
            </div>

            <hr>

            <h3 style="font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">Lignes d'action</h3>
            @foreach($action->lignes as $line)
                <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 1rem; margin-bottom: 1rem;">
                    <div class="info-grid" style="margin-bottom: 0.5rem;">
                        <div class="info-item"><span class="info-label">Catégorie</span> {{ $line->categorie }}</div>
                        <div class="info-item"><span class="info-label">Type</span> {{ $line->action_type }}</div>
                        <div class="info-item"><span class="info-label">Moyen</span> {{ $line->moyen ?? '-' }}</div>
                        <div class="info-item"><span class="info-label">Description</span> {{ $line->description ?? '-' }}</div>
                    </div>
                    <div class="info-grid" style="margin-bottom: 0.5rem;">
                        <div class="info-item"><span class="info-label">Contacts</span>
                            {{ $line->contacts->map(fn($c) => $c->prenom . ' ' . $c->nom)->join(', ') ?: '-' }}
                        </div>
                        <div class="info-item"><span class="info-label">Produits</span>
                            @if($line->products->count())
                                @foreach($line->products as $product)
                                    <a href="{{ route('products.show', $product) }}">{{ $product->titre }}</a>@if(!$loop->last), @endif
                                @endforeach
                            @else -
                            @endif
                        </div>
                        <div class="info-item"><span class="info-label">Examens</span>
                            @if($line->examens->count())
                                @foreach($line->examens as $examen)
                                    <a href="{{ route('examens.show', $examen) }}">{{ $examen->titre }}</a>@if(!$loop->last), @endif
                                @endforeach
                            @else -
                            @endif
                        </div>
                    </div>
                    <div class="info-grid">
                        @if($line->bss)
                            <div class="info-item"><span class="info-label">BSS associé</span>
                                <a href="{{ route('bss.show', $line->bss) }}">{{ $line->bss->numero }}</a>
                            </div>
                        @endif
                        @if($line->retour)
                            <div class="info-item"><span class="info-label">Bon de retour associé</span>
                                <a href="{{ route('retours.index', $line->retour) }}">{{ $line->retour->numero }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-footer">
            <a href="{{ route('actions.index') }}" class="btn-zn btn-zn-ghost">Retour</a>
            @if(in_array(auth()->user()->role, ['admin','rbo']) || (auth()->user()->role === 'delegue' && $action->delegue_id === auth()->id()))
                @if($action->statut === 'planifie')
                    <form method="POST" action="{{ route('actions.realiser', $action) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-zn btn-zn-primary" onclick="return confirm('Marquer comme réalisée ?')">Réaliser</button>
                    </form>
                    <form method="POST" action="{{ route('actions.annuler', $action) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-zn btn-zn-danger" onclick="return confirm('Annuler ?')">Annuler</button>
                    </form>
                    <button type="button" class="btn-zn btn-zn-warning" id="openReportModalBtn">Reporter</button>
                @elseif($action->statut === 'realise' && in_array(auth()->user()->role, ['admin','rbo']))
                    <form method="POST" action="{{ route('actions.valider', $action) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-zn btn-zn-primary" onclick="return confirm('Valider ?')">Valider</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>

{{-- Modal for reporting --}}
<div class="dlg-modal-overlay" id="reportModalOverlay">
    <div class="dlg-modal">
        <div class="dlg-modal-hd">
            <div class="dlg-modal-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div class="dlg-modal-titles">
                <h2>Reporter l'action</h2>
                <p>Choisissez une nouvelle date et heure</p>
            </div>
            <button class="dlg-modal-close" id="closeReportModal">&times;</button>
        </div>
        <div class="dlg-modal-body">
            <form method="POST" action="{{ route('actions.reporter', $action) }}">
                @csrf
                <div class="frm-group">
                    <label class="frm-label">Nouvelle date *</label>
                    <input type="date" name="nouvelle_date" class="frm-input" required>
                </div>
                <div class="frm-group">
                    <label class="frm-label">Nouvelle heure</label>
                    <input type="time" name="nouvelle_heure" class="frm-input">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1rem;">
                    <button type="button" class="btn-zn btn-zn-ghost" id="cancelReportBtn">Annuler</button>
                    <button type="submit" class="btn-zn btn-zn-primary">Reporter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const reportOverlay = document.getElementById('reportModalOverlay');
    const openBtn = document.getElementById('openReportModalBtn');
    const closeBtn = document.getElementById('closeReportModal');
    const cancelBtn = document.getElementById('cancelReportBtn');

    function openModal() { reportOverlay.classList.add('visible'); document.body.style.overflow = 'hidden'; }
    function closeModal() { reportOverlay.classList.remove('visible'); document.body.style.overflow = ''; }

    if (openBtn) openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    reportOverlay.addEventListener('click', (e) => { if (e.target === reportOverlay) closeModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && reportOverlay.classList.contains('visible')) closeModal(); });
</script>
@endpush