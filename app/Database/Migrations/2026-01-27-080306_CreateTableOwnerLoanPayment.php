<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableOwnerLoanPayment extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `owner_loan_payment` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `owner_loan_id` INT NOT NULL,
                `pay_date` DATE NOT NULL,
                `pay_amount` DECIMAL(20,2) NOT NULL,
                `owner_loan_pay_file` TEXT NULL,
                `note` MEDIUMTEXT NULL,
                `status` ENUM('ACTIVE','CANCEL') NOT NULL DEFAULT 'ACTIVE',
                `land_account_id` INT NULL,
                `employee_id` INT NOT NULL,
                `username` VARCHAR(50) NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NULL,
                `deleted_at` DATETIME NULL,
                PRIMARY KEY (`id`),

                INDEX `idx_owner_loan_id` (`owner_loan_id`),
                INDEX `idx_pay_date` (`pay_date`),
                INDEX `idx_status` (`status`),
                INDEX `idx_land_account` (`land_account_id`),
                INDEX `idx_employee` (`employee_id`),

                CONSTRAINT `fk_owner_loan_payment_owner_loan`
                    FOREIGN KEY (`owner_loan_id`) REFERENCES `owner_loan`(`id`)
                    ON DELETE CASCADE
                ) ENGINE=InnoDB
                ";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
