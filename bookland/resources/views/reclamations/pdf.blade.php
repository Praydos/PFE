<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réclamation {{ $reclamation->reference }}</title>
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
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Réclamation {{ $reclamation->reference }}</h1>
        <p>{{ $reclamation->categorie }} – {{ ucfirst($reclamation->statut) }}</p>
    </div>

    <div class="section">
        <div class="section-title">Informations Générales</div>
        <table>
            <tr>
                <th>Compte</th>
                <td>{{ $reclamation->compte->etablissement ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Contact</th>
                <td>{{ $reclamation->contact->prenom ?? '' }} {{ $reclamation->contact->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date réclamation</th>
                <td>{{ $reclamation->date_reclamation ? $reclamation->date_reclamation->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ str_replace('_', ' ', $reclamation->type ?? '-') }}</td>
            </tr>
            <tr>
                <th>Priorité</th>
                <td>{{ ucfirst($reclamation->priorite ?? 'N/A') }}</td>
            </tr>
            <tr>
                <th>Catégorie / Sous-catégorie</th>
                <td>{{ $reclamation->categorie }} @if($reclamation->sous_categorie) / {{ $reclamation->sous_categorie }} @endif</td>
            </tr>
            @if($linkedModule)
            <tr>
                <th>Élément lié</th>
                <td>{{ $linkedModule->titre ?? $linkedModule->nom ?? $linkedModule->numero ?? 'N/A' }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <div class="section-title">Détails et Traitement</div>
        <table>
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Description</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $reclamation->description ?: 'N/A' }}</td>
            </tr>

            @if($reclamation->analyse)
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Analyse</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $reclamation->analyse }}</td>
            </tr>
            @endif

            @if($reclamation->reponse)
            <tr>
                <th colspan="2" style="background-color: #f8f9fd;">Réponse</th>
            </tr>
            <tr>
                <td colspan="2" class="full-width-cell">{{ $reclamation->reponse }}</td>
            </tr>
            @endif
        </table>

        <table>
            <tr>
                <th>Date réponse</th>
                <td>{{ $reclamation->date_reponse ? $reclamation->date_reponse->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Responsable</th>
                <td>{{ $reclamation->responsable->prenom ?? '' }} {{ $reclamation->responsable->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Statut</th>
                <td>{{ ucfirst($reclamation->statut) }}</td>
            </tr>
            <tr>
                <th>Date clôture</th>
                <td>{{ $reclamation->date_cloture ? $reclamation->date_cloture->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Est une non‑conformité ?</th>
                <td>{{ $reclamation->est_non_conformite ? 'Oui' : 'Non' }}</td>
            </tr>
            <tr>
                <th>Besoin d'action d'amélioration ?</th>
                <td>{{ $reclamation->besoin_action_amelioration ? 'Oui' : 'Non' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} - Réclamation {{ $reclamation->reference }}
    </div>
</body>
</html>
