<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableRebuild extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `rebuild_list` ADD `rebuild_location` TEXT NULL AFTER `rebuild_process`");
        $db->query("ALTER TABLE `rebuild_list` ADD `rebuild_responsible` TEXT NULL AFTER `rebuild_location`");
        $db->query("ALTER TABLE `rebuild` DROP COLUMN `rebuild_location`, DROP COLUMN `rebuild_responsible`");
    }

    public function down()
    {
        //
    }
}
