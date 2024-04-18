<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableListPictureRebuild extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `picture_rebuild_other` (`id` INT NOT NULL AUTO_INCREMENT , `picture_rebuild_stock_code` varchar(45) NULL , `picture_rebuild_src` mediumtext NULL ,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL  , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
