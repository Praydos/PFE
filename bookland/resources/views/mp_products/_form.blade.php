@php
    $p = $product;
    $code = old('code_article', $p->code_article ?? '');
    $editeur = old('editeur', $p->editeur ?? '');
    $nom = old('nom', $p->nom ?? '');
    $description = old('description', $p->description ?? '');
@endphp

<div style="display:flex;flex-direction:column;gap:1rem;">
    <div>
        <label style="display:block;font-size:.75rem;font-weight:600;color:#525f7f;margin-bottom:.35rem;">Code article <span style="color:#e8506a">*</span></label>
        <input type="text" name="code_article" value="{{ $code }}" required maxlength="100" style="width:100%;padding:.55rem .75rem;border:1px solid #e4e7f0;border-radius:8px;font-family:ui-monospace,monospace;">
    </div>
    <div>
        <label style="display:block;font-size:.75rem;font-weight:600;color:#525f7f;margin-bottom:.35rem;">Éditeur <span style="color:#e8506a">*</span></label>
        <select name="editeur" required style="width:100%;padding:.55rem .75rem;border:1px solid #e4e7f0;border-radius:8px;">
            <option value="">— Choisir —</option>
            <option value="Bookland" @selected($editeur === 'Bookland')>Bookland</option>
            <option value="Esprit du livre" @selected($editeur === 'Esprit du livre')>Esprit du livre</option>
        </select>
    </div>
    <div>
        <label style="display:block;font-size:.75rem;font-weight:600;color:#525f7f;margin-bottom:.35rem;">Nom <span style="color:#e8506a">*</span></label>
        <input type="text" name="nom" value="{{ $nom }}" required maxlength="255" style="width:100%;padding:.55rem .75rem;border:1px solid #e4e7f0;border-radius:8px;">
    </div>
    <div>
        <label style="display:block;font-size:.75rem;font-weight:600;color:#525f7f;margin-bottom:.35rem;">Description</label>
        <textarea name="description" rows="4" style="width:100%;padding:.55rem .75rem;border:1px solid #e4e7f0;border-radius:8px;resize:vertical;">{{ $description }}</textarea>
    </div>
</div>
