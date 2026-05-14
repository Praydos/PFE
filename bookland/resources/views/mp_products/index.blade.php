@extends('layouts.app')

@section('content')
<div class="zn-page" style="max-width:1100px;margin:0 auto;padding:2rem;">
    <div class="zn-bc" style="font-size:.8rem;color:#525f7f;margin-bottom:1rem;">
        <a href="{{ route('comptes.index') }}">Accueil</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Catalogue matériel pédagogique (admin)</span>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:700;margin:0;">Articles MP</h1>
            <p style="color:#525f7f;margin-top:.35rem;font-size:.9rem;">Gestion du catalogue (réservé administrateurs).</p>
        </div>
        <a href="{{ route('mp-products.create') }}" style="display:inline-flex;align-items:center;padding:.55rem 1.1rem;border-radius:8px;background:#5b8dee;color:#fff;text-decoration:none;font-weight:600;font-size:.85rem;">Nouvel article</a>
    </div>

    @if(session('success'))
        <div style="padding:.75rem 1rem;background:#e8fbf0;color:#166534;border-radius:8px;margin-bottom:1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="padding:.75rem 1rem;background:#fef0f2;color:#b91c1c;border-radius:8px;margin-bottom:1rem;">{{ session('error') }}</div>
    @endif

    <form method="get" action="{{ route('mp-products.index') }}" style="display:flex;gap:.75rem;align-items:flex-end;margin-bottom:1rem;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:.7rem;font-weight:600;color:#525f7f;margin-bottom:.25rem;">Recherche</label>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Code, nom, éditeur…" style="width:100%;padding:.5rem .75rem;border:1px solid #e4e7f0;border-radius:8px;">
        </div>
        <button type="submit" style="padding:.5rem 1rem;background:#5b8dee;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Filtrer</button>
        <a href="{{ route('mp-products.index') }}" style="font-size:.85rem;color:#5b8dee;line-height:2.5rem;">Réinitialiser</a>
    </form>

    <div style="background:#fff;border:1px solid #e4e7f0;border-radius:12px;overflow:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
            <thead>
                <tr style="background:#f8f9fd;text-align:left;">
                    <th style="padding:.75rem 1rem;">Code article</th>
                    <th style="padding:.75rem 1rem;">Éditeur</th>
                    <th style="padding:.75rem 1rem;">Nom</th>
                    <th style="padding:.75rem 1rem;">Livraisons</th>
                    <th style="padding:.75rem 1rem;width:200px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr style="border-top:1px solid #e4e7f0;">
                        <td style="padding:.75rem 1rem;font-family:ui-monospace,monospace;">{{ $p->code_article }}</td>
                        <td style="padding:.75rem 1rem;">{{ $p->editeur }}</td>
                        <td style="padding:.75rem 1rem;">{{ $p->nom }}</td>
                        <td style="padding:.75rem 1rem;color:#525f7f;">{{ $p->deliveries_count }}</td>
                        <td style="padding:.75rem 1rem;white-space:nowrap;">
                            <a href="{{ route('mp-products.edit', $p) }}" style="color:#5b8dee;font-weight:600;">Modifier</a>
                            <form action="{{ route('mp-products.destroy', $p) }}" method="post" style="display:inline;margin-left:.75rem;" onsubmit="return confirm('Supprimer cet article MP ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#e8506a;font-weight:600;cursor:pointer;padding:0;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="padding:2rem;text-align:center;color:#525f7f;">Aucun article.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $products->links() }}</div>

    <p style="margin-top:1.5rem;font-size:.85rem;">
        <a href="{{ route('mp-deliveries.index') }}" style="color:#5b8dee;">← Livraisons MP</a>
    </p>
</div>
@endsection
