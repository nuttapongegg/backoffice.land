<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableRebuild extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `rebuild` ADD `rebuild_location` TEXT NULL AFTER `rebuild_stock_code`, 
       ADD `rebuild_responsible` TEXT NULL AFTER `rebuild_location`, 
       ADD `rebuild_date` TEXT NULL AFTER `rebuild_responsible`
        ");
    }

    public function down()
    {
        //
    }
}
