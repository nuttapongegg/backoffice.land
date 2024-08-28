<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLoanClosePayment extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `loan` ADD `loan_close_payment` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `loan_really_pay`");
    }

    public function down()
    {
        //
    }
}
