<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAutoloan15032023 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `autoloan` ADD `autoloan_payment_sum_installment` decimal(10,2) NOT NULL DEFAULT 0.00 AFTER `autoloan_summary_no_vat`");
    }

    public function down()
    {
        //
    }
}
