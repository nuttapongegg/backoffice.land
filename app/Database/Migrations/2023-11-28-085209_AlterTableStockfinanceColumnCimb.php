<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableStockfinanceColumnCimb extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `car_stock_finance` ADD `car_stock_finance_cimb` INT NULL DEFAULT NULL AFTER `car_stock_finance_maket`, ADD `car_stock_finance_oalt` INT NULL DEFAULT NULL AFTER `car_stock_finance_cimb`, ADD `car_stock_finance_tlt` INT NULL DEFAULT NULL AFTER `car_stock_finance_oalt`");
    }

    public function down()
    {
        //
    }
}
