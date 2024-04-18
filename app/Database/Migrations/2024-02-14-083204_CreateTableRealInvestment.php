<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableRealInvestment extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `real_investment` (`id` INT NOT NULL AUTO_INCREMENT , `investment` int NOT NULL , `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
        $db->query("INSERT INTO `real_investment` (`id`, `investment`, `created_at`, `updated_at`, `deleted_at`) VALUES (NULL, '0', CURRENT_TIMESTAMP, NULL, NULL)");
    }

    public function down()
    {
        //
    }
}
