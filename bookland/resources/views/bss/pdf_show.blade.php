<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détails du BSS</title>
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
    <h1>BSS {{ $bss->numero }}</h1>
    <div class="subtitle">Créé le {{ $bss->date_bss ? $bss->date_bss->format('d/m/Y') : '-' }}</div>

    <div class="section-title">Informations générales</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Compte:</td>
            <td>{{ $bss->compte ? $bss->compte->etablissement : '-' }}</td>
            <td class="info-label">Délégué:</td>
            <td>{{ $bss->delegate ? $bss->delegate->prenom . ' ' . $bss->delegate->nom : '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Contact:</td>
            <td>{{ $bss->contact ? $bss->contact->prenom . ' ' . $bss->contact->nom : '-' }}</td>
            <td class="info-label">Statut:</td>
            <td>{{ ucfirst($bss->statut) }}</td>
        </tr>
        <tr>
            <td class="info-label">Récupéré par:</td>
            <td>{{ $bss->recupere_par_nom }} ({{ $bss->recupere_par_type }})</td>
            <td class="info-label">Moyen de contact:</td>
            <td>{{ $bss->moyen_contact ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Contrôle document:</td>
            <td colspan="3">{{ $bss->controle_document ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Produits ({{ $bss->lignes->count() }})</div>
    <table class="lines-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>ISBN</th>
                <th>Quantité</th>
                <th>Source</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bss->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->product->titre }}</td>
                    <td>{{ $ligne->product->isbn_13 ?? $ligne->product->isbn_10 ?? '-' }}</td>
                    <td>{{ $ligne->quantity }}</td>
                    <td>{{ $ligne->source ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>