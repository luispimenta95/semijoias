<?php

namespace App\Http\Controllers;

use App\Actions\Usuarios\CriarUsuarioAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Usuarios\CriarUsuarioRequest;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Symfony\Component\HttpFoundation\Response;



class UsuarioController extends Controller
{
    public function __construct(
        private readonly CriarUsuarioRequest $criarUsuarioRequest,
        private readonly CriarUsuarioAction $criarUsuarioAction
    )
    {}
    public function store(CriarUsuarioRequest $request){
        // Validação básica
        $user = $this->criarUsuarioAction->execute($request->validated());
        return response()->json($user, Response::HTTP_CREATED);

    }
}