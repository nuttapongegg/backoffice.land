<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLandLogsSummaryNet extends Migration
{
    public function up()
    {
        //

        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `ledger_land_logs` ADD `land_logs_summary_net` DECIMAL(20,2) NOT NULL DEFAULT '0.00' AFTER `land_logs_interest`");
    }

    public function down()
    {
        //
    }
}
