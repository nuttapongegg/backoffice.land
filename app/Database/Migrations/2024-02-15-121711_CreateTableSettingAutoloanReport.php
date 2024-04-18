<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSettingAutoloanReport extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `setting_autoloan_report` (`id` INT NOT NULL AUTO_INCREMENT ,`setting_autoloan_id` int  NOT NULL , `setting_autoloan_report_detail` VARCHAR(200)  NOT NULL ,`setting_autoloan_report_money` int  NOT NULL, `setting_autoloan_report_note` VARCHAR(200) NULL, `setting_autoloan_report_account_balance` INT NULL,`employee_id` int  NOT NULL,`employee_name` VARCHAR(200) NULL,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
