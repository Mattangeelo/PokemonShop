<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Action Figures</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('imagens/pikachuIcone.png') }}">

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{route('/')}}">
                <img src="{{ asset('imagens/logo.png') }}" alt="Logo Pokémon">
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
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-shopping-cart"></i> Carrinho
                            <span class="badge bg-danger ms-1">3</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-heart"></i> Favoritos
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

    <div class="container mt-4">
        <div class="row collections-banner">
            <!-- Coleção 1 - Exibindo um produto específico do banco -->
            <div class="col-md-6 mb-4">
                <div class="collection-card collection-1">
                    <div class="collection-content">
                        <h3>Destaque da Semana</h3>
                        <p>{{ $produtoDestaque->nome ?? 'Produto em Destaque' }}</p>
                        @if(isset($produtoDestaque))
                            <p class="product-price mb-2">
                                @if($produtoDestaque->desconto)
                                    @php
                                        $precoOriginal = $produtoDestaque->preco;
                                        $precoComDesconto = $precoOriginal - ($precoOriginal * $produtoDestaque->desconto / 100);
                                    @endphp
                                    <span class="text-danger"><del>R$
                                            {{ number_format($precoOriginal, 2, ',', '.') }}</del></span>
                                    <span> R$ {{ number_format($precoComDesconto, 2, ',', '.') }}</span>
                                @else
                                    <span>R$ {{ number_format($produtoDestaque->preco, 2, ',', '.') }}</span>
                                @endif
                            </p>
                            <a href="{{ route('produto.detalhes', $produtoDestaque->id) }}" class="btn btn-collection">Ver
                                Detalhes</a>
                        @else
                            <a href="#" class="btn btn-collection">Ver Produtos</a>
                        @endif
                    </div>
                    <div class="collection-image">
                        @if(isset($produtoDestaque))
                            <img src="{{ asset('storage/produtos/' . $produtoDestaque->imagem) }}"
                                alt="{{ $produtoDestaque->nome }}" class="img-fluid">
                        @else
                            <img src="{{ asset('imagens/pikachu.jpg') }}" alt="Produto em Destaque" class="img-fluid">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Coleção 2 - Exibindo um produto aleatório do banco -->
            <div class="col-md-6 mb-4">
                <div class="collection-card collection-2">
                    <div class="collection-content">
                        <h3>Recomendado para Você</h3>
                        <p>{{ $produtoAleatorio->nome ?? 'Produto Recomendado' }}</p>
                        @if(isset($produtoAleatorio))
                            <p class="product-price mb-2">
                                @if($produtoAleatorio->desconto)
                                    @php
                                        $precoOriginal = $produtoAleatorio->preco;
                                        $precoComDesconto = $precoOriginal - ($precoOriginal * $produtoAleatorio->desconto / 100);
                                    @endphp
                                    <span class="text-danger"><del>R$
                                            {{ number_format($precoOriginal, 2, ',', '.') }}</del></span>
                                    <span> R$ {{ number_format($precoComDesconto, 2, ',', '.') }}</span>
                                @else
                                    <span>R$ {{ number_format($produtoAleatorio->preco, 2, ',', '.') }}</span>
                                @endif
                            </p>
                            <a href="{{ route('produto.detalhes', $produtoAleatorio->id) }}"
                                class="btn btn-collection">Comprar Agora</a>
                        @else
                            <a href="#" class="btn btn-collection">Explorar</a>
                        @endif
                    </div>
                    <div class="collection-image">
                        @if(isset($produtoAleatorio))
                            <img src="{{ asset('storage/produtos/' . $produtoAleatorio->imagem) }}"
                                alt="{{ $produtoAleatorio->nome }}" class="img-fluid">
                        @else
                            <img src="{{ asset('imagens/mew.jpg') }}" alt="Produto Recomendado" class="img-fluid">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Filtros e Ordenação -->
