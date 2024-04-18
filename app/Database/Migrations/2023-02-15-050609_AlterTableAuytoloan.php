<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAuytoloan extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `autoloan` ADD `autoloan_status` TEXT  NULL AFTER `autoloan_payment_other`");
    }

    public function down()
    {
        //
    }
}
