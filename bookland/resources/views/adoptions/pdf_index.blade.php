<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Adoptions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Liste des Adoptions de Manuels</h1>
    <table>
        <thead>
            <tr>
                <th>Compte</th>
                <th>Produit</th>
                <th>Délégué</th>
                <th>Année Sc.</th>
                <th>Quantité</th>
                <th>Date adoption</th>
            </tr>
        </thead>
        <tbody>
            @foreach($adoptions as $a)
                <tr>
                    <td>{{ $a->compte ? $a->compte->etablissement : '-' }}</td>
                    <td>
                        {{ $a->product ? $a->product->titre : '-' }}<br>
                        <small>{{ $a->product->isbn_13 ?? $a->product->isbn_10 ?? '-' }}</small>
                    </td>
                    <td>{{ $a->delegate ? $a->delegate->prenom . ' ' . $a->delegate->nom : '-' }}</td>
                    <td>{{ $a->anneeScolaire ? $a->anneeScolaire->libelle : '-' }}</td>
                    <td>{{ $a->quantity }}</td>
                    <td>{{ $a->date_adoption ? $a->date_adoption->format('d/m/Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>