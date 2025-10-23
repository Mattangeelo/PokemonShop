@extends('Admin.layoutAdmin')

@section('title', 'Gerenciamento de Estoque')

@section('header')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gerenciamento de Estoque</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('showProdutos') }}" class="btn btn-sm btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Voltar para Produtos
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('filtroEstoque') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="busca" class="form-control" placeholder="Buscar produto..."
                                        value="{{ request('busca') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Todos os status</option>
                                    <option value="estoque_baixo" {{ request('status') == 'estoque_baixo' ? 'selected' : '' }}>Estoque Baixo</option>
                                    <option value="sem_estoque" {{ request('status') == 'sem_estoque' ? 'selected' : '' }}>Sem
                                        Estoque</option>
                                    <option value="com_estoque" {{ request('status') == 'com_estoque' ? 'selected' : '' }}>Com
                                        Estoque</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="categoria" class="form-select">
                                    <option value="">Todas as categorias</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalProdutos }}</h4>
                            <p class="mb-0">Total Produtos</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $produtosComEstoque }}</h4>
                            <p class="mb-0">Com Estoque</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $produtosEstoqueBaixo }}</h4>
                            <p class="mb-0">Estoque Baixo</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $produtosSemEstoque }}</h4>
                            <p class="mb-0">Sem Estoque</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Estoque -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Controle de Estoque</h5>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Estoque Atual</th>
                            <th>Estoque Mínimo</th>
                            <th>Status</th>
                            <th>Última Atualização</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produtos as $produto)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/produtos/' . $produto->imagem_principal) }}"
                                            alt="{{ $produto->nome }}" class="img-thumbnail me-3"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $produto->nome }}</strong>
                                            <br>
                                            <small class="text-muted">Cód: {{ $produto->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $produto->categoria->nome }}</span>
                                </td>
                                <td>
                                    <span class="h5">{{ $produto->estoque->quantidade ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $produto->estoque->quantidade_minima ?? 10 }}</span>
                                </td>
                                <td>
                                    @php
                                        $quantidade = $produto->estoque->quantidade ?? 0;
                                        $quantidadeMinima = $produto->estoque->quantidade_minima ?? 10;
                                    @endphp
                                    @if($quantidade == 0)
                                        <span class="badge bg-danger">Sem Estoque</span>
                                    @elseif($quantidade <= $quantidadeMinima)
                                        <span class="badge bg-warning text-dark">Estoque Baixo</span>
                                    @else
                                        <span class="badge bg-success">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $produto->estoque ? $produto->estoque->updated_at->format('d/m/Y H:i') : 'Nunca' }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#adicionarEstoqueModal" data-produto-id="{{ $produto->id }}"
                                            data-produto-nome="{{ $produto->nome }}"
                                            data-estoque-atual="{{ $produto->estoque->quantidade ?? 0 }}">
                                            <i class="fas fa-plus me-1"></i> Adicionar
                                        </button>

                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#ajustarEstoqueModal" data-produto-id="{{ $produto->id }}"
                                            data-produto-nome="{{ $produto->nome }}"
                                            data-estoque-atual="{{ $produto->estoque->quantidade ?? 0 }}"
                                            data-estoque-minimo="{{ $produto->estoque->quantidade_minima ?? 10 }}">
                                            <i class="fas fa-edit me-1"></i> Ajustar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($produtos->hasPages())
                <nav aria-label="Page navigation" class="mt-4">
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
            @endif
        </div>
    </div>
@endsection

