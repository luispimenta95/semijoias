<?php

namespace App\Actions\Usuarios;
use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\Cliente;


class CriarUsuarioAction
{
    public function execute(array $data): array
    {
        $user = Cliente::create($data);
        $role = Role::where('slug', RoleEnum::CLIENT->value)->firstOrFail();
        $user->roles()->attach($role->id);

        // Por enquanto apenas simula o retorno:
        return [
            'message' => __('Usuário criado com sucesso.'),
            'data' => $data
        ];
    }
}
