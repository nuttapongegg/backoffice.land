<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRebuildPayment extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `autoloan_payment` (`id` INT NOT NULL AUTO_INCREMENT , 
        `autoloan_code` TEXT NULL, 
        `autoloan_payment_amount` decimal(10,2) NOT NULL DEFAULT 0.00, 
        `autoloan_change` decimal(10,2) NOT NULL DEFAULT 0.00, 
        `autoloan_interest` decimal(10,2) NOT NULL DEFAULT 0.00, 
        `autoloan_employee_response` VARCHAR(150)  NULL,
        `autoloan_payment_type` VARCHAR(150)  NULL,
        `autoloan_payment_installment` VARCHAR(150)  NULL,
        `autoloan_payment_customer` VARCHAR(150)  NULL,
        `autoloan_payment_date` DATETIME DEFAULT  NULL,
        `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , 
        `updated_at` DATETIME NULL DEFAULT NULL , 
        `deleted_at` DATETIME NULL DEFAULT NULL , 
         INDEX(`autoloan_code`),
         PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
