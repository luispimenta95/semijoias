<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];


  public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Cliente::class, 'role_client');
    }

   
}
