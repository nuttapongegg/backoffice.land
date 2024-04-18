<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableBookRegistrationList extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `book_registration_list` (`id` INT NOT NULL AUTO_INCREMENT , `book_registration_id` int  NULL , `book_registration_stock_id` VARCHAR(30) NULL, `book_registration_detail` TEXT NULL , `book_registration_process` TEXT NULL ,`book_registration_location` TEXT NULL ,`book_registration_responsible` TEXT NULL , `book_registration_price` int  NULL ,`book_registration_date` DATETIME NULL DEFAULT NULL ,`book_registration_period` int NOT NULL ,`created_by` VARCHAR(100)  NULL ,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