@push('modals')
    <!-- Modal Adicionar Estoque -->
    <div class="modal fade" id="adicionarEstoqueModal" tabindex="-1" aria-labelledby="adicionarEstoqueModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formAdicionarEstoque" method="POST" action="{{ route('adicionarEstoque') }}">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="adicionarEstoqueModalLabel">
                            <i class="fas fa-plus-circle me-2"></i>Adicionar ao Estoque
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="produto_id" id="produto_id_adicionar">

                        <div class="mb-3">
                            <label class="form-label">Produto</label>
                            <p class="form-control-static fw-bold" id="produto_nome_adicionar"></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estoque Atual</label>
                            <p class="form-control-static" id="estoque_atual_adicionar"></p>
                        </div>

                        <div class="mb-3">
                            <label for="quantidade_adicionar" class="form-label">Quantidade a Adicionar*</label>
                            <input type="number" class="form-control" id="quantidade_adicionar" name="quantidade" min="1"
                                max="10000" required>
                            <small class="text-muted">Quantidade positiva para adicionar ao estoque</small>
                        </div>

                        <div class="mb-3">
                            <label for="observacao_adicionar" class="form-label">Observação (Opcional)</label>
                            <textarea class="form-control" id="observacao_adicionar" name="observacao" rows="2"
                                placeholder="Ex: Compra de fornecedor, reposição de estoque..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Adicionar ao Estoque</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ajustar Estoque -->
    <div class="modal fade" id="ajustarEstoqueModal" tabindex="-1" aria-labelledby="ajustarEstoqueModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formAjustarEstoque" method="POST" action="{{ route('ajustarEstoque') }}">
                    @csrf
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="ajustarEstoqueModalLabel">
                            <i class="fas fa-edit me-2"></i>Ajustar Estoque
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="produto_id" id="produto_id_ajustar">

                        <div class="mb-3">
                            <label class="form-label">Produto</label>
                            <p class="form-control-static fw-bold" id="produto_nome_ajustar"></p>
                        </div>

                        <div class="mb-3">
                            <label for="quantidade_ajustar" class="form-label">Nova Quantidade*</label>
                            <input type="number" class="form-control" id="quantidade_ajustar" name="quantidade" min="0"
                                max="10000" required>
                            <small class="text-muted">Defina a quantidade exata do estoque</small>
                        </div>

                        <div class="mb-3">
                            <label for="quantidade_minima_ajustar" class="form-label">Estoque Mínimo*</label>
                            <input type="number" class="form-control" id="quantidade_minima_ajustar"
                                name="quantidade_minima" min="1" max="1000" required>
                            <small class="text-muted">Alerta será acionado quando estoque chegar neste valor</small>
                        </div>

                        <div class="mb-3">
                            <label for="observacao_ajustar" class="form-label">Motivo do Ajuste (Opcional)</label>
                            <textarea class="form-control" id="observacao_ajustar" name="observacao" rows="2"
                                placeholder="Ex: Inventário, ajuste de contagem..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Ajustar Estoque</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal Adicionar Estoque
            const adicionarEstoqueModal = document.getElementById('adicionarEstoqueModal');
            adicionarEstoqueModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const produtoId = button.getAttribute('data-produto-id');
                const produtoNome = button.getAttribute('data-produto-nome');
                const estoqueAtual = button.getAttribute('data-estoque-atual');

                document.getElementById('produto_id_adicionar').value = produtoId;
                document.getElementById('produto_nome_adicionar').textContent = produtoNome;
                document.getElementById('estoque_atual_adicionar').textContent = estoqueAtual + ' unidades';
            });

            // Modal Ajustar Estoque
            const ajustarEstoqueModal = document.getElementById('ajustarEstoqueModal');
            ajustarEstoqueModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const produtoId = button.getAttribute('data-produto-id');
                const produtoNome = button.getAttribute('data-produto-nome');
                const estoqueAtual = button.getAttribute('data-estoque-atual');
                const estoqueMinimo = button.getAttribute('data-estoque-minimo');

                document.getElementById('produto_id_ajustar').value = produtoId;
                document.getElementById('produto_nome_ajustar').textContent = produtoNome;
                document.getElementById('quantidade_ajustar').value = estoqueAtual;
                document.getElementById('quantidade_minima_ajustar').value = estoqueMinimo;
            });

            // Validação dos formulários
            document.getElementById('formAdicionarEstoque').addEventListener('submit', function (e) {
                const quantidade = document.getElementById('quantidade_adicionar').value;
                if (quantidade <= 0) {
                    e.preventDefault();
                    alert('A quantidade deve ser maior que zero.');
                }
            });

            document.getElementById('formAjustarEstoque').addEventListener('submit', function (e) {
                const quantidade = document.getElementById('quantidade_ajustar').value;
                const quantidadeMinima = document.getElementById('quantidade_minima_ajustar').value;

                if (quantidade < 0) {
                    e.preventDefault();
                    alert('A quantidade não pode ser negativa.');
                }

                if (quantidadeMinima <= 0) {
                    e.preventDefault();
                    alert('O estoque mínimo deve ser maior que zero.');
                }
            });
        });
    </script>
@endpush