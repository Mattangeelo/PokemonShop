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
            <form action="{{ route ('editarProduto',Crypt::encrypt($produto->id))}}" method="POST" enctype="multipart/form-data">
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
                                   value="{{ old('quantidade', $produto->quantidade) }}" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem do Produto</label>
                            <input class="form-control" type="file" id="imagem" name="imagem" accept="image/*">
                            <small class="text-muted">Deixe em branco para manter a imagem atual</small>
                            <div class="mt-2">
                                <img src="{{ asset('storage/produtos/' . $produto->imagem) }}" 
                                     class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                <input type="hidden" name="imagem_atual" value="{{ $produto->imagem }}">
                            </div>
                        </div>
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
            
            // Preview da imagem ao selecionar
            $('#imagem').change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.img-thumbnail').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
@endpush