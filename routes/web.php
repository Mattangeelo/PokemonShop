<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
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
        Route::get('admin',[adminController::class,'index'])->name('admin');
    });

     Route::middleware([CheckIsUser::class])->group(function () {
        
    });

    Route::get('/logout', [loginController::class, 'logout'])->name('logout');
});


Route::get('/',[homeController::class,'index'])->name('/');


