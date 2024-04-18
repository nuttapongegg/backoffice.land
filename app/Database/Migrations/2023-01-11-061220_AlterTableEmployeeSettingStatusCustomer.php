<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployeeSettingStatusCustomer extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `employee_setting_status` ADD `employee_setting_status_customer_seller` INT NOT NULL AFTER `employee_setting_status_loan_work`");
    }

    public function down()
    {
        //
    }
}
