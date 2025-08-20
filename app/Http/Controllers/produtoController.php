<?php

namespace App\Http\Controllers;

use App\Models\produtoModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class produtoController extends Controller
{
    private $produtoModel;
    private $categoriaModel;
    private $elementoModel;

    public function __construct()
    {
        $this->categoriaModel = new \App\Models\categoriaModel();
        $this->elementoModel = new \App\Models\elementoModel();
        $this->produtoModel = new \App\Models\produtoModel();
    }
    public function index()
    {
        $categorias = $this->categoriaModel->buscaTodasCategorias();
        $elementos = $this->elementoModel->buscaTodosElementos();
        $produtos = $this->produtoModel->buscaProdutos();
        return view("Admin.showProdutos", compact('elementos', 'categorias', 'produtos'));
    }

    public function filtro(Request $request)
    {
        $busca = $request->input('busca');
        $tipo = $request->input('tipo');
        $elemento = $request->input('elemento');
        $ordenacao = $request->input('ordenacao');
        $porPagina = $request->input('por_pagina', 10); // Itens por página, padrão 10

        $query = $this->produtoModel->query();

        if (!empty($busca)) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'LIKE', "%{$busca}%")
                    ->orWhere('descricao', 'LIKE', "%{$busca}%");
            });
        }

        if (!empty($tipo) && $tipo != 'Todos os tipos') {
            $query->whereHas('categoria', function ($q) use ($tipo) {
                $q->where('nome', $tipo);
            });
        }

        if (!empty($elemento) && $elemento != 'Todos os elementos') {
            $query->whereHas('elemento', function ($q) use ($elemento) {
                $q->where('nome', $elemento);
            });
        }

        switch ($ordenacao) {
            case 'Nome (A-Z)':
                $query->orderBy('nome', 'asc');
                break;
            case 'Nome (Z-A)':
                $query->orderBy('nome', 'desc');
                break;
            case 'Preço (Maior)':
                $query->orderBy('preco', 'desc');
                break;
            case 'Preço (Menor)':
                $query->orderBy('preco', 'asc');
                break;
            case 'Mais vendidos':
                $query->orderBy('quantidade_vendida', 'desc');
                break;
            default:
                $query->orderBy('nome', 'asc');
        }

        // Alterado de get() para paginate()
        $produtos = $query->with(['categoria', 'elemento'])->paginate($porPagina);

        $categorias = $this->categoriaModel->buscaTodasCategorias();
        $elementos = $this->elementoModel->buscaTodosElementos();

        return view("Admin.showProdutos", compact('produtos', 'categorias', 'elementos'));
    }
    public function cadastrar(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:produtos,nome',
            'descricao' => 'required|string|max:500|min:10',
            'numeracao' => 'required|string|max:50|min:2',
            'categoria_id' => 'required|exists:categorias,id',
            'elemento_id' => 'required|exists:elementos,id',
            'preco' => 'required|min:0.01|max:999999.99',
            'quantidade' => 'required|integer|min:0|max:9999',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nome.required' => 'O nome do produto é obrigatório.',
            'nome.max' => 'O nome não pode ter mais que 100 caracteres.',
            'nome.unique' => 'Já existe um produto com este nome.',

            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'descricao.max' => 'A descrição não pode ter mais que 500 caracteres.',

            'numeracao.required' => 'A numeração/modelo é obrigatória.',
            'numeracao.min' => 'A numeração deve ter pelo menos 2 caracteres.',
            'numeracao.max' => 'A numeração não pode ter mais que 50 caracteres.',

            'categoria_id.required' => 'A categoria é obrigatória.',
            'categoria_id.exists' => 'A categoria selecionada é inválida.',

            'elemento_id.required' => 'O elemento Pokémon é obrigatório.',
            'elemento_id.exists' => 'O elemento Pokémon selecionado é inválido.',

            'preco.required' => 'O preço é obrigatório.',
            'preco.min' => 'O preço mínimo é R$ 0,01.',
            'preco.max' => 'O preço máximo é R$ 999.999,99.',

            'quantidade.required' => 'A quantidade em estoque é obrigatória.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade não pode ser negativa.',
            'quantidade.max' => 'A quantidade máxima é 9999.',

            'imagem.required' => 'A imagem do produto é obrigatória.',
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif.',
            'imagem.max' => 'A imagem não pode ter mais que 2MB.',
        ]);

        
        $dados = $request->only([
            'nome',
            'descricao',
            'numeracao',
            'categoria_id',
            'elemento_id',
            'quantidade'
        ]);

       
        $dados['preco'] = str_replace(['.', ','], ['', '.'], $request->input('preco'));

        
        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $fileName = time() . '_' . Str::slug($request->nome) . '.' . $file->getClientOriginalExtension();

            
            try {
                $path = Storage::disk('public')->putFileAs(
                    'produtos',
                    $file,
                    $fileName
                );

                if (!$path) {
                    throw new \Exception("Falha ao salvar a imagem");
                }

                $dados['imagem'] = $fileName;
            } catch (\Exception $e) {
                
                $destinationPath = storage_path('app/public/produtos');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $fileName);
                $dados['imagem'] = $fileName;
            }
        }

        // Criação do produto
        $this->produtoModel->create($dados);

        return redirect()
            ->route('showProdutos')
            ->with('success', 'Produto cadastrado com sucesso!.');
    }

    public function showEditar($idCriptogradado)
    {
        $id = Crypt::decrypt($idCriptogradado);
        $categorias = $this->categoriaModel->buscaTodasCategorias();
        $elementos = $this->elementoModel->buscaTodosElementos();
        $produto = $this->produtoModel->buscaProduto($id);

        if (!$produto) {
            return redirect()->route('showProdutos')
                ->with('error', 'Produto não encontrado');
        }

        return view("Admin.showEditarProduto", compact('elementos', 'categorias', 'produto'));
    }

    public function editar(Request $request, $idCriptogradado)
    {
        if (!$id = Crypt::decrypt($idCriptogradado)) {
            return redirect()->route('showProdutos')
                ->with('error', 'Produto não encontrado');
        }

        $request->validate([
            'nome' => 'required|string|max:100|unique:produtos,nome,'.$id,
            'descricao' => 'required|string|max:500|min:10',
            'numeracao' => 'required|string|max:50|min:2',
            'categoria_id' => 'required|exists:categorias,id',
            'elemento_id' => 'required|exists:elementos,id',
            'preco' => 'required|min:0.01|max:999999.99',
            'quantidade' => 'required|integer|min:0|max:9999',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nome.required' => 'O nome do produto é obrigatório.',
            'nome.max' => 'O nome não pode ter mais que 100 caracteres.',
            'nome.unique' => 'Já existe um produto com este nome.',

            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'descricao.max' => 'A descrição não pode ter mais que 500 caracteres.',

            'numeracao.required' => 'A numeração/modelo é obrigatória.',
            'numeracao.min' => 'A numeração deve ter pelo menos 2 caracteres.',
            'numeracao.max' => 'A numeração não pode ter mais que 50 caracteres.',

            'categoria_id.required' => 'A categoria é obrigatória.',
            'categoria_id.exists' => 'A categoria selecionada é inválida.',

            'elemento_id.required' => 'O elemento Pokémon é obrigatório.',
            'elemento_id.exists' => 'O elemento Pokémon selecionado é inválido.',

            'preco.required' => 'O preço é obrigatório.',
            'preco.min' => 'O preço mínimo é R$ 0,01.',
            'preco.max' => 'O preço máximo é R$ 999.999,99.',

            'quantidade.required' => 'A quantidade em estoque é obrigatória.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade não pode ser negativa.',
            'quantidade.max' => 'A quantidade máxima é 9999.',

            'imagem.required' => 'A imagem do produto é obrigatória.',
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif.',
            'imagem.max' => 'A imagem não pode ter mais que 2MB.',
        ]);

        if (! $produto = $this->produtoModel->buscaProduto($id)) {
            return redirect()->route('showProdutos')
                ->with('error', 'Produto não encontrado');
        }

        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->numeracao = $request->numeracao;
        $produto->categoria_id = $request->categoria_id;
        $produto->elemento_id = $request->elemento_id;


        $produto->preco = str_replace(['R$', '.', ','], ['', '', '.'], $request->preco);

        $produto->quantidade = $request->quantidade;


        if ($request->hasFile('imagem')) {

            if ($produto->imagem && Storage::exists('produtos/' . $produto->imagem)) {
                Storage::delete('produtos/' . $produto->imagem);
            }


            $imagem = $request->file('imagem');
            $nomeImagem = time() . '_' . $imagem->getClientOriginalName();
            $caminho = $imagem->storeAs('produtos', $nomeImagem, 'public');

            $produto->imagem = $nomeImagem;
        }

        if ($produto->save()) {
            return redirect()->route('showProdutos')
                ->with('success', 'Produto atualizado com sucesso!');
        }

        return back()->with('error', 'Erro ao atualizar o produto');
    }
    public function excluir($idCriptogradado){
        try{
            $id = Crypt::decrypt($idCriptogradado);
            $produto = $this->produtoModel->buscaProduto($id);
            $produto->delete();
            return redirect()
                ->route('showProdutos')
                ->with('success', 'Produto excluido com sucesso!');
        }catch(\Exception $e){
            return redirect()
                ->route('showProdutos')
                ->with('error', 'Produto não encontrado.');
        }
    }
}
