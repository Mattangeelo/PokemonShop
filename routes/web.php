<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\carrinhoController;
use App\Http\Controllers\categoriaController;
use App\Http\Controllers\elementoContorller;
use App\Http\Controllers\elementoController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\produtoController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckIsAdmin;
use App\Http\Middleware\CheckIsLogged;
use App\Http\Middleware\CheckIsNotLogged;
use App\Http\Middleware\CheckIsUser;


Route::middleware([CheckIsNotLogged::class])->group(function () {
    Route::get('/login',[loginController::class,'index'])->name('login');
    Route::post('/loginSubmit',[loginController::class,'loginSubmit'])->name('loginSubmit');
    Route::get('/cadastrarSe',[loginController::class,'cadastrarSe'])->name('cadastrarSe');
    Route::post('/cadastrarSeSubmit',[loginController::class,'cadastrarSeSubmit'])->name('cadastrarSeSubmit');
});

Route::middleware([CheckIsLogged::class])->group(function () {

    /**
     * Proteção de rotas para admin
     */
    Route::middleware([CheckIsAdmin::class])->group(function () {
        /**
        * Rotas Admin
        */
        Route::get('/admin',[adminController::class,'index'])->name('admin');

        /**
         * Rotas Produtos
         */
        Route::get('/showProdutos',[produtoController::class,'index'])->name('showProdutos');
        Route::get('/filtroProdutos',[produtoController::class,'filtro'])->name('filtroProdutos');
        Route::post('/cadastrarProdutos',[produtoController::class,'cadastrar'])->name('cadastrarProdutos');
        Route::get('/showEditarProduto/{id}',[produtoController::class,'showEditar'])->name('showEditarProduto');
        Route::post('/editarProduto/{id}',[produtoController::class,'editar'])->name('editarProduto');
        Route::post('/excluirProduto/{id}',[produtoController::class,'excluir'])->name('excluirProduto');

        /**
         * Rotas Categorias
         */
        Route::get('/filtroCategorias',[categoriaController::class,'filtro'])->name('filtroCategorias');
        Route::get('/showCategorias',[categoriaController::class,'index'])->name('showCategorias');
        Route::post('/cadastrarCategoria',[categoriaController::class,'cadastrar'])->name('cadastrarCategoria');
        Route::post('/editarCategoria/{id}',[categoriaController::class,'editar'])->name('editarCategoria');
        Route::post('/excluirCategoria/{id}',[categoriaController::class,'excluir'])->name('excluirCategoria');

        /**
         * Rotas Elementos
         */
        Route::get('/showElementos',[elementoController::class,'index'])->name('showElementos');
        Route::get('/filtroElementos',[elementoController::class,'filtro'])->name('filtroElementos');
        Route::post('/cadastrarElemento',[elementoController::class,'cadastrar'])->name('cadastrarElemento');
        Route::post('/editarElemento/{id}',[elementoController::class,'editar'])->name('editarElemento');
        Route::post('/excluirElemento/{id}',[elementoController::class,'excluir'])->name('excluirElemento');



    });

     Route::middleware([CheckIsUser::class])->group(function () {
        Route::get('/showComprar' , [carrinhoController::class,'index'])->name('showComprar');
    });

    Route::get('/logout', [loginController::class, 'logout'])->name('logout');
});


Route::get('/',[homeController::class,'index'])->name('/');
Route::get('/fitlro',[homeController::class,'filtro'])->name('filtro');



