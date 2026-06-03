<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Action d'Amélioration {{ $actions_amelioration->numero }}</title>
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
        <h1>Action d'Amélioration {{ $actions_amelioration->numero }}</h1>
        <p>{{ $actions_amelioration->type }} – {{ ucfirst($actions_amelioration->statut) }}</p>
    </div>

    <div class="section">
        <div class="section-title">Informations Générales</div>
        <table>
            <tr>
                <th>Compte</th>
                <td>{{ $actions_amelioration->compte->etablissement ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Émetteur</th>
                <td>{{ $actions_amelioration->emetteur->prenom ?? '' }} {{ $actions_amelioration->emetteur->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date AA</th>
                <td>{{ $actions_amelioration->dateAA ? $actions_amelioration->dateAA->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ $actions_amelioration->type }}</td>
            </tr>
            <tr>
                <th>Origine</th>
                <td>{{ $actions_amelioration->origine }}</td>
            </tr>
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Analyse des causes</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $actions_amelioration->analyse_causes ?: 'N/A' }}</td>
            </tr>
            @if($actions_amelioration->sanctions)
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Sanctions</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $actions_amelioration->sanctions }}</td>
            </tr>
            @endif
            @if($actions_amelioration->resultats_attendus)
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Résultats attendus</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $actions_amelioration->resultats_attendus }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($actions_amelioration->responsable_suivi_id)
    <div class="section">
        <div class="section-title">Suivi de mise en œuvre</div>
        <table>
            <tr>
                <th>Responsable suivi</th>
                <td>{{ $actions_amelioration->responsableSuivi->prenom ?? '' }} {{ $actions_amelioration->responsableSuivi->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date de suivi</th>
                <td>{{ $actions_amelioration->date_suivi ? $actions_amelioration->date_suivi->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            @if($actions_amelioration->verification_mise_en_oeuvre)
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Vérification de mise en œuvre</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $actions_amelioration->verification_mise_en_oeuvre }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    @if($actions_amelioration->responsable_effecacite_id)
    <div class="section">
        <div class="section-title">Évaluation de l'efficacité</div>
        <table>
            <tr>
                <th>Responsable efficacité</th>
                <td>{{ $actions_amelioration->responsableEfficacite->prenom ?? '' }} {{ $actions_amelioration->responsableEfficacite->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date efficacité</th>
                <td>{{ $actions_amelioration->date_effecacite ? $actions_amelioration->date_effecacite->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Mode de contrôle</th>
                <td>{{ $actions_amelioration->mode_controle ?: 'N/A' }}</td>
            </tr>
            <tr>
                <th>Action efficace ?</th>
                <td>
                    @if($actions_amelioration->action_efficace === true || $actions_amelioration->action_efficace === 1) Oui
                    @elseif($actions_amelioration->action_efficace === false || $actions_amelioration->action_efficace === 0) Non
                    @else N/A @endif
                </td>
            </tr>
            <tr>
                <th>Besoin d'autre action ?</th>
                <td>
                    @if($actions_amelioration->besoin_action_amelioration === true || $actions_amelioration->besoin_action_amelioration === 1) Oui
                    @elseif($actions_amelioration->besoin_action_amelioration === false || $actions_amelioration->besoin_action_amelioration === 0) Non
                    @else N/A @endif
                </td>
            </tr>
            <tr>
                <th>Date de clôture</th>
                <td>{{ $actions_amelioration->date_cloture ? $actions_amelioration->date_cloture->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            @if($actions_amelioration->description_resultat)
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Description du résultat</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $actions_amelioration->description_resultat }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} - Action d'Amélioration {{ $actions_amelioration->numero }}
    </div>
</body>
</html>
