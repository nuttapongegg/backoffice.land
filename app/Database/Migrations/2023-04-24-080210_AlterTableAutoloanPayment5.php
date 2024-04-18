<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAutoloanPayment5 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `autoloan_payment` ADD COLUMN `autoloan_payment_pay_type` text NULL AFTER `autoloan_payment_type`");
        $db->query("ALTER TABLE `autoloan_payment` ADD COLUMN `autoloan_payment_src` text NULL AFTER `autoloan_customer_tel`");
    }

    public function down()
    {
        //
    }
}
