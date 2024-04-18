<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableRebuildlistAddStatus extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `rebuild_list` CHANGE `rebuild_process` `rebuild_process_status` TEXT");
    }

    public function down()
    {
        //
    }
}
