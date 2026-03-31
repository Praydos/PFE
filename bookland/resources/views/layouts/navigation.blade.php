<nav class="app-nav">
    <div class="nav-inner">

        {{-- ── Brand ── --}}
        <a href="{{ route('comptes.index') }}" class="nav-brand">
            <div class="nav-brand-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <span class="nav-brand-name">{{ config('app.name', 'CRM') }}</span>
        </a>

        {{-- ── Primary links ── --}}
        <div class="nav-links">

            {{-- Comptes — visible to everyone --}}
            <a href="{{ route('comptes.index') }}"
               class="nav-link {{ request()->routeIs('comptes.*') ? 'nav-link-active' : '' }}">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Comptes
            </a>

            {{-- Users / Rôles — visible to everyone, but index only to admin --}}
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.index') }}"
                   class="nav-link {{ request()->routeIs('users.index','users.create','users.edit') ? 'nav-link-active' : '' }}">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Utilisateurs
                </a>
            @endif

            {{-- Rôles tab — admin, rbo, delegue --}}
            @if(in_array(auth()->user()->role, ['admin', 'rbo', 'delegue']))
                <a href="{{ route('users.roles') }}"
                   class="nav-link {{ request()->routeIs('users.roles') ? 'nav-link-active' : '' }}">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        <path d="M16 3.5a4 4 0 0 1 0 7"/>
                        <path d="M20 20c0-3-2-5-4-6"/>
                    </svg>
                    Rôles
                </a>
            @endif

            {{-- Admin-only: Villes, Zones, Quartiers --}}
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('villes.index') }}"
                   class="nav-link {{ request()->routeIs('villes.*') ? 'nav-link-active' : '' }}">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    Villes
                </a>

                <a href="{{ route('zones.index') }}"
                   class="nav-link {{ request()->routeIs('zones.*') ? 'nav-link-active' : '' }}">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M3 9h18M9 21V9"/>
                    </svg>
                    Zones
                </a>

                <a href="{{ route('quartiers.index') }}"
                   class="nav-link {{ request()->routeIs('quartiers.*') ? 'nav-link-active' : '' }}">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <line x1="9" y1="22" x2="9" y2="12"/>
                        <line x1="15" y1="12" x2="15" y2="22"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                    </svg>
                    Quartiers
                </a>
            @endif
        </div>

        {{-- ── User dropdown ── --}}
        <div class="nav-user" x-data="{ open: false }" @click.outside="open = false">
            <button class="nav-user-btn" @click="open = !open" :aria-expanded="open">
                <div class="nav-avatar">
                    {{ strtoupper(substr(auth()->user()->prenom, 0, 1) . substr(auth()->user()->nom, 0, 1)) }}
                </div>
                <div class="nav-user-info">
                    <span class="nav-user-name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span>
                    <span class="nav-user-role">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
                <svg class="nav-chevron" :class="{ 'rotated': open }" width="13" height="13"
                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>

            <div class="nav-dropdown" x-show="open" x-cloak
                 x-transition:enter="dd-enter" x-transition:enter-start="dd-enter-start"
                 x-transition:enter-end="dd-enter-end"
                 x-transition:leave="dd-leave" x-transition:leave-start="dd-leave-start"
                 x-transition:leave-end="dd-leave-end">

                <div class="nav-dd-header">
                    <div class="nav-dd-avatar">
                        {{ strtoupper(substr(auth()->user()->prenom, 0, 1) . substr(auth()->user()->nom, 0, 1)) }}
                    </div>
                    <div>
                        <div class="nav-dd-name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
                        <div class="nav-dd-email">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="nav-dd-role-badge">
                    <span class="role-chip role-{{ auth()->user()->role }}">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>
                </div>

                <div class="nav-dd-divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-dd-item nav-dd-item-danger">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Mobile hamburger ── --}}
        <button class="nav-hamburger" id="navHamburger" aria-label="Menu">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>

    {{-- ── Mobile drawer ── --}}
    <div class="nav-mobile-drawer" id="navDrawer">
        <a href="{{ route('comptes.index') }}" class="nav-mobile-link">Comptes</a>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}" class="nav-mobile-link">Utilisateurs</a>
        @endif

        @if(in_array(auth()->user()->role, ['admin', 'rbo', 'delegue']))
            <a href="{{ route('users.roles') }}" class="nav-mobile-link">Rôles</a>
        @endif

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('villes.index') }}" class="nav-mobile-link">Villes</a>
            <a href="{{ route('zones.index') }}" class="nav-mobile-link">Zones</a>
            <a href="{{ route('quartiers.index') }}" class="nav-mobile-link">Quartiers</a>
        @endif

        <div class="nav-mobile-divider"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-mobile-link nav-mobile-logout">Déconnexion</button>
        </form>
    </div>
