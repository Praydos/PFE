@extends('layouts.app')

@section('content')
<div class="zn-page" style="max-width:720px;margin:0 auto;padding:2rem;">
    <div class="zn-bc" style="font-size:.8rem;color:#525f7f;margin-bottom:1rem;">
        <a href="{{ route('mp-deliveries.index') }}">Livraisons MP</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Nouvelle livraison</span>
    </div>

    <h1 style="font-size:1.45rem;font-weight:700;margin-bottom:.25rem;">Enregistrer une livraison</h1>
    <p style="color:#525f7f;margin-bottom:1.5rem;font-size:.9rem;">Année scolaire active : <strong>{{ $currentYear->libelle ?? '—' }}</strong></p>

    @if($errors->any())
        <div style="padding:1rem;background:#fef0f2;color:#b91c1c;border-radius:8px;margin-bottom:1rem;">
            <ul style="margin:0;padding-left:1.1rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="post" action="{{ route('mp-deliveries.store') }}" id="mp-delivery-form" style="background:#fff;border:1px solid #e4e7f0;border-radius:12px;padding:1.5rem;">
        @csrf

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-size:.75rem;font-weight:600;color:#525f7f;margin-bottom:.35rem;">Compte (établissement) <span style="color:#e8506a">*</span></label>
            <select name="compte_id" id="compte_id" required style="width:100%;padding:.55rem .75rem;border:1px solid #e4e7f0;border-radius:8px;">
                <option value="">— Choisir —</option>
                @foreach($comptes as $c)
                    <option value="{{ $c->id }}" @selected(old('compte_id') == $c->id)>{{ $c->etablissement }} @if($c->ville) ({{ $c->ville->nom }}) @endif</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-size:.75rem;font-weight:600;color:#525f7f;margin-bottom:.35rem;">Contact <span style="color:#e8506a">*</span></label>
            <select name="contact_id" id="contact_id" required style="width:100%;padding:.55rem .75rem;border:1px solid #e4e7f0;border-radius:8px;">
                <option value="">— Sélectionnez d'abord un compte —</option>
            </select>
        </div>

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-size:.75rem;font-weight:600;color:#525f7f;margin-bottom:.35rem;">Matériel pédagogique <span style="color:#e8506a">*</span></label>
            <select name="mp_product_id" required style="width:100%;padding:.55rem .75rem;border:1px solid #e4e7f0;border-radius:8px;">
                <option value="">— Choisir —</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" @selected(old('mp_product_id') == $p->id)>{{ $p->nom }} — {{ $p->code_article }} ({{ $p->editeur }})</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:1.5rem;">
            <label style="display:block;font-size:.75rem;font-weight:600;color:#525f7f;margin-bottom:.35rem;">Date de livraison <span style="color:#e8506a">*</span></label>
            <input type="date" name="date_delivery" value="{{ old('date_delivery', $defaultDate) }}" required style="width:100%;max-width:220px;padding:.55rem .75rem;border:1px solid #e4e7f0;border-radius:8px;">
        </div>

        <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
            <button type="submit" style="padding:.6rem 1.2rem;background:#5b8dee;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Enregistrer</button>
            <a href="{{ route('mp-deliveries.index') }}" style="padding:.6rem 1.2rem;border:1px solid #e4e7f0;border-radius:8px;color:#525f7f;text-decoration:none;font-weight:600;">Annuler</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    const compteSelect = document.getElementById('compte_id');
    const contactSelect = document.getElementById('contact_id');
    const oldContactId = @json(old('contact_id'));

    async function loadContacts(compteId) {
        contactSelect.innerHTML = '<option value="">Chargement…</option>';
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">— Sélectionnez d\'abord un compte —</option>';
            return;
        }
        try {
            const res = await fetch('{{ url('/api/comptes') }}/' + compteId + '/contacts', { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            contactSelect.innerHTML = '<option value="">— Choisir un contact —</option>';
            (data || []).forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = (c.prenom || '') + ' ' + (c.nom || '') + (c.fonction ? ' — ' + c.fonction : '');
                if (String(oldContactId) === String(c.id)) opt.selected = true;
                contactSelect.appendChild(opt);
            });
        } catch (e) {
            contactSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    }

    compteSelect.addEventListener('change', () => loadContacts(compteSelect.value));
    if (compteSelect.value) loadContacts(compteSelect.value);
})();
</script>
@endpush
@endsection
