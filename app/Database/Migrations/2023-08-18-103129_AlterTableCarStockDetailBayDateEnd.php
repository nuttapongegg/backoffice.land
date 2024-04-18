<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCarStockDetailBayDateEnd extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `car_stock_detail_buy` ADD `car_stock_detail_buy_exprired_act_date` VARCHAR(20) NULL DEFAULT NULL AFTER `car_stock_detail_buy_exprired_tax_date`, ADD `car_stock_detail_buy_exprired_insurance_date` VARCHAR(20) NULL DEFAULT NULL AFTER `car_stock_detail_buy_exprired_act_date`, ADD `car_stock_detail_buy_car_key` VARCHAR(100) NULL DEFAULT NULL AFTER `car_stock_detail_buy_exprired_insurance_date`");
    }

    public function down()
    {
        //
    }
}
