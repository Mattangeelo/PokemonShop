<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class carrinhoController extends Controller
{
    private $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new \App\Models\produtoModel();
    }
    public function index($idCriptografado)
    {
        try {
            $id = Crypt::decrypt($idCriptografado);

            if (!$produto = $this->produtoModel->buscaProduto($id)) {
                return redirect()->back()->with('error', 'Esse produto não foi encontrado!');
            }


            $produto->load('categoria', 'elemento');

            return view('showCompra', compact('produto'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->back()->with('error', 'ID do produto inválido!');
        }
    }
    public function viewCarrinho()
    {
        $carrinho = session('carrinho', []);
        $produtosDetalhados = [];

        if (!empty($carrinho)) {
            $ids = array_keys($carrinho);

            
            $produtosInfo = $this->produtoModel->buscaProdutosPorIds($ids);

            
            foreach ($produtosInfo as $produto) {
                $id = $produto->id;
                if (isset($carrinho[$id])) {
                    $produtosDetalhados[$id] = [
                        'id'        => $id,
                        'nome'      => $produto->nome,
                        'imagem'    => $produto->imagem,
                        'preco'     => $produto->preco,
                        'quantidade' => $carrinho[$id]['quantidade'],
                    ];
                }
            }
        }

        return view('carrinho', compact('produtosDetalhados'));
    }

    public function adicionar(Request $request)
    {

        $request->validate([
            'produto_id' => 'required|integer|exists:produtos,id',
            'quantidade' => 'required|integer|min:1'
        ], [
            'produto_id.required' => 'O campo produto é obrigatório.',
            'produto_id.integer'  => 'Erro ao encontrar o produto selecionado.',
            'produto_id.exists'   => 'Erro ao encontrar o produto selecionado.',

            'quantidade.required' => 'O campo quantidade é obrigatório.',
            'quantidade.integer'  => 'O campo quantidade deve ser um número inteiro.',
            'quantidade.min'      => 'A quantidade deve ser no mínimo 1.'
        ]);


        if (! $produto = $this->produtoModel->buscaProduto($request->input('produto_id'))) {
            return redirect()->back()->with('error', 'O produto que você está tentando comprar não foi encontrado!');
        }

        $quantidade = $request->input('quantidade');


        if (isset($produto->quantidade) && $produto->quantidade < $quantidade) {
            return redirect()->back()->with('error', 'Por favor selecione uma quantidade menor desse produto!');
        }


        $carrinho = session()->get('carrinho', []);

        if (isset($carrinho[$produto->id])) {
            $carrinho[$produto->id]['quantidade'] += $quantidade;
            $carrinho[$produto->id]['valor'] = $carrinho[$produto->id]['quantidade'] * $produto->preco;
        } else {
            $carrinho[$produto->id] = [
                'produto_id' => $produto->id,
                'nome'       => $produto->nome,
                'quantidade' => $quantidade,
                'preco'      => $produto->preco,
                'valor'      => $produto->preco * $quantidade,
            ];
        }


        session()->put('carrinho', $carrinho);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }
    public function limparCarrinho(Request $request){
       $request->session()->forget('carrinho');
       return redirect()->route('/');
    }
}
