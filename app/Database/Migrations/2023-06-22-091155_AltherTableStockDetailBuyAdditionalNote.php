<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableStockDetailBuyAdditionalNote extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `car_stock_detail_buy` ADD `car_stock_detail_buy_additional_note` VARCHAR(150) NULL AFTER `car_stock_detail_buy_name`");
    }

    public function down()
    {
        //
    }
}
