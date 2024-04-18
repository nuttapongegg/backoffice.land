<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableDocument extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        // $db->query("ALTER TABLE `documents` CHANGE COLUMN `doc_type` `doc_type` ENUM('ใบสำคัญรับ','ใบสำคัญจ่าย','ใบส่วนลด','อื่น ๆ') NOT NULL COLLATE 'utf8_general_ci' AFTER `id`;");
    }

    public function down()
    {
        //
    }
}
