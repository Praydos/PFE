@extends('layouts.app')

@section('content')
<div class="nc-page" x-data="notificationCenter()">

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    <div class="nc-header">
        <div class="nc-header-left">
            <div class="nc-header-icon" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </div>
            <h1 class="nc-title">Notifications</h1>
            <span class="nc-count-badge" x-show="unreadCount > 0" x-text="unreadCount"></span>
        </div>
        <button class="nc-mark-all-btn"
                x-show="unreadCount > 0"
                @click="markAllAsRead">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="20 6 9 17 4 12"/>
                <polyline points="20 12 9 23 4 18"/>
            </svg>
            Tout marquer lu
        </button>
    </div>

    {{-- ── Filter pills ────────────────────────────────────────────────────── --}}
    <div class="nc-filters-row">
        {{-- Status --}}
        <div class="nc-filter-group">
            <button class="nc-pill"
                    :class="filters.is_read === '' ? 'active' : ''"
                    @click="setFilter('is_read', '')">Tous</button>
            <button class="nc-pill"
                    :class="filters.is_read === 'false' ? 'active' : ''"
                    @click="setFilter('is_read', 'false')">
                <span class="nc-pill-dot unread-dot" aria-hidden="true"></span>Non lus
            </button>
            <button class="nc-pill"
                    :class="filters.is_read === 'true' ? 'active' : ''"
                    @click="setFilter('is_read', 'true')">Lus</button>
        </div>

        <div class="nc-filter-divider" aria-hidden="true"></div>

        {{-- Type --}}
        <div class="nc-filter-group">
            <template x-for="t in typeOptions" :key="t.value">
                <button class="nc-pill"
                        :class="filters.type === t.value ? 'active type-' + t.value : ''"
                        @click="setFilter('type', t.value)">
                    <span x-html="t.icon" class="nc-pill-icon" aria-hidden="true"></span>
                    <span x-text="t.label"></span>
                </button>
            </template>
        </div>

        <div class="nc-filter-divider" aria-hidden="true"></div>

        {{-- Category --}}
        <div class="nc-filter-group">
            <select class="nc-select"
                    x-model="filters.category"
                    @change="fetchNotifications(1)"
                    aria-label="Filtrer par catégorie">
                <option value="">Toutes catégories</option>
                <option value="tache">Tâche</option>
                <option value="action">Action</option>
                <option value="specimen">Spécimen</option>
                <option value="event">Événement</option>
                <option value="examen">Examen</option>
                <option value="formation">Formation</option>
                <option value="bss">BSS</option>
            </select>
        </div>
    </div>

    {{-- ── Loading ──────────────────────────────────────────────────────────── --}}
    <div class="nc-loading" x-show="loading" aria-live="polite" aria-label="Chargement en cours">
        <div class="nc-spinner"></div>
        <span>Chargement…</span>
    </div>

    {{-- ── Empty state ─────────────────────────────────────────────────────── --}}
    <div class="nc-empty" x-show="!loading && notifications.length === 0">
        <div class="nc-empty-icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                <path d="M18.63 13A17.89 17.89 0 0 1 18 8"/>
                <path d="M6.26 6.26A5.86 5.86 0 0 0 6 8c0 7-3 9-3 9h14"/>
                <path d="M18 8a6 6 0 0 0-9.33-5"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
        </div>
        <p class="nc-empty-title">Aucune notification</p>
        <p class="nc-empty-sub">Rien à afficher avec les filtres actuels.</p>
    </div>

    {{-- ── Notification list ───────────────────────────────────────────────── --}}
    <div class="nc-list" x-show="!loading && notifications.length > 0" role="list">

        {{-- Today group --}}
        <template x-if="todayNotifications.length > 0">
            <div>
                <div class="nc-group-label">Aujourd'hui</div>
                <div class="nc-group-list">
                    <template x-for="n in todayNotifications" :key="n.id">
                        <div class="nc-item"
                             :class="[!n.is_read ? 'unread' : 'read', 'type-border-' + n.type]"
                             @click="!n.is_read && markAsRead(n.id)"
                             :tabindex="!n.is_read ? 0 : -1"
                             :aria-label="n.title + (!n.is_read ? ' — non lu' : '')"
                             role="listitem">
                            <div class="nc-item-icon" :class="'icon-' + n.type" aria-hidden="true">
                                <span x-html="getIcon(n.type)"></span>
                            </div>
                            <div class="nc-item-body">
                                <div class="nc-item-top">
                                    <span class="nc-item-title" x-text="n.title"></span>
                                    <span class="nc-item-time" x-text="formatDate(n.created_at)"></span>
                                </div>
                                <p class="nc-item-msg" x-text="n.message"></p>
                                <div class="nc-item-footer">
                                    <span class="nc-cat-badge" x-text="formatCategory(n.category)"></span>
                                    <span x-show="!n.is_read" class="nc-unread-dot" aria-label="Non lu"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        {{-- Older group --}}
        <template x-if="olderNotifications.length > 0">
            <div>
                <div class="nc-group-label">Plus ancien</div>
                <div class="nc-group-list">
                    <template x-for="n in olderNotifications" :key="n.id">
                        <div class="nc-item"
                             :class="[!n.is_read ? 'unread' : 'read', 'type-border-' + n.type]"
                             @click="!n.is_read && markAsRead(n.id)"
                             :tabindex="!n.is_read ? 0 : -1"
                             :aria-label="n.title + (!n.is_read ? ' — non lu' : '')"
                             role="listitem">
                            <div class="nc-item-icon" :class="'icon-' + n.type" aria-hidden="true">
                                <span x-html="getIcon(n.type)"></span>
                            </div>
                            <div class="nc-item-body">
                                <div class="nc-item-top">
                                    <span class="nc-item-title" x-text="n.title"></span>
                                    <span class="nc-item-time" x-text="formatDate(n.created_at)"></span>
                                </div>
                                <p class="nc-item-msg" x-text="n.message"></p>
                                <div class="nc-item-footer">
                                    <span class="nc-cat-badge" x-text="formatCategory(n.category)"></span>
                                    <span x-show="!n.is_read" class="nc-unread-dot" aria-label="Non lu"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

    </div>

    {{-- ── Pagination ───────────────────────────────────────────────────────── --}}
    <div class="nc-pagination" x-show="pagination.last_page > 1">
        <span class="nc-page-info"
              x-text="`${pagination.from || 0}–${pagination.to || 0} sur ${pagination.total}`">
        </span>
        <div class="nc-page-btns">
            <button class="nc-page-btn"
                    :disabled="pagination.current_page === 1"
                    @click="fetchNotifications(pagination.current_page - 1)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Préc.
            </button>
            <button class="nc-page-btn"
                    :disabled="pagination.current_page === pagination.last_page"
                    @click="fetchNotifications(pagination.current_page + 1)">
                Suiv.
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>
        </div>
    </div>

