<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableRebuildUpdateDate extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `rebuild_list` modify `rebuild_date` DATETIME NULL");
    }

    public function down()
    {
        //
    }
}
