<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Facades\Log;

// Inicializar chave do Mercado Pago

use App\Http\Util\Payments\ApiMercadoPago;

class VendasController extends Controller
{
    //update server
    private $apiMercadoPago;
    private array $codes = [];


    public function __construct()
    {
        $this->apiMercadoPago = new ApiMercadoPago();
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));

    }

public function realizarVenda(): JsonResponse
{
    try {
        // Exemplo de plano
        $plano = (object) [
            'id' => 1,
            'nome' => 'Plano Mensal',
            'descricao' => 'Acesso completo por 30 dias',
            'valor' => 30.00
        ];

        $data = [
            'id' => $plano->id,
            'title' => $plano->nome,
            'description' => $plano->descricao,
            'price' => $plano->valor
        ];

        // Chama o serviço que cria a preferência de pagamento
        $venda = $this->apiMercadoPago->salvarVenda($data);

        // Se a API retornar erro
        if (isset($venda['Erro'])) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao criar a venda no Mercado Pago',
                'details' => $venda
            ], 500);
        }

        // Retorna sucesso com dados úteis para o front-end
        return response()->json([
            'success' => true,
            'message' => 'Preferência criada com sucesso.',
            'checkout_link' => $venda['link'],
            'preference_id' => $venda['idPedido']
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro inesperado ao processar a venda.',
            'details' => $e->getMessage()
        ], 500);
    }
}


    public function recuperarVenda(int $idPagamento): array
    {

        return $this->apiMercadoPago->getPaymentById((int) $idPagamento);
    }

    public function listarVendas()
    {

        $response = $this->apiMercadoPago->getPayments();
        echo json_encode($response);
    }

public function updatePayment(Request $request)
{
    \Log::info('Card response', $data); // $data = array ou json que você vai devolver
return response()->json($data);
}
}