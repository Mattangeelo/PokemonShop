<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class categoriaController extends Controller
{
    private $categoriaModel;

    public function __construct(){
        $this->categoriaModel= new \App\Models\categoriaModel();
    }

    public function filtro(Request $request){
        $query = $this->categoriaModel->query()->whereNull('deleted_at');

        if ($request->filled('q')) {
            $query->where('nome', 'like', '%' . $request->q . '%');
        }

        if ($request->ordenar == 'az') {
            $query->orderBy('nome', 'asc');
        } elseif ($request->ordenar == 'za') {
            $query->orderBy('nome', 'desc');
        }

        $categorias = $query->select('id', 'nome')->paginate(10);

        return view('Admin.showCategorias', compact('categorias'));
    }
    public function index(){
        $categorias =  $this->categoriaModel->buscaCategorias();
        return view('Admin.showCategorias', compact('categorias'));
    }
    public function cadastrar(Request $request){
        $request->validate(
    [
                'nome' => 'required|max:49|unique:categorias,nome',
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

        $this->categoriaModel->create($data);

        return redirect()->route('showCategorias')->with('success','Cadastro realizado com sucesso!');
    }

    public function editar(Request $request,$idCriptografado){
        $id = Crypt::decrypt($idCriptografado);

        $request->validate(
    [
                'nome' => 'required|max:49|unique:categorias,nome,' . $id,
            ],
            [
                'nome.required' => 'O campo nome é obrigatório.',
                'nome.max' => 'O campo nome deve ter no máximo 49 caracteres.',
                'nome.unique' => 'Esse nome já possui um cadastro em nosso sistema!',
            ]
        );


        if(! $categoria = $this->categoriaModel->buscaCategoria($id)){
            return redirect()
                ->route('showCategorias')
                ->with('error', 'Categoria não encontrada.');
        }

        $categoria->nome = $request->input('nome');

        $categoria->save();

        return redirect()
                ->route('showCategorias')
                ->with('success', 'Categoria atualizada com sucesso!.');
    }

    public function excluir($idCriptografado){
        try{
            $id = Crypt::decrypt($idCriptografado);
            $categoria = $this->categoriaModel->buscaCategoria($id);
            $categoria->delete();

            return redirect()
                ->route('showCategorias')
                ->with('success', 'Categoria excluida com sucesso!.');
        } catch (\Exception $e){
             return redirect()
                ->route('showCategorias')
                ->with('error', 'Categoria não encontrada.');
        }
    }
}
