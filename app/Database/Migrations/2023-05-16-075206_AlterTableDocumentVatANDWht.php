<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableDocumentVatANDWht extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `documents` ADD `reference_number` TEXT NULL DEFAULT NULL AFTER `note`, ADD `doc_vat` INT NOT NULL AFTER `reference_number`, ADD `doc_wht` INT NOT NULL AFTER `doc_vat`, ADD `wht_percent` DECIMAL(10,2) NULL DEFAULT '0.00' AFTER `doc_wht`");
    }

    public function down()
    {
        //
    }
}
