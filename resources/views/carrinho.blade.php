<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras - Pokémon Action Figures</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('imagens/pikachuIcone.png') }}">
    <link rel="stylesheet" href="{{asset('css/carrinho.css')}}">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{route('/')}}">
                <img src="{{ asset('imagens/logo.png') }}" alt="Logo Pokémon" height="40">
            </a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ asset('/') }}">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Contato</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="#">
                            <i class="fas fa-shopping-cart"></i> Carrinho
                            <span class="badge bg-danger ms-1">{{ count(session('carrinho', [])) }}</span>
                        </a>
                    </li>
                    @if (session('user'))
                        <!-- Dropdown do perfil quando logado -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ session('user.nome') ?? session('user.email') }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                                        <i class="fas fa-user me-2"></i>Meu Perfil
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">
                                        <i class="fas fa-sign-out-alt me-2"></i>Sair
                                    </a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Entrar
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container my-5 main-content">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Meu Carrinho</h1>
            </div>

            @if(empty(session('carrinho')))
                <!-- Carrinho vazio -->
                <div class="col-12">
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>Seu carrinho está vazio</h3>
                        <p class="text-muted">Adicione alguns produtos incríveis do mundo Pokémon!</p>
                        <a href="{{ route('/') }}" class="btn btn-pokemon mt-3">
                            <i class="fas fa-arrow-left me-2"></i> Continuar Comprando
                        </a>
                    </div>
                </div>
            @else

                <div class="col-lg-8">
                    @foreach($produtosDetalhados as $id => $item)
                        <div class="cart-item-card mb-4 p-3">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ asset('storage/' . $item['imagem']) }}" alt="{{ $item['nome'] ?? 'Produto' }}"
                                        class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                                </div>
                                <div class="col-md-5">
                                    <h5 class="mb-1">{{ $item['nome'] }}</h5>
                                    <p class="text-muted mb-0">Código: #{{ $id }}</p>
                                </div>
                                <div class="col-md-2">
                                    <form action="#" method="POST" class="d-flex">
                                        @csrf
                                        <input type="hidden" name="produto_id" value="{{ $id }}">
                                        <select name="quantidade" class="form-select quantity-selector"
                                            onchange="this.form.submit()">
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $i == $item['quantidade'] ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="price-tag">R$ {{ number_format($item['preco'], 2, ',', '.') }}</span>
                                </div>
                                <div class="col-md-1 text-end">
                                    <form action="#" method="POST">
                                        @csrf
                                        <input type="hidden" name="produto_id" value="{{ $id }}">
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('/') }}" class="btn btn-outline-pokemon">
                            <i class="fas fa-arrow-left me-2"></i> Continuar Comprando
                        </a>
                        <form action="{{route('limparCarrinho')}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i> Limpar Carrinho
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Resumo do pedido -->
                <div class="col-lg-4">
                    <div class="summary-card p-4">
                        <h4 class="mb-4">Resumo do Pedido</h4>

                        @php
                            $subtotal = 0;
                            $frete = 15.00;
                            foreach (session('carrinho') as $item) {
                                $subtotal += $item['preco'] * $item['quantidade'];
                            }
                            $total = $subtotal + $frete;
                        @endphp

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Frete</span>
                            <span>R$ {{ number_format($frete, 2, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong class="price-tag">R$ {{ number_format($total, 2, ',', '.') }}</strong>
                        </div>

                        <form action="{{ route('pagamento.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mt-3">
                                <i class="fa fa-credit-card"></i> Pagar com Mercado Pago
                            </button>
                        </form>

                        <p class="text-center small mt-3 text-muted">
                            <i class="fas fa-lock me-1"></i> Sua compra está segura e criptografada
                        </p>
                    </div>

                    <div class="mt-4 p-3" style="background-color: #f8f9fa; border-radius: 10px;">
                        <h6 class="mb-3">Vantagens de comprar conosco</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-shipping-fast text-success me-2"></i>
                            <span class="small">Entrega rápida para todo Brasil</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            <span class="small">Compra 100% segura</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exchange-alt text-warning me-2"></i>
                            <span class="small">Troca grátis em 30 dias</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="py-4 mt-auto">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-warning">Pokémon Collection</h5>
                    <p class="small">A maior loja de action figures e pelúcias Pokémon do Brasil.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-warning">Links Úteis</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Sobre nós</a></li>
                        <li><a href="#" class="text-white">Política de entrega</a></li>
                        <li><a href="#" class="text-white">Trocas e devoluções</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="text-warning">Contato</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> contato@pokemoncollection.com</li>
                        <li><i class="fas fa-phone me-2"></i> (11) 1234-5678</li>
                        <li class="mt-2">
                            <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="text-center small">
                <p>&copy; 2025 Pokémon Collection. Todos os direitos reservados. Pokémon e seus respectivos nomes são
                    marcas registradas da Nintendo.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>