</div>
@endsection


@push('styles')
<style>
/* ─────────────────────────────────────────────
   Design tokens
───────────────────────────────────────────── */
:root {
    --nc-page-bg:         #0e0f11;
    --nc-surface:         #16181c;
    --nc-surface-hover:   #1c1f24;
    --nc-border:          rgba(255,255,255,0.07);
    --nc-border-md:       rgba(255,255,255,0.12);
    --nc-text-primary:    #f0f0f2;
    --nc-text-secondary:  #8a8fa0;
    --nc-text-muted:      #525769;
    --nc-radius-sm:       6px;
    --nc-radius-md:       10px;
    --nc-radius-lg:       14px;

    /* type palette */
    --nc-success-bg:   rgba(34, 197, 94,  0.10);
    --nc-success-fg:   #4ade80;
    --nc-success-acc:  #22c55e;

    --nc-reminder-bg:  rgba(251, 191, 36, 0.10);
    --nc-reminder-fg:  #fbbf24;
    --nc-reminder-acc: #f59e0b;

    --nc-deadline-bg:  rgba(239, 68,  68,  0.10);
    --nc-deadline-fg:  #f87171;
    --nc-deadline-acc: #ef4444;

    --nc-system-bg:    rgba(99,  179, 237, 0.10);
    --nc-system-fg:    #63b3ed;
    --nc-system-acc:   #3b82f6;
}

/* ─────────────────────────────────────────────
   Page shell
───────────────────────────────────────────── */
.nc-page {
    max-width: 720px;
    margin: 0 auto;
    padding: 2rem 1.25rem 4rem;
    font-family: 'DM Sans', 'Inter', ui-sans-serif, system-ui, sans-serif;
    color: var(--nc-text-primary);
    min-height: 100vh;
}

/* ─────────────────────────────────────────────
   Header
───────────────────────────────────────────── */
.nc-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    gap: 12px;
}

.nc-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.nc-header-icon {
    color: var(--nc-text-secondary);
    display: flex;
    align-items: center;
}

.nc-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--nc-text-primary);
    letter-spacing: -0.4px;
    line-height: 1;
}

.nc-count-badge {
    background: var(--nc-deadline-acc);
    color: #fff;
    font-size: 10.5px;
    font-weight: 700;
    font-family: 'DM Mono', 'Fira Mono', monospace;
    border-radius: 20px;
    padding: 2px 8px;
    line-height: 1.5;
    letter-spacing: 0.2px;
}

