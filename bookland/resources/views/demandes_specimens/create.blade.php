@extends('layouts.app')

@push('styles')
<style>
    .product-row { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; align-items: flex-end; border-bottom: 1px solid #e4e7f0; padding-bottom: 1rem; }
    .product-row .frm-group { flex: 1; min-width: 150px; }
    .remove-product { cursor: pointer; }
    .dr-page { padding: 2rem 2.5rem 3rem; max-width: 900px; margin: 0 auto; }
    .dr-card { background: white; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
    .dr-card-body { padding: 1.75rem; }
    .btn-primary { background: #5b8dee; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; }
    .btn-secondary { background: #f1f5f9; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 8px; }
    .form-label { font-weight: 600; margin-bottom: 0.25rem; display: block; }
    .form-select, .form-control { width: 100%; padding: 0.5rem; border: 1px solid #e4e7f0; border-radius: 8px; }
</style>
@endpush

@section('content')
<div class="dr-page">
    <h1>Nouvelle demande spéciale</h1>
    <div class="dr-card">
        <div class="dr-card-body">
            <form method="POST" action="{{ route('demandes-specimens.store') }}">
                @csrf

                {{-- Type --}}
                <div class="mb-3">
                    <label class="form-label">Type *</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="etablissement">Établissement (lié à un compte)</option>
                        <option value="personnelle">Personnelle (sans compte)</option>
                    </select>
                </div>

                {{-- Compte --}}
                <div class="mb-3">
                    <label class="form-label">Compte</label>
                    <select name="compte_id" id="compte_id" class="form-select">
                        <option value="">-- Sélectionnez (pour type Établissement) --</option>
                        @foreach($comptes as $c)
                            <option value="{{ $c->id }}">{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Contact --}}
                <div class="mb-3">
                    <label class="form-label">Contact</label>
                    <select name="contact_id" id="contact_id" class="form-select">
                        <option value="">-- Sélectionnez un contact --</option>
                    </select>
                </div>

                {{-- Ville et zone --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Ville</label>
                        <select name="ville_id" id="ville_id" class="form-select">
                            <option value="">-- Sélectionnez --</option>
                            @foreach($villes as $v)
                                <option value="{{ $v->id }}">{{ $v->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Zone</label>
                        <select name="zone_id" id="zone_id" class="form-select">
                            <option value="">-- Sélectionnez --</option>
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Raison de la demande, informations complémentaires..."></textarea>
                </div>

                <hr>
                <h3 class="h5">Produits demandés</h3>
                <div id="products-container"></div>
                <button type="button" id="add-product" class="btn-secondary btn-sm mt-2">+ Ajouter un produit</button>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary">Créer la demande</button>
                    <a href="{{ route('demandes-specimens.index') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // DOM elements
    const compteSelect = document.getElementById('compte_id');
    const contactSelect = document.getElementById('contact_id');
    const villeSelect = document.getElementById('ville_id');
    const zoneSelect = document.getElementById('zone_id');

    // Helper: load contacts for a compte (using API)
    function loadContacts(compteId) {
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">-- Sélectionnez un contact --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                let options = '<option value="">-- Sélectionnez un contact --</option>';
                data.forEach(c => {
                    options += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`;
                });
                contactSelect.innerHTML = options;
            })
            .catch(err => console.error('Error loading contacts:', err));
    }

    // Helper: load compte details (ville_id, zone_id) and auto‑fill, then load contacts
    function loadCompteDetails(compteId) {
        if (!compteId) {
            villeSelect.value = '';
            zoneSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
            contactSelect.innerHTML = '<option value="">-- Sélectionnez un contact --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/details`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.ville_id) {
                    villeSelect.value = data.ville_id;
                    // After setting ville, load zones (and optionally select the one from data)
                    loadZonesForVille(data.ville_id, data.zone_id);
                }
                if (data.zone_id) {
                    zoneSelect.value = data.zone_id;
                }
                // Load contacts for this compte
                loadContacts(compteId);
            })
            .catch(err => console.error('Error loading compte details:', err));
    }

    // Helper: load zones for a given ville, optionally select a specific zone
    function loadZonesForVille(villeId, selectedZoneId = null) {
        if (!villeId) {
            zoneSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
            return;
        }
        fetch(`/api/villes/${villeId}/zones`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez une zone --</option>';
                data.forEach(z => {
                    options += `<option value="${z.id}">${z.name}</option>`;
                });
                zoneSelect.innerHTML = options;
                if (selectedZoneId) {
                    zoneSelect.value = selectedZoneId;
                }
            })
            .catch(err => console.error('Error loading zones:', err));
    }

    // Event listener: when compte changes, load details + contacts
    compteSelect.addEventListener('change', () => loadCompteDetails(compteSelect.value));

    // Event listener: when ville changes (manual), load zones
    villeSelect.addEventListener('change', () => loadZonesForVille(villeSelect.value));

    // Initial load: if a compte is pre‑selected (in edit mode), trigger the change
    if (compteSelect.value) {
        loadCompteDetails(compteSelect.value);
    }

    // Dynamic product rows (same as before)
    const productsData = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->titre . ' (' . ($p->isbn_13 ?? $p->isbn_10) . ')']));
    let productIndex = 0;
    const container = document.getElementById('products-container');

    function createProductRow(index) {
        const row = document.createElement('div');
        row.className = 'product-row';
        row.innerHTML = `
            <div class="frm-group">
                <label class="form-label">Produit *</label>
                <select name="products[${index}][product_id]" class="form-select" required>
                    <option value="">-- Sélectionnez --</option>
                    ${productsData.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                </select>
            </div>
            <div class="frm-group">
                <label class="form-label">Quantité *</label>
                <input type="number" name="products[${index}][quantity]" class="form-control" required min="1">
            </div>
            <button type="button" class="remove-product btn-danger btn-sm">X</button>
        `;
        row.querySelector('.remove-product').addEventListener('click', () => row.remove());
        return row;
    }

    function addProductRow() {
        const newRow = createProductRow(productIndex++);
        container.appendChild(newRow);
    }
    // Add initial product row
    addProductRow();
    document.getElementById('add-product').addEventListener('click', addProductRow);
</script>
@endsection