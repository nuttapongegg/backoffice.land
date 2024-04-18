<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableEmployeeSettingStatus extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `employee_setting_status` ADD `employee_setting_status_buy_type` INT NOT NULL AFTER `employee_setting_status_landing`, ADD `employee_setting_status_check_car` INT NOT NULL AFTER `employee_setting_status_buy_type`, ADD `employee_setting_status_doc_payment` INT NOT NULL AFTER `employee_setting_status_check_car`, ADD `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `employee_setting_status_doc_payment`, ADD `updated_at` DATETIME NULL DEFAULT NULL AFTER `created_at`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`");
    }

    public function down()
    {
        //
    }
}
