<?php

namespace App\Http\Controllers;

use App\Models\enderecoModel;
use App\Models\PedidoHistorico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


class carrinhoController extends Controller
{
    private $produtoModel;
    private $categoriaModel;
    private $elementoModel;
    private $pedidoModel;
    private $estoqueModel;
    private $pedidoItensModel;
    private $pedidoHistoricoModel;
    private $enderecoModel;

    public function __construct()
    {
        $this->produtoModel = new \App\Models\produtoModel();
        $this->categoriaModel = new \App\Models\categoriaModel();
        $this->elementoModel = new \App\Models\elementoModel();
        $this->pedidoModel = new \App\Models\Pedido();
        $this->estoqueModel = new \App\Models\estoqueModel();
        $this->pedidoItensModel = new \App\Models\PedidoItem();
        $this->pedidoHistoricoModel = new \App\Models\PedidoHistorico();
        $this->enderecoModel = new \App\Models\enderecoModel();
    }

    public function index($idCriptografado)
    {
        $id = Crypt::decrypt($idCriptografado);
        $categorias = $this->categoriaModel->buscaTodasCategorias();
        $elementos = $this->elementoModel->buscaTodosElementos();

        $produto = $this->produtoModel->with(['estoque', 'imagens'])->find($id);

        if (!$produto) {
            return redirect()->route('showProdutos')
                ->with('error', 'Produto não encontrado');
        }

        return view("showCompra", compact('elementos', 'categorias', 'produto'));
    }

    public function viewCarrinho()
    {
        $carrinho = session('carrinho', []);
        $produtosDetalhados = [];

        if (!empty($carrinho)) {
            $ids = array_keys($carrinho);
            $produtosInfo = $this->produtoModel
                ->with(['estoque', 'imagens'])
                ->whereIn('id', $ids)
                ->get();

            foreach ($produtosInfo as $produto) {
                $id = $produto->id;
                if (isset($carrinho[$id])) {
                    $quantidadeCarrinho = $carrinho[$id]['quantidade'];
                    $estoqueDisponivel = $produto->estoque->quantidade ?? 0;
                    $imagem = $produto->imagens->first()->caminho ?? 'produtos/' . $produto->imagem_principal;
                    $produtosDetalhados[$id] = [
                        'id' => $id,
                        'nome' => $produto->nome,
                        'imagem' => $imagem,
                        'preco' => $produto->preco,
                        'quantidade' => $quantidadeCarrinho,
                        'estoque' => $estoqueDisponivel,
                        'subtotal' => $produto->preco * $quantidadeCarrinho
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

    public function limparCarrinho(Request $request)
    {
        $request->session()->forget('carrinho');
        return redirect()->route('/');
    }

    public function preparaPix(Request $request)
    {
        $carrinho = session()->get('carrinho', []);
        $usuario  = session()->get('user'); // id, nome, email
        $idUsuario = $usuario['id'];

        if (empty($carrinho)) {
            return redirect()->back()->with('error', 'Seu carrinho está vazio.');
        }

        // =============================
        // 1) VALIDAR PRODUTOS E ESTOQUE
        // =============================
        $valorTotal = 0;

        foreach ($carrinho as $item) {
            $id = $item['produto_id'];
            $nome = $item['nome'];
            $qtd = $item['quantidade'];

            if (!$produto = $this->produtoModel->buscaProduto($id)) {
                return redirect()->back()->with('error', 'O produto não foi encontrado, por favor tente novamente!');
            }

            if (!$this->estoqueModel->validaEstoque($qtd, $id)) {
                return redirect()->back()->with('error', "O produto {$nome} não possui estoque suficiente.");
            }

            // Soma valor total com preço REAL do banco
            $valorTotal += ($produto->preco * $qtd);
        }

        // ===================================
        // 2) BUSCAR ENDEREÇO DO USUÁRIO
        // ===================================
        $endereco = $this->enderecoModel
            ->where('id_usuario', $idUsuario)
            ->first();

        if (!$endereco) {
            return redirect()->back()->with('error', 'Nenhum endereço cadastrado para este usuário.');
        }

        // Transformar o objeto em JSON
        $enderecoJson = json_encode($endereco);

        // =============================
        // 3) CRIAR O PEDIDO
        // =============================
        $pedidoId = $this->pedidoModel->insertGetId([
            'id_usuario'      => $idUsuario,
            'status_pedido'   => 'pago', // STATUS CONCLUÍDO
            'valor_total'     => $valorTotal,
            'valor_frete'     => 0.00,
            'valor_desconto'  => 0.00,
            'endereco_entrega_json' => $enderecoJson,
            'observacoes'     => $request->input('observacoes', null),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // =============================
        // 4) INSERIR ITENS DO PEDIDO
        // =============================
        foreach ($carrinho as $item) {

            $idProduto = $item['produto_id'];
            $qtd       = $item['quantidade'];

            $produto = $this->produtoModel->buscaProduto($idProduto);

            $this->pedidoItensModel->insert([
                'id_pedido'      => $pedidoId,
                'id_produto'     => $idProduto,
                'quantidade'     => $qtd,
                'valor_unitario' => $produto->preco,
                'produto_nome'   => $produto->nome,
                'produto_imagem' => $produto->imagem_principal ?? null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // =======================================================
            // 5) ATUALIZAR ESTOQUE APÓS PAGAMENTO APROVADO
            // =======================================================
            $this->estoqueModel
                ->where('id_produto', $idProduto)
                ->decrement('quantidade', $qtd);
        }
        session()->put('pedido_id', $pedidoId);
        // =============================
        // 6) REGISTRAR HISTÓRICO DO PEDIDO
        // =============================
        $this->pedidoHistoricoModel->insert([
            'id_pedido'             => $pedidoId,
            'status_anterior'       => 'aguardando_pagamento',
            'status_novo'           => 'pago',
            'observacao'            => 'Pagamento via PIX concluído com sucesso.',
            'id_usuario_responsavel' => $idUsuario,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);


        // =============================
        // 7) SALVAR STATUS NA SESSÃO
        // =============================
        session()->put('status_pagamento', 'pago');



        // =============================
        // 8) LIMPAR O CARRINHO
        // =============================
        session()->forget('carrinho');
        session()->forget('valorTotal');


        return redirect()->away('https://link.mercadopago.com.br/laravelpokemonshop');
    }
}
