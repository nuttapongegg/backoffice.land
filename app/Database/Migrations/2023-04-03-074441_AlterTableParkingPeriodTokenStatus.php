<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableParkingPeriodTokenStatus extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `parking_period` ADD `line_token_parking_period_status` INT NOT NULL DEFAULT '1' AFTER `line_token_parking_period`");
    }

    public function down()
    {
        //
    }
}
