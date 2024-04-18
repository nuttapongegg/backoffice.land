<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableParkingPeriodToken extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `parking_period` ADD `line_token_parking_period` VARCHAR(100) NULL AFTER `parking_period_red`");
    }

    public function down()
    {
        //
    }
}
