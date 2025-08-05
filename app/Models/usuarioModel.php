<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class usuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $fillable = ['nome','cpf','email','senha','telefone','cep','logradouro','numero','complemento','bairro','cidade','uf'];

    public function existeEmail($email){
        return $this->where('email', $email)->where('deleted_at',NULL)->exists();
    }
    public function existeCpf($cpf){
        return $this->where('cpf',$cpf)->exists();
    }

    public function buscaEmail($email){
        return $this->where('email',$email)->where('deleted_at',NULL)->first();
    }
}
