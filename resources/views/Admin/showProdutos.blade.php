@extends('Admin.layoutAdmin')

@section('title', 'Gerenciamento de Produtos')

@section('header')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gerenciamento de Produtos</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus me-1"></i> Novo Produto
            </button>
        </div>
    </div>
@endsection

@section('content')

    <div class="row mb-4">
        <form method="GET" action="{{ route('filtroProdutos') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" name="busca" class="form-control" placeholder="Buscar produto..."
                            value="{{ request('busca') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="tipo" class="form-select">
                        <option value="">Todos os tipos</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->nome }}"
                                {{ request('tipo') == $categoria->nome ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="elemento" class="form-select">
                        <option value="">Todos os elementos</option>
                        @foreach ($elementos as $elemento)
                            <option value="{{ $elemento->nome }}"
                                {{ request('elemento') == $elemento->nome ? 'selected' : '' }}>
                                {{ $elemento->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="ordenacao" class="form-select">
                        <option value="">Ordenar por</option>
                        <option value="Nome (A-Z)" {{ request('ordenacao') == 'Nome (A-Z)' ? 'selected' : '' }}>Nome (A-Z)
                        </option>
                        <option value="Nome (Z-A)" {{ request('ordenacao') == 'Nome (Z-A)' ? 'selected' : '' }}>Nome (Z-A)
                        </option>
                        <option value="Preço (Maior)" {{ request('ordenacao') == 'Preço (Maior)' ? 'selected' : '' }}>Preço
                            (Maior)</option>
                        <option value="Preço (Menor)" {{ request('ordenacao') == 'Preço (Menor)' ? 'selected' : '' }}>Preço
                            (Menor)</option>
                        <option value="Mais vendidos" {{ request('ordenacao') == 'Mais vendidos' ? 'selected' : '' }}>Mais
                            vendidos</option>
                    </select>
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('showProdutos') }}" class="btn btn-secondary">Limpar filtros</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabela de Produtos -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Todos os Produtos</h5>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produtos as $produto)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/produtos/' . $produto->imagem) }}"
                                        alt="{{ $produto->nome }}" class="img-thumbnail"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                </td>
                                <td>{{ $produto->nome }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $produto->categoria->nome }}</span>
                                    @if ($produto->elemento)
                                        <span class="badge bg-success">{{ $produto->elemento->nome }}</span>
                                    @endif
                                </td>
                                <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                                <td>
                                    @if ($produto->quantidade > 10)
                                        <span class="badge bg-success">{{ $produto->quantidade }} unidades</span>
                                    @elseif($produto->quantidade > 0)
                                        <span class="badge bg-warning text-dark">{{ $produto->quantidade }} unidades</span>
                                    @else
                                        <span class="badge bg-danger">Esgotado</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($produto->deleted_at)
                                        <span class="badge bg-secondary">Inativo</span>
                                    @else
                                        <span class="badge bg-success">Ativo</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal" data-id="{{ Crypt::encrypt($elemento->id) }}"
                                        data-nome="{{ $elemento->nome }}">
                                        Editar
                                    </button>

                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteCategoryModal" data-id="{{ Crypt::encrypt($elemento->id) }}"
                                        data-nome="{{ $elemento->nome }}">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @if ($produtos->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Anterior</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $produtos->previousPageUrl() }}">Anterior</a>
                        </li>
                    @endif

                    @for ($i = 1; $i <= $produtos->lastPage(); $i++)
                        <li class="page-item {{ $produtos->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $produtos->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    @if ($produtos->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $produtos->nextPageUrl() }}">Próxima</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Próxima</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endsection

    @push('modals')
        <!-- Modal Adicionar Produto -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('cadastrarProdutos') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $erro)
                                        <li>{{ $erro }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addProductModalLabel">
                                <i class="fas fa-plus-circle me-2"></i>Adicionar Novo Produto
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">

                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="nome" class="form-label">Nome do Produto*</label>
                                        <input type="text" class="form-control" id="nome" name="nome"
                                            required>
                                    </div>


                                    <div class="mb-3">
                                        <label for="descricao" class="form-label">Descrição*</label>
                                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                                    </div>


                                    <div class="mb-3">
                                        <label for="numeracao" class="form-label">Numeração/Modelo</label>
                                        <input type="text" class="form-control" id="numeracao" name="numeracao">
                                    </div>


                                    <div class="mb-3">
                                        <label for="preco" class="form-label">Preço*</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="text" class="form-control" id="preco" name="preco"
                                                required>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="categoria_id" class="form-label">Categoria*</label>
                                        <select class="form-select select2" id="categoria_id" name="categoria_id"
                                            required>
                                            <option value="" selected disabled>Selecione uma categoria</option>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label for="elemento_id" class="form-label">Elemento Pokémon*</label>
                                        <select class="form-select select2" id="elemento_id" name="elemento_id" required>
                                            <option value="" selected disabled>Selecione um elemento</option>
                                            @foreach ($elementos as $elemento)
                                                <option value="{{ $elemento->id }}">{{ $elemento->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label for="quantidade" class="form-label">Quantidade em Estoque*</label>
                                        <input type="number" class="form-control" id="quantidade" name="quantidade"
                                            min="0" required>
                                    </div>


                                    <div class="mb-3">
                                        <label for="imagem" class="form-label">Imagem do Produto*</label>
                                        <input class="form-control" type="file" id="imagem" name="imagem"
                                            accept="image/*" required>
                                        <small class="text-muted">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo:
                                            2MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Salvar Produto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editProductForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="editProductModalLabel">
                                <i class="fas fa-edit me-2"></i>Editar Produto
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_nome" class="form-label">Nome do Produto*</label>
                                        <input type="text" class="form-control" id="edit_nome" name="nome"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_descricao" class="form-label">Descrição*</label>
                                        <textarea class="form-control" id="edit_descricao" name="descricao" rows="3" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_numeracao" class="form-label">Numeração/Modelo</label>
                                        <input type="text" class="form-control" id="edit_numeracao" name="numeracao">
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_preco" class="form-label">Preço*</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="text" class="form-control" id="edit_preco" name="preco"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_categoria_id" class="form-label">Categoria*</label>
                                        <select class="form-select select2" id="edit_categoria_id" name="categoria_id"
                                            required>
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_elemento_id" class="form-label">Elemento Pokémon*</label>
                                        <select class="form-select select2" id="edit_elemento_id" name="elemento_id"
                                            required>
                                            @foreach ($elementos as $elemento)
                                                <option value="{{ $elemento->id }}">{{ $elemento->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_quantidade" class="form-label">Quantidade em Estoque*</label>
                                        <input type="number" class="form-control" id="edit_quantidade"
                                            name="quantidade" min="0" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_imagem" class="form-label">Imagem do Produto</label>
                                        <input class="form-control" type="file" id="edit_imagem" name="imagem"
                                            accept="image/*">
                                        <small class="text-muted">Deixe em branco para manter a imagem atual</small>
                                        <div class="mt-2">
                                            <img id="edit_imagem_preview" src="" class="img-thumbnail"
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                // Inicializa o Select2 quando o modal é aberto
                $('#addProductModal').on('shown.bs.modal', function() {
                    $('.select2').select2({
                        placeholder: "Selecione uma opção",
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#addProductModal')
                    });
                });

                // Máscara para o preço
                $('#preco').mask('000.000.000.000.000,00', {
                    reverse: true
                });

                // Configuração do modal de edição
                document.getElementById('editCategoryModal').addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-id');
                    var nome = button.getAttribute('data-nome');

                    var form = document.getElementById('editCategoryForm');
                    form.action = `/editarProduto/${id}`;

                    document.getElementById('editCategoryName').value = nome;
                });

                // Configuração do modal de exclusão
                document.getElementById('deleteCategoryModal').addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-id');
                    var nome = button.getAttribute('data-nome');

                    var form = document.getElementById('deleteCategoryForm');
                    form.action = `/excluirProduto/${id}`;

                    document.getElementById('deleteCategoryName').textContent = nome;
                });
            });
        </script>
    @endpush
