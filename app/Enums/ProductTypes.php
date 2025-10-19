<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum ProductTypes: string
{
    case POLICIAL = 'Policial';
    case TRIBUNAL = 'Tribunal';

    public static function values(): array
    {
        return collect(self::cases())->map(fn ($enum) => Str::slug($enum->value))->toArray();
    }

    public static function array(): array
    {
        return collect(self::cases())->map(fn ($enum) => [
            'label' => $enum->value,
            'value' => Str::slug($enum->value),
        ])->toArray();
    }

    public static function getLabel(string $value): string
    {
        return collect(self::cases())->first(fn ($enum) => Str::slug($enum->value) === $value)->value;
    }
}
