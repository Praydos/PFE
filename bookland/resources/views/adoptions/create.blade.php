@extends('layouts.app')

@push('styles')
{{-- Same CSS block as in convert view (full zones style) --}}
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    /* ===== FULL CSS FROM ZONES EXAMPLE ===== */
    /* Paste the entire <style> block from the zones index here */
    /* (We'll include the essential parts; replace with the complete block) */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --bg-base:       #f5f6fa;
        --bg-card:       #ffffff;
        --bg-hover:      #f8f9fd;
        --bg-subtle:     #f0f2f8;
        --border:        #e4e7f0;
        --border-md:     #d0d5e8;
        --blue:          #5b8dee;
        --blue-dark:     #3d6fd6;
        --blue-light:    #eef3fd;
        --blue-mid:      #dce8fb;
        --teal:          #0cb8b6;
        --teal-light:    #e6faf9;
        --violet:        #7c6fcd;
        --violet-light:  #f0eeff;
        --amber:         #e8a020;
        --amber-light:   #fff8ec;
        --rose:          #e8506a;
        --rose-light:    #fef0f2;
        --green:         #28c76f;
        --green-light:   #e8fbf0;
        --text-primary:   #1a1f36;
        --text-secondary: #525f7f;
        --text-muted:     #9ba8c5;
        --text-hint:      #bcc5dc;
        --r-xs: 6px; --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 20px;
        --shadow-xs: 0 1px 3px rgba(31,45,80,.06), 0 1px 2px rgba(31,45,80,.04);
        --shadow-sm: 0 2px 8px rgba(31,45,80,.08), 0 1px 3px rgba(31,45,80,.05);
        --shadow-md: 0 8px 24px rgba(31,45,80,.10), 0 2px 8px rgba(31,45,80,.06);
        --shadow-blue: 0 4px 14px rgba(91,141,238,.35);
        --font: 'DM Sans', sans-serif;
        --font-mono: 'DM Mono', monospace;
        --ease: cubic-bezier(.4,0,.2,1);
        --t: .18s var(--ease);
    }

    body { font-family: var(--font); background: var(--bg-base); color: var(--text-primary); -webkit-font-smoothing: antialiased; }

    .zn-page { padding: 2rem 2.5rem 3rem; animation: pageIn .4s var(--ease) both; max-width: 900px; margin: 0 auto; }
    @keyframes pageIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .zn-bc { display: flex; align-items: center; gap: .4rem; font-size: .76rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.4rem; }
    .zn-bc a { color: var(--text-muted); text-decoration: none; transition: color var(--t); }
    .zn-bc a:hover { color: var(--blue); }
    .zn-bc-sep { color: var(--text-hint); }
    .zn-bc-cur { color: var(--text-secondary); }

    .zn-header { margin-bottom: 2rem; }
    .zn-header h1 { font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; color: var(--text-primary); line-height: 1.15; margin: 0; }
    .zn-header p { font-size: .83rem; color: var(--text-muted); margin-top: .3rem; }

    .btn-zn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .56rem 1.1rem; border-radius: var(--r-sm);
        font-family: var(--font); font-size: .82rem; font-weight: 600;
        cursor: pointer; border: 1px solid transparent;
        transition: all var(--t); text-decoration: none;
        white-space: nowrap; letter-spacing: -.01em; line-height: 1;
    }
    .btn-zn-primary { background: var(--blue); color: #fff; border-color: var(--blue); box-shadow: var(--shadow-blue); }
    .btn-zn-primary:hover { background: var(--blue-dark); color: #fff; transform: translateY(-1px); }
    .btn-zn-ghost { background: var(--bg-card); color: var(--text-secondary); border-color: var(--border); box-shadow: var(--shadow-xs); }
    .btn-zn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-md); }

    .zn-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--r-xl); box-shadow: var(--shadow-sm); overflow: hidden; }
    .zn-card-header { padding: 1.1rem 1.6rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: .55rem; background: linear-gradient(to bottom, #fafbff, #fff); }
    .zn-card-pip { width: 7px; height: 7px; border-radius: 50%; background: var(--amber); box-shadow: 0 0 0 3px rgba(232,160,32,.2); }
    .zn-card-title { font-size: .88rem; font-weight: 700; color: var(--text-primary); letter-spacing: -.01em; }
    .zn-card-body { padding: 1.75rem 1.6rem; }

    .frm-group {
        display: flex; flex-direction: column; gap: .45rem;
        margin-bottom: 1.25rem;
    }
    .frm-label {
        font-size: .8rem; font-weight: 600;
        color: var(--text-secondary); letter-spacing: -.01em;
    }
    .frm-label .req { color: var(--rose); margin-left: .2rem; }
    .frm-input, .frm-select {
        width: 100%; padding: .62rem .9rem;
        border: 1px solid var(--border); border-radius: var(--r-sm);
        background: var(--bg-card); font-family: var(--font);
        font-size: .84rem; color: var(--text-primary);
        box-shadow: var(--shadow-xs);
        transition: all var(--t);
        outline: none;
    }
    .frm-input:focus, .frm-select:focus {
        border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-mid);
    }
    .frm-select-wrap { position: relative; }
    .frm-select-wrap::after {
        content: '';
        position: absolute; right: .9rem; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid var(--text-muted);
        pointer-events: none;
    }
    .frm-select { padding-right: 2.2rem; cursor: pointer; }

    .card-footer {
        padding: 1.1rem 1.6rem;
        border-top: 1px solid var(--border);
        background: var(--bg-base);
        display: flex; align-items: center; justify-content: flex-end;
        gap: .6rem;
        margin-top: 1.5rem;
    }

    @media (max-width: 768px) {
        .zn-page { padding: 1.25rem 1rem 2rem; }
        .card-footer { flex-direction: column-reverse; }
        .btn-zn { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="zn-page">

    {{-- Breadcrumb --}}
    <div class="zn-bc">
        <a href="{{ route('adoptions.index') }}">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>
        <span class="zn-bc-sep">›</span>
        <a href="{{ route('adoptions.index') }}">Adoptions</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Nouvelle adoption manuelle</span>
    </div>

    <div class="zn-header">
        <h1>Nouvelle adoption manuelle</h1>
        <p>Créez une adoption directement sans passer par un BSS</p>
    </div>

    <div class="zn-card">
        <div class="zn-card-header">
            <span class="zn-card-pip"></span>
            <span class="zn-card-title">Formulaire d'adoption</span>
        </div>
        <div class="zn-card-body">
            <form method="POST" action="{{ route('adoptions.store') }}">
                @csrf
                @include('adoptions._form', ['adoption' => null])
                <div class="card-footer">
                    <button type="submit" class="btn-zn btn-zn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Enregistrer
                    </button>
                    <a href="{{ route('adoptions.index') }}" class="btn-zn btn-zn-ghost">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('compte_id').addEventListener('change', function() {
    const compteId = this.value;
    const contactSelect = document.querySelector('select[name="contact_id"]');
    if (!compteId) {
        contactSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un compte --</option>';
        return;
    }
    fetch(`/api/comptes/${compteId}/contacts`)
        .then(r => r.json())
        .then(data => {
            let html = '<option value="">-- Sélectionnez un contact --</option>';
            data.forEach(c => {
                html += `<option value="${c.id}">${c.prenom} ${c.nom} (${c.fonction || ''})</option>`;
            });
            contactSelect.innerHTML = html;
        });
});
</script>
@endsection