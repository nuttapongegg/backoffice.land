<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableNotify extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        // $db->query("ALTER TABLE `notify` ADD `notify_line_token_status` VARCHAR(100) NULL AFTER `notify_line_token`");
    }

    public function down()
    {
        //
    }
}
