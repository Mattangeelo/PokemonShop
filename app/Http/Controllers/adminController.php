<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class adminController extends Controller
{
    private $produtoModel;
    private $estoqueModel;
    private $pedidoModel;
    private $pedidoItemModel;

    public function __construct()
    {
        $this->produtoModel = new \App\Models\produtoModel();
        $this->estoqueModel = new \App\Models\estoqueModel();
        $this->pedidoModel = new \App\Models\Pedido();
        $this->pedidoItemModel = new \App\Models\PedidoItem();
    }
    public function index()
    {

        $hoje = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();

        $vendasHoje = $this->pedidoModel->whereDate('created_at', $hoje)
            ->where('status_pedido', '!=', 'cancelado')
            ->sum('valor_total');

        $pedidosHoje = $this->pedidoModel->whereDate('created_at', $hoje)->count();

        $clientesHoje = $this->pedidoModel->whereDate('created_at', $hoje)
            ->distinct('id_usuario')
            ->count('id_usuario');

        $estoqueBaixo = $this->estoqueModel->where('quantidade', '<=', DB::raw('quantidade_minima'))
            ->count();

        $vendasMensais = $this->pedidoModel->select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as ano'),
                DB::raw('SUM(valor_total) as total')
            )
            ->where('status_pedido', '!=', 'cancelado')
            ->whereYear('created_at', date('Y'))
            ->groupBy('ano', 'mes')
            ->orderBy('ano', 'asc')
            ->orderBy('mes', 'asc')
            ->get();
        
        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        $dadosGrafico = array_fill(0, 12, 0);

        foreach ($vendasMensais as $venda) {
            $dadosGrafico[$venda->mes - 1] = floatval($venda->total);
        }
        
        // Top produtos vendidos (você precisará criar a tabela pedido_itens para isso funcionar)
        // Por enquanto, vou usar uma consulta alternativa baseada nos produtos
        $topProdutos = $this->produtoModel->withCount(['pedidoItens as vendas_count' => function($query) {
                $query->select(DB::raw('SUM(quantidade)'));
            }])
            ->orderBy('vendas_count', 'desc')
            ->limit(5)
            ->get();
        
        // Últimas vendas
        $ultimasVendas = $this->pedidoModel->with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Comparação com o dia anterior
        $ontem = Carbon::yesterday();
        $vendasOntem = $this->pedidoModel->whereDate('created_at', $ontem)
            ->where('status_pedido', '!=', 'cancelado')
            ->sum('valor_total');
        
        $pedidosOntem = $this->pedidoModel->whereDate('created_at', $ontem)->count();
        
        // Calcular variações percentuais
        $variacaoVendas = $vendasOntem > 0 ? (($vendasHoje - $vendasOntem) / $vendasOntem) * 100 : 0;
        $variacaoPedidos = $pedidosOntem > 0 ? (($pedidosHoje - $pedidosOntem) / $pedidosOntem) * 100 : 0;
        
        return view('Admin.admin', compact(
            'vendasHoje',
            'pedidosHoje',
            'clientesHoje',
            'estoqueBaixo',
            'meses',
            'dadosGrafico',
            'topProdutos',
            'ultimasVendas',
            'variacaoVendas',
            'variacaoPedidos'
        ));
    }
}
