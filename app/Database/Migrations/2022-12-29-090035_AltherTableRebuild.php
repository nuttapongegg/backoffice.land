<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableRebuild extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "ALTER TABLE `rebuild` ADD `rebuild_date` DATETIME NULL  AFTER `rebuild_responsible`";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
