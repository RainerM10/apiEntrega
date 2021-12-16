<?php

namespace App\Validation;

class RegisterValidation extends BaseValidation {
    /**
     * Validate the data.
     * 
     * @param Request $request
     * 
     * @return boolean
     */
    public function validateData($request = null) {
        if ($request != null) {
            return $this->baseValidation($request->all(), [
                'id' => 'required|integer',
                'rota' => 'required|string',
                'entregue' => 'required|boolean',
                'placa-veiculo' => 'required|string',
                'data-limite' => 'required|date_format:d-m-Y',
                'encomendas' => 'required'
            ]);
        }
        return false;
    }

    ////////////////////////////////////////////////////////////////

    /**
     * Validate the order data.
     * 
     * @param Request $request
     * 
     * @return boolean
     */
    public function validateOrderData($order = null) {
        if ($order != null) {
            $sizeOrder = count($order);
            if ($sizeOrder > 0) {
                foreach ($order as $specificOrder) {
                    if (!isset($specificOrder['id']) || !isset($specificOrder['produto']) || !isset($specificOrder['endereco'])) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }
}