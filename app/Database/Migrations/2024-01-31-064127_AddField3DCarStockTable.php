<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddField3DCarStockTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `car_stock` ADD `url_3d` TEXT NULL AFTER `date_to_sold_out`, ADD `url_space` TEXT NULL AFTER `url_3d`;");
    }

    public function down()
    {
        //
    }
}
