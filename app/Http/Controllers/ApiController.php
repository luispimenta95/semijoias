<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use LSNepomuceno\LaravelBrazilianCeps\Services\CepService;

class ApiController extends Controller
{
    private CepService $cepService;

    public function __construct(CepService $cepService)
    {
        $this->cepService = $cepService;
    }

    public function getCep(string | int $cep): JsonResponse
    {
        $endereco = $this->cepService->get($cep);
        return response()->json($endereco);
    }
}