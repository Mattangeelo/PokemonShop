<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('imagens/mini.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/entrar.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('imagens/pikachuIcone.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <button class="btn"><a class="nav-link" href="{{ asset('/') }}">Início <i class="fa fa-arrow-right"></i></a></button>
            </li>
        </ul>
    </nav>

    <div class="login-container">
        <div class="login-card">
            <img src="{{ asset('imagens/logo.png') }}" alt="Logo Contabilidade Angelos" class="logo">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <form method="POST" action="{{ route('loginSubmit') }}">
                @csrf
                <div class="mb-3 input-box">
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="E-mail" value="{{ old('email') }}">
                    <i class="fa-solid fa-user"></i>
                    @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 input-box">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Senha"><i class="fa-solid fa-lock"></i>
                    @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-login w-100">Entrar</button>
            </form>
            <div class="login-footer">
                <p>Não tem uma conta?<a href="{{ route('cadastrarSe') }}"> Cadastre-se</a></p>
            </div>
            @if (session('loginError'))
            <div class="alert alert-danger">
                {{ session('loginError') }}
            </div>
            @endif
        </div>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>

</body>

</html>