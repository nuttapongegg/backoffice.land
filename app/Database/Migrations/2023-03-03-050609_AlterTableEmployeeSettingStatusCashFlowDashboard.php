<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployeeSettingStatusCashFlowDashboard extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `employee_setting_status` ADD `employee_setting_status_cashflow_dashboard` INT NOT NULL AFTER `employee_setting_status_report_summary`");
    }

    public function down()
    {
        //
    }
}
