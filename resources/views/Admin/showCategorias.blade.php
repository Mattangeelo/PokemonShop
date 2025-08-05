@extends('Admin.layoutAdmin')

@section('title', 'Gerenciamento de Categorias')

@section('header')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gerenciamento de Categorias</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus me-1"></i> Nova Categoria
            </button>
        </div>
    </div>
@endsection

@section('content')
    <form method="GET" action="{{ route('filtroCategorias') }}">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Buscar categoria..."
                        value="{{ request('q') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <select name="ordenar" class="form-select" onchange="this.form.submit()">
                    <option value="">Ordenar por</option>
                    <option value="az" {{ request('ordenar') == 'az' ? 'selected' : '' }}>Nome (A-Z)</option>
                    <option value="za" {{ request('ordenar') == 'za' ? 'selected' : '' }}>Nome (Z-A)</option>
                    </option>
                </select>
            </div>
        </div>
    </form>

    <!-- Tabela de Categorias -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Todas as Categorias</h5>
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
                            <th>Nome</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria['nome'] }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal" data-id="{{ Crypt::encrypt($categoria->id) }}"
                                        data-nome="{{ $categoria->nome }}">
                                        Editar
                                    </button>

                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteCategoryModal" data-id="{{ Crypt::encrypt($categoria->id) }}"
                                        data-nome="{{ $categoria->nome }}">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="d-flex justify-content-center">
                {{ $categorias->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('modals')
    <!-- Modal Adicionar Categoria -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Adicionar Nova Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('cadastrarCategoria') }}" method="POST">
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
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Nome da Categoria</label>
                            <input type="text" name="nome" class="form-control" id="categoryName" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Categoria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editCategoryForm">
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
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">Nome da Categoria</label>
                            <input type="text" class="form-control" name="nome" id="editCategoryName" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="deleteCategoryForm">
                    @csrf
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir a categoria "<span id="deleteCategoryName"></span>"?</p>
                        <p class="text-danger">Esta ação não pode ser desfeita!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    @if ($errors->any())
        <script>
            // Mostra o modal apropriado se houver erros
            @if (request()->isMethod('put'))
                var editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                editModal.show();
            @else
                var addModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
                addModal.show();
            @endif
        </script>
    @endif

    <script>
        // Configuração do modal de edição
        document.getElementById('editCategoryModal').addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nome = button.getAttribute('data-nome');

            var form = document.getElementById('editCategoryForm');
            form.action = `/editarCategoria/${id}`;

            document.getElementById('editCategoryName').value = nome;
        });

        // Configuração do modal de exclusão
        document.getElementById('deleteCategoryModal').addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nome = button.getAttribute('data-nome');

            var form = document.getElementById('deleteCategoryForm');
            form.action = `/excluirCategoria/${id}`;

            document.getElementById('deleteCategoryName').textContent = nome;
        });
    </script>
@endpush
