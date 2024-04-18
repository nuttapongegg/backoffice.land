<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAutoloanAccount extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `autoloan` ADD `autoloan_account_id` INT NULL DEFAULT NULL AFTER `autoloan_remnark`, ADD `autoloan_account_name` VARCHAR(100) NULL DEFAULT NULL AFTER `autoloan_account_id`");
        $db->query("ALTER TABLE `autoloan_payment` ADD `autoloan_account_id` INT NULL DEFAULT NULL AFTER `autoloan_payment_src`, ADD `autoloan_account_name` VARCHAR(100) NULL DEFAULT NULL AFTER `autoloan_account_id`");
    }

    public function down()
    {
        //
    }
}
