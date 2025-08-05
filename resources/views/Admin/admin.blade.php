@extends('Admin.layoutAdmin')

@section('title', 'Título da Página')

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
        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Vendas Hoje</h6>
                            <h3 class="mb-0">R$ 3.245</h3>
                        </div>
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <p class="mt-2 mb-0 text-success">
                        <i class="fas fa-arrow-up me-1"></i> 12% desde ontem
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Pedidos</h6>
                            <h3 class="mb-0">24</h3>
                        </div>
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                    <p class="mt-2 mb-0 text-danger">
                        <i class="fas fa-arrow-down me-1"></i> 5% desde ontem
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Clientes</h6>
                            <h3 class="mb-0">18</h3>
                        </div>
                        <div class="icon-circle bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <p class="mt-2 mb-0 text-success">
                        <i class="fas fa-arrow-up me-1"></i> 8% desde ontem
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted">Estoque Baixo</h6>
                            <h3 class="mb-0">5</h3>
                        </div>
                        <div class="icon-circle bg-danger">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    </div>
                    <p class="mt-2 mb-0 text-warning">
                        <i class="fas fa-arrow-up me-1"></i> 2 produtos críticos
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos e Tabelas -->
    <div class="row">
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

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Top Pokémon Vendidos</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Pikachu">
                                Pikachu
                            </div>
                            <span class="badge bg-primary rounded-pill">42</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Charizard">
                                Charizard
                            </div>
                            <span class="badge bg-primary rounded-pill">35</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Mewtwo">
                                Mewtwo
                            </div>
                            <span class="badge bg-primary rounded-pill">28</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Eevee">
                                Eevee
                            </div>
                            <span class="badge bg-primary rounded-pill">22</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Snorlax">
                                Snorlax
                            </div>
                            <span class="badge bg-primary rounded-pill">18</span>
                        </li>
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

                </table>
            </div>
        </div>
    </div>
    </main>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts Personalizados -->
    <script>
        // Gráfico de Vendas
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                datasets: [{
                    label: 'Vendas 2023',
                    data: [1250, 1900, 2100, 2800, 3200, 3900, 4200, 3800, 3500, 4100, 4800, 5200],
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
