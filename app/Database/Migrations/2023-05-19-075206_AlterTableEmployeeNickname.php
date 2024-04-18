<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployeeNickname extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `employees` ADD `nickname` VARCHAR(50) NULL AFTER `name`");
    }

    public function down()
    {
        //
    }
}