</nav>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap');

/* ── Nav shell ─────────────────────────────────── */
.app-nav {
    position: sticky; top: 0; z-index: 100;
    background: #fff;
    border-bottom: 1px solid #e4e7f0;
    box-shadow: 0 1px 3px rgba(31,45,80,.06);
    font-family: 'DM Sans', sans-serif;
}
.nav-inner {
    max-width: 1400px; margin: 0 auto;
    padding: 0 1.5rem;
    height: 58px;
    display: flex; align-items: center; gap: 1.5rem;
}

/* ── Brand ─────────────────────────────────────── */
.nav-brand {
    display: flex; align-items: center; gap: .6rem;
    text-decoration: none; flex-shrink: 0;
}
.nav-brand-icon {
    width: 34px; height: 34px; border-radius: 8px;
    background: linear-gradient(135deg, #5b8dee, #6c63ff);
    display: flex; align-items: center; justify-content: center;
    color: #fff;
}
.nav-brand-name {
    font-size: .92rem; font-weight: 700;
    color: #1a1f36; letter-spacing: -.02em;
}

/* ── Links ─────────────────────────────────────── */
.nav-links {
    display: flex; align-items: center; gap: .2rem;
    flex: 1;
}
.nav-link {
    display: inline-flex; align-items: center; gap: .38rem;
    padding: .42rem .82rem; border-radius: 7px;
    font-size: .82rem; font-weight: 500;
    color: #525f7f; text-decoration: none;
    transition: all .15s ease; white-space: nowrap;
}
.nav-link:hover { background: #f0f2f8; color: #1a1f36; text-decoration: none; }
.nav-link-active {
    background: #eef3fd; color: #5b8dee; font-weight: 600;
}
.nav-link svg { opacity: .7; }
.nav-link-active svg { opacity: 1; }

/* ── User ──────────────────────────────────────── */
.nav-user { position: relative; margin-left: auto; flex-shrink: 0; }
.nav-user-btn {
    display: flex; align-items: center; gap: .6rem;
    padding: .35rem .55rem .35rem .35rem;
    border-radius: 9px; border: 1px solid #e4e7f0;
    background: #fff; cursor: pointer;
    transition: all .15s ease;
}
.nav-user-btn:hover { background: #f8f9fd; border-color: #d0d5e8; }
.nav-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: linear-gradient(135deg, #5b8dee, #6c63ff);
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; font-weight: 700; color: #fff;
    flex-shrink: 0; letter-spacing: .02em;
}
.nav-user-info { text-align: left; }
.nav-user-name { display: block; font-size: .8rem; font-weight: 600; color: #1a1f36; line-height: 1.2; }
.nav-user-role { display: block; font-size: .68rem; color: #9ba8c5; font-weight: 500; }
.nav-chevron { color: #9ba8c5; transition: transform .15s ease; flex-shrink: 0; }
.nav-chevron.rotated { transform: rotate(180deg); }

/* ── Dropdown ──────────────────────────────────── */
.nav-dropdown {
    position: absolute; top: calc(100% + 8px); right: 0;
    width: 230px;
    background: #fff; border: 1px solid #e4e7f0;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(31,45,80,.12), 0 2px 8px rgba(31,45,80,.06);
    overflow: hidden;
}
[x-cloak] { display: none !important; }

.dd-enter { transition: opacity .15s ease, transform .15s ease; }
.dd-enter-start { opacity: 0; transform: translateY(-6px) scale(.97); }
.dd-enter-end { opacity: 1; transform: translateY(0) scale(1); }
.dd-leave { transition: opacity .1s ease; }
.dd-leave-start { opacity: 1; }
.dd-leave-end { opacity: 0; }

.nav-dd-header {
    padding: 1rem 1.1rem .7rem;
    display: flex; align-items: center; gap: .65rem;
}
.nav-dd-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, #5b8dee, #6c63ff);
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 700; color: #fff; flex-shrink: 0;
}
.nav-dd-name { font-size: .83rem; font-weight: 600; color: #1a1f36; }
.nav-dd-email { font-size: .73rem; color: #9ba8c5; margin-top: .1rem; }

.nav-dd-role-badge { padding: 0 1.1rem .75rem; }
.role-chip {
    display: inline-block; padding: .18rem .6rem;
    border-radius: 20px; font-size: .68rem; font-weight: 700;
    letter-spacing: .04em;
}
.role-admin   { background: #eef3fd; color: #5b8dee; border: 1px solid #dce8fb; }
.role-rbo     { background: #e6faf9; color: #0cb8b6; border: 1px solid #b2efed; }
.role-delegue { background: #f0eeff; color: #7c6fcd; border: 1px solid #d9d4f7; }
.role-abo     { background: #fff8ec; color: #e8a020; border: 1px solid #fde8b5; }

.nav-dd-divider { height: 1px; background: #e4e7f0; margin: 0 .8rem .4rem; }

.nav-dd-item {
    display: flex; align-items: center; gap: .55rem;
    width: 100%; padding: .65rem 1.1rem;
    font-family: 'DM Sans', sans-serif;
    font-size: .82rem; font-weight: 500;
    color: #525f7f; background: none; border: none;
    cursor: pointer; text-align: left;
    transition: background .12s ease;
    text-decoration: none;
}
.nav-dd-item:hover { background: #f8f9fd; color: #1a1f36; text-decoration: none; }
.nav-dd-item-danger:hover { background: #fef0f2; color: #e8506a; }

.nav-dd-footer { padding: .4rem .5rem; }

/* ── Mobile hamburger ──────────────────────────── */
.nav-hamburger {
    display: none; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 7px;
    border: 1px solid #e4e7f0; background: #fff;
    color: #525f7f; cursor: pointer; margin-left: auto;
    flex-shrink: 0;
}
.nav-mobile-drawer {
    display: none; flex-direction: column;
    padding: .5rem 1rem .75rem; border-top: 1px solid #e4e7f0;
    background: #fff;
}
.nav-mobile-drawer.open { display: flex; }
.nav-mobile-link {
    padding: .65rem .75rem; border-radius: 7px;
    font-size: .85rem; font-weight: 500; color: #525f7f;
    text-decoration: none; background: none; border: none;
    cursor: pointer; text-align: left; width: 100%;
    transition: background .12s ease; font-family: 'DM Sans', sans-serif;
}
.nav-mobile-link:hover { background: #f0f2f8; color: #1a1f36; }
.nav-mobile-logout:hover { background: #fef0f2; color: #e8506a; }
.nav-mobile-divider { height: 1px; background: #e4e7f0; margin: .4rem 0; }

@media (max-width: 900px) {
    .nav-links { display: none; }
    .nav-user  { display: none; }
    .nav-hamburger { display: flex; }
}
</style>

<script>
document.getElementById('navHamburger')?.addEventListener('click', function () {
    document.getElementById('navDrawer').classList.toggle('open');
});
</script>