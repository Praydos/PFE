<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détails de la Demande</title>
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

        .info-table,
        .lines-table {
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

        .lines-table th,
        .lines-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .lines-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Demande spéciale #{{ $demandes_specimen->id }}</h1>
    <div class="subtitle">{{ ucfirst($demandes_specimen->type) }} –
        {{ $demandes_specimen->date_demande ? $demandes_specimen->date_demande->format('d/m/Y') : '-' }}</div>

    <div class="section-title">Informations de la demande</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Type:</td>
            <td>{{ ucfirst($demandes_specimen->type) }}</td>
            <td class="info-label">Délégué:</td>
            <td>{{ $demandes_specimen->delegate ? $demandes_specimen->delegate->prenom . ' ' . $demandes_specimen->delegate->nom : '-' }}
            </td>
        </tr>
        <tr>
            <td class="info-label">Compte:</td>
            <td>{{ $demandes_specimen->compte->etablissement ?? '-' }}</td>
            <td class="info-label">Contact:</td>
            <td>{{ optional($demandes_specimen->contact)->prenom ?? '' }}
                {{ optional($demandes_specimen->contact)->nom ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Zone:</td>
            <td>{{ $demandes_specimen->zone->name ?? '-' }}</td>
            <td class="info-label">Ville:</td>
            <td>{{ $demandes_specimen->ville->nom ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Statut:</td>
            <td>{{ ucfirst($demandes_specimen->statut) }}</td>
            <td class="info-label">BSS associé:</td>
            <td>{{ $demandes_specimen->originalBss ? $demandes_specimen->originalBss->numero : '-' }}</td>
        </tr>
        @if($demandes_specimen->description)
            <tr>
                <td class="info-label">Description:</td>
                <td colspan="3">{{ $demandes_specimen->description }}</td>
            </tr>
        @endif
    </table>

    <div class="section-title">Produits demandés</div>
    <table class="lines-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>ISBN</th>
                <th>Quantité</th>
            </tr>
        </thead>
        <tbody>
            @foreach($demandes_specimen->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->product->titre }}</td>
                    <td>{{ $ligne->product->isbn_13 ?? $ligne->product->isbn_10 ?? '-' }}</td>
                    <td>{{ $ligne->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>