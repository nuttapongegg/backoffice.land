<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableStockOwner extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `car_stock_owner` ADD `car_stock_owner_car_engin_brand` varchar(150) NULL AFTER `car_stock_owner_car_brand`,
        ADD `car_stock_owner_car_weight` INT NULL AFTER `car_stock_owner_book_src`,
        ADD `car_stock_owner_car_manual` varchar(45) NULL AFTER `car_stock_owner_car_weight`,
        ADD `car_stock_owner_car_number_owner` INT NULL AFTER `car_stock_owner_car_manual`,
        ADD `car_stock_owner_car_responsibility` TEXT NULL AFTER `car_stock_owner_car_number_owner`,
        ADD `car_stock_owner_car_experience` TEXT NULL AFTER `car_stock_owner_car_responsibility`
        ");
    }

    public function down()
    {
        //
    }
}
