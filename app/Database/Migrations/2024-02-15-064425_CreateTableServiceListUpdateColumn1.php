<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableServiceListUpdateColumn1 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `service_list` 
          CHANGE `rebuild_period` `service_period` int(11) NULL");

        $db->query("ALTER TABLE `service_list` 
        CHANGE `rebuild_src` `service_src` TEXT NULL NULL");

        $db->query("ALTER TABLE `service_list` 
        ADD `service_process_status` VARCHAR(100) NULL DEFAULT NULL AFTER `service_date`, 
        ADD `service_category` INT NULL DEFAULT NULL AFTER `service_process_status`");
    }

    public function down()
    {
        //
    }
}
