<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableSetUpDoc extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `document_set_up` ADD `set_up_postcode` VARCHAR(10) NULL AFTER `set_up_address`, ADD `set_up_branch` VARCHAR(100) NULL AFTER `set_up_postcode`, ADD `set_up_email` VARCHAR(255) NULL AFTER `set_up_branch`");
    }

    public function down()
    {
        //
    }
}
