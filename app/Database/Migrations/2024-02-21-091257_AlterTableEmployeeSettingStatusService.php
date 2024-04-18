<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployeeSettingStatusServiceTable extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `employee_setting_status` ADD `employee_setting_status_service` INT NOT NULL AFTER `employee_setting_status_setting_car_stock_table`, ADD `employee_setting_status_edit_in_autoloan_account` INT NOT NULL AFTER `employee_setting_status_service`");
    }

    public function down()
    {
        //
    }
}
