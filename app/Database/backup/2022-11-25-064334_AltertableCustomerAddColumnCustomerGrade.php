<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltertableCustomerAddColumnCustomerGrade extends Migration
{
    public function up()
    {
        //
        // $db = \Config\Database::connect();
        // $db->query("ALTER TABLE `customers` ADD `customer_grade` VARCHAR(3)  NULL AFTER `employee_id`");
    }

    public function down()
    {
        //
    }
}
