<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détails de l'Adoption</title>
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
    </style>
</head>

<body>
    <h1>Adoption #{{ $adoption->id }}</h1>
    <div class="subtitle">Enregistrée le
        {{ $adoption->date_adoption ? $adoption->date_adoption->format('d/m/Y') : '-' }}</div>

    <div class="section-title">Informations de l'Adoption</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Compte:</td>
            <td>{{ $adoption->compte ? $adoption->compte->etablissement : '-' }}</td>
            <td class="info-label">Délégué:</td>
            <td>{{ $adoption->delegate ? $adoption->delegate->prenom . ' ' . $adoption->delegate->nom : '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Contact:</td>
            <td>{{ $adoption->contact ? $adoption->contact->prenom . ' ' . $adoption->contact->nom : '-' }}</td>
            <td class="info-label">Type d'adoption:</td>
            <td>{{ $adoption->type_adoption }}</td>
        </tr>
        <tr>
            <td class="info-label">Produit:</td>
            <td colspan="3">
                {{ $adoption->product ? $adoption->product->titre . ' (' . ($adoption->product->isbn_13 ?? $adoption->product->isbn_10 ?? '-') . ')' : '-' }}
            </td>
        </tr>
        <tr>
            <td class="info-label">ISBN manuel:</td>
            <td>{{ $adoption->isbn ?? '-' }}</td>
            <td class="info-label">Sous-catégorie:</td>
            <td>{{ $adoption->sous_categorie ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Méthode:</td>
            <td>{{ $adoption->methode }}</td>
            <td class="info-label">Année scolaire:</td>
            <td>{{ $adoption->anneeScolaire ? $adoption->anneeScolaire->libelle : '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Quantité:</td>
            <td>{{ $adoption->quantity }}</td>
            <td class="info-label">Niveau:</td>
            <td>{{ $adoption->niveau ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Cycle:</td>
            <td>{{ $adoption->cycle ?? '-' }}</td>
            <td class="info-label">BSS Source:</td>
            <td>{{ $adoption->bssLigne ? $adoption->bssLigne->bss->numero : '-' }}</td>
        </tr>
    </table>
</body>

</html>