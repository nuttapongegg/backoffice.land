<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCarStockDetailBuyStatusUpdateDate extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `car_stock_detail_buy` ADD `car_stock_detail_buy_date_car_rebuild_status`  DATETIME DEFAULT NULL  AFTER `car_stock_detail_buy_mileage`");
        $db->query("ALTER TABLE `car_stock_detail_buy` ADD `car_stock_detail_buy_date_car_doc_status`  DATETIME DEFAULT NULL  AFTER `car_stock_detail_buy_date_car_rebuild_status`");
    }

    public function down()
    {
        //
    }
}
