<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableBookRegistration extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `book_registration` (`id` INT NOT NULL AUTO_INCREMENT ,`book_registration_stock_code` VARCHAR(50) NULL,`book_registration_location` TEXT NULL ,`book_registration_responsible` TEXT NULL ,`book_registration_date` TEXT NULL ,`book_registration_created_by` VARCHAR(150)  NULL ,`book_registration_created_at` DATETIME NULL DEFAULT NULL , `book_registration_updated_at` DATETIME NULL DEFAULT NULL ,PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
