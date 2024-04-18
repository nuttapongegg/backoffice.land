<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableBooking extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        // $db->query("ALTER TABLE `bookings` CHANGE COLUMN `status` `status` ENUM('จอง','รอเซ็นต์สัญญา','รออนุมัติ','รอรับรถ','สำเร็จ','ยกเลิก','ตัดปล่อยรถ') NOT NULL COMMENT 'สถานะใบจอง' COLLATE 'utf8_general_ci' AFTER `id`;");
    }

    public function down()
    {
        //
    }
}
