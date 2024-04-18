<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTabledetailbuy extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        // $db->query("ALTER TABLE `car_stock_detail_buy` ADD `car_stock_detail_buy_car_document_status` VARCHAR(150)  NULL AFTER `car_stock_detail_buy_car_build_status`");
        // $db->query("ALTER TABLE `car_stock_detail_buy` ADD `car_stock_detail_buy_exprired_tax_date` VARCHAR(10)  NULL AFTER `car_stock_detail_buy_bid_date`");
    }

    public function down()
    {
        //
    }
}
