<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableSellers extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `sellers` ADD `seller_branch` VARCHAR(100) NULL AFTER `id_number`, ADD `seller_postcode` VARCHAR(10) NULL AFTER `seller_branch`, ADD `seller_email` VARCHAR(255) NULL AFTER `seller_postcode`, ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `seller_email`, ADD `updated_at` DATETIME NULL AFTER `created_at`, ADD `deleted_at` DATETIME NULL AFTER `updated_at`");
    }

    public function down()
    {
        //
    }
}
