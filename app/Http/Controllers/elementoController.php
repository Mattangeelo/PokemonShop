<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;

class elementoController extends Controller
{
    private $elementoModel;

    public function __construct(){
        $this->elementoModel = new \App\Models\elementoModel();
    }
    public function index(){
        $elementos =  $this->elementoModel->buscaElementos();
        return view('Admin.showElementos', compact('elementos'));
    }
    public function filtro(Request $request){
        $query = $this->elementoModel->query()->whereNull('deleted_at');

        if ($request->filled('q')) {
            $query->where('nome', 'like', '%' . $request->q . '%');
        }

        if ($request->ordenar == 'az') {
            $query->orderBy('nome', 'asc');
        } elseif ($request->ordenar == 'za') {
            $query->orderBy('nome', 'desc');
        }

        $elementos = $query->select('id', 'nome')->paginate(10);

        return view('Admin.showElementos', compact('elementos'));
    }
    public function cadastrar(Request $request){
        $request->validate(
    [
                'nome' => 'required|max:49|unique:elementos,nome',
            ],
            [
                'nome.required' => 'O campo nome é obrigatório.',
                'nome.max' => 'O campo nome deve ter no máximo 49 caracteres.',
                'nome.unique' => 'Esse nome já possui um cadastro em nosso sistema!',
            ]
        );

        $data = [
            'nome' =>  trim(strip_tags($request->input('nome'))),
        ];

        $this->elementoModel->create($data);

        return redirect()->route('showElementos')->with('success','Cadastro realizado com sucesso!');
    }
    public function editar(Request $request,$idCriptografado){
        $id = Crypt::decrypt($idCriptografado);

        $request->validate(
    [
                'nome' => 'required|max:49|unique:elementos,nome,' . $id,
            ],
            [
                'nome.required' => 'O campo nome é obrigatório.',
                'nome.max' => 'O campo nome deve ter no máximo 49 caracteres.',
                'nome.unique' => 'Esse nome já possui um cadastro em nosso sistema!',
            ]
        );


        if(! $elemento = $this->elementoModel->buscaElemento($id)){
            return redirect()
                ->route('showElementos')
                ->with('error', 'Elemento não encontrado.');
        }

        $elemento->nome = $request->input('nome');

        $elemento->save();

        return redirect()
                ->route('showElementos')
                ->with('success', 'Elemento atualizado com sucesso!.');
    }
    public function excluir($idCriptografado){
        try{
            $id = Crypt::decrypt($idCriptografado);
            $elemento = $this->elementoModel->buscaElemento($id);
            $elemento->delete();

            return redirect()
                ->route('showElementos')
                ->with('success', 'Elemento excluido com sucesso!.');
        } catch (\Exception $e){
             return redirect()
                ->route('showElementos')
                ->with('error', 'Elemento não encontrado.');
        }
    }
}
