@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Nouveau BSS</h1>
    <form method="POST" action="{{ route('bss.store') }}" id="bssForm">
        @csrf
        <input type="hidden" name="numero" value="{{ $numero }}">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Compte *</label>
                <select name="compte_id" id="compte_id" class="form-select" required>
                    <option value="">-- Sélectionnez --</option>
                    @foreach($comptes as $c)
                        <option value="{{ $c->id }}">{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Contact *</label>
                <select name="contact_id" id="contact_id" class="form-select" required>
                    <option value="">-- D'abord choisir un compte --</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Date livraison prévue</label>
                <input type="date" name="date_livraison_prevue" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>Moyen de contact</label>
                <select name="moyen_contact" class="form-select">
                    <option value="">--</option>
                    <option value="telephone">Téléphone</option>
                    <option value="email">Email</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Date de création</label>
                <input type="text" class="form-control" value="{{ now()->format('d/m/Y') }}" readonly disabled>
                <small class="text-muted">Date automatique</small>
            </div>
        </div>

        {{-- Récupéré par --}}
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Récupéré par *</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="recupere_par_type" id="radio_contact" value="contact" required>
                        <label class="form-check-label" for="radio_contact">Contact (nom)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="recupere_par_type" id="radio_transport" value="transport">
                        <label class="form-check-label" for="radio_transport">Transport (numéro d'expédition)</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3" id="contact_field" style="display: none;">
            <div class="col-md-6">
                <label>Nom du contact *</label>
                <input type="text" name="recupere_par_nom_contact" id="recupere_par_nom_contact" class="form-control" placeholder="Nom complet">
            </div>
        </div>

        <div class="row mb-3" id="transport_field" style="display: none;">
            <div class="col-md-6">
                <label>Numéro d'expédition *</label>
                <input type="text" name="numero_expedition" id="numero_expedition" class="form-control" placeholder="Ex: EXP-12345">
            </div>
        </div>

        {{-- Contrôle document --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Contrôle document physique</label>
                <select name="controle_document" class="form-select">
                    <option value="">-- Non spécifié --</option>
                    <option value="OK">OK</option>
                    <option value="Absence signature">Absence signature</option>
                    <option value="Absence cachet">Absence cachet</option>
                    <option value="Absence Document">Absence Document</option>
                </select>
            </div>
        </div>

        <hr>
        <h3>Produits</h3>
        <div id="products-container">
            <div class="product-row mb-2" style="display:flex; gap:1rem;">
                <select name="products[0][product_id]" class="form-select product-select" style="flex:2" required>
                    <option value="">-- Produit --</option>
                    @foreach($consignations as $cons)
                        <option value="{{ $cons->product_id }}" data-stock="{{ $cons->quantity }}">{{ $cons->product->titre }} (stock: {{ $cons->quantity }})</option>
                    @endforeach
                </select>
                <input type="number" name="products[0][quantity]" class="form-control" placeholder="Quantité" style="flex:1" required>
                <button type="button" class="btn-dr btn-dr-danger remove-product" style="display:none;">X</button>
            </div>
        </div>
        <button type="button" id="add-product" class="btn-dr btn-dr-sm btn-dr-ghost mt-2">+ Ajouter un produit</button>

        <div class="mt-4">
            <button type="submit" class="btn-dr btn-dr-primary">Soumettre BSS</button>
            <a href="{{ route('bss.index') }}" class="btn-dr btn-dr-ghost">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Contact dropdown based on compte
    document.getElementById('compte_id').addEventListener('change', function() {
        let compteId = this.value;
        let contactSelect = document.getElementById('contact_id');
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">-- D\'abord choisir un compte --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let html = '<option value="">-- Sélectionnez --</option>';
                data.forEach(c => {
                    html += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`;
                });
                contactSelect.innerHTML = html;
            });
    });

    // Toggle fields for "Récupéré par"
    const radioContact = document.getElementById('radio_contact');
    const radioTransport = document.getElementById('radio_transport');
    const contactField = document.getElementById('contact_field');
    const transportField = document.getElementById('transport_field');
    const contactInput = document.getElementById('recupere_par_nom_contact');
    const transportInput = document.getElementById('numero_expedition');

    function toggleRecuperePar() {
        if (radioContact.checked) {
            contactField.style.display = 'flex';
            transportField.style.display = 'none';
            contactInput.required = true;
            transportInput.required = false;
        } else if (radioTransport.checked) {
            contactField.style.display = 'none';
            transportField.style.display = 'flex';
            contactInput.required = false;
            transportInput.required = true;
        } else {
            contactField.style.display = 'none';
            transportField.style.display = 'none';
            contactInput.required = false;
            transportInput.required = false;
        }
    }

    radioContact.addEventListener('change', toggleRecuperePar);
    radioTransport.addEventListener('change', toggleRecuperePar);

    // Dynamic product rows
    let productIndex = 1;
    document.getElementById('add-product').addEventListener('click', function() {
        const container = document.getElementById('products-container');
        const newRow = container.children[0].cloneNode(true);
        newRow.querySelectorAll('select, input').forEach(el => {
            let name = el.name;
            if (name) {
                el.name = name.replace(/\[\d+\]/, `[${productIndex}]`);
                el.value = '';
            }
        });
        newRow.querySelector('.remove-product').style.display = 'inline-block';
        container.appendChild(newRow);
        productIndex++;
        attachRemoveEvent(newRow);
    });

    function attachRemoveEvent(row) {
        row.querySelector('.remove-product')?.addEventListener('click', () => row.remove());
    }
    document.querySelectorAll('.product-row').forEach(row => attachRemoveEvent(row));
</script>
@endpush