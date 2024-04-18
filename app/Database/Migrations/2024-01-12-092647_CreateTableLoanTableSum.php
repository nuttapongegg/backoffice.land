<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLoanTableSum extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `autoloan_payment` ADD `loan_amount` decimal(10,2) NOT NULL DEFAULT 0.00  AFTER `autoloan_payment_date`, 
         ADD `loan_interest` decimal(10,2) NOT NULL DEFAULT 0.00  AFTER `loan_amount`, 
         ADD `loan_balance` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `loan_interest`,
         ADD `yiled` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `loan_balance`
        ");
    }

    public function down()
    {
        //
    }
}
