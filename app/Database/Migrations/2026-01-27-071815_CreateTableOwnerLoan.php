<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableOwnerLoan extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `owner_loan` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `owner_code` VARCHAR(50) NULL,
                `owner_loan_date` DATE NOT NULL,
                `amount` DECIMAL(20,2) NOT NULL,
                `owner_loan_file` TEXT NULL,
                `note` MEDIUMTEXT NULL,
                `status` ENUM('OPEN','CLOSED','CANCEL') NOT NULL DEFAULT 'OPEN',
                `land_account_id` INT NULL,
                `employee_id` INT NOT NULL,
                `username` VARCHAR(50) NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NULL,
                `deleted_at` DATETIME NULL,
                PRIMARY KEY (`id`),
                INDEX `idx_status` (`status`),
                INDEX `idx_loan_date` (`owner_loan_date`),
                INDEX `idx_land_account` (`land_account_id`),
                INDEX `idx_employee` (`employee_id`)
                ) ENGINE=InnoDB
                ";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
