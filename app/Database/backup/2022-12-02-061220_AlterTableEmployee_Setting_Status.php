<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEMPLOYEE_SETTING_STATUS extends Migration
{
    public function up()
    {
        
        $db = \Config\Database::connect();
        $sql = "ALTER TABLE `employee_setting_status` ADD `employee_setting_status_manager_approval` INT NOT NULL AFTER `employee_setting_status_profit_in_cut_release`";
        $db->query($sql);

        $sql = "ALTER TABLE `employee_setting_status` ADD `employee_setting_status_registration` INT NOT NULL AFTER `employee_setting_status_manager_approval`, ADD `employee_setting_status_acclimate` INT NOT NULL AFTER `employee_setting_status_registration`, ADD `employee_setting_status_loan_work` INT NOT NULL AFTER `employee_setting_status_acclimate`";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
