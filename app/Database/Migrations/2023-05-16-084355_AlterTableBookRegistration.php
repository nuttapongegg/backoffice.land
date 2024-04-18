<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableBookRegistration extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `book_registration_list`  ADD COLUMN `book_registration_status` TEXT NULL AFTER `book_registration_period`");
        $db->query("ALTER TABLE `book_registration_list`  ADD COLUMN `book_registration_src` TEXT NULL AFTER `book_registration_status`");
    }

    public function down()
    {
        //
    }
}
