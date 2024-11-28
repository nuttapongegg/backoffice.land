<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLoanType extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `loan` ADD `loan_type` ENUM('เงินสด','เช่าซื้อ') NOT NULL AFTER `loan_payment_process`");
    }

    public function down()
    {
        //
    }
}
