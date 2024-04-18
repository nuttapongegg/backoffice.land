<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableServiceListUpdateColumn2 extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `service_list` 
          CHANGE `service_category` `service_category` varchar(150) NULL");
    }

    public function down()
    {
        //
    }
}
