<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployee extends Migration
{
    public function up()
    {
        // $db = \Config\Database::connect();
        // $db->query("ALTER TABLE `position` ADD `employee_level` VARCHAR(1) NOT NULL AFTER `position_name`");
    }

    public function down()
    {
        //
    }
}
