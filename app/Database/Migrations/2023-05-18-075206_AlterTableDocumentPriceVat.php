<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableDocumentPriceVat extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `documents` ADD `price_vat` VARCHAR(100) NOT NULL AFTER `reference_number`");
    }

    public function down()
    {
        //
    }
}