.nc-mark-all-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
    color: var(--nc-text-secondary);
    background: transparent;
    border: 1px solid var(--nc-border-md);
    border-radius: var(--nc-radius-sm);
    padding: 6px 14px;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}
.nc-mark-all-btn:hover {
    background: var(--nc-surface-hover);
    color: var(--nc-text-primary);
    border-color: rgba(255,255,255,0.2);
}

/* ─────────────────────────────────────────────
   Filters
───────────────────────────────────────────── */
.nc-filters-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    margin-bottom: 1.5rem;
    padding: 10px 14px;
    background: var(--nc-surface);
    border: 1px solid var(--nc-border);
    border-radius: var(--nc-radius-md);
}

.nc-filter-group {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    align-items: center;
}

.nc-filter-divider {
    width: 1px;
    height: 18px;
    background: var(--nc-border-md);
    margin: 0 2px;
}

.nc-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 500;
    font-family: inherit;
    padding: 4px 11px;
    border-radius: 20px;
    border: 1px solid transparent;
    background: transparent;
    color: var(--nc-text-secondary);
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
    line-height: 1.5;
}
.nc-pill:hover {
    background: var(--nc-surface-hover);
    color: var(--nc-text-primary);
    border-color: var(--nc-border-md);
}
.nc-pill.active {
    background: var(--nc-text-primary);
    color: var(--nc-page-bg);
    border-color: var(--nc-text-primary);
}
.nc-pill.active.type-success  { background: var(--nc-success-acc); border-color: var(--nc-success-acc); color: #fff; }
.nc-pill.active.type-reminder { background: var(--nc-reminder-acc); border-color: var(--nc-reminder-acc); color: #fff; }
.nc-pill.active.type-deadline { background: var(--nc-deadline-acc); border-color: var(--nc-deadline-acc); color: #fff; }
.nc-pill.active.type-system   { background: var(--nc-system-acc); border-color: var(--nc-system-acc); color: #fff; }

.nc-pill-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}
.nc-pill-dot.unread-dot { background: #3b82f6; }

.nc-pill-icon {
    display: flex;
    align-items: center;
    line-height: 1;
}
.nc-pill-icon svg {
    width: 11px;
    height: 11px;
}

.nc-select {
    font-size: 12px;
    font-weight: 500;
    font-family: inherit;
    color: var(--nc-text-secondary);
    background: transparent;
    border: 1px solid var(--nc-border-md);
    border-radius: var(--nc-radius-sm);
    padding: 4px 10px;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238a8fa0' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    padding-right: 24px;
    transition: all 0.15s;
}
.nc-select:hover, .nc-select:focus {
    color: var(--nc-text-primary);
    border-color: rgba(255,255,255,0.2);
    outline: none;
}
.nc-select option {
    background: #1c1f24;
    color: var(--nc-text-primary);
}

/* ─────────────────────────────────────────────
   Loading
───────────────────────────────────────────── */
.nc-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 3.5rem 1rem;
    color: var(--nc-text-muted);
    font-size: 13px;
}

.nc-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid var(--nc-border-md);
    border-top-color: var(--nc-system-acc);
    border-radius: 50%;
    animation: nc-spin 0.75s linear infinite;
    flex-shrink: 0;
}
@keyframes nc-spin { to { transform: rotate(360deg); } }

/* ─────────────────────────────────────────────
   Empty state
───────────────────────────────────────────── */
.nc-empty {
    text-align: center;
    padding: 4rem 1rem;
}

.nc-empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--nc-surface);
    border: 1px solid var(--nc-border);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: var(--nc-text-muted);
}

.nc-empty-title {
    font-size: 15px;
    font-weight: 500;
    color: var(--nc-text-secondary);
    margin-bottom: 4px;
}
.nc-empty-sub {
    font-size: 13px;
    color: var(--nc-text-muted);
}

/* ─────────────────────────────────────────────
   Notification list
───────────────────────────────────────────── */
.nc-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.nc-group-label {
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 0.9px;
    text-transform: uppercase;
    color: var(--nc-text-muted);
    padding: 0 2px 8px;
    font-family: 'DM Mono', 'Fira Mono', monospace;
}

