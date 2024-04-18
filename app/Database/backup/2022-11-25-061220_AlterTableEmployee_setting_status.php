<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployee_setting_status extends Migration
{
    public function up()
    {
        
        // $db = \Config\Database::connect();
        // $sql = "ALTER TABLE `employee_setting_status` ADD `employee_setting_status_employee` INT NOT NULL AFTER `employee_setting_status_doc_payment`, ADD `employee_setting_status_seller` INT NOT NULL AFTER `employee_setting_status_employee`, ADD `employee_setting_status_customer` INT NOT NULL AFTER `employee_setting_status_seller`, ADD `employee_setting_status_car_stock` INT NOT NULL AFTER `employee_setting_status_customer`, ADD `employee_setting_status_cut_release` INT NOT NULL AFTER `employee_setting_status_car_stock`, ADD `employee_setting_status_booking` INT NOT NULL AFTER `employee_setting_status_cut_release`, ADD `employee_setting_status_cost_in_car_stock` INT NOT NULL AFTER `employee_setting_status_booking`, ADD `employee_setting_status_profit_in_cut_release` INT NOT NULL AFTER `employee_setting_status_cost_in_car_stock`";
        // $db->query($sql);
    }

    public function down()
    {
        //
    }
}
