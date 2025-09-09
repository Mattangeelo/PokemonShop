<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Pokémon Action Figures</title>
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
                        <a class="nav-link text-white active" href="{{route('contato')}}">Contato</a>
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



    <!-- Conteúdo Principal -->
    <div class="container mb-5 mt-5">
        <div class="row">
            <!-- Informações de Contato -->
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-4 text-pokemon">Informações de Contato</h3>
                        
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <i class="fas fa-map-marker-alt fa-2x text-pokemon"></i>
                            </div>
                            <div>
                                <h5>Endereço</h5>
                                <p class="text-muted mb-0">Av.Goioere 1940<br>Centro, Campo Mourão</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <i class="fas fa-phone fa-2x text-pokemon"></i>
                            </div>
                            <div>
                                <h5>Telefone</h5>
                                <p class="text-muted mb-0">(44) 9949-8588</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <i class="fas fa-envelope fa-2x text-pokemon"></i>
                            </div>
                            <div>
                                <h5>Email</h5>
                                <p class="text-muted mb-0">contato@pokemonfigures.com</p>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-clock fa-2x text-pokemon"></i>
                            </div>
                            <div>
                                <h5>Horário de Atendimento</h5>
                                <p class="text-muted mb-0">Segunda a Sexta: 9h às 18h<br>Sábado: 10h às 16h</p>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Siga-nos</h5>
                        <div class="d-flex">
                            <a href="#" class="social-icon me-3">
                                <i class="fab fa-facebook-f fa-2x"></i>
                            </a>
                            <a href="#" class="social-icon me-3">
                                <i class="fab fa-instagram fa-2x"></i>
                            </a>
                            <a href="#" class="social-icon me-3">
                                <i class="fab fa-twitter fa-2x"></i>
                            </a>
                            <a href="#" class="social-icon">
                                <i class="fab fa-youtube fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Formulário de Contato -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="mb-4 text-pokemon">Envie sua Mensagem</h3>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <form action="#" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nome" class="form-label">Nome completo</label>
                                    <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                           id="nome" name="nome" value="{{ old('nome') }}" required>
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="assunto" class="form-label">Assunto</label>
                                <input type="text" class="form-control @error('assunto') is-invalid @enderror" 
                                       id="assunto" name="assunto" value="{{ old('assunto') }}" required>
                                @error('assunto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="mensagem" class="form-label">Mensagem</label>
                                <textarea class="form-control @error('mensagem') is-invalid @enderror" 
                                          id="mensagem" name="mensagem" rows="5" required>{{ old('mensagem') }}</textarea>
                                @error('mensagem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-pokemon btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Enviar Mensagem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mapa -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="map-container" style="height: 400px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                            <div class="text-center p-4">
                                <i class="fas fa-map-marked-alt fa-4x text-pokemon mb-3"></i>
                                <h4>Localização da Loja</h4>
                                <p class="text-muted">Av.Goioere 1940 - Centro, Campo Mourão</p>
                                <button class="btn btn-outline-pokemon">
                                    <i class="fas fa-directions me-2"></i> Ver rotas no mapa
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        // Script para validação do formulário
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(e) {
                let valid = true;
                
                // Validação simples dos campos
                const nome = document.getElementById('nome');
                const email = document.getElementById('email');
                const assunto = document.getElementById('assunto');
                const mensagem = document.getElementById('mensagem');
                
                if (nome.value.trim() === '') {
                    nome.classList.add('is-invalid');
                    valid = false;
                }
                
                if (email.value.trim() === '' || !email.value.includes('@')) {
                    email.classList.add('is-invalid');
                    valid = false;
                }
                
                if (assunto.value.trim() === '') {
                    assunto.classList.add('is-invalid');
                    valid = false;
                }
                
                if (mensagem.value.trim() === '') {
                    mensagem.classList.add('is-invalid');
                    valid = false;
                }
                
                if (!valid) {
                    e.preventDefault();
                }
            });
            
            // Remover a classe de erro quando o usuário começar a digitar
            const inputs = document.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            });
        });
    </script>
</body>

</html>