.nc-group-list {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

/* ─────────────────────────────────────────────
   Notification item
───────────────────────────────────────────── */
.nc-item {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 14px 16px;
    background: var(--nc-surface);
    border: 1px solid var(--nc-border);
    border-radius: var(--nc-radius-lg);
    transition: background 0.15s, border-color 0.15s, transform 0.12s;
    position: relative;
    overflow: hidden;
}
.nc-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    border-radius: var(--nc-radius-lg) 0 0 var(--nc-radius-lg);
    opacity: 0;
    transition: opacity 0.15s;
}
.nc-item.unread::before     { opacity: 1; }
.nc-item.type-border-success::before  { background: var(--nc-success-acc); }
.nc-item.type-border-reminder::before { background: var(--nc-reminder-acc); }
.nc-item.type-border-deadline::before { background: var(--nc-deadline-acc); }
.nc-item.type-border-system::before   { background: var(--nc-system-acc); }

.nc-item.unread {
    cursor: pointer;
}
.nc-item.unread:hover {
    background: var(--nc-surface-hover);
    border-color: var(--nc-border-md);
    transform: translateX(2px);
}
.nc-item.read {
    opacity: 0.55;
}
.nc-item.read:hover {
    opacity: 0.8;
    background: var(--nc-surface-hover);
}

/* Icon pill */
.nc-item-icon {
    width: 38px;
    height: 38px;
    border-radius: var(--nc-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.nc-item-icon svg {
    width: 17px;
    height: 17px;
}
.icon-success  { background: var(--nc-success-bg);  color: var(--nc-success-fg); }
.icon-reminder { background: var(--nc-reminder-bg); color: var(--nc-reminder-fg); }
.icon-deadline { background: var(--nc-deadline-bg); color: var(--nc-deadline-fg); }
.icon-system   { background: var(--nc-system-bg);   color: var(--nc-system-fg); }

/* Item body */
.nc-item-body  { flex: 1; min-width: 0; }

.nc-item-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 4px;
}

.nc-item-title {
    font-size: 13.5px;
    font-weight: 500;
    color: var(--nc-text-primary);
    line-height: 1.35;
    flex: 1;
    min-width: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.nc-item.read .nc-item-title {
    color: var(--nc-text-secondary);
}

.nc-item-time {
    font-size: 11px;
    color: var(--nc-text-muted);
    white-space: nowrap;
    flex-shrink: 0;
    font-family: 'DM Mono', 'Fira Mono', monospace;
    letter-spacing: 0.2px;
}

.nc-item-msg {
    font-size: 12.5px;
    color: var(--nc-text-secondary);
    line-height: 1.55;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.nc-item-footer {
    display: flex;
    align-items: center;
    gap: 7px;
}

.nc-cat-badge {
    font-size: 10.5px;
    font-weight: 500;
    letter-spacing: 0.3px;
    padding: 2px 8px;
    border-radius: 20px;
    border: 1px solid var(--nc-border-md);
    color: var(--nc-text-muted);
    background: transparent;
    font-family: 'DM Mono', 'Fira Mono', monospace;
}

.nc-unread-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--nc-system-acc);
    flex-shrink: 0;
    margin-left: auto;
    display: block;
}

/* ─────────────────────────────────────────────
   Pagination
───────────────────────────────────────────── */
.nc-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid var(--nc-border);
}

.nc-page-info {
    font-size: 11.5px;
    color: var(--nc-text-muted);
    font-family: 'DM Mono', 'Fira Mono', monospace;
}

.nc-page-btns {
    display: flex;
    gap: 6px;
}

.nc-page-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 500;
    font-family: inherit;
    padding: 6px 14px;
    border-radius: var(--nc-radius-sm);
    border: 1px solid var(--nc-border-md);
    background: var(--nc-surface);
    color: var(--nc-text-secondary);
    cursor: pointer;
    transition: all 0.15s;
}
.nc-page-btn:hover:not(:disabled) {
    background: var(--nc-surface-hover);
    color: var(--nc-text-primary);
    border-color: rgba(255,255,255,0.2);
}
.nc-page-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

/* ─────────────────────────────────────────────
   Responsive
───────────────────────────────────────────── */
@media (max-width: 560px) {
    .nc-page { padding: 1rem 0.75rem 3rem; }
    .nc-filters-row { gap: 5px; padding: 8px 10px; }
    .nc-filter-divider { display: none; }
    .nc-item { padding: 12px 13px; gap: 11px; }
    .nc-item-icon { width: 34px; height: 34px; }
    .nc-item-time { display: none; }
}
</style>
@endpush


