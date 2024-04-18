<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTablebookings extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        // $db->query("ALTER TABLE `bookings` ADD `customer_grade` VARCHAR(3)  NULL AFTER `comment`");
    }

    public function down()
    {
        //
    }
}
