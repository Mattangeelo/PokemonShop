<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{
    private $produtoModel;
    private $estoqueModel;
    private $categoriaModel;

    public function __construct()
    {
        $this->produtoModel = new \App\Models\produtoModel();
        $this->estoqueModel = new \App\Models\estoqueModel();
        $this->categoriaModel = new \App\Models\categoriaModel();
    }

    public function index()
    {
        $categorias = $this->categoriaModel->buscaTodasCategorias();
        
        // Buscar produtos com estoque
        $produtos = $this->produtoModel->with(['categoria', 'estoque'])
                                      ->orderBy('nome')
                                      ->paginate(15);

        // Estatísticas
        $totalProdutos = $this->produtoModel->count();
        $produtosComEstoque = $this->produtoModel->whereHas('estoque', function($query) {
            $query->where('quantidade', '>', 0);
        })->count();
        
        $produtosEstoqueBaixo = $this->produtoModel->whereHas('estoque', function($query) {
            $query->where('quantidade', '>', 0)
                  ->whereRaw('quantidade <= quantidade_minima');
        })->count();
        
        $produtosSemEstoque = $this->produtoModel->whereHas('estoque', function($query) {
            $query->where('quantidade', '<=', 0);
        })->orWhereDoesntHave('estoque')
          ->count();

        return view('Admin.showEstoque', compact(
            'produtos', 
            'categorias',
            'totalProdutos',
            'produtosComEstoque',
            'produtosEstoqueBaixo',
            'produtosSemEstoque'
        ));
    }

    public function filtro(Request $request)
    {
        $categorias = $this->categoriaModel->buscaTodasCategorias();
        
        $query = $this->produtoModel->with(['categoria', 'estoque']);

        // Filtro por busca
        if ($request->filled('busca')) {
            $query->where('nome', 'LIKE', "%{$request->busca}%");
        }

        // Filtro por categoria
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        // Filtro por status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'estoque_baixo':
                    $query->whereHas('estoque', function($q) {
                        $q->where('quantidade', '>', 0)
                          ->whereRaw('quantidade <= quantidade_minima');
                    });
                    break;
                case 'sem_estoque':
                    $query->whereHas('estoque', function($q) {
                        $q->where('quantidade', '<=', 0);
                    })->orWhereDoesntHave('estoque');
                    break;
                case 'com_estoque':
                    $query->whereHas('estoque', function($q) {
                        $q->where('quantidade', '>', 0);
                    });
                    break;
            }
        }

        $produtos = $query->orderBy('nome')->paginate(15);

        // Recalcular estatísticas para os produtos filtrados
        $totalProdutos = $produtos->total();
        $produtosComEstoque = $this->produtoModel->whereHas('estoque', function($query) {
            $query->where('quantidade', '>', 0);
        })->count();
        
        $produtosEstoqueBaixo = $this->produtoModel->whereHas('estoque', function($query) {
            $query->where('quantidade', '>', 0)
                  ->whereRaw('quantidade <= quantidade_minima');
        })->count();
        
        $produtosSemEstoque = $this->produtoModel->whereHas('estoque', function($query) {
            $query->where('quantidade', '<=', 0);
        })->orWhereDoesntHave('estoque')
          ->count();

        return view('Admin.showEstoque', compact(
            'produtos', 
            'categorias',
            'totalProdutos',
            'produtosComEstoque',
            'produtosEstoqueBaixo',
            'produtosSemEstoque'
        ));
    }

    public function adicionarEstoque(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1|max:10000',
            'observacao' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $produto = $this->produtoModel->find($request->produto_id);
            $estoque = $this->estoqueModel->where('id_produto', $request->produto_id)->first();

            if (!$estoque) {
                // Criar estoque se não existir
                $estoque = $this->estoqueModel->create([
                    'id_produto' => $request->produto_id,
                    'quantidade' => $request->quantidade,
                    'quantidade_minima' => 10
                ]);
            } else {
                // Adicionar ao estoque existente
                $estoque->quantidade += $request->quantidade;
                $estoque->save();
            }

            // Aqui você pode registrar no histórico de movimentações se quiser

            DB::commit();

            return redirect()->route('showEstoques')
                ->with('success', "Estoque de {$produto->nome} atualizado com sucesso! Adicionadas {$request->quantidade} unidades.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('showEstoques')
                ->with('error', 'Erro ao adicionar estoque: ' . $e->getMessage());
        }
    }

    public function ajustarEstoque(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:0|max:10000',
            'quantidade_minima' => 'required|integer|min:1|max:1000',
            'observacao' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $produto = $this->produtoModel->find($request->produto_id);
            
            // Usar updateOrCreate para garantir que o estoque exista
            $this->estoqueModel->updateOrCreate(
                ['id_produto' => $request->produto_id],
                [
                    'quantidade' => $request->quantidade,
                    'quantidade_minima' => $request->quantidade_minima
                ]
            );

            DB::commit();

            return redirect()->route('showEstoques')
                ->with('success', "Estoque de {$produto->nome} ajustado com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('showEstoques')
                ->with('error', 'Erro ao ajustar estoque: ' . $e->getMessage());
        }
    }
}