@push('scripts')
{{-- Optional: load DM Sans from Google Fonts if not already in your layout --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notificationCenter', () => ({

        /* ── State ──────────────────────────── */
        notifications: [],
        loading: true,
        unreadCount: 0,
        filters: {
            is_read: '',
            type: '',
            category: ''
        },
        pagination: {
            current_page: 1,
            last_page: 1,
            total: 0,
            from: 0,
            to: 0
        },

        /* Filter options rendered by Alpine */
        typeOptions: [
            { value: '', label: 'Tous types', icon: '' },
            {
                value: 'success',
                label: 'Succès',
                icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`
            },
            {
                value: 'reminder',
                label: 'Rappel',
                icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`
            },
            {
                value: 'deadline',
                label: 'Échéance',
                icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`
            },
            {
                value: 'system',
                label: 'Système',
                icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`
            },
        ],

        /* ── Computed groups ─────────────────── */
        get todayNotifications() {
            return this.notifications.filter(n => this._isToday(n.created_at));
        },
        get olderNotifications() {
            return this.notifications.filter(n => !this._isToday(n.created_at));
        },

        /* ── Lifecycle ──────────────────────── */
        init() {
            this.fetchNotifications();
            this.fetchUnreadCount();

            const userId = {{ auth()->id() }};
            if (window.Echo) {
                window.Echo.private(`App.Models.User.${userId}`)
                    .listen('NewNotification', () => {
                        if (this.pagination.current_page === 1) {
                            this.fetchNotifications(1);
                        }
                        this.fetchUnreadCount();
                    });
            }
        },

        /* ── API calls ──────────────────────── */
        async fetchNotifications(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({ page });
                Object.entries(this.filters).forEach(([k, v]) => {
                    if (v !== '') params.set(k, v);
                });
                const { data } = await axios.get(`/api/notifications?${params}`);
                this.notifications = data.data;
                this.pagination = {
                    current_page: data.current_page,
                    last_page:    data.last_page,
                    total:        data.total,
                    from:         data.from,
                    to:           data.to,
                };
            } catch (err) {
                console.error('Erreur chargement notifications', err);
            } finally {
                this.loading = false;
            }
        },

        async fetchUnreadCount() {
            try {
                const { data } = await axios.get('/api/notifications/unread-count');
                this.unreadCount = data.count;
                window.dispatchEvent(new CustomEvent('notifications-updated', {
                    detail: { count: this.unreadCount }
                }));
            } catch (err) {
                console.error('Erreur comptage non lus', err);
            }
        },

        async markAsRead(id) {
            try {
                await axios.post(`/api/notifications/${id}/read`);
                const n = this.notifications.find(n => n.id === id);
                if (n) n.is_read = true;
                this.fetchUnreadCount();
            } catch (err) {
                console.error('Erreur markAsRead', err);
            }
        },

        async markAllAsRead() {
            try {
                await axios.post('/api/notifications/read-all');
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
                window.dispatchEvent(new CustomEvent('notifications-updated', {
                    detail: { count: 0 }
                }));
            } catch (err) {
                console.error('Erreur markAllAsRead', err);
            }
        },

        /* ── Filter helper ──────────────────── */
        setFilter(key, value) {
            this.filters[key] = value;
            this.fetchNotifications(1);
        },

        /* ── Icon SVGs ──────────────────────── */
        getIcon(type) {
            const icons = {
                success: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
                reminder: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
                deadline: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
                system: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
            };
            return icons[type] || icons.system;
        },

        /* ── Label maps ─────────────────────── */
        formatCategory(category) {
            return {
                tache:             'Tâche',
                action:            'Action',
                specimen:          'Spécimen',
                event:             'Événement',
                examen:            'Examen',
                formation:         'Formation',
                bss:               'BSS',
                reclamation:       'Réclamation',
                non_conformite:    'Non-Conformité',
                action_amelioration: 'Action Amélioration',
                system:            'Système',
            }[category] || category;
        },

        /* ── Date helpers ───────────────────── */
        formatDate(dateString) {
            const date = new Date(dateString);
            const now  = new Date();
            const diffMs   = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffH    = Math.floor(diffMins / 60);
            const diffD    = Math.floor(diffH / 24);

            if (diffMins < 1)  return "À l'instant";
            if (diffMins < 60) return `${diffMins} min`;
            if (diffH < 24)    return `${diffH} h`;
            if (diffD === 1)   return 'Hier';
            if (diffD < 7)     return `${diffD} j`;
            return date.toLocaleDateString('fr-FR', {
                day: '2-digit', month: '2-digit', year: 'numeric',
            });
        },

        _isToday(dateString) {
            const d   = new Date(dateString);
            const now = new Date();
            return (
                d.getDate()     === now.getDate()     &&
                d.getMonth()    === now.getMonth()    &&
                d.getFullYear() === now.getFullYear()
            );
        },

    }));
});
</script>
@endpush