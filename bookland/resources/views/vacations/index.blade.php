@extends('layouts.app')

@push('styles')
<style>
    .vacation-page { padding: 2rem 2.5rem 3rem; max-width: 800px; margin: 0 auto; }
    .vacation-card { background: white; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
    .vacation-card-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid #e4e7f0; background: #fafbff; font-weight: 600; }
    .vacation-card-body { padding: 1.5rem; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; font-weight: 600; margin-bottom: 0.3rem; }
    .form-control { width: 100%; padding: 0.5rem; border: 1px solid #e4e7f0; border-radius: 8px; }
    .btn-primary { background: #5b8dee; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; }
    .btn-danger { background: #dc3545; color: white; border: none; padding: 0.2rem 0.6rem; border-radius: 6px; }
    .vacation-table { width: 100%; border-collapse: collapse; }
    .vacation-table th, .vacation-table td { padding: 0.6rem; border-bottom: 1px solid #e4e7f0; text-align: left; }
    .mt-4 { margin-top: 1.5rem; }
</style>
@endpush

@section('content')
<div class="vacation-page">
    <div class="vacation-card">
        <div class="vacation-card-header">
            Gestion des vacances / jours fériés
        </div>
        <div class="vacation-card-body">
            <form method="POST" action="{{ route('vacations.store') }}" class="mb-4">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nom (ex: Congés, Fête nationale)</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Date début</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_recurring" value="1"> Annuelle (récurrente chaque année)
                    </label>
                </div>
                <button type="submit" class="btn-primary">Ajouter</button>
            </form>

            <hr>

            <h4>Vacances enregistrées</h4>
            <table class="vacation-table">
                <thead>
                    <tr><th>Nom</th><th>Début</th><th>Fin</th><th>Récurrent</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($vacations as $v)
                    <tr>
                        <td>{{ $v->name }}</td>
                        <td>{{ $v->start_date->format('d/m/Y') }}</td>
                        <td>{{ $v->end_date->format('d/m/Y') }}</td>
                        <td>{{ $v->is_recurring ? 'Oui' : 'Non' }}</td>
                        <td>
                            <form method="POST" action="{{ route('vacations.destroy', $v) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" onclick="return confirm('Supprimer ?')">X</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection