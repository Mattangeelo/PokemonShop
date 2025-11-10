<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use Illuminate\Support\Facades\DB;

class PagamentoController extends Controller
{
    private $produtoModel;

    public function __construct(){
        $this->produtoModel = new \App\Models\produtoModel();
    }
    public function checkout(Request $request)
    {
        $carrinho = session('carrinho', []);
        if (empty($carrinho)) {
            return redirect()->route('carrinho.view')->with('error', 'Carrinho vazio');
        }

        MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
        $client = new PreferenceClient();

        $items = [];
        foreach ($carrinho as $item) {
            $items[] = [
                'title' => $item['nome'],
                'quantity' => $item['quantidade'],
                'unit_price' => (float)$item['preco'],
                'currency_id' => 'BRL',
            ];
        }

        // Cria a preferência de pagamento
        $preference = $client->create([
            'items' => $items,
            'back_urls' => [
                'success' => route('pagamento.sucesso'),
                'failure' => route('pagamento.falha'),
            ],
            'auto_return' => 'approved',
        ]);

        // Armazena temporariamente o carrinho para usar após retorno
        session(['checkout_carrinho' => $carrinho]);

        return redirect($preference->init_point);
    }

    public function sucesso(Request $request)
    {
        $status = $request->query('status'); // status vindo do Mercado Pago
        $carrinho = session('checkout_carrinho', []);

        if ($status === 'approved' && !empty($carrinho)) {
            DB::beginTransaction();
            try {
                foreach ($carrinho as $item) {
                    $produto = $this->produtoModel->with('estoque')->find($item['id']);
                    if ($produto && $produto->estoque) {
                        $novoEstoque = max(0, $produto->estoque->quantidade - $item['quantidade']);
                        $produto->estoque->update(['quantidade' => $novoEstoque]);
                    }
                }

                DB::commit();
                session()->forget(['carrinho', 'checkout_carrinho']);

                return view('pagamento.sucesso')->with('message', 'Pagamento aprovado! Estoque atualizado.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('carrinho.view')->with('error', 'Erro ao atualizar estoque.');
            }
        }

        return redirect()->route('carrinho.view')->with('error', 'Pagamento não aprovado.');
    }

    public function falha()
    {
        return view('pagamento.falha');
    }
}
