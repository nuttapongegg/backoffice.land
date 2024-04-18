<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableRebuildUpdate extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `rebuild_list` ADD `rebuild_date` TEXT NULL AFTER `rebuild_responsible`");
        $db->query("ALTER TABLE `rebuild` DROP COLUMN `rebuild_date`");
    }

    public function down()
    {
        //
    }
}
