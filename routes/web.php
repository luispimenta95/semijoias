<?php
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VendasController;

use Illuminate\Support\Facades\Route;

Route::get('/', [VendasController::class, 'realizarVenda']);
Route::get('/pagamento/retorno', [VendasController::class, 'updatePayment'])
    ->name('updatePayment');

// Endpoint to show the address form
Route::get('/cadastro', function () {
    return view('endereco');
})->name('usuarios.endereco');

// API route for CEP lookup
Route::get('/api/cep/{cep}', [ApiController::class, 'getCep']);
Route::post('/usuarios/endereco', [UsuarioController::class, 'store'])->name('usuarios.endereco.store');