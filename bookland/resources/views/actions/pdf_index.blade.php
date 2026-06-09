<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Actions</title>
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

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <h1>Liste des Actions Commerciales</h1>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Objet</th>
                <th>Compte</th>
                <th>Délégué</th>
                <th>Statut</th>
                <th>Type</th>
                <th>Contacts</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actions as $action)
                <tr>
                    <td>{{ $action->date_planification ? $action->date_planification->format('d/m/Y') : '-' }}</td>
                    <td>{{ $action->objet }}</td>
                    <td>{{ $action->compte ? $action->compte->etablissement : '-' }}</td>
                    <td>{{ $action->delegate ? $action->delegate->prenom . ' ' . $action->delegate->nom : '-' }}</td>
                    <td>{{ ucfirst($action->statut) }}</td>
                    <td>{{ ucfirst($action->type) }}</td>
                    <td>
                        @php
                            $contacts = $action->lignes->flatMap->contacts->unique('id');
                        @endphp
                        @if($contacts->count() > 0)
                            {{ $contacts->map(fn($c) => $c->prenom . ' ' . $c->nom)->join(', ') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>