<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCarStockDetailBuyBrandThai extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `car_stock_detail_buy` ADD `car_stock_detail_buy_brand_thai` VARCHAR(100) NULL DEFAULT NULL AFTER `car_stock_detail_buy_sale_dow`, ADD `car_stock_detail_buy_car_installments` INT NULL DEFAULT NULL AFTER `car_stock_detail_buy_brand_thai`, ADD `car_stock_detail_buy_car_number_installments` INT NULL DEFAULT NULL AFTER `car_stock_detail_buy_car_installments`");
    }

    public function down()
    {
        //
    }
}
