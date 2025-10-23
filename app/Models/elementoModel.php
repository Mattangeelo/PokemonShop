<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class elementoModel extends Model
{
    protected $table = 'elementos';
    protected $fillable = ['nome'];

    public function buscaElementos(){
        return $this->select('id','nome')->paginate(10);
    }
    public function buscaTodosElementos(){
        return $this
                ->select('id', 'nome')
                ->get();
    }
    public function buscaElemento($id){
        return $this->where('id',$id)
                    ->first();
    }
}
