<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableRebuildPeriod extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `rebuild_list` ADD `rebuild_period` INT NOT NULL AFTER `rebuild_date`");
        
    }

    public function down()
    {
        //
    }
}
