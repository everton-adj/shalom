<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ComandaModel;
use App\Models\ItemModel;
use App\Models\CartaoModel;

class SistemaController extends Controller
{
    public function permissao($id)
    { // Lista de id's permitidos
        switch ($id) {
            case '1':
                return true;
            case '2':
                return true;
            default:
                return false;
        }
    }

    public function search(Request $request)
    {
        if (isset($request)) {
            return json_encode(ItemModel::where('nome', 'LIKE', '%' . $request->search . '%')->get()->toArray());
        } else {
            return json_encode(ItemModel::get()->toArray());
        }
    }

    public function index()
    {
        $permitido = $this->permissao(Auth::user()->id);
        return view('sistema.index', compact('permitido'));
    }

    public function create(Request $request)
    {
        if ($this->permissao(Auth::user()->id)) {
            $cartao = isset(CartaoModel::where('id', $request->card_id)->first()->id) ? CartaoModel::where('id', $request->card_id)->first() : null; // Busca o cartão
            if (isset($cartao)) { //verifica se cartao existe
                if (isset(ItemModel::where('id', $request->item)->first()->id)) { //verifica se item existe
                    $item = ItemModel::where('id', $request->item)->first();
                    ComandaModel::create([
                        "card_id" => $cartao->id,
                        "item_id" => $item->id,
                        "qtde" => $request->qtde
                    ]);
                    $request->id = $cartao->id;
                    return $this->store($request);
                }
            } else {
                return redirect()->back()->with('erroMsg', 'Codigo não encontrado.');
            }
        } else {
            return view('sistema.index', compact('permitido'));
        }
    }

    public function store(Request $request)
    {
        // if(isset($request->nome)){ // se tiver nome ja altera
        //     CartaoModel::where('id', '=', $request->id)->update(["nome" => $request->nome]);
        //     $permitido = $this->permissao(Auth::user()->id);
        // }

        $cartao = isset(CartaoModel::where('code', $request->code)->first()->id) ? CartaoModel::where('code', $request->code)->first() : null; // Busca o cartão
        if (isset($cartao)) {
            if (isset(ComandaModel::where([['card_id', '=', $cartao->id], ['pago', '=', 0],])->first()->card_id)) { //verifica se existe esse cartao com débito em alguma comanda
                $comanda = array();
                foreach (ComandaModel::where([
                    ['card_id', '=', $cartao->id],
                    ['pago', '=', 0],
                ])->get() as $item) { // Pega todos os itens não pagos do cartão
                    array_push($comanda, [
                        "card_id" => $item->card_id,
                        "item_id" => $item->item_id,
                        "qtde" => $item->qtde,
                        "nome" => $cartao->nome,
                        "item_nome" => ItemModel::where('id', $item->item_id)->first()->nome,
                        "item_valor" => ItemModel::where('id', $item->item_id)->first()->valor,
                    ]);
                }
                $permitido = $this->permissao(Auth::user()->id);
                return view('sistema.index', compact('comanda', 'permitido'));
            } else { // caso o cartao nao esteja vinculado ou ja foi pago
                return redirect()->back()->with('modal', $cartao->id); // Cria a sessao modal com id do cartão
            }
        } else {
            return redirect()->back()->with('erroMsg', 'Cartão não encontrado.');
        }
    }

    public function show($id)
    {
        return "show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return 'destroy';
    }
}
