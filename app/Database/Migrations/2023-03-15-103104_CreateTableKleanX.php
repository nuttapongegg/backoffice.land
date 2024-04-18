<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableKleanX extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `kleanx` (`id` INT NOT NULL AUTO_INCREMENT , 
        `kleanx_code` TEXT NULL, 
        `kleanx_customer_name` TEXT NULL,
        `kleanx_tel` VARCHAR(20) NULL,
        `kleanx_brand` TEXT NULL,
        `kleanx_model` TEXT NULL,
        `kleanx_vin` TEXT NULL,
        `kleanx_service_List` TEXT NULL,
        `kleanx_service_price` decimal(10,2) NOT NULL DEFAULT 0.00,  
        `kleanx_receive_date` DATETIME DEFAULT  NULL,
        `created_by` VARCHAR(20) NULL, 
        `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , 
        `updated_at` DATETIME NULL DEFAULT NULL , 
        `deleted_at` DATETIME NULL DEFAULT NULL , 
         INDEX(`kleanx_code`),
         PRIMARY KEY (`id`))";
        $db->query($sql);

        $sql_running = "CREATE TABLE `kleanx_running` (`id` INT NOT NULL AUTO_INCREMENT , 
        `kleanx_code` varchar(20) NULL,  PRIMARY KEY (`id`))";
        $db->query($sql_running);
    }

    public function down()
    {
        //
    }
}
