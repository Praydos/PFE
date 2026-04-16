@php
    $isEdit = isset($adoption) && $adoption; // not used in multi‑product mode
    $defaultCompteId = $defaultCompteId ?? old('compte_id');
    $defaultYearId = $defaultYearId ?? old('annee_scolaire_id');
    $defaultDate = $defaultDate ?? old('date_adoption', now()->format('Y-m-d'));
    $defaultContactId = $defaultContactId ?? old('contact_id');
    $defaultMethode = $defaultMethode ?? old('methode');
@endphp

<div class="form-row" style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem;">
    {{-- Compte --}}
    <div class="frm-group" style="flex:1; min-width:200px;">
        <label class="frm-label" for="compte_id">Compte <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="compte_id" id="compte_id" class="frm-select" required>
                <option value="">-- Sélectionnez --</option>
                @foreach($comptes as $c)
                    <option value="{{ $c->id }}" {{ $defaultCompteId == $c->id ? 'selected' : '' }}>{{ $c->etablissement }} ({{ $c->ville->nom }})</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Année scolaire --}}
    <div class="frm-group" style="flex:1; min-width:180px;">
        <label class="frm-label" for="annee_scolaire_id">Année scolaire <span class="req">*</span></label>
        <div class="frm-select-wrap">
            <select name="annee_scolaire_id" id="annee_scolaire_id" class="frm-select" required>
                @foreach($years as $y)
                    <option value="{{ $y->id }}" {{ $defaultYearId == $y->id ? 'selected' : '' }}>{{ $y->libelle }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Date adoption --}}
    <div class="frm-group" style="flex:1; min-width:150px;">
        <label class="frm-label" for="date_adoption">Date adoption <span class="req">*</span></label>
        <input type="date" name="date_adoption" id="date_adoption" class="frm-input" value="{{ $defaultDate }}" required>
    </div>

    {{-- Contact (shared) --}}
    <div class="frm-group" style="flex:1; min-width:200px;">
        <label class="frm-label">Contact *</label>
        <select name="contact_id" id="contact_id" class="frm-select" required>
            <option value="">-- Sélectionnez d'abord un compte --</option>
        </select>
    </div>

    {{-- Méthode (shared) --}}
    <div class="frm-group" style="flex:1; min-width:200px;">
        <label class="frm-label">Méthode *</label>
        <input type="text" name="methode" class="frm-input" value="{{ $defaultMethode }}" required>
    </div>
</div>

<hr>
<h3>Produits</h3>
<div id="products-container">
    <div class="product-row" style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1rem; align-items:flex-end;">
        <div class="frm-group" style="flex:2; min-width:200px;">
            <label class="frm-label">Produit *</label>
            <select name="products[0][product_id]" class="frm-select product-select" required>
                <option value="">-- Sélectionnez --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->titre }} ({{ $p->isbn_13 ?? $p->isbn_10 }})</option>
                @endforeach
            </select>
        </div>
        <div class="frm-group" style="flex:1; min-width:150px;">
            <label class="frm-label">Niveau</label>
            <select name="products[0][niveau]" class="frm-select niveau-select" required>
                <option value="">-- Niveau --</option>
            </select>
        </div>
        <div class="frm-group" style="flex:1; min-width:150px;">
            <label class="frm-label">Cycle</label>
            <select name="products[0][cycle]" class="frm-select cycle-select" required>
                <option value="">-- Cycle --</option>
                <option value="primaire">Primaire</option>
                <option value="college">Collège</option>
                <option value="Lycée">Lycée</option>
                <option value="Learners">Learners</option>
                <option value="Pre-teens">Pre-teens</option>
                <option value="Teens">Teens</option>
                <option value="Adults">Adults</option>
            </select>
        </div>
        <div class="frm-group" style="flex:1; min-width:100px;">
            <label class="frm-label">Quantité</label>
            <input type="number" name="products[0][quantity]" class="frm-input quantity-input" readonly style="background:#f5f5f5;" required>
        </div>
        <div>
            <button type="button" class="btn-zn btn-zn-danger remove-product" style="display:none;">X</button>
        </div>
    </div>
