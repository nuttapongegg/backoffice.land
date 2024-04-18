<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatedTableAutoloan extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `autoloan` (`id` INT NOT NULL AUTO_INCREMENT , `autoloan_customer_id` int NULL , `autoloan_branch` TEXT NULL, `autoloan_employee_response` VARCHAR(150)  NULL, `autoloan_customer_grade` VARCHAR(5) NULL,  `autoloan_date_promise` DATETIME NULL DEFAULT NULL, `autoloan_summary_no_vat` decimal(10,2) NOT NULL DEFAULT 0.00 , `autoloan_payment_year_counter` int(11) NULL,  `autoloan_payment_interest` decimal(10,2) NOT NULL DEFAULT 0.00, `autoloan_payment_process` decimal(10,2)NOT NULL DEFAULT 0.00, `autoloan_tranfer` decimal(10,2)NOT NULL DEFAULT 0.00, `autoloan_payment_other` decimal(10,2) NOT NULL DEFAULT 0.00, `autoloan_remnark` TEXT NULL,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
