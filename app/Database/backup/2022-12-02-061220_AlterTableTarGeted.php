<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableTarGeted extends Migration
{
    public function up()
    {
        
        $db = \Config\Database::connect();
        $sql = "ALTER TABLE `targeted` ADD `desired_goals_month` INT NOT NULL AFTER `desired_goal`";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
