<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterDocumentTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE documents ADD INDEX(`car_stock_id`)");
    }

    public function down()
    {
        //
    }
}
