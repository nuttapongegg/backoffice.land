<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLoanPaymentFileRefNo extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `loan_payment` ADD `payment_file_ref_no` TEXT NULL AFTER `payment_file_time`");
    }

    public function down()
    {
        //
    }
}
