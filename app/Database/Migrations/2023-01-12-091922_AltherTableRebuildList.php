<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableRebuildList extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `rebuild_list` ADD `rebuild_process` TEXT NULL AFTER `rebuild_detail`");
    }

    public function down()
    {
        //
    }
}
