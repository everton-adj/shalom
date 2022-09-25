@extends('layouts.app')

@section('content')
    @if($permitido)
        <div class="container">
            <h1>Sistema</h1>
            
        </div>
    @else
    <div class="container">
        <h1>Acesso negado para o usuário: {{Auth::user()->name}}</h1>
    </div>
    @endif
@endsection
