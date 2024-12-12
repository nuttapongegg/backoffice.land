<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLedgerLandLogs extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `ledger_land_logs` (`id` INT NOT NULL AUTO_INCREMENT, `land_logs_loan_amount` DECIMAL(20,2) NOT NULL DEFAULT '0.00' , `land_logs_cash_flow` DECIMAL(20,2) NOT NULL DEFAULT '0.00' , `land_logs_interest` DECIMAL(20,2) NOT NULL DEFAULT '0.00' , `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
