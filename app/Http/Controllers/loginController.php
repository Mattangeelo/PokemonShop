<?php

namespace App\Http\Controllers;

use App\Rules\CpfValido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{
    private $adminModel;
    private $usuarioModel;

    public function __construct()
    {
        $this->adminModel = new \App\Models\adminModel();
        $this->usuarioModel = new \App\Models\usuarioModel();
    }
    public function index()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
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
        if ($admin = $this->adminModel->existeEmail($email)) {

            if (! Hash::check($senha, $admin['senha'])) {
                return redirect()->back()->with('loginError', 'Email ou senha invalidos, por favor tente novamente!');
            }

            session([
                'user' => [
                    'id' => $admin['id'],
                    'email' => $admin['email'],
                    'is_admin' => true,
                ],
            ]);

            return redirect('admin');
        }

        /**
         * Verifica se o email esta cadastrado nos usuarios
         */
        if ($usuario = $this->usuarioModel->buscaEmail($email)) {
            if (! Hash::check($senha, $usuario['senha'])) {
                return redirect()->back()->with('loginError', 'Email ou senha invalidos, por favor tente novamente!');
            }

            session([
                'user' => [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                ],
            ]);

            return redirect('/');
        }

        return redirect()->back()->with('loginError', 'Você ainda não possui um cadastro, por favor cadastre-se!');
    }

    public function logout()
    {
        session()->invalidate(); // destrói a sessão inteira
        session()->regenerateToken(); // segurança (CSRF)

        return redirect()->to('/login')
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
    }

    public function cadastrarSe()
    {
        return view('cadastrarSe');
    }

    public function cadastrarSeSubmit(Request $request)
    {
        $request->validate(
            [
                'nome' => 'required|max:49',
                'cpf' => ['required', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', new CpfValido, 'unique:usuarios,cpf'],
                'email' => 'required|email|unique:usuarios,email',
                'senha' => 'required|min:6|max:16',
                'telefone' => 'required|max:19|unique:usuarios,telefone',
                'cep'         => ['required', 'regex:/^\d{5}-\d{3}$/'],
                'logradouro'  => ['required', 'string', 'max:100'],
                'numero'      => ['nullable', 'string', 'max:10'],
                'complemento' => ['nullable', 'string', 'max:100'],
                'bairro'      => ['required', 'string', 'max:60'],
                'cidade'      => ['required', 'string', 'max:60'],
                'uf'          => ['required', 'regex:/^[A-Z]{2}$/'],
            ],
            [
                'nome.required' => 'O campo nome é Obrigatório.',
                'nome.max' => 'O campo nome deve ter no máximo 49 caracteres.',
                'cpf.required' => 'O campo CPF é Obrigatório.',
                'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00.',
                'cpf.CpfValido' => 'O CPF informado é inválido.',
                'cpf.unique' => 'Esse CPF já possui um cadastro em nosso sistema!',
                'email.required' => 'O campo email é Obrigatório.',
                'email.email' => 'O email inserido deve ser um email válido.',
                'email.unique' => 'Esse email já possui um cadastro em nosso sistema!',
                'senha.required' => 'O campo Senha é Obrigatório.',
                'senha.min' => 'O campo senha deve conter pelo menos 6 caracteres.',
                'senha.max' => 'O campo senha deve no máximo 16 caracteres.',
                'telefone.required' => 'O campo telefone é Obrigatório',
                'telefone.max' => 'O campo telefone deve ter no maximo 19 caracteres.',
                'telefone.unique' => 'Esse telefone já possui um cadastro em nosso sistema!',
                'cep.required'        => 'O campo CEP é Obrigatório.',
                'cep.regex'           => 'O CEP deve estar no formato 00000-000.',
                'logradouro.required' => 'O logradouro é Obrigatório.',
                'logradouro.max'      => 'O logradouro não pode ter mais de 100 caracteres.',
                'numero.max'          => 'O número não pode ter mais de 10 caracteres.',
                'complemento.max'     => 'O complemento não pode ter mais de 100 caracteres.',
                'bairro.required'     => 'O bairro é Obrigatório.',
                'bairro.max'          => 'O bairro não pode ter mais de 60 caracteres.',
                'cidade.required'     => 'A cidade é Obrigatória.',
                'cidade.max'          => 'A cidade não pode ter mais de 60 caracteres.',
                'uf.required'         => 'O estado (UF) é Obrigatório.',
                'uf.size'             => 'O UF deve conter exatamente 2 letras.',

            ]
        );

        $email = $request->input('email');
        $nome = $request->input('nome');
        $cpf = $request->input('cpf');
        $senha = $request->input('senha');
        $telefone = $request->input('telefone');
        $cep = $request->input('cep');
        $numero  = $request->input('numero');
        $complemento = $request->input('complemento');


        $response = file_get_contents("https://viacep.com.br/ws/{$cep}/json/");
        $dataCep = json_decode($response);

        if (isset($data->erro)) {
            return back()->withErrors(['cep' => 'CEP inválido']);
        }

        $data = [
            'nome' => $nome,
            'cpf' => $cpf,
            'email' => $email,
            'senha' => Hash::make($senha),
            'telefone' => $telefone,
            'cep' => $cep,
            'logradouro' => $dataCep->logradouro,
            'numero' => $numero,
            'complemento' => $complemento,
            'bairro' => $dataCep->bairro,
            'cidade' => $dataCep->localidade,
            'uf' => $dataCep->uf,

        ];

        $this->usuarioModel->create($data);

        return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso!');
    }
}
