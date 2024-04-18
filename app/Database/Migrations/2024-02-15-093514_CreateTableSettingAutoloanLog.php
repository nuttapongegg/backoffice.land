<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingAutoloanLog extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `setting_autoloan_logs` (`id` INT NOT NULL AUTO_INCREMENT ,`setting_autoloan_id` int  NOT NULL , `setting_autoloan_detail` VARCHAR(200)  NOT NULL ,`setting_autoloan_money` int  NOT NULL, `setting_autoloan_note` VARCHAR(200) NULL,`employee_id` int  NOT NULL,`employee_name` VARCHAR(200) NULL,`created_at_logs` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at_logs` DATETIME NULL DEFAULT NULL , `deleted_at_logs` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
