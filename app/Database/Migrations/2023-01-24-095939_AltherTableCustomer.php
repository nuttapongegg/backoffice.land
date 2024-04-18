<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableCustomer extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `customers` ADD `types_interest` TEXT NULL AFTER `interest`,
        ADD `brand_interest` TEXT NULL AFTER `types_interest`,
        ADD `model_interest` TEXT NULL AFTER `brand_interest`,
        ADD `color_interest` TEXT NULL AFTER `model_interest`,
        ADD `number_interest` TEXT NULL AFTER `color_interest`
        ");
    }

    public function down()
    {
        //
    }
}
