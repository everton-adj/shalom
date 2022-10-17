@extends('layouts.app')
@section('content')
    @if ($permitido)
        @include('sistema.modal')
        <div class="container">
            @include('sistema.msg')
            <h1>Sistema</h1>
            <div class="card container p-3">
                <form method="post" action="sistema">
                    @csrf
                    <div class="typeahead__container">
                        <input id="code" name="code" type="text" class="js-typeahead form-control my-3"
                            autocomplete="off" />
                    </div>
                </form>
            </div>
            @if (isset($comanda))
                <div class="d-none">
                    <form action="{{ route('sistema.create') }}" id="formItem">
                        @csrf
                        <input id="codeModal" name="code" type="text" value="{{ $comanda[0]['code'] }}" />
                        <input id="item" name="item" type="text" />
                        <input id="qtde" name="qtde" type="text" />
                    </form>
                </div>
                <div class="card container p-3 my-5">
                    <h1><a style="color:rgb(113, 113, 224); rounded;cursor:pointer" id="btnModal" data-bs-toggle="modal"
                            data-bs-target="#modalNome"><i class="fa-solid fa-pen-to-square"></i></a>
                        {{ $comanda[0]['nome'] }}</h1>
                    <hr>
                    <?php $total = 0; ?>
                    @foreach ($comanda as $item)
                        {{ $item['qtde'] }} X {{ $item['item_nome'] }} R$ {{ $item['item_valor'] }} = R$
                        {{ $item['qtde'] * $item['item_valor'] }}<br>
                        <?php $total += $item['qtde'] * $item['item_valor']; ?>
                    @endforeach
                    Total R$ {{ $total }}
                </div>
            @endif
        </div>
    @else
        <div class="container">
            <h1>Acesso negado para o usuário: {{ Auth::user()->name }}</h1>
        </div>
    @endif
@endsection

@section('scriptEnd')
    <script>
        $('#modalInit').on('hide.bs.modal', function(event) {
            location.reload();
        });

        window.addEventListener('keydown', () => {
            @if (Session::has('modal'))
                $('#nome').focus();
            @else
                $('#code').focus();
            @endif
        });
        setTimeout(() => {
            $('#fechar-alerta').click();
        }, 3000);
        $.typeahead({
            input: '.js-typeahead',
            minLength: 3,
            maxItem: 10,
            order: "asc",
            display: "nome",
            source: {
                ajax: {
                    url: "{{ route('search') }}",
                    data: {
                        search: $('#code').val()
                    }
                }
            },
            callback: {
                onClickAfter: function(node, a, item, event) {
                    event.preventDefault();
                    @if (!Session::has('modal'))
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
