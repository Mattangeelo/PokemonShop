@extends('Admin.layoutAdmin')

@section('title', 'Edição de Produtos')

@section('header')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"> {{ $produto->nome }}</h1>
        <a href="{{ route('showProdutos') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('editarProduto', Crypt::encrypt($produto->id)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Produto*</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                   value="{{ old('nome', $produto->nome) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição*</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" required>{{ old('descricao', $produto->descricao) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="numeracao" class="form-label">Numeração/Modelo</label>
                            <input type="text" class="form-control" id="numeracao" name="numeracao"
                                   value="{{ old('numeracao', $produto->numeracao) }}">
                        </div>

                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço*</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="preco" name="preco"
                                    value="{{ old('preco', number_format($produto->preco, 2, ',', '.')) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoria*</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ $produto->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="elemento_id" class="form-label">Elemento Pokémon*</label>
                            <select class="form-select" id="elemento_id" name="elemento_id" required>
                                @foreach ($elementos as $elemento)
                                    <option value="{{ $elemento->id }}"
                                        {{ $produto->elemento_id == $elemento->id ? 'selected' : '' }}>
                                        {{ $elemento->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade em Estoque*</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" 
                                   value="{{ old('quantidade', $produto->estoque->quantidade ?? 0) }}" min="0" required>
                        </div>

                        <!-- Imagem Principal -->
                        <div class="mb-3">
                            <label for="imagem_principal" class="form-label">Imagem Principal</label>
                            @if($produto->imagem_principal)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/produtos/' . $produto->imagem_principal) }}" 
                                         alt="Imagem principal" class="img-thumbnail" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            @endif
                            <input class="form-control" type="file" id="imagem_principal" name="imagem_principal" accept="image/*">
                            <small class="text-muted">Deixe em branco para manter a imagem atual</small>
                        </div>
                    </div>
                </div>

                <!-- Seção de Imagens Adicionais Existentes -->
                @if(isset($produto->imagens) && $produto->imagens->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Imagens Adicionais Existentes</h5>
                        <div class="row">
                            @foreach($produto->imagens as $imagem)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $imagem->caminho_imagem) }}" 
                                         class="card-img-top" alt="Imagem adicional" 
                                         style="height: 150px; object-fit: cover;">
                                    <div class="card-body text-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="imagens_remover[]" value="{{ $imagem->id }}" 
                                                   id="imagem_{{ $imagem->id }}">
                                            <label class="form-check-label" for="imagem_{{ $imagem->id }}">
                                                Remover
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Seção para Adicionar Novas Imagens -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Adicionar Novas Imagens</h5>
                        <div class="mb-3">
                            <input class="form-control" type="file" name="imagens_adicionais[]" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="file" name="imagens_adicionais[]" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="file" name="imagens_adicionais[]" accept="image/*">
                        </div>
                        <small class="text-muted">Máximo 3 imagens adicionais. Deixe em branco para não adicionar novas imagens.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save me-1"></i> Salvar Alterações
                    </button>
                    <a href="{{ route('showProdutos') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Máscara para o preço
            $('#preco').mask('#.##0,00', { reverse: true });
            
            // Preview da imagem principal ao selecionar
            $('#imagem_principal').change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.img-thumbnail').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
        
        $('form').on('submit', function(e) {
            const imagensExistentes = {{ isset($produto->imagens) ? $produto->imagens->count() : 0 }};
            const imagensParaRemover = $('input[name="imagens_remover[]"]:checked').length;
            const novasImagens = $('input[name="imagens_adicionais[]"]').filter(function() {
                return this.files.length > 0;
            }).length;

            const totalAposOperacao = imagensExistentes - imagensParaRemover + novasImagens;

            if (totalAposOperacao > 3) {
                e.preventDefault();
                alert('Erro: O produto pode ter no máximo 3 imagens adicionais. Você está tentando ter ' + totalAposOperacao + ' imagens.');
                return false;
            }
        });

        
        $('input[name="imagens_adicionais[]"]').change(function() {
            const imagensExistentes = {{ isset($produto->imagens) ? $produto->imagens->count() : 0 }};
            const imagensParaRemover = $('input[name="imagens_remover[]"]:checked').length;
            const novasImagens = $('input[name="imagens_adicionais[]"]').filter(function() {
                return this.files.length > 0;
            }).length;

            const totalAposOperacao = imagensExistentes - imagensParaRemover + novasImagens;

            if (totalAposOperacao > 3) {
                alert('Atenção: Com estas imagens, o produto terá ' + totalAposOperacao + ' imagens adicionais. O máximo permitido é 3.');
                $(this).val('');
            }
        });
    </script>
@endpush