<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class produtoController extends Controller
{
    public function index(){
        return view("Admin.showProdutos");
    }
}
