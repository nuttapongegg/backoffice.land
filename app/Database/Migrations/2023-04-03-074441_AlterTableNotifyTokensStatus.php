<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableNotifyTokensStatus extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect(); 
        $db->query("ALTER TABLE `notify` ADD `line_token_status` INT NOT NULL DEFAULT '1' AFTER `notify_line_token_status`, ADD `line_token_doc_status` INT NOT NULL DEFAULT '1' AFTER `line_token_status`");
    }

    public function down()
    {
        //
    }
}
