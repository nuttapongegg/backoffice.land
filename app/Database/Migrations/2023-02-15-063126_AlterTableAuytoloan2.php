<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAuytoloan2 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `autoloan` ADD `autoloan_code` TEXT NULL AFTER `id`");
    }

    public function down()
    {
        //
    }
}
