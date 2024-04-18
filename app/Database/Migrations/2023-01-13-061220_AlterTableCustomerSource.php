<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCustomerSource extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `customers` ADD `customer_source` ENUM('Facebook', 'Tiktok', 'Youtube') NULL AFTER `customer_grade`");
    }

    public function down()
    {
        //
    }
}
