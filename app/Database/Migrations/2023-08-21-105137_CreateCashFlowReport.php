<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCashFlowReport extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `cash_flow_report` (`id` INT NOT NULL AUTO_INCREMENT ,`cash_flow_id` int  NOT NULL , `cash_flow_report_detail` VARCHAR(200)  NOT NULL ,`cash_flow_report_money` int  NOT NULL, `cash_flow_report_note` VARCHAR(200) NULL,`employee_id` int  NOT NULL,`employee_name` VARCHAR(200) NULL,`created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`))";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
