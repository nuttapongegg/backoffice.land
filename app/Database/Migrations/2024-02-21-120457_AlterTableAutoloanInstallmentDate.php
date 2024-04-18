<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAutoloanInstallmentDate extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `autoloan` ADD `autoloan_installment_date` DATE NULL AFTER `autoloan_date_promise`");
    }

    public function down()
    {
        //
    }
}
