<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployeeSettingStatusCarStockTable extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `employee_setting_status` ADD `employee_setting_status_setting_car_stock_table` INT NOT NULL AFTER `employee_setting_status_booking_all`");
    }

    public function down()
    {
        //
    }
}
