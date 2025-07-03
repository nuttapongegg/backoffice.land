<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableOpenLoanTarget extends Migration
{
    public function up()
    {
        //
         $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `targeted` ADD `open_loan_target` INT(20) NOT NULL AFTER `id`");
        $db->query("UPDATE `targeted` SET `open_loan_target` = '60000000'");
    }

    public function down()
    {
        //
    }
}
