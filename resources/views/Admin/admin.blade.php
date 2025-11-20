@extends('Admin.layoutAdmin')

@section('title', 'Dashboard de Vendas')

@section('content')

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard de Vendas</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Hoje</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Semana</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Mês</button>
            </div>
            <button type="button" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Nova Venda
            </button>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row mb-4">

        <!-- Vendas Hoje -->
        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Vendas Hoje</h6>
                            <h3 class="mb-0">R$ {{ number_format($vendasHoje, 2, ',', '.') }}</h3>
                        </div>
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>

                    <p class="mt-2 mb-0 {{ $variacaoVendas >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas fa-arrow-{{ $variacaoVendas >= 0 ? 'up' : 'down' }} me-1"></i>
                        {{ number_format($variacaoVendas, 1, ',', '.') }}% desde ontem
                    </p>
                </div>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Pedidos</h6>
                            <h3 class="mb-0">{{ $pedidosHoje }}</h3>
                        </div>
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>

                    <p class="mt-2 mb-0 {{ $variacaoPedidos >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas fa-arrow-{{ $variacaoPedidos >= 0 ? 'up' : 'down' }} me-1"></i>
                        {{ number_format($variacaoPedidos, 1, ',', '.') }}% desde ontem
                    </p>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Clientes</h6>
                            <h3 class="mb-0">{{ $clientesHoje }}</h3>
                        </div>
                        <div class="icon-circle bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <p class="mt-2 mb-0 text-success">
                        Hoje
                    </p>
                </div>
            </div>
        </div>

        <!-- Estoque Baixo -->
        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Estoque Baixo</h6>
                            <h3 class="mb-0">{{ $estoqueBaixo }}</h3>
                        </div>
                        <div class="icon-circle bg-danger">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    </div>

                    <p class="mt-2 mb-0 text-warning">
                        Produtos críticos
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico e Top Produtos -->
    <div class="row">

        <!-- Gráfico -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Vendas Mensais</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Produtos -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Top Pokémon Vendidos</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">

                        @foreach($topProdutos as $produto)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $produto->imagem_principal ?? 'https://via.placeholder.com/40' }}"
                                         class="rounded-circle me-3" width="40">
                                    {{ $produto->nome }}
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $produto->vendas_count ?? 0 }}
                                </span>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- Últimas Vendas -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Últimas Vendas</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Pokémon</th>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ultimasVendas as $venda)
                            <tr>
                                <td>{{ $venda->id }}</td>

                                <td>{{ $venda->usuario->nome ?? 'Cliente removido' }}</td>

                                <td>
                                    @if($venda->itens)
                                        @foreach($venda->itens as $item)
                                            {{ $item->produto->nome }}<br>
                                        @endforeach
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>{{ $venda->created_at->format('d/m/Y H:i') }}</td>

                                <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>

                                <td>
                                    <span class="badge 
                                        @if($venda->status_pedido === 'concluido') bg-success 
                                        @elseif($venda->status_pedido === 'pendente') bg-warning
                                        @else bg-danger @endif
                                    ">
                                        {{ ucfirst($venda->status_pedido) }}
                                    </span>
                                </td>

                                <td>
                                    <a href="#" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>

    </div>


    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Gráfico JS -->
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');

        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($meses),
                datasets: [{
                    label: 'Vendas {{ date("Y") }}',
                    data: @json($dadosGrafico),
                    backgroundColor: '#fe5f2f',
                    borderColor: '#ffcc33',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

@endsection
