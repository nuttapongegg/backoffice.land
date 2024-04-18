<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableDocumentDetail extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `documents` ADD `doc_detail` TEXT NULL AFTER `note`");
    }

    public function down()
    {
        //
    }
}
