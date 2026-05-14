@extends('layouts.app')

@section('content')
<div class="zn-page" style="max-width:1200px;margin:0 auto;padding:2rem;">
    <div class="zn-bc" style="font-size:.8rem;color:#525f7f;margin-bottom:1rem;">
        <a href="{{ route('comptes.index') }}">Accueil</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Matériel pédagogique — livraisons</span>
    </div>

    <div class="zn-header" style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:700;margin:0;">Livraisons MP</h1>
            <p style="color:#525f7f;margin-top:.35rem;font-size:.9rem;">Suivi des livraisons de matériel pédagogique par établissement.</p>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'rbo', 'delegue']))
            <a href="{{ route('mp-deliveries.create') }}" class="btn-zn btn-zn-primary" style="display:inline-flex;align-items:center;padding:.55rem 1.1rem;border-radius:8px;background:#5b8dee;color:#fff;text-decoration:none;font-weight:600;font-size:.85rem;">Nouvelle livraison</a>
        @endif
    </div>

    @if(session('success'))
        <div style="padding:.75rem 1rem;background:#e8fbf0;color:#166534;border-radius:8px;margin-bottom:1rem;font-size:.9rem;">{{ session('success') }}</div>
    @endif

    <form method="get" action="{{ route('mp-deliveries.index') }}" style="display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-end;margin-bottom:1.25rem;padding:1rem;background:#fff;border:1px solid #e4e7f0;border-radius:12px;">
        <div>
            <label style="display:block;font-size:.7rem;font-weight:600;color:#525f7f;text-transform:uppercase;margin-bottom:.25rem;">Compte</label>
            <select name="compte_id" class="frm-select" style="min-width:200px;padding:.5rem;border:1px solid #e4e7f0;border-radius:8px;">
                <option value="">— Tous —</option>
                @foreach($comptesForFilter as $c)
                    <option value="{{ $c->id }}" @selected(request('compte_id') == $c->id)>{{ $c->etablissement }}</option>
                @endforeach
            </select>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'abo', 'rbo']) && $delegatesForFilter->isNotEmpty())
            <div>
                <label style="display:block;font-size:.7rem;font-weight:600;color:#525f7f;text-transform:uppercase;margin-bottom:.25rem;">Délégué</label>
                <select name="delegate_id" class="frm-select" style="min-width:180px;padding:.5rem;border:1px solid #e4e7f0;border-radius:8px;">
                    <option value="">— Tous —</option>
                    @foreach($delegatesForFilter as $d)
                        <option value="{{ $d->id }}" @selected(request('delegate_id') == $d->id)>{{ $d->prenom }} {{ $d->nom }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div>
            <label style="display:block;font-size:.7rem;font-weight:600;color:#525f7f;text-transform:uppercase;margin-bottom:.25rem;">Du</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="frm-input" style="padding:.5rem;border:1px solid #e4e7f0;border-radius:8px;">
        </div>
        <div>
            <label style="display:block;font-size:.7rem;font-weight:600;color:#525f7f;text-transform:uppercase;margin-bottom:.25rem;">Au</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="frm-input" style="padding:.5rem;border:1px solid #e4e7f0;border-radius:8px;">
        </div>
        <button type="submit" class="btn-zn btn-zn-primary" style="padding:.5rem 1rem;border-radius:8px;border:none;background:#5b8dee;color:#fff;font-weight:600;cursor:pointer;">Filtrer</button>
        <a href="{{ route('mp-deliveries.index') }}" style="font-size:.85rem;color:#5b8dee;">Réinitialiser</a>
    </form>

    <div style="background:#fff;border:1px solid #e4e7f0;border-radius:12px;overflow:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:.88rem;">
            <thead>
                <tr style="background:#f8f9fd;text-align:left;">
                    <th style="padding:.75rem 1rem;">Numéro</th>
                    <th style="padding:.75rem 1rem;">Compte</th>
                    <th style="padding:.75rem 1rem;">Contact</th>
                    <th style="padding:.75rem 1rem;">Article MP</th>
                    <th style="padding:.75rem 1rem;">Date livraison</th>
                    <th style="padding:.75rem 1rem;">Statut</th>
                    <th style="padding:.75rem 1rem;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $row)
                    <tr style="border-top:1px solid #e4e7f0;">
                        <td style="padding:.75rem 1rem;font-family:ui-monospace,monospace;">{{ $row->numero }}</td>
                        <td style="padding:.75rem 1rem;">{{ $row->compte?->etablissement ?? '—' }}</td>
                        <td style="padding:.75rem 1rem;">{{ $row->contact ? trim($row->contact->prenom.' '.$row->contact->nom) : '—' }}</td>
                        <td style="padding:.75rem 1rem;">{{ $row->mpProduct?->nom ?? '—' }} <span style="color:#9ba8c5;">({{ $row->mpProduct?->code_article }})</span></td>
                        <td style="padding:.75rem 1rem;">{{ $row->date_delivery?->format('d/m/Y') }}</td>
                        <td style="padding:.75rem 1rem;">
                            @if($row->statut === 'delivered') Livré @else Retourné @endif
                        </td>
                        <td style="padding:.75rem 1rem;white-space:nowrap;">
                            <a href="{{ route('mp-deliveries.show', $row) }}" style="color:#5b8dee;font-weight:600;">Voir</a>
                            @if(auth()->user()->role === 'admin')
                                <form action="{{ route('mp-deliveries.destroy', $row) }}" method="post" style="display:inline;margin-left:.75rem;" onsubmit="return confirm('Supprimer cette livraison MP ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#e8506a;font-weight:600;cursor:pointer;padding:0;">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="padding:2rem;text-align:center;color:#525f7f;">Aucune livraison MP.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $deliveries->links() }}</div>
</div>
@endsection
