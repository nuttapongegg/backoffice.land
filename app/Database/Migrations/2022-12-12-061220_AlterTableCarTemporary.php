<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCarTemporary extends Migration
{
    public function up()
    {
        
        // $db = \Config\Database::connect();
        // $sql = "ALTER TABLE `bookings` ADD `car_stock_id_temporary` INT NOT NULL COMMENT 'รถที่จะเปลี่ยน' AFTER `date_status_change`";
        // $db->query($sql);
    }

    public function down()
    {
        //
    }
}
