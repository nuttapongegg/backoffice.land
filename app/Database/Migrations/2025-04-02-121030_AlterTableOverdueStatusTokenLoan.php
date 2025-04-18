<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableOverdueStatusTokenLoan extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `overdue_status` CHANGE `token_loan` `token_loan` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL");
    }

    public function down()
    {
        //
    }
}
