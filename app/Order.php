<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model {
    protected $table = 'entrega';
    protected $fillable = [
        'idEncomenda', 
        'produto', 
        'endereco', 
        'id_entrega'
    ];

    /**
     * Insert a new Order.
     * 
     * @param array $arrayOrder
     * 
     * @return object
     */
    public function insertOrder($arrayOrder, $idEntrega) {
        DB::beginTransaction();
        try {
            foreach ($arrayOrder as $order) {
                DB::table('entrega')->insert([
                    'idEncomenda' => $order['id'],
                    'produto' => $order['rota'],
                    'endereco' => $order['entregue'],
                    'id_entrega' => $idEntrega
                ]);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    ////////////////////////////////////////////////////////////////

    /**
     * Collect the orders of a specific delivery.
     * 
     * @param $idDelivery
     * 
     * @return object
     */
    public function getOrders($idEntrega) {
        return Order::where(['id_entrega' => $idEntrega])->get()->toArray();
    }

}