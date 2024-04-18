<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableKleanXDetail extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("
        CREATE TABLE `kleanx_detail` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `kleanx_detail_code` text DEFAULT NULL,
      `kleanx_detail_service_List` text DEFAULT NULL,
      `kleanx_detail_service_price` decimal(10,2) NOT NULL DEFAULT 0.00,
      `created_by` varchar(20) DEFAULT NULL,
      `created_at` datetime DEFAULT current_timestamp(),
      `updated_at` datetime DEFAULT NULL,
      `deleted_at` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `kleanx_detail_code` (`kleanx_detail_code`(1024))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
    }

    public function down()
    {
        //
    }
}
