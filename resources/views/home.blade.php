<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Action Figures</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>


    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('imagens/logo.png') }}" alt="Logo Pokémon">
            </a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ asset('/') }}">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">Entre</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container my-4">
        <div class="text-center">
            <h1 class="fw-bold" style="color: #fe5f2f;">Action Figures de Pokémon</h1>
            <p>Escolha seu Pokémon favorito e adicione à sua coleção!</p>
        </div>
    </div>

    <div id="carouselExampleControls" class="carousel slide custom-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('imagens/mew (2).jpg') }}" class="d-block w-100" alt="Mew">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('imagens/mew (2).jpg') }}" class="d-block w-100" alt="Mew">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('imagens/mew (2).jpg') }}" class="d-block w-100" alt="Mew">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <div class="container product-container">


        <div class="product-card">
            <img src="{{ asset('imagens/pikachu.jpg') }}" class="product-image" alt="Pikachu Figure">
            <div class="product-info">
                <h3 class="card-title">Pikachu</h3>
                <p class="card-text">Figura colecionável do Pikachu em alta qualidade. Medindo 15cm de altura, com
                    detalhes realistas e pintura premium.</p>
                <div class="product-actions">
                    <button class="btn btn-pokemon">Comprar - R$ 89,90</button>
                    <button class="btn btn-details">Ver mais</button>
                </div>
            </div>
        </div>


        <div class="product-card">
            <img src="{{ asset('imagens/charmander.jpeg') }}" class="product-image" alt="Charmander Figure">
            <div class="product-info">
                <h3 class="card-title">Charmander</h3>
                <p class="card-text">Action figure do Charmander, perfeito para sua coleção Pokémon. Inclui base
                    especial com efeito de chamas.</p>
                <div class="product-actions">
                    <button class="btn btn-pokemon">Comprar - R$ 79,90</button>
                    <button class="btn btn-details">Ver mais</button>
                </div>
            </div>
        </div>


        <div class="product-card">
            <img src="{{ asset('imagens/bulbasauro.jpg') }}" class="product-image" alt="Bulbasaur Figure">
            <div class="card-body product-info">
                <h3 class="card-title">Bulbasaur</h3>
                <p class="card-text">Figura colecionável de Bulbasaur com detalhes impressionantes. Feito em PVC de alta
                    qualidade, 12cm de altura.</p>
                <div class="product-actions">
                    <button class="btn btn-pokemon">Comprar - R$ 75,90</button>
                    <button class="btn btn-details">Ver mais</button>
                </div>
            </div>
        </div>

    </div>


    <footer>
        <p>&copy; 2025 Pokémon Action Figures. Todos os direitos reservados.</p>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
