<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableDocumentsFileTime extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `documents`ADD `doc_file_date` DATE NULL AFTER `doc_file`,ADD `doc_file_time` TIME NULL AFTER `doc_file_date`, ADD `doc_file_price` DECIMAL(20,2) NULL AFTER `doc_file_time`");
    }

    public function down()
    {
        //
    }
}
