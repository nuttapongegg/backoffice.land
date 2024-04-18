<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAutoloanAddIndex extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE autoloan ADD INDEX(`autoloan_code`)");
    }

    public function down()
    {
        //
    }
}
