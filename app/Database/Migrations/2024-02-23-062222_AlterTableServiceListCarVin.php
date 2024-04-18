<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableServiceListCarVin extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `service_list` ADD `service_car_vin` TEXT NULL DEFAULT NULL AFTER `service_responsible`, ADD `service_car_mile` TEXT NULL DEFAULT NULL AFTER `service_car_vin`"); 
    }

    public function down()
    {
        //
    }
}
