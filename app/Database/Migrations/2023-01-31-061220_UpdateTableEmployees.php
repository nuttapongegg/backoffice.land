<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTableEmployees extends Migration
{
    public function up()
    {
        $pw = '$2y$10$SBM9WqRRVs8Ln4p0UyTqrO7YLSsQ6FDcHroA9aLGY02TGphJfU/mK';
        //
        $db = \Config\Database::connect();
        $db->query("UPDATE `employees` SET `password` = '$pw' WHERE `employees`.`username` = 'spadmin';");
    }

    public function down()
    {
        //
    }
}
