<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAutoloan extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `autoloan_payment` ADD `autoloan_customer_tel` varchar(20) NULL AFTER `autoloan_payment_customer`");
    }

    public function down()
    {
        //
    }
}
