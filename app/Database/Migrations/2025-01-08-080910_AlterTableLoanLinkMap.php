<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLoanLinkMap extends Migration
{
    public function up()
    {
        //

        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `loan` ADD `link_map` TEXT NULL AFTER `loan_close_payment`");
    }

    public function down()
    {
        //
    }
}