</div>
<button type="button" id="add-product" class="btn-zn btn-zn-sm btn-zn-ghost mt-2">+ Ajouter un produit</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const compteSelect = document.getElementById('compte_id');
    const yearSelect = document.getElementById('annee_scolaire_id');
    const contactSelect = document.getElementById('contact_id');
    let currentNiveaux = [];

    // Load contacts based on compte
    function loadContacts() {
        const compteId = compteSelect.value;
        if (!compteId) {
            contactSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
            return;
        }
        fetch(`/api/comptes/${compteId}/contacts`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">-- Sélectionnez un contact --</option>';
                data.forEach(c => options += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`);
                contactSelect.innerHTML = options;
                const defaultContactId = '{{ $defaultContactId }}';
                if (defaultContactId) contactSelect.value = defaultContactId;
            })
            .catch(err => console.error('Erreur chargement contacts:', err));
    }

    // Load niveaux for the compte and populate all rows
    function loadNiveauxForRows() {
        const compteId = compteSelect.value;
        if (!compteId) {
            document.querySelectorAll('.niveau-select').forEach(sel => {
                sel.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
            });
            return;
        }
        fetch(`/api/comptes/${compteId}/niveaux`)
            .then(r => r.json())
            .then(data => {
                currentNiveaux = data;
                const options = '<option value="">-- Sélectionnez un niveau --</option>' + data.map(n => `<option value="${n}">${n}</option>`).join('');
                document.querySelectorAll('.niveau-select').forEach(sel => {
                    sel.innerHTML = options;
                });
                // After loading, trigger quantity fetch for each row (if values already set)
                document.querySelectorAll('.product-row').forEach(row => fetchQuantityForRow(row));
            })
            .catch(err => console.error('Erreur chargement niveaux:', err));
    }

    // Fetch quantity for a single row
    function fetchQuantityForRow(row) {
        const compteId = compteSelect.value;
        const yearId = yearSelect.value;
        const niveauSelect = row.querySelector('.niveau-select');
        const cycleSelect = row.querySelector('.cycle-select');
        const quantityInput = row.querySelector('.quantity-input');
        const niveau = niveauSelect.value;
        const cycle = cycleSelect.value;

        if (!compteId || !yearId || !niveau || !cycle) {
            quantityInput.value = '';
            return;
        }

        fetch(`/api/comptes/${compteId}/effectif?annee_scolaire_id=${yearId}&niveau=${encodeURIComponent(niveau)}&cycle=${encodeURIComponent(cycle)}`)
            .then(r => r.json())
            .then(data => {
                if (data.effectif_valide !== null && data.effectif_valide > 0) {
                    quantityInput.value = data.effectif_valide;
                } else {
                    quantityInput.value = '';
                }
            })
            .catch(err => console.error('Erreur chargement effectif:', err));
    }

    // Attach event listeners to a row
    function attachRowEvents(row) {
        row.querySelector('.niveau-select').addEventListener('change', () => fetchQuantityForRow(row));
        row.querySelector('.cycle-select').addEventListener('change', () => fetchQuantityForRow(row));
        row.querySelector('.remove-product').addEventListener('click', () => row.remove());
    }

    // Add a new product row
    function addProductRow() {
        const container = document.getElementById('products-container');
        const prototype = container.querySelector('.product-row');
        const newRow = prototype.cloneNode(true);
        const index = container.children.length;
        newRow.querySelectorAll('select, input').forEach(el => {
            let name = el.name;
            if (name) {
                el.name = name.replace(/\[\d+\]/, `[${index}]`);
            }
            if (el.classList.contains('product-select')) el.value = '';
            if (el.classList.contains('niveau-select')) el.innerHTML = '<option value="">-- Niveau --</option>';
            if (el.classList.contains('cycle-select')) el.value = '';
            if (el.classList.contains('quantity-input')) el.value = '';
        });
        newRow.querySelector('.remove-product').style.display = 'inline-block';
        container.appendChild(newRow);
        attachRowEvents(newRow);
        // If niveaux already loaded, populate the new row's niveau dropdown
        if (currentNiveaux.length > 0) {
            const options = '<option value="">-- Sélectionnez un niveau --</option>' + currentNiveaux.map(n => `<option value="${n}">${n}</option>`).join('');
            newRow.querySelector('.niveau-select').innerHTML = options;
        }
    }

    // Initial row events
    document.querySelectorAll('.product-row').forEach(row => attachRowEvents(row));

    // Event listeners
    compteSelect.addEventListener('change', function() {
        loadContacts();
        loadNiveauxForRows();
    });
    yearSelect.addEventListener('change', function() {
        document.querySelectorAll('.product-row').forEach(row => fetchQuantityForRow(row));
    });
    document.getElementById('add-product').addEventListener('click', addProductRow);

    // Initial load
    if (compteSelect.value) {
        loadContacts();
        loadNiveauxForRows();
    }
});
</script>