<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableStockOwnerDetail extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "ALTER TABLE `car_stock_owner` ADD `car_stock_owner_car_vin_old` varchar(45)  NULL COMMENT 'ทะเบียนเก่า' AFTER `car_stock_owner_car_vin`";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
