<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableLoanDateClose extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `loan` ADD `loan_date_close` DATE NULL DEFAULT NULL AFTER `land_account_name`");
        $db->query("UPDATE `loan` SET `loan_date_close` = DATE(`updated_at`)");

    }

    public function down()
    {
        //
    }
}
