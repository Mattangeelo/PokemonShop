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
<!-- Filtros e Busca -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar produto...">
            <button class="btn btn-outline-secondary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="col-md-4">
        <select class="form-select">
            <option selected>Todos os tipos</option>
            <option>Action Figures</option>
            <option>Pelúcias</option>
            <option>Colecionáveis</option>
        </select>
    </div>
    <div class="col-md-4">
        <select class="form-select">
            <option selected>Ordenar por</option>
            <option>Nome (A-Z)</option>
            <option>Nome (Z-A)</option>
            <option>Preço (Maior)</option>
            <option>Preço (Menor)</option>
            <option>Mais vendidos</option>
        </select>
    </div>
</div>

<!-- Tabela de Produtos -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Todos os Produtos</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
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
                    <!-- Conteúdo da tabela -->
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Anterior</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Próxima</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Adicionar Produto -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Conteúdo do modal -->
        </div>
    </div>
</div>
@endpush

@push('scripts')

@endpush