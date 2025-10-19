<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\RoleEnum;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RoleEnum::cases() as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role->value],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $role->value,
                    'slug' => Str::slug($role->value)
                ]
            );
        }
    }
}
