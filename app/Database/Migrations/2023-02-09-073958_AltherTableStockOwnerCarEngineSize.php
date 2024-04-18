<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableStockOwnerEngineSize extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `car_stock_owner` ADD `car_stock_owner_car_engine_size` INT NOT NULL AFTER `car_stock_owner_car_experience`");
    }

    public function down()
    {
        //
    }
}
