<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableBookingsStatus extends Migration
{
    public function up()
    {
        
        // $db = \Config\Database::connect();
        // $db->query("ALTER TABLE `bookings` CHANGE `status` `status` ENUM('จอง','รอเซ็นต์สัญญา','รออนุมัติ','รอรับรถ','สำเร็จ','ยกเลิก','ตัดปล่อยรถ','รอคอนเฟิร์มเพื่อยกเลิก','รอคอนเฟิร์มเพื่อรอเซ็นต์สัญญา','รอคอนเฟิร์มเพื่อรออนุมัติ','รอคอนเฟิร์มเพื่อทำการเปลี่ยนรถ') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL");
        // $db->query("ALTER TABLE `bookings` CHANGE `old_status` `old_status` ENUM('จอง','รอเซ็นต์สัญญา','รออนุมัติ','รอรับรถ','สำเร็จ','ยกเลิก','ตัดปล่อยรถ','รอคอนเฟิร์มเพื่อยกเลิก','รอคอนเฟิร์มเพื่อรอเซ็นต์สัญญา','รอคอนเฟิร์มเพื่อรออนุมัติ','รอคอนเฟิร์มเพื่อทำการเปลี่ยนรถ') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL");
        // $db->query($sql);
    }

    public function down()
    {
        //
    }
}
