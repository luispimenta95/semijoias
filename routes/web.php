<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendasController;

Route::get('/', [VendasController::class, 'realizarVenda']);
Route::get('/pagamento/retorno', [VendasController::class, 'updatePayment'])
    ->name('updatePayment');