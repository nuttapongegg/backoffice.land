<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCustomerSource extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `customers` CHANGE `customer_source` `customer_source` ENUM('Facebook','Tiktok','Youtube','หน้าร้าน') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL");
    }

    public function down()
    {
        //
    }
}
