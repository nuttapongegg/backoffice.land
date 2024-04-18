<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableStockOwnerCarEngineSize extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `car_stock_owner` CHANGE `car_stock_owner_car_engine_size` `car_stock_owner_car_engine_size` VARCHAR(100) NULL DEFAULT NULL");
        $db->query("ALTER TABLE `car_stock_owner` CHANGE `car_stock_owner_car_weight` `car_stock_owner_car_weight` VARCHAR(100) NULL DEFAULT NULL");
    }

    public function down()
    {
        //
    }
}
