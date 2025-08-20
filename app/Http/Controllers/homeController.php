<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class homeController extends Controller
{
    private $produtoModel;
    private $categoriaModel;
    private $elementoModel;

    public function __construct()
    {
        $this->produtoModel = new \App\Models\produtoModel();
        $this->categoriaModel = new \App\Models\categoriaModel();
        $this->elementoModel = new \App\Models\elementoModel();
    }
    public function filtro(Request $request)
    {
        $query = $this->produtoModel->newQuery();

        // Filtro por categorias
        if ($request->has('categorias') && !empty($request->categorias)) {
            $query->whereIn('categoria_id', $request->categorias);
        }

        // Filtro por elementos
        if ($request->has('elementos') && !empty($request->elementos[0])) {
            $elementosArray = explode(',', $request->elementos[0]);
            $query->whereIn('elemento_id', $elementosArray);
        }

        // Filtro por faixa de preço
        if ($request->has('preco_min') && $request->has('preco_max')) {
            $query->whereBetween('preco', [$request->preco_min, $request->preco_max]);
        }

        // Ordenação
        if ($request->has('ordenar')) {
            switch ($request->ordenar) {
                case 'menor_preco':
                    $query->orderBy('preco', 'asc');
                    break;
                case 'maior_preco':
                    $query->orderBy('preco', 'desc');
                    break;
                case 'mais_vendidos':
                    $query->orderBy('vendas', 'desc');
                    break;
                case 'melhores_avaliacoes':
                    $query->orderBy('avaliacao', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $produtos = $query->paginate(12);

        $categorias = $this->categoriaModel->buscaTodasCategorias();
        $elementos = $this->elementoModel->buscaTodosElementos();


        return view('home', compact('produtos', 'categorias', 'elementos'));
    }
    public function index()
    {
        $categorias = $this->categoriaModel->buscaTodasCategorias();
        $elementos = $this->elementoModel->buscaTodosElementos();
        $produtos = $this->produtoModel->buscaProdutos();
        return view('home', compact('elementos', 'categorias', 'produtos'));
    }
}
