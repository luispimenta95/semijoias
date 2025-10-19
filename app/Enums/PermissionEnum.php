<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case VIEW_PRODUCTS = 'view-products';
    case ATTEMPT_PRODUCT = 'attempt-product';
    case MANAGE_USERS = 'manage-users';

    public function label(): string
    {
        return match ($this) {
            self::VIEW_PRODUCTS => 'Ver Produtos',
            self::ATTEMPT_PRODUCT => 'Realizar Produto',
            self::MANAGE_USERS => 'Gerenciar Usuários',
        };
    }
}
