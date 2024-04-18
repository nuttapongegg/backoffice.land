<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployeeSettingStatusEditInCashFlow extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `employee_setting_status` ADD `employee_setting_status_edit_in_cashflow` INT NOT NULL AFTER `employee_setting_status_edit_in_car_show`");
        $db->query("UPDATE `employee_setting_status` SET `employee_setting_status_edit_in_cashflow` = '0'");
    }

    public function down()
    {
        //
    }
}
