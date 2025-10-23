<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class usuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $fillable = ['nome','cpf','email','senha','telefone','id_categoria'];

    public function existeCpf($cpf){
        return $this->where('cpf',$cpf)->exists();
    }

    public function buscaEmail($email){
        return $this->where('email',$email)->first();
    }
}
