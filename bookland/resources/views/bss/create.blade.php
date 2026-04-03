@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Nouveau BSS</h1>
    <form method="POST" action="{{ route('bss.store') }}" id="bssForm">
        @csrf
        @if(auth()->user()->role !== 'delegue')
        <div class="row">
            <div class="col-md-6">
                <div class="frm-group">
                    <label>Délégué *</label>
                    <select name="delegate_id" class="frm-select" id="delegateSelect" required>
                        <option value="">-- Sélectionnez --</option>
                        @foreach($delegates as $del)
                            <option value="{{ $del->id }}" {{ $delegateId == $del->id ? 'selected' : '' }}>{{ $del->prenom }} {{ $del->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @else
            <input type="hidden" name="delegate_id" value="{{ auth()->user()->id }}">
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="frm-group">
                    <label>Compte *</label>
                    <select name="compte_id" class="frm-select" id="compteSelect" required>
                        <option value="">-- Sélectionnez --</option>
                        @foreach($comptes as $c)
                            <option value="{{ $c->id }}">{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="frm-group">
                    <label>Contact *</label>
                    <select name="contact_id" class="frm-select" id="contactSelect" required>
                        <option value="">-- Choisissez un compte d'abord --</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="frm-group">
                    <label>Source</label>
                    <select name="source" class="frm-select">
                        <option value="consignation">Consignation</option>
                        <option value="magasin">Magasin</option>
                        <option value="transport">Transport</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="frm-group">
                    <label>Date BSS *</label>
                    <input type="date" name="date_bss" class="frm-input" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="frm-group">
                    <label>Date livraison prévue</label>
                    <input type="date" name="date_livraison" class="frm-input">
                </div>
            </div>
        </div>

        <div class="frm-group">
            <label>Produits (depuis votre consignation)</label>
            <table class="table" id="productsTable">
                <thead>
                    <tr><th>Produit</th><th>Quantité</th><th>Stock dispo</th><th></th></tr>
                </thead>
                <tbody id="productsTbody">
                    <tr><td colspan="4" class="text-center">Cliquez sur "Ajouter un produit"</td></tr>
                </tbody>
            </table>
            <button type="button" class="btn-dr btn-dr-sm btn-dr-ghost" id="addProductBtn">Ajouter un produit</button>
        </div>

        <div class="frm-group">
            <label>Observation</label>
            <textarea name="observation" class="frm-textarea" rows="2"></textarea>
        </div>

        <button type="submit" class="btn-dr btn-dr-primary">Créer le BSS</button>
        <a href="{{ route('bss.index') }}" class="btn-dr btn-dr-ghost">Annuler</a>
    </form>
</div>

@push('scripts')
<script>
    // Load contacts when compte changes
    document.getElementById('compteSelect').addEventListener('change', function() {
        const compteId = this.value;
        const contactSelect = document.getElementById('contactSelect');
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">-- Sélectionnez un compte d\'abord --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(res => res.json())
            .then(data => {
                contactSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
                data.forEach(c => {
                    contactSelect.innerHTML += `<option value="${c.id}">${c.prenom} ${c.nom}</option>`;
                });
            });
    });

    // Dynamic product rows with stock info
    let productIndex = 0;
    const productsData = @json($products);
    const stockData = @json($stock);

    document.getElementById('addProductBtn').addEventListener('click', function() {
        const tbody = document.getElementById('productsTbody');
        if (tbody.children.length === 1 && tbody.children[0].innerText.includes('Aucun produit')) {
            tbody.innerHTML = '';
        }
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="products[${productIndex}][product_id]" class="frm-select product-select" required>
                    <option value="">-- Produit --</option>
                    ${Object.values(productsData).map(p => `<option value="${p.id}" data-stock="${stockData[p.id] || 0}">${p.titre} (${p.isbn_13 || p.isbn_10})</option>`).join('')}
                </select>
            </td>
            <td><input type="number" name="products[${productIndex}][quantite]" class="frm-input qty" min="1" value="1" required></td>
            <td class="stock-display">-</td>
            <td><button type="button" class="btn-dr btn-dr-sm btn-dr-danger remove-row">×</button></td>
        `;
        tbody.appendChild(row);

        const productSelect = row.querySelector('.product-select');
        const stockCell = row.querySelector('.stock-display');
        productSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const stock = selected.dataset.stock || 0;
            stockCell.innerHTML = `<span class="dr-badge">${stock}</span>`;
        });
        row.querySelector('.remove-row').addEventListener('click', () => row.remove());
        productIndex++;
    });

    // If delegate changes (for admin/RBO), reload page with selected delegate to refresh stock
    const delegateSelect = document.getElementById('delegateSelect');
    if (delegateSelect) {
        delegateSelect.addEventListener('change', function() {
            const delegateId = this.value;
            if (delegateId) {
                window.location.href = `{{ route('bss.create') }}?delegate_id=${delegateId}`;
            }
        });
    }
</script>
@endpush
@endsection