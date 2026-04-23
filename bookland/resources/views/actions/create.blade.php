@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Nouvelle action</h1>
        <p>Créer une action commerciale ou une tâche</p>
    </div>
    <div class="dr-card">
        <div class="dr-card-body p-4">
            <form method="POST" action="{{ route('actions.store') }}" id="actionForm">
                @csrf
                @include('actions._form')

                <div class="mt-4">
                    <h5>Lignes d'action</h5>
                    <div id="lines-container">
                        <div class="line-item card p-3 mb-3" data-line-index="0">
                            @include('actions._line_form', ['lineIndex' => 0, 'line' => null, 'products' => $products, 'examens' => $examens])
                        </div>
                    </div>
                    <button type="button" id="add-line" class="btn-ghost btn-sm mt-2">+ Ajouter une ligne</button>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary">Créer</button>
                    <a href="{{ route('actions.index') }}" class="btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let lineCounter = 1;
    const categories = @json($categories);
    const requiresProduct = @json($requiresProduct);
    const requiresBss = @json($requiresBss);
    const requiresRetour = @json($requiresRetour);
    const requiresExamen = @json($requiresExamen);

    function loadContacts(compteId, selectElement) {
        if (!compteId) return;
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez --</option>';
                data.forEach(c => options += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`);
                selectElement.innerHTML = options;
            });
    }

    function loadActionTypes(categorie, selectElement) {
        selectElement.innerHTML = '<option value="">Chargement...</option>';
        fetch(`/api/action-types-by-categorie?categorie=${encodeURIComponent(categorie)}`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez --</option>';
                data.forEach(at => options += `<option value="${at}">${at}</option>`);
                selectElement.innerHTML = options;
                selectElement.dispatchEvent(new Event('change'));
            });
    }

    function loadMoyens(actionType, selectElement) {
        fetch(`/api/moyens-by-action-type?action_type=${encodeURIComponent(actionType)}`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez --</option>';
                data.forEach(m => options += `<option value="${m}">${m}</option>`);
                selectElement.innerHTML = options;
            });
    }

    function updateRequiredFields(line) {
        const actionType = line.querySelector('.action-type-select').value;
        const productGroup = line.querySelector('select[name$="[product_ids][]"]')?.closest('.col-md-4');
        const examenGroup = line.querySelector('select[name$="[examen_ids][]"]')?.closest('.col-md-4');
        const bssSelect = line.querySelector('.bss-select');
        const bssGroup = bssSelect?.closest('.col-md-4');
        const retourSelect = line.querySelector('.retour-select');
        const retourGroup = retourSelect?.closest('.col-md-4');

        if (productGroup) productGroup.style.display = 'none';
        if (examenGroup) examenGroup.style.display = 'none';
        if (bssGroup) bssGroup.style.display = 'none';
        if (retourGroup) retourGroup.style.display = 'none';

        if (requiresProduct.includes(actionType)) {
            if (productGroup) productGroup.style.display = 'block';
            if (line.querySelector('select[name$="[product_ids][]"]')) line.querySelector('select[name$="[product_ids][]"]').required = true;
        } else if (requiresBss.includes(actionType)) {
            if (bssGroup) bssGroup.style.display = 'block';
            if (bssSelect) bssSelect.required = true;
        } else if (requiresRetour.includes(actionType)) {
            if (retourGroup) retourGroup.style.display = 'block';
            if (retourSelect) retourSelect.required = true;
        } else if (requiresExamen.includes(actionType)) {
            if (examenGroup) examenGroup.style.display = 'block';
            if (line.querySelector('select[name$="[examen_ids][]"]')) line.querySelector('select[name$="[examen_ids][]"]').required = true;
        }
    }

    function attachLineEvents(lineElement) {
        const compteSelect = document.getElementById('compte_id');
        const categorieSelect = lineElement.querySelector('.categorie-select');
        const actionTypeSelect = lineElement.querySelector('.action-type-select');
        const moyenSelect = lineElement.querySelector('.moyen-select');
        const contactSelect = lineElement.querySelector('select[name$="[contact_ids][]"]');

        if (compteSelect) {
            compteSelect.addEventListener('change', () => loadContacts(compteSelect.value, contactSelect));
            if (compteSelect.value) loadContacts(compteSelect.value, contactSelect);
        }

        categorieSelect?.addEventListener('change', () => loadActionTypes(categorieSelect.value, actionTypeSelect));
        actionTypeSelect?.addEventListener('change', () => {
            loadMoyens(actionTypeSelect.value, moyenSelect);
            updateRequiredFields(lineElement);
        });
        if (categorieSelect?.value) loadActionTypes(categorieSelect.value, actionTypeSelect);
        if (actionTypeSelect?.value) {
            loadMoyens(actionTypeSelect.value, moyenSelect);
            updateRequiredFields(lineElement);
        }
    }

    document.getElementById('add-line').addEventListener('click', function() {
        const container = document.getElementById('lines-container');
        const template = container.querySelector('.line-item').cloneNode(true);
        const newIndex = lineCounter++;
        template.setAttribute('data-line-index', newIndex);
        template.querySelectorAll('[name]').forEach(el => {
            let name = el.name;
            if (name) {
                el.name = name.replace(/\[\d+\]/, `[${newIndex}]`);
            }
            if (el.tagName === 'SELECT' && el.name.includes('categorie')) el.selectedIndex = 0;
            if (el.tagName === 'SELECT' && el.name.includes('action_type')) el.innerHTML = '<option value="">-- D\'abord choisir catégorie --</option>';
            if (el.tagName === 'SELECT' && el.name.includes('moyen')) el.innerHTML = '<option value="">-- Sélectionnez --</option>';
            if (el.tagName === 'SELECT' && el.name.includes('contact_ids')) el.innerHTML = '<option value="">-- Sélectionnez --</option>';
            if (el.tagName === 'SELECT' && el.name.includes('product_ids')) Array.from(el.options).forEach(opt => opt.selected = false);
            if (el.tagName === 'SELECT' && el.name.includes('examen_ids')) Array.from(el.options).forEach(opt => opt.selected = false);
            if (el.tagName === 'SELECT' && el.name.includes('bss_id')) el.value = '';
            if (el.tagName === 'SELECT' && el.name.includes('retour_id')) el.value = '';
            if (el.tagName === 'INPUT') el.value = '';
        });
        container.appendChild(template);
        attachLineEvents(template);
    });

    document.querySelectorAll('.line-item').forEach(attachLineEvents);
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-line')) {
            const container = document.getElementById('lines-container');
            if (container.children.length > 1) {
                e.target.closest('.line-item').remove();
            } else {
                alert('Vous devez conserver au moins une ligne.');
            }
        }
    });
</script>
@endpush