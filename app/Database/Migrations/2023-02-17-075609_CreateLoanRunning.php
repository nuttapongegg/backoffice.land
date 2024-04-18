<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoanRunning extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `autoloan_running` (`id` INT NOT NULL AUTO_INCREMENT , `autoloan_running_code` varchar(20) NULL, PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
