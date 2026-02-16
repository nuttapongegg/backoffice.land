<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableOwnerLoanPaymentInterestRateUsed extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `owner_loan_payment` ADD `interest_rate_used` DECIMAL(5,2) NOT NULL DEFAULT '0.00' AFTER `interest_amount`");
    }

    public function down()
    {
        //
    }
}
