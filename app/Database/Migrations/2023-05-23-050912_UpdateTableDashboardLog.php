<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTableDashboardLog extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("UPDATE `dashboard_logs` SET `dashboard_logs_summarize` = '0',`dashboard_logs_car_cost` = '0' WHERE `dashboard_logs_summarize` = '99999999.99' or `dashboard_logs_car_cost` = '99999999.99';");
    }

    public function down()
    {
        //
    }
}
