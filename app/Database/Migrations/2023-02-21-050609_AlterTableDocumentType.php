<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableDocumentTypeLists extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `document_title_lists` CHANGE `doc_type` `doc_type` ENUM('ใบสำคัญรับ','ใบสำคัญจ่าย','ใบส่วนลด','รายจ่าย') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;");
    }

    public function down()
    {
        //
    }
}
