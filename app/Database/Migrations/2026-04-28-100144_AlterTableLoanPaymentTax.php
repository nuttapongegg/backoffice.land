<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLoanPaymentTax extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `loan_payment`
            ADD `main_account_amount` DECIMAL(20,2) NOT NULL DEFAULT 0.00 AFTER `land_account_name`,
            ADD `tax_status` TINYINT(1) NOT NULL DEFAULT 0 AFTER `main_account_amount`,
            ADD `tax_account_id` INT NULL DEFAULT NULL AFTER `tax_status`,
            ADD `tax_account_name` VARCHAR(100) NULL DEFAULT NULL AFTER `tax_account_id`,
            ADD `tax_amount` DECIMAL(20,2) NOT NULL DEFAULT 0.00 AFTER `tax_account_name`");
    }

    public function down()
    {
        //
    }
}
