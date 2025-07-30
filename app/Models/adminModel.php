<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class adminModel extends Model
{
    protected $table = 'admins';
    protected $fillable = ['email','senha','ativo'];

    public function existeEmail($email){
        return $this->where('email', $email)->first();
    }
}
