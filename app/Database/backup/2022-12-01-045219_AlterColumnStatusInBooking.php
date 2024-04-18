<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterColumnStatusInBooking extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `bookings` MODIFY `status` ENUM('จอง', 'รอเซ็นต์สัญญา', 'รออนุมัติ', 'รอรับรถ', 'สำเร็จ', 'ยกเลิก', 'ตัดปล่อยรถ', 'รอคอนเฟิร์มเพื่อยกเลิก', 'รอคอนเฟิร์มเพื่อรอเซ็นต์สัญญา', 'รอคอนเฟิร์มเพื่อรออนุมัติ'), ADD `old_status` ENUM('จอง', 'รอเซ็นต์สัญญา', 'รออนุมัติ', 'รอรับรถ', 'สำเร็จ', 'ยกเลิก', 'ตัดปล่อยรถ', 'รอคอนเฟิร์มเพื่อยกเลิก', 'รอคอนเฟิร์มเพื่อรอเซ็นต์สัญญา', 'รอคอนเฟิร์มเพื่อรออนุมัติ') AFTER `status`");
       ;
    }

    public function down()
    {
        //
    }
}
