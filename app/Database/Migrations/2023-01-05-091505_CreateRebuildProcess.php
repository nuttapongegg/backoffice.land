<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRebuildProcess extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `rebuild_process` (`id` INT NOT NULL AUTO_INCREMENT , `rebuild_id` int  NULL , `rebuild_list_id` int  NULL , `rebuild_process_name` TEXT NULL , `created_by` VARCHAR(100)  NULL ,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
