<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployeeSettingStatusReportSummary extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `employee_setting_status` ADD `employee_setting_status_report_summary` INT NOT NULL AFTER `employee_setting_status_customer_seller`");
    }

    public function down()
    {
        //
    }
}
