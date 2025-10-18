<?php
namespace App\Http\Util\Payments;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Net\MPSearchRequest;
use MercadoPago\Client\Payment\PaymentClient;
use Carbon\Carbon;
use MercadoPago\Preapproval;
use App\Http\Util\Helper;
use App\Models\Usuarios;

class ApiMercadoPago
{
    private $_client;
    private $_options;
    private $payer;

    public function __construct()
    {
        $this->_client = new PreferenceClient();
        $this->_options = new RequestOptions();
        $this->payer = new PaymentClient();

        if (!env('MP_ACCESS_TOKEN')) {
            throw new \Exception('MP_ACCESS_TOKEN não encontrado no .env');
        }
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
    }

    public function salvarVenda(array $data): mixed
{
    $this->_options->setCustomHeaders(["X-Idempotency-Key: " . uniqid()]);

    $successUrl = env('APP_URL') . '/pagamento/retorno';

    $createRequest = [
        "external_reference" => 3,
        "items" => [
            [
                "id" => $data['id'],
                "title" => $data['title'],
                "description" => $data['description'],
                "picture_url" => "http://www.myapp.com/myimage.jpg",
                "category_id" => "SERVICES",
                "quantity" => 1,
                "currency_id" => Helper::MOEDA ?? 'BRL',
                "unit_price" => (float) $data['price'],
            ]
        ],
        "back_urls" => [
            "success" => $successUrl,
            "failure" => $successUrl,
            "pending" => $successUrl
        ],
        "auto_return" => "all", // 🔹 todos os status
    ];

    try {
        $preference = $this->_client->create($createRequest, $this->_options);

        return [
            'link' => $preference->init_point,
            'idPedido' => $preference->id
        ];
    } catch (MPApiException $e) {
        $response = $e->getApiResponse();
        $status = $e->getStatusCode();

        $rawContent = $response ? $response->getContent() : null;
        $decodedContent = is_string($rawContent) ? json_decode($rawContent, true) : $rawContent;

        return [
            'Erro' => $e->getMessage(),
            'Status HTTP' => $status,
            'Detalhes' => $decodedContent ?? 'Nenhum detalhe retornado',
        ];
    }
}

    public function getPayments()
    {
        $searchRequest = new MPSearchRequest(30, 0, [
            "sort" => "date_created",
            "criteria" => "desc"
        ]);

        return $this->payer->search($searchRequest);
    }

    public function getPaymentById(int $idPagamento): array
    {
        try {

            $payment = $this->payer->get($idPagamento);

            if (!$payment) {
                return [
                    "Erro" => "Pagamento não encontrado.",
                    "id" => $idPagamento
                ];
            }

            return [
                'status' => $payment->status,
                'detalhe_status' => $payment->status_detail,
                'payment_method' => $payment->payment_method_id,
                'id' => $payment->id,
                'valorFinal' => $payment->transaction_details->total_paid_amount,
                'dataPagamento' => Carbon::parse($payment->date_approved)->format('Y-m-d H:i:s')
            ];
        } catch (MPApiException $e) {
            $response = $e->getApiResponse();
            $statusCode = $e->getStatusCode();

            return [
                "Erro" => "Api error. Check response for details.",
                "Detalhes" => $response ? $response->getContent() : "Nenhuma informação detalhada disponível",
                "Codigo HTTP" => $statusCode
            ];
        }
    }

   
}
