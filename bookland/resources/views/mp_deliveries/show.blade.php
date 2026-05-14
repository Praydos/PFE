@extends('layouts.app')

@section('content')
<div class="zn-page" style="max-width:720px;margin:0 auto;padding:2rem;">
    <div class="zn-bc" style="font-size:.8rem;color:#525f7f;margin-bottom:1rem;">
        <a href="{{ route('mp-deliveries.index') }}">Livraisons MP</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">{{ $delivery->numero }}</span>
    </div>

    @if(session('success'))
        <div style="padding:.75rem 1rem;background:#e8fbf0;color:#166534;border-radius:8px;margin-bottom:1rem;">{{ session('success') }}</div>
    @endif

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;">
        <h1 style="font-size:1.45rem;font-weight:700;margin:0;">{{ $delivery->numero }}</h1>
        @if(auth()->user()->role === 'admin')
            <form action="{{ route('mp-deliveries.destroy', $delivery) }}" method="post" onsubmit="return confirm('Supprimer cette livraison ?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding:.45rem .9rem;background:#fef0f2;color:#e8506a;border:1px solid rgba(232,80,106,.25);border-radius:8px;font-weight:600;cursor:pointer;">Supprimer</button>
            </form>
        @endif
    </div>

    <div style="background:#fff;border:1px solid #e4e7f0;border-radius:12px;padding:1.5rem;line-height:1.6;">
        <p><strong>Compte :</strong> {{ $delivery->compte?->etablissement ?? '—' }}</p>
        <p><strong>Contact :</strong> {{ $delivery->contact ? trim($delivery->contact->prenom.' '.$delivery->contact->nom) : '—' }}</p>
        <p><strong>Délégué :</strong> {{ $delivery->delegate ? trim($delivery->delegate->prenom.' '.$delivery->delegate->nom) : '—' }}</p>
        <p><strong>Article MP :</strong> {{ $delivery->mpProduct?->nom }} <span style="color:#9ba8c5;">({{ $delivery->mpProduct?->code_article }})</span></p>
        <p><strong>Éditeur :</strong> {{ $delivery->mpProduct?->editeur }}</p>
        <p><strong>Date de livraison :</strong> {{ $delivery->date_delivery?->format('d/m/Y') }}</p>
        <p><strong>Année scolaire :</strong> {{ $delivery->anneeScolaire?->libelle ?? '—' }}</p>
        <p><strong>Statut :</strong>
            @if($delivery->statut === 'livre')
                Livré
            @else
                Planifié
            @endif
        </p>
        @if($delivery->linkedCommercialAction)
            <p style="margin-top:1rem;">
                <strong>Action commerciale liée :</strong>
                <a href="{{ route('actions.show', $delivery->linkedCommercialAction) }}" style="color:#5b8dee;font-weight:600;">{{ $delivery->linkedCommercialAction->objet }}</a>
            </p>
        @endif
    </div>

    <p style="margin-top:1.25rem;font-size:.85rem;color:#9ba8c5;">Après enregistrement, la livraison est figée (pas de modification).</p>
</div>
@endsection
