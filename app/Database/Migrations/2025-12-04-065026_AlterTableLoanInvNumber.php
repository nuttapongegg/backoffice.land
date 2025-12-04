<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLoanInvNumber extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `loan` ADD `inv_number` VARCHAR(100) NULL DEFAULT NULL AFTER `loan_date_close`");
    }

    public function down()
    {
        //
    }
}
