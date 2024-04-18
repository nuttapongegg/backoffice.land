<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableEmployeeStatus extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `employee_setting_status` (`id` INT NOT NULL AUTO_INCREMENT , `employee_id` int NOT NULL ,`employee_setting_status_document` int  NOT NULL,`employee_setting_status_report` int  NOT NULL,`employee_setting_status_setting` int  NOT NULL,`employee_setting_status_landing` int  NOT NULL, PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
