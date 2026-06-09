<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Demandes Spéciales</title>
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
    <h1>Demandes Spéciales</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Compte / Contact</th>
                <th>Délégué</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($demandes as $d)
                <tr>
                    <td>#{{ $d->id }}</td>
                    <td>{{ ucfirst($d->type) }}</td>
                    <td>
                        @if($d->type == 'etablissement')
                            {{ $d->compte->etablissement ?? '-' }}
                        @else
                            {{ $d->contact->prenom ?? '' }} {{ $d->contact->nom ?? '-' }}
                        @endif
                    </td>
                    <td>{{ $d->delegate ? $d->delegate->prenom . ' ' . $d->delegate->nom : '-' }}</td>
                    <td>{{ $d->date_demande ? $d->date_demande->format('d/m/Y') : '-' }}</td>
                    <td>{{ ucfirst($d->statut) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>