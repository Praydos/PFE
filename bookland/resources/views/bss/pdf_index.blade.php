<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des BSS</title>
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
    <h1>Bons de Sortie Spécimens (BSS)</h1>
    <table>
        <thead>
            <tr>
                <th>N° BSS</th>
                <th>Date</th>
                <th>Compte</th>
                <th>Contact</th>
                <th>Délégué</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bssList as $bss)
                <tr>
                    <td>{{ $bss->numero }}</td>
                    <td>{{ $bss->date_bss ? $bss->date_bss->format('d/m/Y') : '-' }}</td>
                    <td>{{ $bss->compte ? $bss->compte->etablissement : '-' }}</td>
                    <td>{{ $bss->contact ? $bss->contact->prenom . ' ' . $bss->contact->nom : '-' }}</td>
                    <td>{{ $bss->delegate ? $bss->delegate->prenom . ' ' . $bss->delegate->nom : '-' }}</td>
                    <td>{{ ucfirst($bss->statut) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>