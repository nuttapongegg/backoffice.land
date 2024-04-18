<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableDocumentCashFlowName extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE `documents` ADD `cash_flow_name` VARCHAR(200) NOT NULL AFTER `doc_payment_type_etc`");
    }

    public function down()
    {
        //
    }
}
