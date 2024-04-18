<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableCarStockDetailBuyStatusUpdateColumnDateRebuild extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE `car_stock_detail_buy` SET `car_stock_detail_buy_date_car_rebuild_status` = `car_stock_detail_buy_updated_at` WHERE `car_stock_detail_buy_car_build_status` = 'พร้อมขาย'");
    }

    public function down()
    {
        //
    }
}
