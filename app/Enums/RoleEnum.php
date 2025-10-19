<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case FINANCIAL = 'financial';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::CLIENT => 'Cliente',
            self::FINANCIAL => 'Financeiro',
        };
    }
}
