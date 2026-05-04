@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ── exact same global CSS as previous forms (exam, formation, event) ── */
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
    .btn-zn-primary:hover { background: var(--blue-dark); border-color: var(--blue-dark); color: #fff; transform: translateY(-1px); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); text-decoration: none; }
    .btn-zn-secondary { background: var(--bg-subtle); color: var(--text-secondary); border-color: var(--border); }
    .btn-zn-secondary:hover { background: var(--bg-hover); color: var(--text-primary); }

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

    .fp-row { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.25rem; align-items: flex-end; }
    .fp-row:last-child { margin-bottom: 0; }
    .fp-row .frm-group { flex: 1; min-width: 180px; }

    .frm-group { display: flex; flex-direction: column; gap: .38rem; }
    .frm-label { font-size: .77rem; font-weight: 600; color: var(--text-secondary); letter-spacing: -.01em; }
    .frm-input, .frm-select {
        width: 100%; padding: .6rem .88rem;
        border: 1px solid var(--border); border-radius: var(--r-sm);
        background: var(--bg-card); font-family: var(--font);
        font-size: .83rem; color: var(--text-primary);
        box-shadow: var(--shadow-xs);
        transition: border-color var(--t), box-shadow var(--t);
        outline: none;
    }
    .frm-input:focus, .frm-select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: ''; position: absolute; right: .88rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent; border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted); pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }

    .checkbox-group {
        display: flex; flex-direction: column; gap: 0.5rem;
        max-height: 300px; overflow-y: auto;
        padding: 0.5rem 0;
        border-top: 1px solid var(--border);
        margin-top: 1rem;
    }
    .checkbox-item {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.4rem 0.2rem;
        border-radius: var(--r-sm);
        transition: background var(--t);
    }
    .checkbox-item:hover { background: var(--bg-hover); }
    .checkbox-item input[type="checkbox"] {
        width: 16px; height: 16px; cursor: pointer;
    }
    .checkbox-item label {
        font-size: .83rem; color: var(--text-secondary); cursor: pointer;
        flex: 1;
    }

    .btn-group-row {
        display: flex; gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .fp-footer {
        padding: 1.25rem 2rem;
        background: linear-gradient(to bottom, #fafbff, #fff);
        border-top: 1px solid var(--border);
        display: flex; align-items: center; gap: .75rem;
    }
    .fp-footer-spacer { flex: 1; }

    @media (max-width: 680px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .fp-row { flex-direction: column; align-items: stretch; }
        .btn-group-row { flex-direction: column; }
        .btn-zn { justify-content: center; }
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
        <span class="zn-bc-cur">Inviter des contacts</span>
    </div>

    <div class="zn-header">
        <h1>Inviter des contacts</h1>
        <p>{{ $event->type }} – {{ $event->date_event->format('d/m/Y') }}</p>
    </div>

    <div class="fp-card">
        <div class="fp-section">
            <div class="btn-group-row">
                <button type="button" id="invite-by-city-btn" class="btn-zn btn-zn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2a8 8 0 0 0-8 8c0 5 8 12 8 12s8-7 8-12a8 8 0 0 0-8-8z"/><circle cx="12" cy="10" r="3"/></svg>
                    Inviter par ville
                </button>
                <button type="button" id="invite-manual-btn" class="btn-zn btn-zn-secondary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Sélectionner des contacts
                </button>
            </div>

            {{-- Panels --}}
            <div id="invite-by-city-panel" style="display: none;">
                <div class="fp-row">
                    <div class="frm-group">
                        <label class="frm-label">Sélectionnez les villes</label>
                        <div class="frm-select-wrap">
                            <select id="city-select" multiple class="frm-select" size="4">
                                @foreach($villes as $v)
                                    <option value="{{ $v->id }}">{{ $v->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="frm-group" style="justify-content: flex-end;">
                        <button type="button" id="load-contacts-by-city" class="btn-zn btn-zn-primary" style="margin-bottom:0;">Charger les contacts</button>
                    </div>
                </div>
                <div id="contacts-by-city-list" class="checkbox-group"></div>
            </div>

            <div id="invite-manual-panel" style="display: none;">
                <div class="frm-group">
                    <label class="frm-label">Rechercher un contact</label>
                    <input type="text" id="contact-search" class="frm-input" placeholder="Nom, fonction, ville...">
                </div>
                <div id="contacts-list" class="checkbox-group"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('events.store-invitations', $event) }}" id="invite-form">
            @csrf
            <input type="hidden" name="contact_ids" id="selected-contact-ids">
            <div class="fp-footer">
                <div class="fp-footer-spacer"></div>
                <a href="{{ route('events.show', $event) }}" class="btn-zn btn-zn-ghost">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="btn-zn btn-zn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Envoyer les invitations
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedContacts = [];

    function updateSelectedIds() {
        document.getElementById('selected-contact-ids').value = selectedContacts.join(',');
    }

    // Helper to build checkbox group HTML
    function renderContactList(container, contacts, preChecked = false) {
        container.innerHTML = contacts.map(c => `
            <div class="checkbox-item">
                <input type="checkbox" class="contact-checkbox" value="${c.id}" id="contact_${c.id}" ${preChecked ? 'checked' : ''}>
                <label for="contact_${c.id}">${c.name} (${c.ville})${c.fonction ? ' · ' + c.fonction : ''}</label>
            </div>
        `).join('');

        const checkboxes = container.querySelectorAll('.contact-checkbox');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const id = parseInt(this.value);
                if (this.checked) {
                    if (!selectedContacts.includes(id)) selectedContacts.push(id);
                } else {
                    selectedContacts = selectedContacts.filter(i => i !== id);
                }
                updateSelectedIds();
            });
        });

        if (preChecked) {
            contacts.forEach(c => {
                if (!selectedContacts.includes(c.id)) selectedContacts.push(c.id);
            });
            updateSelectedIds();
        }
    }

    // Load all contacts for manual panel
    fetch('{{ route("api.events.all-contacts") }}')
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('contacts-list');
            renderContactList(container, data, false);
            // add search filter
            const searchInput = document.getElementById('contact-search');
            searchInput?.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                const filtered = data.filter(c =>
                    c.name.toLowerCase().includes(term) ||
                    (c.ville && c.ville.toLowerCase().includes(term)) ||
                    (c.fonction && c.fonction.toLowerCase().includes(term))
                );
                renderContactList(container, filtered, false);
            });
        })
        .catch(err => console.error('Failed to load contacts:', err));

    // Toggle panels
    const cityPanel = document.getElementById('invite-by-city-panel');
    const manualPanel = document.getElementById('invite-manual-panel');
    document.getElementById('invite-by-city-btn').addEventListener('click', () => {
        cityPanel.style.display = 'block';
        manualPanel.style.display = 'none';
    });
    document.getElementById('invite-manual-btn').addEventListener('click', () => {
        manualPanel.style.display = 'block';
        cityPanel.style.display = 'none';
    });

    // Load contacts by city
    document.getElementById('load-contacts-by-city').addEventListener('click', () => {
        const citySelect = document.getElementById('city-select');
        const cityIds = Array.from(citySelect.selectedOptions).map(opt => opt.value);
        if (!cityIds.length) {
            alert('Sélectionnez au moins une ville.');
            return;
        }
        const params = cityIds.map(id => `ville_ids[]=${id}`).join('&');
        fetch(`{{ route("api.events.contacts-by-city") }}?${params}`)
            .then(r => r.json())
            .then(data => {
                const container = document.getElementById('contacts-by-city-list');
                renderContactList(container, data, true);
            })
            .catch(err => console.error('Error loading contacts by city:', err));
    });
</script>
@endsection