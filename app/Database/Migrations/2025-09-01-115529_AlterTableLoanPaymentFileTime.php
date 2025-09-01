<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLoanPaymentFileTime extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `loan_payment` ADD `payment_file_date` DATE NULL AFTER `loan_payment_src`, ADD `payment_file_time` TIME NULL AFTER `payment_file_date`, ADD `payment_file_price` DECIMAL(20,2) NULL AFTER `payment_file_time`");
    }

    public function down()
    {
        //
    }
}
