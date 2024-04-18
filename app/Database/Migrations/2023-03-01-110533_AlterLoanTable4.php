<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterLoanTable4 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `autoloan` ADD `autoloan_summary_all` decimal(10,2) NOT NULL DEFAULT 0.00  AFTER `autoloan_payment_interest`");
        $db->query("ALTER TABLE `autoloan` ADD `autoloan_payment_month` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `autoloan_summary_all`");
    }

    public function down()
    {
        //
    }
}
