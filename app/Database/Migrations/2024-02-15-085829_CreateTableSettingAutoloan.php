<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSettingAutoloan extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `setting_autoloan` (`id` INT NOT NULL AUTO_INCREMENT , `autoloan_account_name` VARCHAR(200)  NOT NULL ,`autoloan_account_cash` int  NOT NULL ,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
