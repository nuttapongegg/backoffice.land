<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAutoloanPayment4 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `autoloan_payment` MODIFY COLUMN `autoloan_payment_date` DATE NULL");
    }

    public function down()
    {
        //
    }
}
