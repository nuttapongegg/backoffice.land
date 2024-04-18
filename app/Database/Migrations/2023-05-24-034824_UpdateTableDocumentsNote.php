<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTableDocumentsNote extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("UPDATE `documents` SET `doc_detail` = `note` WHERE `note` != '' AND (`doc_detail` IS NULL OR `doc_detail` = '')");
        $db->query("UPDATE `documents` SET `note` = '' WHERE `doc_detail` IS NOT NULL AND `doc_detail` != ''");
    }

    public function down()
    {
        //
    }
}
