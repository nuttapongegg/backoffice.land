<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTableTargeted extends Migration
{
    public function up()
    {
        
        $db = \Config\Database::connect();
        $sql = "UPDATE `targeted` SET `desired_goals_month` = '1000000' WHERE `targeted`.`id` = 1";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
