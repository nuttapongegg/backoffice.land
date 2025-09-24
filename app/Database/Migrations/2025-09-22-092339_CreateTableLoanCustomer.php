<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLoanCustomer extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `loan_customer` (`id` INT NOT NULL AUTO_INCREMENT , `loan_code` TEXT NOT NULL , `customer_fullname` VARCHAR(50) NULL , `customer_phone` VARCHAR(20) NULL , `customer_birthday` DATE NULL , `customer_card_id` VARCHAR(13) NULL , `customer_email` VARCHAR(255) NULL , `customer_gender` ENUM('ชาย','หญิง','เพศทางเลือก') NULL , `customer_address` MEDIUMTEXT NULL , `img` MEDIUMTEXT NULL , `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL , `deleted_at` DATETIME NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
