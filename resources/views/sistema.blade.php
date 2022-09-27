@extends('layouts.app')

@section('content')
    @if($permitido)
        <div class="container">
            @if (Session::has('msg'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h3>{{ Session::get('msg') }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="fechar-alerta"></button>
                </div>
            @endif
            <h1>Sistema</h1>
            <div class="card container p-3">
                <form method="post" action="sistema">
                    @csrf
                    <div class="typeahead__container">
                        <input id="id" name="id" type="text" class="js-typeahead form-control my-3" autocomplete="off"/>
                    </div>
                </form>
            </div>
            @if(isset($comanda->id))
            <div class="card container p-3 my-5">
                <h1>{{$comanda->nome}}</h1>
                <hr>
                {{$comanda->qtde}} x item
            </div>
            @endif
        </div>
    @else
    <div class="container">
        <h1>Acesso negado para o usuário: {{Auth::user()->name}}</h1>
    </div>
    @endif
@endsection

@section('scriptEnd')
<script>
    window.addEventListener('keydown', () => {
        $('#id').focus();
    });
    setTimeout(()=>{$('#fechar-alerta').click();},3000);

    $.typeahead({
        input: '.js-typeahead',
        minLength: 3,
        maxItem: 10,
        order: "asc",
        source: {
            ajax: {
                url: "{{route('search')}}"
            }
        },
        callback: {
            onResult: function (node, query, result, resultCount) {
                console.log(result);
            }
        }
    });
</script>
@endsection
