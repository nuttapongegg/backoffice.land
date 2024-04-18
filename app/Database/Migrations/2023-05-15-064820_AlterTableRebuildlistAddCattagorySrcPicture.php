<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableRebuildlistAddCattagorySrcPicture extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `rebuild_list`  ADD COLUMN `rebuild_category` TEXT NULL AFTER `rebuild_stock_id`");
        $db->query("ALTER TABLE `rebuild_list`  ADD COLUMN `rebuild_src` TEXT NULL AFTER `rebuild_period`");
    }

    public function down()
    {
        //
    }
}
