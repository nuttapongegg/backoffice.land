<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStatusDocNew extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE `car_stock_detail_buy` SET `car_stock_detail_buy_car_document_status` = 'เอกสารพร้อม' WHERE `car_stock_detail_buy_car_document_status` = 'เล่มทะเบียนพร้อม'");
        $db->query("UPDATE `car_stock_detail_buy` SET `car_stock_detail_buy_car_document_status` = 'เอกสารไม่พร้อม' WHERE `car_stock_detail_buy_car_document_status` = 'รอเล่มทะเบียน'");
    }

    public function down()
    {
        //
    }
}
