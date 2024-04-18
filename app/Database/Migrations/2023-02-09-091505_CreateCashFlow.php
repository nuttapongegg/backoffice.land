<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCashFlow extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `cash_flow` (`id` INT NOT NULL AUTO_INCREMENT , `cash_flow_name` VARCHAR(200)  NOT NULL ,`cash_flow_cash` int  NOT NULL ,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
