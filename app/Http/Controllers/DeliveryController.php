<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Validation\RegisterValidation;
use App\Delivery;
use App\Order;
use Exception;

class DeliveryController extends Controller {
    /**
     * @var RegisterValidation
     */
    protected $registerValidation;

    /**
     * Create a new controller instance.
     *
     * @param RegisterValidation $registerValidation
     * 
     * @return void
     */
    public function __construct(RegisterValidation $registerValidation) {
        $this->registerValidation = $registerValidation;
    }

    ////////////////////////////////////////////////////////////////
    ///////////////////////////// CRUD /////////////////////////////
    ////////////////////////////////////////////////////////////////

    /**
     * 
     */
    public function register(Request $request, Delivery $delivery, Order $order) {
        try {
            // We will validate the request parameters.
            if ($this->registerValidation->validateData($request)) {
                // Now verify the order parameters.
                if ($this->registerValidation->validateOrderData($request->encomendas)) {
                    // Verify if this deliveryId is unique in the database.
                    $existDelivery = $delivery->getDeliveryById($request->id);
                    if ($existDelivery != null) {
                        return response()->json(['message' => 'Algum(ns) do(s) parâmetros enviados não seguem o padrão exigido ou estão faltando.'], 403);
                    } else {
                        if ($delivery->insertDelivery($request)) {
                            if ($order->insertOrder($request->encomendas, $request->id)) {
                                return response()->json(['message' => 'O pedido e a(s) encomenda(s) teve sucesso no cadastro.'], 200);
                            } else {
                                return response()->json(['message' => 'Ocorreu um erro inesperado ao cadastrar as encomedas.'], 500);
                            }
                        } else {
                            return response()->json(['message' => 'Ocorreu um erro inesperado ao cadastrar a entrega.'], 500);
                        }
                    }
                }
            }
            return response()->json(['message' => 'Algum(ns) do(s) parâmetros enviados não seguem o padrão exigido ou estão faltando.'], 400);
        } catch (Exception $e) {
            return response()->json(['message' => 'Ocorreu um erro inesperado ao realizar a operação.'], 500);
        }
    }

    ////////////////////////////////////////////////////////////////

    /**
     * 
     */
    public function getDelivery(Request $request, Delivery $delivery, Order $order) {
        try {
            // Collect the next delivery in DESC order.
            $arrayDelivery = $delivery->getNextDelivery();
            if ($arrayDelivery == null) {
                return response()->json('', 200);
            } else {
                // Collect the order of this delivery.
                $arrayOrder = $order->getOrders($arrayDelivery['id']);
                $response = $arrayDelivery;
                $response['encomendas'] = $arrayOrder;
                return response()->json($response, 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Ocorreu um erro inesperado ao realizar a operação.'], 500);
        }
    }
}