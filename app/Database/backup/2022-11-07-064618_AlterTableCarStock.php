<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCarStock extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        // $db->query("ALTER TABLE `car_stock` ADD COLUMN `date_to_sold_out` DATETIME NULL DEFAULT NULL AFTER `car_stock_created_by`;");
    }

    public function down()
    {
        //
    }
}
