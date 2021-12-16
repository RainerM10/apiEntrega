<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Delivery extends Model {
    protected $table = 'entrega';
    protected $fillable = [
        'idEntrega', 
        'rota', 
        'status', 
        'placa',
        'dataLimite'
    ];
    public $timestamps = true;

    /**
     * Collects the delivery by id.
     * 
     * @param integer @idEntrega
     * 
     * @return object
     */
    public function getDeliveryById($idEntrega) {
        return Delivery::where(['idEntrega' => $idEntrega])
            ->get()->toArray();
    }

    ////////////////////////////////////////////////////////////////

    /**
     * Collect the next delivery not delivered.
     * 
     * 
     * @return object
     */
    public function getNextDelivery() {
        return Delivery::where(['entrega' => false])
            ->orderBy('dataLimite', 'DESC')->first()->toArray();
    }

    ////////////////////////////////////////////////////////////////

    /**
     * Insert a new Delivery.
     * 
     * @param array $arrayDelivery
     * 
     * @return object
     */
    public function insertDelivery($arrayDelivery) {
        DB::beginTransaction();
        try {
            DB::table('entrega')->insert([
                'idEntrega' => $arrayDelivery['id'],
                'rota' => $arrayDelivery['rota'],
                'status' => $arrayDelivery['entregue'],
                'placa' => $arrayDelivery['placa'],
                'dataLimite' => $arrayDelivery['data-limite']
            ]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

}