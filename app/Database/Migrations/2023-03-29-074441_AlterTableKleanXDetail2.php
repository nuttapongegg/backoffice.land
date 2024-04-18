<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableKleanXDetail2 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `kleanx_detail` ADD `kleanx_detail_status` text NULL AFTER `kleanx_detail_service_current_payment`");
    }

    public function down()
    {
        //
    }
}
