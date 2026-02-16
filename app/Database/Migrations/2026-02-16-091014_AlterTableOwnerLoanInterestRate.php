<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableOwnerLoanInterestRate extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `owner_loan` ADD `interest_rate` DECIMAL(5,2) NULL DEFAULT NULL AFTER `amount`");
    }

    public function down()
    {
        //
    }
}
