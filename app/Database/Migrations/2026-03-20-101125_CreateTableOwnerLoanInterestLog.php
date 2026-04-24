<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableOwnerLoanInterestLog extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "
            CREATE TABLE `owner_loan_ledger` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `owner_loan_id` INT NOT NULL,
                `log_date` DATE NOT NULL,

                `type` ENUM('INIT','INTEREST','PAY','CANCEL') NOT NULL,

                `amount` DECIMAL(20,2) NOT NULL,

                `ref_id` INT NULL,
                `note` VARCHAR(255) NULL,

                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

                PRIMARY KEY (`id`),
                INDEX `idx_owner_loan_id` (`owner_loan_id`),
                INDEX `idx_loan_date` (`owner_loan_id`, `log_date`)
            ) ENGINE=InnoDB;
        ";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
