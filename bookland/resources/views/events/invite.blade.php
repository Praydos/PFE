@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Inviter des contacts - {{ $event->type }}</h1>
    <p>Événement du {{ $event->date_event->format('d/m/Y') }}</p>

    <div class="mb-3">
        <button type="button" id="invite-by-city-btn" class="btn-primary">Inviter par ville</button>
        <button type="button" id="invite-manual-btn" class="btn-ghost">Sélectionner des contacts</button>
    </div>

    <div id="invite-by-city-panel" style="display:none;">
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Sélectionnez les villes</label>
                <select id="city-select" multiple class="form-select">
                    @foreach($villes as $v)
                        <option value="{{ $v->id }}">{{ $v->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="button" id="load-contacts-by-city" class="btn-primary">Charger les contacts</button>
            </div>
        </div>
        <div id="contacts-by-city-list" class="mb-3"></div>
    </div>

    <div id="invite-manual-panel" style="display:none;">
        <div class="mb-3">
            <input type="text" id="contact-search" class="form-control" placeholder="Rechercher un contact...">
            <div id="contacts-list" class="mt-2" style="max-height: 300px; overflow-y: auto;"></div>
        </div>
    </div>

    <form method="POST" action="{{ route('events.store-invitations', $event) }}" id="invite-form">
        @csrf
        <input type="hidden" name="contact_ids" id="selected-contact-ids">
        <div class="mt-3">
            <button type="submit" class="btn-primary">Envoyer les invitations</button>
            <a href="{{ route('events.show', $event) }}" class="btn-ghost">Annuler</a>
        </div>
    </form>
</div>

<script>
    let selectedContacts = [];

    function updateSelectedIds() {
        document.getElementById('selected-contact-ids').value = selectedContacts.join(',');
    }

    // Load all contacts for manual selection
    fetch('{{ route("api.events.all-contacts") }}')
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('contacts-list');
            container.innerHTML = data.map(c => `
                <div class="form-check">
                    <input type="checkbox" class="form-check-input contact-checkbox" value="${c.id}" id="contact_${c.id}">
                    <label class="form-check-label" for="contact_${c.id}">${c.name} (${c.ville}) - ${c.fonction || ''}</label>
                </div>
            `).join('');
            document.querySelectorAll('.contact-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.checked) selectedContacts.push(parseInt(this.value));
                    else selectedContacts = selectedContacts.filter(id => id !== parseInt(this.value));
                    updateSelectedIds();
                });
            });
        });

    // Invite by city
    document.getElementById('invite-by-city-btn').addEventListener('click', () => {
        document.getElementById('invite-by-city-panel').style.display = 'block';
        document.getElementById('invite-manual-panel').style.display = 'none';
    });
    document.getElementById('invite-manual-btn').addEventListener('click', () => {
        document.getElementById('invite-manual-panel').style.display = 'block';
        document.getElementById('invite-by-city-panel').style.display = 'none';
    });

    document.getElementById('load-contacts-by-city').addEventListener('click', () => {
    const cityIds = Array.from(document.getElementById('city-select').selectedOptions).map(opt => opt.value);
    if (!cityIds.length) return alert('Sélectionnez au moins une ville.');

    const params = cityIds.map(id => `ville_ids[]=${id}`).join('&');
    fetch(`{{ route('api.events.contacts-by-city') }}?${params}`)
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('contacts-by-city-list');
            container.innerHTML = data.map(c => `
                <div class="form-check">
                    <input type="checkbox" class="form-check-input city-contact-checkbox"
                        value="${c.id}" id="city_contact_${c.id}" checked>
                    <label class="form-check-label" for="city_contact_${c.id}">
                        ${c.name} (${c.ville}) - ${c.fonction || ''}
                    </label>
                </div>
            `).join('');

            // Pre-add all to selectedContacts
            data.forEach(c => {
                if (!selectedContacts.includes(c.id)) {
                    selectedContacts.push(c.id);
                }
            });
            updateSelectedIds();

            // Handle unchecking
            document.querySelectorAll('.city-contact-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        if (!selectedContacts.includes(parseInt(this.value)))
                            selectedContacts.push(parseInt(this.value));
                    } else {
                        selectedContacts = selectedContacts.filter(id => id !== parseInt(this.value));
                    }
                    updateSelectedIds();
                });
            });
        });
});
</script>
@endsection