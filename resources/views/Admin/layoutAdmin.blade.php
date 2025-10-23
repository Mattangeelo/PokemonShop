<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokemonShop - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('imagens/pikachuIcone.png') }}">
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="sidebar-header">
                    <h3 class="text-center py-3">
                        <i class="fas fa-pokeball"></i> PokemonShop
                    </h3>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/admin') ? 'active' : '' }}" href="{{ route('admin')}}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/vendas') ? 'active' : '' }}" href="{{ url('admin/vendas') }}">
                            <i class="fas fa-shopping-cart me-2"></i> Vendas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/categorias*') ? 'active' : '' }}" href="{{ route('showCategorias') }}">
                            <i class="fas fa-tags me-2"></i> Categorias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/elementos*') ? 'active' : '' }}" href="{{ route('showElementos') }}">
                            <i class="fas fa-fire me-2"></i> Elementos Pokemons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/produtos*') ? 'active' : '' }}" href="{{ route('showProdutos') }}">
                            <i class="fas fa-box-open me-2"></i> Produtos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/estoques*') ? 'active' : '' }}" href="{{ route('showEstoques') }}">
                            <i class="fas fa-box-open me-2"></i> Estoque
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/clientes*') ? 'active' : '' }}" href="{{ url('admin/clientes') }}">
                            <i class="fas fa-users me-2"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/relatorios*') ? 'active' : '' }}" href="{{ url('admin/relatorios') }}">
                            <i class="fas fa-chart-line me-2"></i> Relatórios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/sair*') ? 'active' : '' }}" href="{{ url('logout') }}">
                            <i class="fas fa-chart-bar me-2"></i> Sair
                        </a>
                    </li>
                </ul>

                <div class="sidebar-footer mt-auto p-3">
                    <div class="text-center">
                        <img src="https://via.placeholder.com/80" class="rounded-circle mb-2" alt="Usuário">
                        <p class="mb-1">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <small class="text-muted">Admin</small>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('header')
                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</body>
</html>
