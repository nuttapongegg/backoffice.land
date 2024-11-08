<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployeeSettingStatusBookingAll extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `employee_setting_status` ADD `employee_setting_status_booking_all` INT NOT NULL AFTER `employee_setting_status_edit_in_cashflow`");
    }

    public function down()
    {
        //
    }
}
