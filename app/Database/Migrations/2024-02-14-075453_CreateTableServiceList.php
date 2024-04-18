<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableServiceList extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `service_list` (`id` INT NOT NULL AUTO_INCREMENT , 
        `service_stock_id` TEXT NULL , 
        `service_detail` TEXT NULL ,
        `service_location` TEXT NULL ,
        `service_responsible` TEXT NULL ,
        `service_price` decimal(10,2) NOT NULL ,
        `rebuild_period` int(11) not NULL ,
        `rebuild_src` text NULL ,
        `service_date` DATETIME  NULL DEFAULT NULL ,
        `created_by` VARCHAR(100)  NULL ,
        `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , 
        `updated_at` DATETIME NULL DEFAULT NULL , 
        `deleted_at` DATETIME NULL DEFAULT NULL ,
          PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
