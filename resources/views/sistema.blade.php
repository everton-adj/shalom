@extends('layouts.app')

@section('content')
    @if($permitido)
        @if(Session::has('modal'))
            <div class="modal fade" id="modalInit" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="modalInitForm" action="{{ route('sistema.create') }}">
                            <div class="modal-header">
                                <h1>Comanda vazia</h1>
                            </div>
                            <div class="modal-body">
                                @csrf
                                <h3>Nome</h3>
                                <input class="form-control" name="nome" type="text"/>
                                <h3 class="mt-3">Pedido</h3>
                                <div class="typeahead__container">
                                    <input name="code" type="text" class="js-typeahead form-control my-3" autocomplete="off"/>
                                </div>
                                <div class="d-none">
                                    <input id="codeModal" name="code" type="text" value="{{Session::get('modal')}}"/>
                                    <input id="itemInit" name="item" type="text"/>
                                    <input id="qtdeInit" name="qtde" type="text"/>
                                </div>


                                <input class="d-none" name="code" type="text" value="{{Session::get('modal')}}"/>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Salvar</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('#modalInit').modal('show');
                });
            </script>
        @endif
        <div class="modal fade" id="modalNome" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="modalForm" method="post" action="sistema">
                        <div class="modal-body">
                            @csrf
                            Nome
                            <input class="form-control" id="modalNome" name="nome" type="text"/>
                            <input class="d-none" id="modalId" name="id" type="text" value="{{isset($comanda[0]['id'])?$comanda[0]['id']:''}}" />
                            <input class="d-none" id="modalCode" name="code" type="text" value="{{isset($comanda[0]['code'])?$comanda[0]['code']:''}}" />
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            @if (Session::has('erroMsg'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h3>{{ Session::get('erroMsg') }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="fechar-alerta"></button>
                </div>
            @elseif(Session::has('msg'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <h3>{{ Session::get('msg') }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" id="fechar-alerta"></button>
                </div>
            @endif
            <a href="{{ route('cadastro')}}"class="btn btn-primary my-3">Cadastro</a>
            <h1>Sistema</h1>
            <div class="card container p-3">
                <form method="post" action="sistema">
                    @csrf
                    <div class="typeahead__container">
                        <input id="code" name="code" type="text" class="js-typeahead form-control my-3" autocomplete="off"/>
                    </div>
                </form>
            </div>
            @if(isset($comanda))
            <div class="d-none">
                <form action="{{route('sistema.create')}}" id="formItem">
                    @csrf
                    <input id="codeModal" name="code" type="text" value="{{$comanda[0]['code']}}"/>
                    <input id="item" name="item" type="text"/>
                    <input id="qtde" name="qtde" type="text"/>
                </form>
            </div>
            <div class="card container p-3 my-5">
                <h1><a style="color:rgb(113, 113, 224); rounded;cursor:pointer" id="btnModal" data-bs-toggle="modal" data-bs-target="#modalNome"><i class="fa-solid fa-pen-to-square"></i></a> {{$comanda[0]['nome']}}</h1>
                <hr>
                <?php $total=0; ?>
                @foreach($comanda as $item)
                    {{$item['qtde']}} X {{$item['item_nome']}} R$ {{$item['item_valor']}} = R$ {{$item['qtde']*$item['item_valor']}}<br>
                    <?php $total += $item['qtde']*$item['item_valor']; ?>
                @endforeach
                Total R$ {{$total}}
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
    @if(!Session::has('modal'))
        window.addEventListener('keydown', () => {
            $('#code').focus();
        });
    @endif
    setTimeout(()=>{$('#fechar-alerta').click();},3000);
    $.typeahead({
        input: '.js-typeahead',
        minLength: 3,
        maxItem: 10,
        order: "asc",
        display: "nome",
        source: {
            ajax: {
                url: "{{route('search')}}",
                data: {
                    search: $('#code').val()
                }
            }
        },
        callback: {
            onClickAfter: function (node, a, item, event) {
                event.preventDefault();
                @if(!Session::has('modal'))
                    $('#code').val('');
                    $('#item').val(item.id);
                    $('#qtde').val(prompt('Digite a QUANTIDADE:'));
                    $('#formItem').submit();
                @else
                    $('#code').val('');
                    $('#itemInit').val(item.id);
                    $('#qtdeInit').val(prompt('Digite a QUANTIDADE:'));
                    $('#modalInitForm').submit();
                @endif
            }
        }
    });
</script>
@endsection
{{-- APOS PRIMEIRA INSERSAO ELE NAO TROCA DE COMANDA --}}