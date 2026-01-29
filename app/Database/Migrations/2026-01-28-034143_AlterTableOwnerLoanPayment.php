<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableOwnerLoanPayment extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE owner_loan_payment
                ADD interest_amount DECIMAL(20,2) NOT NULL DEFAULT 0.00 AFTER pay_amount,
                ADD principal_amount DECIMAL(20,2) NOT NULL DEFAULT 0.00 AFTER interest_amount,
                ADD principal_balance DECIMAL(20,2) NOT NULL DEFAULT 0.00 AFTER principal_amount,
                ADD days_diff INT NOT NULL DEFAULT 0 AFTER principal_balance;
                ");
    }

    public function down()
    {
        //
    }
}
