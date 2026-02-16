<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableOwnerSetting extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `owner_setting` (`id` INT NOT NULL AUTO_INCREMENT , `default_interest_rate` DECIMAL(5,2) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL , `deleted_at` DATETIME NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $db->query($sql);
        $db->query("INSERT INTO `owner_setting` (`id`, `default_interest_rate`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, '25', CURRENT_TIMESTAMP, NULL, NULL)");
    }

    public function down()
    {
        //
    }
}
