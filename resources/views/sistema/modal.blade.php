@if (Session::has('modal'))
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
                        <input class="form-control" id="nome" name="nome" type="text" />
                        <input class="d-none" id="card_id" name="card_id" type="text"
                            value="{{ Session::get('modal') }}" />
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

{{-- Modal de Alteração --}}
<div class="modal fade" id="modalNome" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="modalForm" method="" action="sistema">
                <div class="modal-body">
                    @csrf
                    Nome
                    <input class="form-control" id="modalNome" name="nome" type="text" />
                    <input class="d-none" id="modalId" name="id" type="text"
                        value="{{ isset($comanda[0]['id']) ? $comanda[0]['id'] : '' }}" />
                    <input class="d-none" id="modalCode" name="code" type="text"
                        value="{{ isset($comanda[0]['code']) ? $comanda[0]['code'] : '' }}" />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
