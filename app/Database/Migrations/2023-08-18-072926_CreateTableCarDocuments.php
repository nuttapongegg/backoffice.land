<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableCarDocuments extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `car_document` (`id` INT NOT NULL AUTO_INCREMENT , `expired_tax` INT NOT NULL , `expired_act` INT NOT NULL , `expired_insurance` INT NOT NULL , `line_token_car_document` VARCHAR(100) NULL , `line_token_car_document_status` INT NOT NULL DEFAULT '1' , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $db->query($sql);
        $db->query("INSERT INTO `car_document` (`id`, `expired_tax`, `expired_act`, `expired_insurance`, `line_token_car_document`, `line_token_car_document_status`) VALUES (NULL, '0', '0', '0', NULL, '0')");
    }

    public function down()
    {
        //
    }
}
