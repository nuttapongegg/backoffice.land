<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableDashboardLogModify extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `dashboard_logs` CHANGE `dashboard_logs_cash_flow` `dashboard_logs_cash_flow` DECIMAL(20,2) NOT NULL DEFAULT '0.00'");
        $db->query("ALTER TABLE `dashboard_logs` CHANGE `dashboard_logs_car_cost` `dashboard_logs_car_cost` DECIMAL(20,2) NOT NULL DEFAULT '0.00'");
        $db->query("ALTER TABLE `dashboard_logs` CHANGE `dashboard_logs_summarize` `dashboard_logs_summarize` DECIMAL(20,2) NOT NULL DEFAULT '0.00'");
        $db->query("ALTER TABLE `dashboard_logs` CHANGE `dashboard_logs_revenue` `dashboard_logs_revenue` DECIMAL(20,2) NOT NULL DEFAULT '0.00'");
        $db->query("ALTER TABLE `dashboard_logs` CHANGE `dashboard_logs_expenses` `dashboard_logs_expenses` DECIMAL(20,2) NOT NULL DEFAULT '0.00'");
        $db->query("ALTER TABLE `dashboard_logs` CHANGE `dashboard_logs_net_profit` `dashboard_logs_net_profit` DECIMAL(20,2) NOT NULL DEFAULT '0.00'");
    }

    public function down()
    {
        //
    }
}
