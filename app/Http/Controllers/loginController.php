<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{
    private $adminModel;

    public function __construct(){
        $this->adminModel = new \App\Models\adminModel();
    }
    public function index(){
        return view('login');
    }

    public function loginSubmit(Request $request){
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:6|max:16'
            ],
            [
                'email.required' => 'O campo email deve ser preenchido.',
                'email.email' => 'O email inserido deve ser um email válido.',
                'password.required' => 'O campo senha deve ser preenchido.',
                'password.min' => 'O campo senha deve conter no mínimo 6 caracteres.',
                'password.max' => 'O campo senha deve conter no máximo 16 caracteres.'
            ]
        );

        $email = $request->input('email');
        $senha = $request->input('password');

        /**
         * Verifica se o email está cadastrado no admin
         * Tenta criar uma variavel que vai receber o dados do admin
         * Se conseguir ser criado, verifica a senha com o que está cadastrado
         * Caso contrario, retorna com erro.
         */
        if($admin = $this->adminModel->existeEmail($email)){

            if(! Hash::check($senha, $admin['senha'])){
                return redirect()->back()->with('loginError','Email ou senha invalidos, por favor tente novamente!');
            }

            session([
                'user'=> [
                    'id'=> $admin['id'],
                    'email' => $admin['email'],
                    'is_admin' => true,
                ],
            ]);

            return redirect('admin');
        }else{
            return redirect()->back()->with('loginError','Email ou senha invalidos, por favor tente novamente!');
        }

        /**
         * Verifica se o email esta cadastrado nos usuarios
         */

        

    }

    public function logout(){
         session()->forget('user');
        return response()
            ->redirectTo('/login')  
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    public function cadastrarSe(){
        return view('cadastrarSe');
    }

    public function cadastrarSeSubmit(Request $request){
        dd($request);
    }
}
