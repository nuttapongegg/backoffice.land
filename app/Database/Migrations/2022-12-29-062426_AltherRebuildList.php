<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherRebuildList extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "ALTER TABLE `rebuild_list` ADD `rebuild_stock_id` VARCHAR(30)  NULL AFTER `rebuild_id`";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
