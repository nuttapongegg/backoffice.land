<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableStockfinanceColumnMarket extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `car_stock_finance` ADD COLUMN `car_stock_finance_maket` int(11) NULL AFTER `car_stock_finance_kkp`");
    }

    public function down()
    {
        //
    }
}
