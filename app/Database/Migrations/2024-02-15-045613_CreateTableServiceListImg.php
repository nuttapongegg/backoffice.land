<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableServiceListImg extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `picture_service_other` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `picture_service_stock_code` varchar(45) DEFAULT NULL,
            `picture_service_src` mediumtext DEFAULT NULL,
            `created_at` datetime DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
