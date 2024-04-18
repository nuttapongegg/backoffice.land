<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableEmployeeANDCustomerEmail extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `employees` ADD `employee_email` VARCHAR(255) NULL AFTER `thumbnail`");
        $db->query("ALTER TABLE `customers` ADD `customer_email` VARCHAR(255) NULL AFTER `customer_source_other`");
    }

    public function down()
    {
        //
    }
}
