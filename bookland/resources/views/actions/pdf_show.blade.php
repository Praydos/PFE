<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détails de l'Action</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
            margin-top: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 6px;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            width: 30%;
            color: #555;
        }

        .line-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .line-info {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <h1>{{ $action->objet }}</h1>
    <div class="subtitle">Action du
        {{ $action->date_planification ? $action->date_planification->format('d/m/Y') : '' }} –
        {{ $action->compte ? $action->compte->etablissement : '' }}</div>

    <div class="section-title">Détails de l'action</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Compte:</td>
            <td>{{ $action->compte ? $action->compte->etablissement : '-' }}</td>
            <td class="info-label">Délégué:</td>
            <td>{{ $action->delegate ? $action->delegate->prenom . ' ' . $action->delegate->nom : '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Date:</td>
            <td>{{ $action->date_planification ? $action->date_planification->format('d/m/Y') : '-' }}</td>
            <td class="info-label">Lieu:</td>
            <td>{{ $action->lieu ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Statut:</td>
            <td>{{ ucfirst($action->statut) }}</td>
            <td class="info-label">Type:</td>
            <td>{{ ucfirst($action->type) }}</td>
        </tr>
        @if($action->statut === 'realise' && $action->date_realisation)
            <tr>
                <td class="info-label">Réalisée le:</td>
                <td colspan="3">{{ $action->date_realisation->format('d/m/Y H:i') }}</td>
            </tr>
        @endif
    </table>

    @if($action->module_lie === 'mp_delivery' && ($mpDelivery ?? null))
        <div class="section-title">Livraison MP</div>
        <table class="info-table">
            <tr>
                <td class="info-label">N° livraison:</td>
                <td>{{ $mpDelivery->numero }}</td>
                <td class="info-label">Article:</td>
                <td>{{ $mpDelivery->mpProduct?->nom ?? '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Statut:</td>
                <td colspan="3">{{ ucfirst($mpDelivery->statut) }}</td>
            </tr>
        </table>
    @endif

    @if($action->rapport_titre)
        <div class="section-title">Rapport de réalisation</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Titre:</td>
                <td>{{ $action->rapport_titre }}</td>
                <td class="info-label">Date du rapport:</td>
                <td>{{ $action->rapport_date ? $action->rapport_date->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Description:</td>
                <td colspan="3">{!! nl2br(e($action->rapport_description)) !!}</td>
            </tr>
        </table>
    @endif

    <div class="section-title">Lignes d'action</div>
    @foreach($action->lignes as $line)
        <div class="line-box">
            <div class="line-info"><strong>Catégorie:</strong> {{ $line->categorie }} | <strong>Type:</strong>
                {{ $line->action_type }}</div>
            <div class="line-info"><strong>Moyen:</strong> {{ $line->moyen ?? '-' }} | <strong>Description:</strong>
                {{ $line->description ?? '-' }}</div>

            <div class="line-info">
                <strong>Contacts:</strong>
                {{ $line->contacts->map(fn($c) => $c->prenom . ' ' . $c->nom)->join(', ') ?: '-' }}
            </div>

            <div class="line-info">
                <strong>Produits:</strong>
                @if($line->products->count())
                    {{ $line->products->pluck('titre')->join(', ') }}
                @else -
                @endif
            </div>

            <div class="line-info">
                <strong>Examens:</strong>
                @if($line->examens->count())
                    {{ $line->examens->pluck('titre')->join(', ') }}
                @else -
                @endif
            </div>

            @if($line->bss)
                <div class="line-info"><strong>BSS associé:</strong> {{ $line->bss->numero }}</div>
            @endif
            @if($line->retour)
                <div class="line-info"><strong>Bon de retour:</strong> {{ $line->retour->numero }}</div>
            @endif
        </div>
    @endforeach

</body>

</html>