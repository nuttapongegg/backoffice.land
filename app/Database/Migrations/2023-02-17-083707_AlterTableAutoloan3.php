<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAutoloan3 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `autoloan` ADD `autoloan_stock_name` TEXT NULL AFTER `autoloan_code`");
    }

    public function down()
    {
        //
    }
}
