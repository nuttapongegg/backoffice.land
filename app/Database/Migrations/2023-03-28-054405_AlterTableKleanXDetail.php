<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableKleanXDetail extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `kleanx_detail` ADD `kleanx_detail_service_change` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `kleanx_detail_service_price`");
        $db->query("ALTER TABLE `kleanx_detail` ADD `kleanx_detail_service_current_payment` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `kleanx_detail_service_change`");
    }

    public function down()
    {
        //
    }
}
