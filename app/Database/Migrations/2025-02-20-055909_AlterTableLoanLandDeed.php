<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLoanLandDeed extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `loan` ADD `land_deed_status` INT NOT NULL AFTER `loan_payment_other`");
    }

    public function down()
    {
        //
    }
}
