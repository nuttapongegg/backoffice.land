<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AltherTableBookingsOrders extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `bookings` ADD `giveaway_list` VARCHAR(200) NULL AFTER `employee_title`, ADD `booking_note` VARCHAR(200) NULL AFTER `giveaway_list`");
    }

    public function down()
    {
        //
    }
}
