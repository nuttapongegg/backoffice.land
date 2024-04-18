<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableOverdueStatus extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `usedcar`.`overdue_status` (`id` INT NOT NULL AUTO_INCREMENT , `token_book_registration` VARCHAR(100) NULL DEFAULT NULL , `token_rebuild` VARCHAR(100) NULL DEFAULT NULL , `token_autoload` VARCHAR(100) NULL DEFAULT NULL , `token_book_registration_status` INT NOT NULL DEFAULT '0' , `token_rebuild_status` INT NOT NULL DEFAULT '0' , `token_autoload_status` INT NOT NULL DEFAULT '0' , `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
        $db->query("INSERT INTO `overdue_status` (`id`, `token_book_registration`, `token_rebuild`, `token_autoload`, `token_book_registration_status`, `token_rebuild_status`, `token_autoload_status`) VALUES (NULL, NULL, NULL, NULL, '0', '0', '0')");
    }

    public function down()
    {
        //
    }
}
