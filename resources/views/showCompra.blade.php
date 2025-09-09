<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produto->nome }} - Pokémon Action Figures</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/showComprar.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('imagens/pikachuIcone.png') }}">
    <style>

    </style>
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
                        <a class="nav-link text-white" href="{{ asset('carrinho') }}">
                            <i class="fas fa-shopping-cart"></i> Carrinho
                            <span class="badge bg-danger ms-1">
                                {{ count(session('carrinho', [])) }}
                            </span>
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
                        <!-- Link de login quando não logado -->
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
            <!-- Imagem do Produto -->
            <div class="col-md-6 mb-4">
                <div class="text-center">
                    <img src="{{ asset('storage/produtos/' . $produto->imagem) }}" alt="{{ $produto->nome }}"
                        class="product-image img-fluid" style="max-height: 400px; object-fit: contain;">

                </div>
            </div>

            <!-- Detalhes do Produto -->
            <div class="col-md-6">
                <div class="product-details-card p-4">
                    <h1 class="h2">{{ $produto->nome }}</h1>
                    <div class="mb-3">
                        <span
                            class="badge badge-{{ strtolower($produto->elemento->nome) }} me-2">{{ $produto->elemento->nome }}</span>

                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <span class="price-tag me-3">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>

                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-2">Descrição</h5>
                        <p class="text-muted">
                            {{ $produto->descricao }}
                        </p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-2">Detalhes do Produto</h5>
                            <ul class="list-unstyled">
                                <li><strong>Numeração:</strong> {{ $produto->numeracao }}</li>
                                <li><strong>Categoria:</strong> {{ $produto->categoria->nome }}</li>
                                <li><strong>Elemento:</strong> {{ $produto->elemento->nome }}</li>
                                <!-- Adicione mais detalhes específicos se necessário -->
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-2">Disponibilidade</h5>
                            @if($produto->quantidade > 0)
                                <p class="text-success"><i class="fas fa-check-circle me-1"></i> Em estoque</p>
                            @else
                                <p class="text-danger"><i class="fas fa-times-circle me-1"></i> Esgotado</p>
                            @endif
                            <p class="text-muted small">Entrega em 3-5 dias úteis</p>
                        </div>
                    </div>

                    <form id="comprarForm" action="{{ route('adicionarCarrinho') }}" method="POST"
                        class="d-flex align-items-center gap-2">
                        @csrf
                        <input type="hidden" name="produto_id" value="{{ $produto->id }}">

                        <div class="me-2" style="min-width: 100px;">
                            <label for="quantity" class="form-label mb-1"><strong>Quantidade:</strong></label>
                            <select name="quantidade" class="form-select" id="quantity">
                                @for($i = 1; $i <= min(5, $produto->quantidade); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="d-flex flex-column justify-content-end">
                            <button type="button" class="btn btn-pokemon btn-lg" data-bs-toggle="modal"
                                data-bs-target="#confirmacaoModal">
                                <i class="fas fa-shopping-cart me-2"></i> Comprar Agora
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="row mt-5">
                <div class="col-12">
                    <div class="product-details-card p-4">
                        <ul class="nav nav-tabs" id="productTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    data-bs-target="#description" type="button" role="tab">Descrição</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs"
                                    type="button" role="tab">Especificações</button>
                            </li>
                        </ul>
                        <div class="tab-content p-3" id="productTabsContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel">
                                <h4>{{ $produto->nome }} - Detalhes</h4>
                                <p>{{ $produto->descricao }}</p>
                            </div>
                            <div class="tab-pane fade" id="specs" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Informações do Produto</h5>
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Nome</th>
                                                <td>{{ $produto->nome }}</td>
                                            </tr>
                                            <tr>
                                                <th>Descrição</th>
                                                <td>{{ $produto->descricao }}</td>
                                            </tr>
                                            <tr>
                                                <th>Numeração</th>
                                                <td>{{ $produto->numeracao }}</td>
                                            </tr>
                                            <tr>
                                                <th>Categoria</th>
                                                <td>{{ $produto->categoria->nome }}</td>
                                            </tr>
                                            <tr>
                                                <th>Elemento</th>
                                                <td>{{ $produto->elemento->nome }}</td>
                                            </tr>
                                            <tr>
                                                <th>Preço</th>
                                                <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Quantidade em estoque</th>
                                                <td>{{ $produto->quantidade }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Confirmação -->
    <div class="modal fade modal-pokemon" id="confirmacaoModal" tabindex="-1" aria-labelledby="confirmacaoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmacaoModalLabel">Confirmação de Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-shopping-cart fa-3x mb-3" style="color: var(--pokemon-red);"></i>
                    <h4>Você quer finalizar a compra?</h4>
                    <p>O produto será adicionado ao seu carrinho de compras.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-continue me-2" id="continuarBtn">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </button>
                    <button type="button" class="btn btn-modal-confirm" id="finalizarCompraBtn">
                        <i class="fas fa-check me-1"></i> Sim, Finalizar Compra
                    </button>
                </div>
            </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const continuarBtn = document.getElementById('continuarBtn');
            const finalizarCompraBtn = document.getElementById('finalizarCompraBtn');
            const comprarForm = document.getElementById('comprarForm');

            
            continuarBtn.addEventListener('click', function () {
                window.location.href = "{{ url('/') }}";
                
            });

            
            finalizarCompraBtn.addEventListener('click', function () {
                comprarForm.submit();

                setTimeout(function () {
                    window.location.href = "{{ route('carrinho') }}";
                }, 500);
            });
        });
    </script>
</body>

</html>