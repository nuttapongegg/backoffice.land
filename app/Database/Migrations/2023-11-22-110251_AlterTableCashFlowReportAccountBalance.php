<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCashFlowReportAccountBalance extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `cash_flow_report` ADD `cash_flow_report_account_balance` INT NULL AFTER `cash_flow_report_note`");
    }

    public function down()
    {
        //
    }
}
