@extends('layouts.app')

@section('content')
<div class="zn-page" style="max-width:640px;margin:0 auto;padding:2rem;">
    <div class="zn-bc" style="font-size:.8rem;color:#525f7f;margin-bottom:1rem;">
        <a href="{{ route('mp-products.index') }}">Articles MP</a>
        <span class="zn-bc-sep">›</span>
        <span class="zn-bc-cur">Nouvel article</span>
    </div>

    <h1 style="font-size:1.45rem;font-weight:700;margin-bottom:1.25rem;">Nouvel article MP</h1>

    @if($errors->any())
        <div style="padding:1rem;background:#fef0f2;color:#b91c1c;border-radius:8px;margin-bottom:1rem;">
            <ul style="margin:0;padding-left:1.1rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="post" action="{{ route('mp-products.store') }}" style="background:#fff;border:1px solid #e4e7f0;border-radius:12px;padding:1.5rem;">
        @csrf
        @include('mp_products._form', ['product' => null])
        <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
            <button type="submit" style="padding:.6rem 1.2rem;background:#5b8dee;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Créer</button>
            <a href="{{ route('mp-products.index') }}" style="padding:.6rem 1.2rem;border:1px solid #e4e7f0;border-radius:8px;color:#525f7f;text-decoration:none;font-weight:600;">Annuler</a>
        </div>
    </form>
</div>
@endsection
