<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class produtoController extends Controller
{
    private $produtoModel;
    private $categoriaModel;
    private $elementoModel;
    private $estoqueModel;
    private $produtoImagemModel;

    public function __construct()
    {
        $this->categoriaModel = new \App\Models\categoriaModel();
        $this->elementoModel = new \App\Models\elementoModel();
        $this->produtoModel = new \App\Models\produtoModel();
        $this->estoqueModel = new \App\Models\estoqueModel();
        $this->produtoImagemModel = new \App\Models\produtoImagemModel();
    }

    public function index()
    {
        $categorias = $this->categoriaModel->buscaTodasCategorias();
        $elementos = $this->elementoModel->buscaTodosElementos();
        $produtos = $this->produtoModel->buscaProdutos();
        return view("Admin.showProdutos", compact('elementos', 'categorias', 'produtos'));
    }

    public function filtro(Request $request)
    {
        $busca = $request->input('busca');
        $tipo = $request->input('tipo');
        $elemento = $request->input('elemento');
        $ordenacao = $request->input('ordenacao');
        $porPagina = $request->input('por_pagina', 10);

        $query = $this->produtoModel->query();

        if (!empty($busca)) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'LIKE', "%{$busca}%")
                    ->orWhere('descricao', 'LIKE', "%{$busca}%");
            });
        }

        if (!empty($tipo) && $tipo != 'Todos os tipos') {
            $query->whereHas('categoria', function ($q) use ($tipo) {
                $q->where('nome', $tipo);
            });
        }

        if (!empty($elemento) && $elemento != 'Todos os elementos') {
            $query->whereHas('elemento', function ($q) use ($elemento) {
                $q->where('nome', $elemento);
            });
        }

        switch ($ordenacao) {
            case 'Nome (A-Z)':
                $query->orderBy('nome', 'asc');
                break;
            case 'Nome (Z-A)':
                $query->orderBy('nome', 'desc');
                break;
            case 'Preço (Maior)':
                $query->orderBy('preco', 'desc');
                break;
            case 'Preço (Menor)':
                $query->orderBy('preco', 'asc');
                break;
            case 'Mais vendidos':
                $query->orderBy('quantidade_vendida', 'desc');
                break;
            default:
                $query->orderBy('nome', 'asc');
        }

        $produtos = $query->with(['categoria', 'elemento'])->paginate($porPagina);
        $categorias = $this->categoriaModel->buscaTodasCategorias();
        $elementos = $this->elementoModel->buscaTodosElementos();

        return view("Admin.showProdutos", compact('produtos', 'categorias', 'elementos'));
    }

    public function cadastrar(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:produtos,nome',
            'descricao' => 'required|string|max:500|min:10',
            'numeracao' => 'required|string|max:50|min:2',
            'categoria_id' => 'required|exists:categorias,id',
            'elemento_id' => 'required|exists:elementos,id',
            'preco' => 'required|min:0.01|max:999999.99',
            'quantidade' => 'required|integer|min:0|max:9999',
            'imagem_principal' => 'required|image|mimes:jpeg,png,jpg,gif|max:5000',
            'imagens_adicionais.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ], [
            'nome.required' => 'O nome do produto é obrigatório.',
            'nome.max' => 'O nome não pode ter mais que 100 caracteres.',
            'nome.unique' => 'Já existe um produto com este nome.',

            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'descricao.max' => 'A descrição não pode ter mais que 500 caracteres.',

            'numeracao.required' => 'A numeração/modelo é obrigatória.',
            'numeracao.min' => 'A numeração deve ter pelo menos 2 caracteres.',
            'numeracao.max' => 'A numeração não pode ter mais que 50 caracteres.',

            'categoria_id.required' => 'A categoria é obrigatória.',
            'categoria_id.exists' => 'A categoria selecionada é inválida.',

            'elemento_id.required' => 'O elemento Pokémon é obrigatório.',
            'elemento_id.exists' => 'O elemento Pokémon selecionado é inválido.',

            'preco.required' => 'O preço é obrigatório.',
            'preco.min' => 'O preço mínimo é R$ 0,01.',
            'preco.max' => 'O preço máximo é R$ 999.999,99.',

            'quantidade.required' => 'A quantidade em estoque é obrigatória.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade não pode ser negativa.',
            'quantidade.max' => 'A quantidade máxima é 9999.',

            'imagem_principal.required' => 'A imagem principal do produto é obrigatória.',
            'imagem_principal.image' => 'O arquivo deve ser uma imagem.',
            'imagem_principal.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif.',
            'imagem_principal.max' => 'A imagem não pode ter mais que 5MB.',

            'imagens_adicionais.*.image' => 'Cada arquivo adicional deve ser uma imagem.',
            'imagens_adicionais.*.mimes' => 'As imagens adicionais devem ser do tipo: jpeg, png, jpg ou gif.',
            'imagens_adicionais.*.max' => 'Cada imagem adicional não pode ter mais que 5MB.',
        ]);

        try {
            DB::beginTransaction();

            // Preparar dados do produto
            $dadosProduto = $request->only([
                'nome',
                'descricao',
                'numeracao',
                'categoria_id',
                'elemento_id',
            ]);

            // Formatar preço
            $precoFormatado = str_replace(['R$', ' ', '.'], '', $request->input('preco'));
            $precoFormatado = str_replace(',', '.', $precoFormatado);
            $dadosProduto['preco'] = (float) $precoFormatado;

            // Processar imagem principal ANTES de criar o produto
            $nomeImagemPrincipal = null;
            if ($request->hasFile('imagem_principal')) {
                $nomeImagemPrincipal = $this->salvarImagemPrincipal($request->file('imagem_principal'));
                $dadosProduto['imagem_principal'] = $nomeImagemPrincipal;
            }

            // Criar o produto COM a imagem principal
            $produto = $this->produtoModel->create($dadosProduto);

            // Processar imagens adicionais (vão para produto_imagens)
            if ($request->hasFile('imagens_adicionais')) {
                $ordem = 1; // Ordem começa em 1 para as imagens adicionais

                foreach ($request->file('imagens_adicionais') as $imagem) {
                    if ($imagem && $imagem->isValid()) {
                        $this->salvarImagemAdicional($imagem, $produto->id, $ordem);
                        $ordem++;

                        // Limitar a 3 imagens adicionais
                        if ($ordem > 3) {
                            break;
                        }
                    }
                }
            }

            // Criar registro de estoque
            $this->estoqueModel->create([
                'id_produto' => $produto->id,
                'quantidade' => $request->quantidade,
                'quantidade_minima' => 10
            ]);

            DB::commit();

            return redirect()
                ->route('showProdutos')
                ->with('success', 'Produto cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar produto: ' . $e->getMessage());
        }
    }

    /**
     * Função para salvar a IMAGEM PRINCIPAL
     */
    private function salvarImagemPrincipal($arquivo)
    {
        $nomeArquivo = time() . '_principal_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();

        try {
            $path = Storage::disk('public')->putFileAs(
                'produtos',
                $arquivo,
                $nomeArquivo
            );

            if (!$path) {
                throw new \Exception("Falha ao salvar a imagem principal");
            }

            return $nomeArquivo;
        } catch (\Exception $e) {
            // Fallback se o Storage falhar
            $destinationPath = storage_path('app/public/produtos');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $arquivo->move($destinationPath, $nomeArquivo);
            return $nomeArquivo;
        }
    }

    /**
     * Função para salvar IMAGENS ADICIONAIS 
     */
    private function salvarImagemAdicional($arquivo, $produtoId, $ordem = 1)
    {
        $nomeArquivo = time() . '_adicional_' . $ordem . '_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();

        try {
            $path = Storage::disk('public')->putFileAs(
                'produtos',
                $arquivo,
                $nomeArquivo
            );

            if (!$path) {
                throw new \Exception("Falha ao salvar a imagem adicional");
            }

            // Salvar na tabela produto_imagens
            $this->produtoImagemModel->create([
                'id_produto' => $produtoId,
                'caminho_imagem' => 'produtos/' . $nomeArquivo,
                'nome_arquivo' => $nomeArquivo,
                'ordem' => $ordem,
                'principal' => false, // Sempre false para imagens adicionais
                'legenda' => null
            ]);
        } catch (\Exception $e) {
            // Fallback se o Storage falhar
            $destinationPath = storage_path('app/public/produtos');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $arquivo->move($destinationPath, $nomeArquivo);

            $this->produtoImagemModel->create([
                'id_produto' => $produtoId,
                'caminho_imagem' => 'produtos/' . $nomeArquivo,
                'nome_arquivo' => $nomeArquivo,
                'ordem' => $ordem,
                'principal' => false,
                'legenda' => null
            ]);
        }
    }

    public function showEditar($idCriptogradado)
    {
        $id = Crypt::decrypt($idCriptogradado);
        $categorias = $this->categoriaModel->buscaTodasCategorias();
        $elementos = $this->elementoModel->buscaTodosElementos();


        $produto = $this->produtoModel->with(['estoque', 'imagens'])->find($id);

        if (!$produto) {
            return redirect()->route('showProdutos')
                ->with('error', 'Produto não encontrado');
        }

        return view("Admin.showEditarProduto", compact('elementos', 'categorias', 'produto'));
    }

    public function editar(Request $request, $idCriptogradado)
    {
        if (!$id = Crypt::decrypt($idCriptogradado)) {
            return redirect()->route('showProdutos')
                ->with('error', 'Produto não encontrado');
        }

        $request->validate([
            'nome' => 'required|string|max:100|unique:produtos,nome,' . $id,
            'descricao' => 'required|string|max:500|min:10',
            'numeracao' => 'required|string|max:50|min:2',
            'categoria_id' => 'required|exists:categorias,id',
            'elemento_id' => 'required|exists:elementos,id',
            'preco' => 'required|min:0.01|max:999999.99',
            'quantidade' => 'required|integer|min:0|max:9999',
            'imagem_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'imagens_adicionais.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'imagens_remover' => 'nullable|array',
            'imagens_remover.*' => 'integer',
        ], [
            'nome.required' => 'O nome do produto é obrigatório.',
            'nome.max' => 'O nome não pode ter mais que 100 caracteres.',
            'nome.unique' => 'Já existe um produto com este nome.',

            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'descricao.max' => 'A descrição não pode ter mais que 500 caracteres.',

            'numeracao.required' => 'A numeração/modelo é obrigatória.',
            'numeracao.min' => 'A numeração deve ter pelo menos 2 caracteres.',
            'numeracao.max' => 'A numeração não pode ter mais que 50 caracteres.',

            'categoria_id.required' => 'A categoria é obrigatória.',
            'categoria_id.exists' => 'A categoria selecionada é inválida.',

            'elemento_id.required' => 'O elemento Pokémon é obrigatório.',
            'elemento_id.exists' => 'O elemento Pokémon selecionado é inválido.',

            'preco.required' => 'O preço é obrigatório.',
            'preco.min' => 'O preço mínimo é R$ 0,01.',
            'preco.max' => 'O preço máximo é R$ 999.999,99.',

            'quantidade.required' => 'A quantidade em estoque é obrigatória.',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'quantidade.min' => 'A quantidade não pode ser negativa.',
            'quantidade.max' => 'A quantidade máxima é 9999.',

            'imagem_principal.image' => 'O arquivo deve ser uma imagem.',
            'imagem_principal.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif.',
            'imagem_principal.max' => 'A imagem não pode ter mais que 5MB.',

            'imagens_adicionais.*.image' => 'Cada arquivo adicional deve ser uma imagem.',
            'imagens_adicionais.*.mimes' => 'As imagens adicionais devem ser do tipo: jpeg, png, jpg ou gif.',
            'imagens_adicionais.*.max' => 'Cada imagem adicional não pode ter mais que 5MB.',
        ]);

        try {
            DB::beginTransaction();

            if (!$produto = $this->produtoModel->find($id)) {
                return redirect()->route('showProdutos')
                    ->with('error', 'Produto não encontrado');
            }


            $produto->nome = $request->nome;
            $produto->descricao = $request->descricao;
            $produto->numeracao = $request->numeracao;
            $produto->categoria_id = $request->categoria_id;
            $produto->elemento_id = $request->elemento_id;


            $precoFormatado = str_replace(['R$', ' ', '.'], '', $request->input('preco'));
            $precoFormatado = str_replace(',', '.', $precoFormatado);
            $produto->preco = (float) $precoFormatado;


            if ($request->hasFile('imagem_principal')) {

                if ($produto->imagem_principal && Storage::disk('public')->exists('produtos/' . $produto->imagem_principal)) {
                    Storage::disk('public')->delete('produtos/' . $produto->imagem_principal);
                }


                $nomeImagemPrincipal = $this->salvarImagemPrincipal($request->file('imagem_principal'));
                $produto->imagem_principal = $nomeImagemPrincipal;
            }


            $produto->save();


            $this->gerenciarImagensAdicionais($request, $produto->id);


            $estoque = $this->estoqueModel->where('id_produto', $produto->id)->first();
            if ($estoque) {
                $estoque->quantidade = $request->quantidade;
                $estoque->save();
            } else {

                $this->estoqueModel->create([
                    'id_produto' => $produto->id,
                    'quantidade' => $request->quantidade,
                    'quantidade_minima' => 10
                ]);
            }

            DB::commit();

            return redirect()->route('showProdutos')
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }


    private function gerenciarImagensAdicionais(Request $request, $produtoId)
    {
        try {
            
            $imagensExistentesCount = $this->produtoImagemModel->where('id_produto', $produtoId)->count();

            
            $imagensRemovidas = 0;
            if ($request->has('imagens_remover')) {
                foreach ($request->imagens_remover as $imagemId) {
                    $imagem = $this->produtoImagemModel->find($imagemId);
                    if ($imagem && $imagem->id_produto == $produtoId) { 
                        
                        if (Storage::disk('public')->exists($imagem->caminho_imagem)) {
                            Storage::disk('public')->delete($imagem->caminho_imagem);
                        }
                        
                        $imagem->delete();
                        $imagensRemovidas++;
                    }
                }
            }

            
            $imagensExistentesCount -= $imagensRemovidas;

            
            if ($request->hasFile('imagens_adicionais')) {
                
                $vagasDisponiveis = 3 - $imagensExistentesCount;

                if ($vagasDisponiveis > 0) {
                    
                    $ultimaOrdem = $this->produtoImagemModel->where('id_produto', $produtoId)->max('ordem') ?? 0;
                    $ordem = $ultimaOrdem + 1;

                    foreach ($request->file('imagens_adicionais') as $imagem) {
                        
                        if ($vagasDisponiveis <= 0) {
                            break;
                        }

                        if ($imagem && $imagem->isValid()) {
                            $this->salvarImagemAdicional($imagem, $produtoId, $ordem);
                            $ordem++;
                            $vagasDisponiveis--;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            throw new \Exception("Erro ao gerenciar imagens adicionais: " . $e->getMessage());
        }
    }

    public function excluir($idCriptogradado)
    {
        try {
            $id = Crypt::decrypt($idCriptogradado);
            $produto = $this->produtoModel->buscaProduto($id);
            $produto->delete();
            return redirect()
                ->route('showProdutos')
                ->with('success', 'Produto excluido com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->route('showProdutos')
                ->with('error', 'Produto não encontrado.');
        }
    }
}
