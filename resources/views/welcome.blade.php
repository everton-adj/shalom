@extends('layouts.app')

@section('content')
    @if($permitido)
        <div class="container">
            <h1>Editar Cardápio</h1>
        </div>
    @else
        <div class="container">
            <h1>Cardápio</h1>
        </div>
    @endif
@endsection
