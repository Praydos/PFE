@extends('layouts.app')

@section('content')
<div class="dr-page">
    <h1>Bon de retour – BSS {{ $bss->numero }}</h1>
    <form method="POST" action="{{ route('retours.store', $bss) }}" id="retourForm">
        @csrf
        <input type="hidden" name="numero" value="{{ $numero }}">

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Date de retour *</label>
                <input type="date" name="date_retour" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
                <label for="motif">Motif du retour</label>
                <textarea name="motif" id="motif" class="form-control" rows="3" placeholder="Expliquez la raison du retour...">{{ old('motif') }}</textarea>
            </div>
        </div>

        <hr>
        <h3>Articles à retourner</h3>
        <table class="dr-table">
            <thead>
                <tr><th>Sélection</th><th>Produit</th><th>Quantité livrée</th><th>Quantité à retourner</th></tr>
            </thead>
            <tbody>
                @foreach($returnableLines as $index => $ligne)
                <tr>
                    <td>
                        <input type="checkbox" name="lignes[{{ $index }}][selected]" class="line-checkbox" data-index="{{ $index }}">
                        <input type="hidden" name="lignes[{{ $index }}][id]" value="{{ $ligne->id }}">
                    </td>
                    <td>{{ $ligne->product->titre }} ({{ $ligne->product->isbn_13 ?? $ligne->product->isbn_10 }})</td>
                    <td>{{ $ligne->quantity }}</td>
                    <td>
                        <input type="number" name="lignes[{{ $index }}][quantite]" class="form-control quantite-input" data-max="{{ $ligne->quantity }}" style="width:100px;" disabled>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <button type="submit" class="btn-dr btn-dr-primary" onclick="return confirm('Confirmer le retour des articles sélectionnés ?')">Créer le bon de retour</button>
            <a href="{{ route('bss.show', $bss) }}" class="btn-dr btn-dr-ghost">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.line-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const index = this.dataset.index;
            const quantiteInput = document.querySelector(`input[name="lignes[${index}][quantite]"]`);
            quantiteInput.disabled = !this.checked;
            if (!this.checked) quantiteInput.value = '';
        });
    });

    document.querySelectorAll('.quantite-input').forEach(input => {
        input.addEventListener('change', function() {
            let max = parseInt(this.dataset.max);
            let val = parseInt(this.value);
            if (isNaN(val)) val = 0;
            if (val > max) {
                alert(`La quantité retournée ne peut pas dépasser ${max}.`);
                this.value = max;
            }
            if (val < 0) this.value = 0;
        });
    });
</script>
@endpush