<div class="container">
    <form action="{{ route('filtro') }}" method="GET" id="filterForm">
        <div class="row">
            <div class="col-md-3">
                <div class="filter-section">
                    <h5 class="filter-title"><i class="fas fa-filter me-2"></i>Filtrar Por</h5>

                    <!-- Filtro por Categoria -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Categoria</h6>
                        @foreach($categorias as $categoria)
                        <div class="form-check">
                            <input class="form-check-input category-filter" type="checkbox" 
                                name="categorias[]" 
                                value="{{ $categoria->id }}"
                                id="categoria_{{ $categoria->id }}"
                                {{ in_array($categoria->id, request('categorias', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="categoria_{{ $categoria->id }}">
                                {{ $categoria->nome }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Filtro por Elemento/Tipo -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Elemento Pokémon</h6>
                        <div class="pokemon-element">
                            @foreach($elementos as $elemento)
                            <button type="button" class="{{ strtolower($elemento->nome) }} mb-1 element-filter"
                                    data-elemento="{{ $elemento->id }}"
                                    data-selected="{{ in_array($elemento->id, request('elementos', [])) ? 'true' : 'false' }}">
                                {{ $elemento->nome }}
                            </button>
                            @endforeach
                            <input type="hidden" name="elementos[]" id="elementosInput" value="{{ implode(',', request('elementos', [])) }}">
                        </div>
                    </div>

                    <!-- Filtro por Preço -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Faixa de Preço</h6>
                        <input type="range" class="form-range" min="0" max="500" step="10" 
                               id="priceRange" name="preco_max"
                               value="{{ request('preco_max', 500) }}">
                        <div class="d-flex justify-content-between">
                            <span>R$ 0</span>
                            <span id="priceValue">R$ {{ request('preco_max', 500) }}</span>
                        </div>
                        <input type="hidden" name="preco_min" value="0">
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary w-100">Aplicar Filtros</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 mt-2" onclick="limparFiltros()">
                        Limpar Filtros
                    </button>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Barra de Ordenação e Busca -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="me-2">Ordenar por:</span>
                        <div class="sort-options">
                            <select class="form-select form-select-sm" name="ordenar" style="width: auto;" onchange="document.getElementById('filterForm').submit()">
                                <option value="relevantes" {{ request('ordenar') == 'relevantes' ? 'selected' : '' }}>Mais relevantes</option>
                                <option value="menor_preco" {{ request('ordenar') == 'menor_preco' ? 'selected' : '' }}>Menor preço</option>
                                <option value="maior_preco" {{ request('ordenar') == 'maior_preco' ? 'selected' : '' }}>Maior preço</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <span class="me-2">{{ $produtos->total() }} itens</span>
                    </div>
                </div>

                <!-- Listagem de Produtos -->
                <div class="product-container">
                    @forelse($produtos as $produto)
                        <div class="product-card">
                            <div class="position-relative">
                                <img src="{{ asset('storage/produtos/' . $produto->imagem) }}" class="product-image"
                                    alt="{{ $produto->nome }}">
                                @if($produto->desconto)
                                    <span
                                        class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded small">-{{ $produto->desconto }}%</span>
                                @endif
                                @if($produto->novo)
                                    <span
                                        class="position-absolute top-0 end-0 bg-success text-white px-2 py-1 m-2 rounded small">Novo</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">{{ $produto->nome }}</h3>
                                <div class="mb-2">
                                    <span
                                        class="badge badge-{{ strtolower($produto->elemento->nome) }}">{{ $produto->elemento->nome }}</span>
                                </div>
                                <p class="product-description">{{ $produto->descricao }}</p>
                                <div class="product-price">
                                    @if($produto->desconto)
                                        @php
                                            $precoOriginal = $produto->preco;
                                            $precoComDesconto = $precoOriginal - ($precoOriginal * $produto->desconto / 100);
                                        @endphp
                                        <span class="text-danger"><del>R$
                                                {{ number_format($precoOriginal, 2, ',', '.') }}</del></span>
                                        <span> R$ {{ number_format($precoComDesconto, 2, ',', '.') }}</span>
                                    @else
                                        <span>R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                                    @endif
                                </div>
                                <div class="product-actions">
                                    <!-- Botão Comprar - Redireciona para a rota de compra -->
                                    <a href="{{ route('showComprar', $produto->id) }}" class="btn btn-pokemon">
                                        <i class="fas fa-shopping-cart me-1"></i> Comprar
                                    </a>
                                    <a href="#" class="btn btn-details">
                                        <i class="fas fa-info-circle me-1"></i> Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-exclamation-circle fa-3x mb-3 text-secondary"></i>
                            <h4>Nenhum produto encontrado</h4>
                            <p class="text-muted">Não há produtos disponíveis no momento.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Paginação -->
                @if($produtos->hasPages())
                    <nav aria-label="Page navigation" class="mt-5">
                        <ul class="pagination justify-content-center">
                            <!-- Link Página Anterior -->
                            <li class="page-item {{ $produtos->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $produtos->previousPageUrl() }}" tabindex="-1"
                                    aria-disabled="{{ $produtos->onFirstPage() ? 'true' : 'false' }}">
                                    Anterior
                                </a>
                            </li>

                            <!-- Links das Páginas -->
                            @foreach($produtos->getUrlRange(1, $produtos->lastPage()) as $page => $url)
                                <li class="page-item {{ $produtos->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            <!-- Link Próxima Página -->
                            <li class="page-item {{ $produtos->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $produtos->nextPageUrl() }}"
                                    aria-disabled="{{ $produtos->hasMorePages() ? 'false' : 'true' }}">
                                    Próxima
                                </a>
                            </li>
                        </ul>
                    </nav>
                @endif
        
            </div>
        </div>
    </form>
</div>

    <!-- Modal de Perfil (mantido igual) -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-pokemon">
                    <h5 class="modal-title text-white" id="profileModalLabel">Meu Perfil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (session('user'))
                        <div class="text-center mb-4">
                            <div class="profile-avatar mb-3">
                                <i class="fas fa-user-circle fa-5x text-secondary"></i>
                            </div>
                            <h4>{{ session('user.nome') ?? 'Usuário' }}</h4>
                            <p class="text-muted">{{ session('user.email') }}</p>
                        </div>

                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-history me-2"></i> Meus Pedidos
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-heart me-2"></i> Favoritos
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-cog me-2"></i> Configurações
                            </a>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="py-4">
        <div class="container">
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
        // Função para os filtros (pode ser implementada com JavaScript real posteriormente)
        document.addEventListener('DOMContentLoaded', function () {
            // Exemplo de interação com os filtros
            const badges = document.querySelectorAll('.pokemon-element .badge');
            badges.forEach(badge => {
                badge.addEventListener('click', function () {
                    this.classList.toggle('opacity-50');
                });
            });

            // Atualizar valor do range de preço
            const priceRange = document.getElementById('priceRange');
            priceRange.addEventListener('input', function () {
                // Aqui você pode atualizar a exibição do valor selecionado
                console.log('Preço selecionado:', this.value);
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
    // Elementos filtrados
    const elementosSelecionados = [];
    const elementosInput = document.getElementById('elementosInput');
    
    // Atualizar elementos selecionados
    document.querySelectorAll('.element-filter').forEach(badge => {
        const elementoId = badge.getAttribute('data-elemento');
        const isSelected = badge.getAttribute('data-selected') === 'true';
        
        if (isSelected) {
            elementosSelecionados.push(elementoId);
            badge.classList.add('active');
        }
        
        badge.addEventListener('click', function () {
            const index = elementosSelecionados.indexOf(elementoId);
            
            if (index > -1) {
                elementosSelecionados.splice(index, 1);
                badge.classList.remove('active');
            } else {
                elementosSelecionados.push(elementoId);
                badge.classList.add('active');
            }
            
            elementosInput.value = elementosSelecionados.join(',');
        });
    });

    // Atualizar valor do range de preço
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    
    priceRange.addEventListener('input', function () {
        priceValue.textContent = 'R$ ' + this.value;
    });
    
    // Inicializar valor do range
    priceValue.textContent = 'R$ ' + priceRange.value;
});

function limparFiltros() {
    // Redirecionar para a página inicial sem parâmetros
    window.location.href = "{{ route('/') }}";
}

// Enviar formulário quando checkbox for alterado
document.querySelectorAll('.category-filter').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
    </script>
</body>

</html>