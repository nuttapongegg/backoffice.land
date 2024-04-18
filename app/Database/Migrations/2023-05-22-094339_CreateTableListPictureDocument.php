<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableListPictureDocument extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `picture_document_other` (`id` INT NOT NULL AUTO_INCREMENT , `picture_document_stock_code` varchar(45) NULL , `picture_document_src` mediumtext NULL ,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL  , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
