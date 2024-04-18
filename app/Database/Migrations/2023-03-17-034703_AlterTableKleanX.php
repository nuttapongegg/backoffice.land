<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableKleanX extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `kleanx` ADD `kleanx_current_payment` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `kleanx_service_price`");
        $db->query("ALTER TABLE `kleanx` ADD `kleanx_status` text NULL AFTER `kleanx_receive_date`");
    }

    public function down()
    {
        //
    }
}
