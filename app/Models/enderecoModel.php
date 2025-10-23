<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class enderecoModel extends Model
{
    protected $table = 'enderecos';
    protected $fillable = ['id_usuario','cep','logradouro','complemento','bairro','localidade','numero'];
}
