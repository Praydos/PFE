@extends('layouts.app')

@section('content')
<div class="dr-page">
    <div class="dr-header">
        <h1>Modifier l'action</h1>
        <p>Mise à jour</p>
    </div>
    <div class="dr-card">
        <div class="dr-card-body p-4">
            <form method="POST" action="{{ route('actions.update', $action) }}">
                @csrf
                @method('PUT')
                @include('actions._form', ['action' => $action])

                <div class="mt-4">
                    <h5>Lignes d'action</h5>
                    <div id="lines-container">
                        @foreach($action->lignes as $idx => $line)
                            <div class="line-item card p-3 mb-3" data-line-index="{{ $idx }}">
                                @include('actions._line_form', ['lineIndex' => $idx, 'line' => $line, 'products' => $products, 'examens' => $examens, 'bssOptions' => $bssOptions])
                            </div>
                        @endforeach
                        @if($action->lignes->isEmpty())
                            <div class="line-item card p-3 mb-3" data-line-index="0">
                                @include('actions._line_form', ['lineIndex' => 0, 'line' => null, 'products' => $products, 'examens' => $examens, 'bssOptions' => $bssOptions])
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-line" class="btn-ghost btn-sm mt-2">+ Ajouter une ligne</button>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary">Mettre à jour</button>
                    <a href="{{ route('actions.show', $action) }}" class="btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection