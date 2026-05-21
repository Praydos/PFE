@extends('layouts.app')

@push('styles')
<style>
/* ── Page ──────────────────────────────────────────── */
.vl-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 1200px; margin: 0 auto; }
@keyframes pageIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Header ────────────────────────────────────────── */
.vl-header {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 1.5rem;
    margin-bottom: 1.75rem; flex-wrap: wrap;
    padding-bottom: 1.75rem;
    border-bottom: 1px solid var(--border);
}
.vl-header-left { display: flex; align-items: center; gap: .95rem; }
.vl-header-icon {
    width: 44px; height: 44px; border-radius: var(--r-md);
    background: var(--blue-light); border: 1px solid var(--blue-mid);
    display: flex; align-items: center; justify-content: center;
    color: var(--blue); flex-shrink: 0;
}
.vl-header-left h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -.035em; color: var(--text-primary); line-height: 1.2; }
.vl-header-left p { font-size: .8rem; color: var(--text-muted); margin-top: .22rem; font-weight: 400; }

/* ── Tabs ────────────────────────────────────────── */
.logs-tabs {
    display: flex; gap: 0.5rem; margin-bottom: 1.5rem;
}
.logs-tab {
    padding: 0.5rem 1rem; border-radius: var(--r-sm);
    font-size: 0.85rem; font-weight: 600; text-decoration: none;
    transition: all var(--t);
    background: var(--bg-card); color: var(--text-secondary);
    border: 1px solid var(--border); box-shadow: var(--shadow-xs);
}
.logs-tab:hover {
    background: var(--bg-hover); color: var(--text-primary); text-decoration: none;
}
.logs-tab.active {
    background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue);
}

/* ── Card ──────────────────────────────────────────── */
.vl-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
.vl-card-header {
    padding: 1rem 1.5rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.vl-card-title { font-size: .86rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: .55rem; letter-spacing: -.01em; }
.title-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid); flex-shrink: 0; }
.vl-count {
    font-size: .73rem; color: var(--text-secondary); font-weight: 600;
    background: var(--bg-subtle); border: 1px solid var(--border);
    padding: .2rem .65rem; border-radius: 20px;
}

/* ── Table ─────────────────────────────────────────── */
.vl-table { width: 100%; border-collapse: collapse; }
.vl-table thead tr { border-bottom: 1px solid var(--border); }
.vl-table th {
    padding: .8rem 1.25rem; font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-hint); text-align: left;
    background: var(--bg-base); white-space: nowrap;
}
.vl-table td { padding: .9rem 1.25rem; font-size: .83rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); vertical-align: middle; }
.vl-table tbody tr { transition: background var(--t); }
.vl-table tbody tr:hover { background: var(--bg-hover); }
.vl-table tbody tr:last-child td { border-bottom: none; }

/* Action badges */
.badge-auth { background: var(--green-light); color: #14532d; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.badge-logout { background: var(--rose-light); color: #881337; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.badge-created { background: var(--blue-light); color: var(--blue-dark); padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.badge-updated { background: var(--amber-light); color: var(--amber); padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.badge-deleted { background: var(--rose-light); color: var(--rose); padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }

.btn-details {
    color: var(--blue); font-size: 0.8rem; font-weight: 600; cursor: pointer; text-decoration: underline; background: none; border: none; padding: 0;
}
.details-box {
    margin-top: 0.5rem; background: var(--bg-subtle); padding: 0.8rem; border-radius: var(--r-sm); font-family: var(--font-mono); font-size: 0.75rem; display: none; overflow-x: auto;
}
</style>
@endpush

@section('content')
<div class="vl-page">

    {{-- Header --}}
    <div class="vl-header">
        <div class="vl-header-left">
            <div class="vl-header-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div>
                <h1>Logs & Activités</h1>
                <p>Suivez les connexions et les modifications dans le système</p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="logs-tabs">
        <a href="{{ route('logs.index') }}" class="logs-tab {{ !request('type') || request('type') == 'all' ? 'active' : '' }}">Tout</a>
        <a href="{{ route('logs.index', ['type' => 'auth']) }}" class="logs-tab {{ request('type') == 'auth' ? 'active' : '' }}">Connexions</a>
        <a href="{{ route('logs.index', ['type' => 'system']) }}" class="logs-tab {{ request('type') == 'system' ? 'active' : '' }}">Activités système</a>
    </div>

    {{-- Table Card --}}
    <div class="vl-card">
        <div class="vl-card-header">
            <div class="vl-card-title">
                <span class="title-pip"></span>
                Historique
            </div>
            <span class="vl-count">{{ $logs->total() }} log{{ $logs->total() > 1 ? 's' : '' }}</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="vl-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td style="font-weight: 600; color: var(--text-primary);">
                                {{ $log->causer ? $log->causer->nom . ' ' . $log->causer->prenom : 'Système' }}
                            </td>
                            <td>
                                @if($log->log_name == 'auth')
                                    @if($log->description == 'logged_in')
                                        <span class="badge-auth">S'est connecté(e)</span>
                                    @else
                                        <span class="badge-logout">S'est déconnecté(e)</span>
                                    @endif
                                @else
                                    @php
                                        $actionColor = match ($log->description) {
                                            'updated' => 'badge-updated',
                                            'deleted' => 'badge-deleted',
                                            default => 'badge-created',
                                        };
                                        $actionText = \App\Support\ActivityLogLabels::eventLabel($log->description);
                                        $subjectLabel = \App\Support\ActivityLogLabels::subjectLabel($log->subject_type);
                                    @endphp
                                    <span class="{{ $actionColor }}">{{ $actionText }} {{ $subjectLabel }}</span>
                                    @if($log->subject_id)
                                        <span style="font-size: 0.75rem; color: var(--text-hint);">#{{ $log->subject_id }}</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @php
                                    $changes = $log->attribute_changes;
                                    $hasChanges = $changes && (
                                        (isset($changes['attributes']) && count((array) $changes['attributes']) > 0)
                                        || (isset($changes['old']) && count((array) $changes['old']) > 0)
                                    );
                                    $hasProperties = $log->properties && $log->properties->count() > 0;
                                @endphp
                                @if($hasChanges || $hasProperties)
                                    <div>
                                        <button type="button" class="btn-details" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'block' ? 'none' : 'block'">Voir les détails</button>
                                        <div class="details-box">
                                            @if($hasChanges && isset($changes['old']) && isset($changes['attributes']))
                                                <div style="display: flex; gap: 1rem;">
                                                    <div style="flex: 1;">
                                                        <strong style="color: var(--rose);">Anciennes valeurs</strong>
                                                        <pre style="white-space: pre-wrap; margin-top: 0.5rem;">{{ json_encode($changes['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    </div>
                                                    <div style="flex: 1;">
                                                        <strong style="color: var(--green);">Nouvelles valeurs</strong>
                                                        <pre style="white-space: pre-wrap; margin-top: 0.5rem;">{{ json_encode($changes['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    </div>
                                                </div>
                                            @elseif($hasChanges && isset($changes['old']))
                                                <div>
                                                    <strong style="color: var(--rose);">Valeurs supprimées</strong>
                                                    <pre style="white-space: pre-wrap; margin-top: 0.5rem;">{{ json_encode($changes['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @elseif($hasChanges && isset($changes['attributes']))
                                                <div>
                                                    <strong style="color: var(--green);">Valeurs créées</strong>
                                                    <pre style="white-space: pre-wrap; margin-top: 0.5rem;">{{ json_encode($changes['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            @elseif($hasProperties)
                                                <pre style="white-space: pre-wrap;">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">
                                Aucun log trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border);">
            {{ $logs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
