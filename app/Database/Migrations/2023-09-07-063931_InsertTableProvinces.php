<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InsertTableProvinces extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "INSERT INTO `provinces` (`id`, `code`, `name_th`, `name_en`, `geography_id`) VALUES (NULL, '98', 'เบตง', 'Betong', '6')";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
