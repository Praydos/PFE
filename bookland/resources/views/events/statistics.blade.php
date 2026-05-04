@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ── Global design system (same as previous forms) ── */
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
        --teal:           #0cb8b6;
        --teal-light:     #e6faf9;
        --violet:         #7c6fcd;
        --violet-light:   #f0eeff;
        --amber:          #e8a020;
        --amber-light:    #fff8ec;
        --rose:           #e8506a;
        --rose-light:     #fef0f2;
        --green:          #28c76f;
        --green-light:    #e8fbf0;
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

    .fp-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .fp-section {
        padding: 2rem 2rem;
        border-bottom: 1px solid var(--border);
    }
    .fp-section:last-child { border-bottom: none; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
        background: var(--bg-subtle);
        border-radius: var(--r-lg);
        padding: 1.25rem;
    }
    .info-item {
        font-size: 1rem;
        color: var(--text-primary);
        background: var(--bg-card);
        border-radius: var(--r-md);
        padding: 1rem 1.2rem;
        box-shadow: var(--shadow-xs);
        text-align: center;
    }
    .info-label {
        display: block;
        font-size: .7rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 0.3rem;
    }
    .info-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .zn-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0.5rem;
    }
    .zn-table thead tr {
        border-bottom: 1px solid var(--border);
    }
    .zn-table th {
        padding: .85rem 1rem;
        font-size: .69rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--text-hint);
        text-align: left;
        background: var(--bg-base);
    }
    .zn-table td {
        padding: .85rem 1rem;
        font-size: .83rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .zn-table tbody tr:hover {
        background: #f8f9fd;
    }
    .zn-table tbody tr:last-child td {
        border-bottom: none;
    }

    .fp-footer {
        padding: 1.25rem 2rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .fp-footer-spacer {
        flex: 1;
    }

    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .info-grid { grid-template-columns: 1fr; }
        .zn-table th, .zn-table td { padding: .75rem .9rem; }
        .fp-footer { flex-wrap: wrap; }
        .fp-footer-spacer { display: none; }
        .btn-zn { width: 100%; justify-content: center; }
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
        <a href="{{ route('events.index') }}">Événements</a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('events.show', $event) }}">{{ $event->type }}</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Statistiques</span>
    </div>

    <div class="zn-header">
        <h1>Statistiques – {{ $event->type }}</h1>
        <p>{{ $event->date_event->format('d/m/Y') }}</p>
    </div>

    <div class="fp-card">
        <div class="fp-section">
            {{-- Summary cards --}}
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Total invités</span>
                    <div class="info-value">{{ $stats['total_invites'] }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Présents</span>
                    <div class="info-value">{{ $stats['total_presents'] }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Taux de participation</span>
                    <div class="info-value">{{ $stats['participation_rate'] }}%</div>
                </div>
            </div>

            {{-- Table by city --}}
            <h3 style="font-size: .9rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;">Par ville</h3>
            <div style="overflow-x: auto;">
                <table class="zn-table">
                    <thead>
                        <tr><th>Ville</th><th>Invités</th><th>Présents</th><th>Taux</th></tr>
                    </thead>
                    <tbody>
                        @foreach($stats['by_ville'] as $ville => $data)
                        <tr>
                            <td>{{ $ville }}</td>
                            <td>{{ $data['total'] }}</td>
                            <td>{{ $data['presents'] }}</td>
                            <td>{{ $data['rate'] }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="fp-footer">
            <div class="fp-footer-spacer"></div>
            <a href="{{ route('events.show', $event) }}" class="btn-zn btn-zn-ghost">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Retour
            </a>
        </div>
    </div>
</div>
@endsection