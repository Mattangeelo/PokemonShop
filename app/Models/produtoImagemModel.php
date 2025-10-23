<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produtoImagemModel extends Model
{
    use HasFactory;

    protected $table = 'produto_imagens';

    protected $primaryKey = 'id';


    protected $fillable = [
        'id_produto',
        'caminho_imagem',
        'nome_arquivo',
        'ordem',
        'principal',
        'legenda'
    ];

    // Campos que devem ser convertidos para tipos nativos
    protected $casts = [
        'principal' => 'boolean',
        'ordem' => 'integer'
    ];

    // Relacionamento com o produto
    public function produto()
    {
        return $this->belongsTo(produtoModel::class, 'id_produto');
    }
    

    /**
     * Busca todas as imagens de um produto
     */
    public function buscaImagensPorProduto($idProduto)
    {
        return $this->where('id_produto', $idProduto)
                    ->orderBy('ordem')
                    ->get();
    }

    /**
     * Busca a imagem principal de um produto
     */
    public function buscaImagemPrincipal($idProduto)
    {
        return $this->where('id_produto', $idProduto)
                    ->where('principal', true)
                    ->first();
    }

    /**
     * Busca imagens adicionais (não principais) de um produto
     */
    public function buscaImagensAdicionais($idProduto)
    {
        return $this->where('id_produto', $idProduto)
                    ->where('principal', false)
                    ->orderBy('ordem')
                    ->get();
    }

    /**
     * Verifica se o caminho da imagem existe
     */
    public function getCaminhoCompletoAttribute()
    {
        return storage_path('app/public/' . $this->caminho_imagem);
    }

    /**
     * URL pública da imagem
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->caminho_imagem);
    }
}