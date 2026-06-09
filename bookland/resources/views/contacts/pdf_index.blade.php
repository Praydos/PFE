<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Contacts</title>
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
    <h1>Liste des Contacts</h1>
    <table>
        <thead>
            <tr>
                <th>Nom complet</th>
                <th>Téléphone</th>
                <th>Ville</th>
                <th>Catégories</th>
                <th>Cycles</th>
                <th>Comptes assignés</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $c)
                @php
                    $categories = is_array($c->categories) ? $c->categories : json_decode($c->categories, true);
                    $cycles = is_array($c->cycles) ? $c->cycles : json_decode($c->cycles, true);
                    $categoriesStr = is_array($categories) ? implode(', ', $categories) : '-';
                    $cyclesStr = is_array($cycles) ? implode(', ', $cycles) : '-';
                    $comptesStr = $c->comptes->count() > 0 ? $c->comptes->pluck('etablissement')->implode(', ') : '-';
                @endphp
                <tr>
                    <td>{{ $c->civilite }} {{ $c->prenom }} {{ $c->nom }}</td>
                    <td>{{ $c->telephone ?? '-' }}</td>
                    <td>{{ $c->ville ? $c->ville->nom : '-' }}</td>
                    <td>{{ $categoriesStr }}</td>
                    <td>{{ $cyclesStr }}</td>
                    <td>{{ $comptesStr }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>