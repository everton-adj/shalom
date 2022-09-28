<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ComandaModel;
use App\Models\ItemModel;
use App\Models\CartaoModel;

class SistemaController extends Controller
{
    public function permissao($id){
        switch ($id) {
            case '1':
                return true;
            case '2':
                return true;
            default:
                return false;
        }
    }

    public function search(Request $request){
        if(isset($request)){
            return json_encode(ItemModel::where('nome', 'LIKE', '%'.$request->search.'%')->get()->toArray());
        }else{
            return json_encode(ItemModel::get()->toArray());
        }
    }

    public function index()
    {
        $permitido = $this->permissao(Auth::user()->id);
        return view('sistema', compact('permitido'));
    }

    public function create(Request $request)
    {
        if(isset($request->code)){
            if(isset(CartaoModel::where('code',$request->code)->first()->id)){ //verifica se cartao existe
                $cartao = CartaoModel::where('code',$request->code)->first();
                if(isset(ItemModel::where('id', $request->item)->first()->id)){ //verifica se item existe
                    $item = ItemModel::where('id',$request->item)->first();
                    ComandaModel::create([
                        "card_id" => $cartao->id,
                        "item_id" => $item->id,
                        "qtde" => $request->qtde
                    ]);
                    $request->id = $cartao->id;
                    return $this->store($request);
                }
            }
        }else{
            return redirect()->back()->with('erroMsg','Codigo não encontrado.');
        }
    }

    public function store(Request $request)
    {
        if(isset($request->nome)){ // se tiver nome ja altera
            CartaoModel::where('id', '=', $request->id)->update(["nome" => $request->nome]);
            $permitido = $this->permissao(Auth::user()->id);
        }
        if(isset(CartaoModel::where('code',$request->code)->first()->id)){ //verifica se cartao existe
            $cartao = CartaoModel::where('code',$request->code)->first();
            if(isset(ComandaModel::where([['card_id','=',$cartao->id],['pago','=', 0],])->first()->card_id)){ //verifica se existe esse cartao com débito em alguma comanda
                $comanda = array();
                foreach(ComandaModel::where([
                    ['card_id','=',$cartao->id],
                    ['pago','=', 0],
                    ])->get() as $item){
                        array_push($comanda, [
                            "id" => $cartao->id,
                            "code" => $cartao->code,
                            "nome" => $cartao->nome,
                            "item_id" => $item->item_id,
                            "item_nome" => ItemModel::where('id',$item->item_id)->first()->nome,
                            "item_valor" => ItemModel::where('id',$item->item_id)->first()->valor,
                            "qtde" => $item->qtde
                        ]);
                }
                $permitido = $this->permissao(Auth::user()->id);
                return view('sistema', compact('comanda', 'permitido'));
            }else{ // caso o cartao nao esteja vinculado ou ja foi pago
                return redirect()->back()->with('modal', $cartao->code);
            }
        }else{
            return redirect()->back()->with('erroMsg','Não encontrado');
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
