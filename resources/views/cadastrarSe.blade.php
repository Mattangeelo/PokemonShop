<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/cadastrarSe.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('imagens/pikachuIcone.png') }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('/') }}">
                <img src="{{ asset('imagens/logo.png') }}" alt="Logo Pokémon">
            </a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('/') }}">Início</a>
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card register-card">
                    <div class="card-header">
                        <h2 class="text-center">Cadastrar-se</h2>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('cadastrarSeSubmit') }}">
                            @csrf

                            <!-- Dados Pessoais -->
                            <div class="mb-4">
                                <h4 class="section-title">Dados Pessoais</h4>
                                <hr>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="nome" class="form-label">Nome Completo *</label>
                                        <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                            id="nome" name="nome" value="{{ old('nome') }}">
                                        @error('nome')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cpf" class="form-label">CPF *</label>
                                        <input type="text" class="form-control @error('cpf') is-invalid @enderror"
                                            id="cpf" name="cpf" value="{{ old('cpf') }}">
                                        @error('cpf')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="telefone" class="form-label">Telefone *</label>
                                        <input type="text"
                                            class="form-control @error('telefone') is-invalid @enderror" id="telefone"
                                            name="telefone" value="{{ old('telefone') }}">
                                        @error('telefone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Dados de Acesso -->
                            <div class="mb-4">
                                <h4 class="section-title">Dados de Acesso</h4>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">E-mail *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="senha" class="form-label">Senha *</label>
                                        <input type="password" class="form-control @error('senha') is-invalid @enderror"
                                            id="senha" name="senha">
                                        @error('senha')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Endereço -->
                            <div class="mb-4">
                                <h4 class="section-title">Endereço</h4>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cep" class="form-label">CEP *</label>
                                        <input type="text" class="form-control @error('cep') is-invalid @enderror"
                                            id="cep" name="cep" value="{{ old('cep') }}"
                                            placeholder="00000-000" maxlength="9" onblur="buscarCep(this.value)">
                                        @error('cep')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="logradouro" class="form-label">Logradouro *</label>
                                        <input type="text"
                                            class="form-control @error('logradouro') is-invalid @enderror"
                                            id="logradouro" name="logradouro" value="{{ old('logradouro') }}"
                                            maxlength="100">
                                        @error('logradouro')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="numero" class="form-label">Número</label>
                                        <input type="text"
                                            class="form-control @error('numero') is-invalid @enderror" id="numero"
                                            name="numero" value="{{ old('numero') }}" maxlength="10">
                                        @error('numero')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-9 mb-3">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text"
                                            class="form-control @error('complemento') is-invalid @enderror"
                                            id="complemento" name="complemento" value="{{ old('complemento') }}"
                                            maxlength="100">
                                        @error('complemento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5 mb-3">
                                        <label for="bairro" class="form-label">Bairro *</label>
                                        <input type="text"
                                            class="form-control @error('bairro') is-invalid @enderror" id="bairro"
                                            name="bairro" value="{{ old('bairro') }}" maxlength="60">
                                        @error('bairro')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="cidade" class="form-label">Cidade *</label>
                                        <input type="text"
                                            class="form-control @error('cidade') is-invalid @enderror" id="cidade"
                                            name="cidade" value="{{ old('cidade') }}" maxlength="60">
                                        @error('cidade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="uf" class="form-label">UF *</label>
                                        <input type="text" class="form-control @error('uf') is-invalid @enderror"
                                            id="uf" name="uf" value="{{ old('uf') }}"
                                            maxlength="2">
                                        @error('uf')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-lg">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function() {
            // Máscara para CPF
            $('#cpf').mask('000.000.000-00', {
                reverse: true
            });

            // Máscara para Telefone (aceita 8 ou 9 dígitos)
            var SPMaskBehavior = function(val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };
            $('#telefone').mask(SPMaskBehavior, spOptions);

            // Máscara para CEP
            $('#cep').mask('00000-000');

            // Buscar CEP
            $('#cep').on('blur', function() {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep.length !== 8) return;

                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.erro) {
                            $('#logradouro').val(data.logradouro);
                            $('#bairro').val(data.bairro);
                            $('#cidade').val(data.localidade);
                            $('#uf').val(data.uf);
                        }
                    })
                    .catch(err => console.log('Erro ao buscar CEP:', err));
            });

            // Mostrar/ocultar senha
            $('.toggle-password i').click(function() {
                const passwordInput = $('.password-input input');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
                $(this).attr('title', type === 'password' ? 'Mostrar senha' : 'Ocultar senha');
            });
        });


        function buscarCep(cep) {
            cep = cep.replace(/\D/g, '');
            if (cep.length !== 8) return;
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(res => res.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('logradouro').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('uf').value = data.uf;
                    }
                });
        }
    </script>

    <footer>
        <p>&copy; 2025 Pokémon Action Figures. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
