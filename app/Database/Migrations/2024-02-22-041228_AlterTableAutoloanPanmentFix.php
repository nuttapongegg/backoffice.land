<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAutoloanPanmentFix extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `autoloan_payment` ADD `autoloan_payment_date_fix` DATE NULL AFTER `autoloan_account_name`");
    }

    public function down()
    {
        //
    }
}
