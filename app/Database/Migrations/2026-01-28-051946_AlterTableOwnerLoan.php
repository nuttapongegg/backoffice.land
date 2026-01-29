<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableOwnerLoan extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE owner_loan
                ADD COLUMN closed_at DATETIME NULL AFTER status,
                ADD COLUMN closed_by INT NULL AFTER closed_at
                ");
    }

    public function down()
    {
        //
    }
}
