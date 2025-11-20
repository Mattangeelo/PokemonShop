<?php

namespace App\Http\Controllers;



class PagamentoController extends Controller
{
    private $pedidoModel = new \App\Models\Pedido();
    public function index()
    {
        $pedidoId = session()->get('pedido_id');

        if (!$pedidoId) {
            return redirect()->route('carrinho')
                ->with('error', 'Nenhum pedido encontrado.');
        }   

        $pedido = $this->pedidoModel->find($pedidoId);

        if (!$pedido) {
            return redirect()->route('carrinho')
                ->with('error', 'Pedido inválido.');
        }

        // Para segurança, criptografar o ID
        $pedidoEncrypted = encrypt($pedidoId);

        return view('pixView', compact('pedido', 'pedidoEncrypted'));
    }
}
