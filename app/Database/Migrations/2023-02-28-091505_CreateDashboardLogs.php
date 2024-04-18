<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDashboardLogs extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `dashboard_logs` (`id` INT NOT NULL AUTO_INCREMENT ,`dashboard_logs_cash_flow` decimal(10,2) NOT NULL DEFAULT 0.00 ,`dashboard_logs_car_cost` decimal(10,2) NOT NULL DEFAULT 0.00 ,`dashboard_logs_summarize` decimal(10,2) NOT NULL DEFAULT 0.00 ,`dashboard_logs_revenue` decimal(10,2) NOT NULL DEFAULT 0.00 ,`dashboard_logs_expenses` decimal(10,2) NOT NULL DEFAULT 0.00 ,`dashboard_logs_net_profit` decimal(10,2) NOT NULL DEFAULT 0.00 ,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
