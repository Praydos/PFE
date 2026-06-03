<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Non-Conformité {{ $non_conformite->numero }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #5b8dee; padding-bottom: 15px; }
        .header h1 { margin: 0; color: #1a1f36; font-size: 24px; }
        .header p { margin: 5px 0 0 0; color: #525f7f; font-size: 14px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 16px; font-weight: bold; color: #1a1f36; margin-bottom: 10px; border-bottom: 1px solid #e4e7f0; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 8px 10px; border: 1px solid #e4e7f0; text-align: left; vertical-align: top; }
        th { background-color: #f8f9fd; width: 35%; font-weight: bold; color: #525f7f; text-transform: uppercase; font-size: 10px; }
        td { color: #1a1f36; }
        .full-width-cell { text-align: left; }
        .footer { position: fixed; bottom: -30px; left: 0px; right: 0px; height: 50px; text-align: center; font-size: 10px; color: #9ba8c5; border-top: 1px solid #e4e7f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Non-Conformité {{ $non_conformite->numero }}</h1>
        <p>{{ $non_conformite->categorie }} – {{ ucfirst($non_conformite->statut) }}</p>
    </div>

    <div class="section">
        <div class="section-title">Informations Générales</div>
        <table>
            <tr>
                <th>Compte</th>
                <td>{{ $non_conformite->compte->etablissement ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Contact</th>
                <td>{{ $non_conformite->contact->prenom ?? '' }} {{ $non_conformite->contact->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date NC</th>
                <td>{{ $non_conformite->date_nc ? \Carbon\Carbon::parse($non_conformite->date_nc)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Catégorie / Sous-catégorie</th>
                <td>{{ $non_conformite->categorie }} @if($non_conformite->sous_categorie) / {{ $non_conformite->sous_categorie }} @endif</td>
            </tr>
            @if($non_conformite->linked_module)
            <tr>
                <th>Élément lié</th>
                <td>
                    @php $lm = $non_conformite->linked_module; @endphp
                    {{ $lm->titre ?? $lm->nom ?? $lm->numero ?? $lm->type ?? 'N/A' }}
                </td>
            </tr>
            @endif
            @if($non_conformite->reclamation)
            <tr>
                <th>Réclamation liée</th>
                <td>{{ $non_conformite->reclamation->reference }}</td>
            </tr>
            @endif
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Objet</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $non_conformite->objet }}</td>
            </tr>
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Description</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $non_conformite->description }}</td>
            </tr>
            @if($non_conformite->evaluation)
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Évaluation</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $non_conformite->evaluation }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($non_conformite->responsable_efficacite_id)
    <div class="section">
        <div class="section-title">Évaluation de l'efficacité</div>
        <table>
            <tr>
                <th>Responsable efficacité</th>
                <td>{{ $non_conformite->responsableEfficacite->prenom ?? '' }} {{ $non_conformite->responsableEfficacite->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date efficacité</th>
                <td>{{ $non_conformite->date_efficacite ? \Carbon\Carbon::parse($non_conformite->date_efficacite)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Mode de contrôle</th>
                <td>{{ $non_conformite->mode_controle ?: 'N/A' }}</td>
            </tr>
            <tr>
                <th>Action efficace ?</th>
                <td>
                    @if($non_conformite->action_efficace === true || $non_conformite->action_efficace === 1) Oui
                    @elseif($non_conformite->action_efficace === false || $non_conformite->action_efficace === 0) Non
                    @else N/A @endif
                </td>
            </tr>
            <tr>
                <th>Besoin d'action d'amélioration ?</th>
                <td>
                    @if($non_conformite->besoin_action_amelioration === true || $non_conformite->besoin_action_amelioration === 1) Oui
                    @elseif($non_conformite->besoin_action_amelioration === false || $non_conformite->besoin_action_amelioration === 0) Non
                    @else N/A @endif
                </td>
            </tr>
            <tr>
                <th>Date de clôture</th>
                <td>{{ $non_conformite->date_cloture ? \Carbon\Carbon::parse($non_conformite->date_cloture)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            @if($non_conformite->description_resultat)
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Description du résultat</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $non_conformite->description_resultat }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} - Non-Conformité {{ $non_conformite->numero }}
    </div>
</body>
</html>
