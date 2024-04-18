<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Example extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        /******************************************
         * วิธีใช้งาน
         * 1. สร้างไฟล์ใช้คำสั่ง php spark migrate:create
         * 2. เขียนคำสั่ง >> หลักการทำงานก็คือ เอาคำสั่ง MySQL มาโยนไว้ในฟังก์ชั่น <<<
         * 3. สั่งทำงาน php spark migrate
         * note: หลังจากเขียนแล้ว ลองรันเทส แล้วตรวจสอบดูก่อนนะ
         * ****************************************/

        /**********
         * ตัวอย่างการเพิ่มตาราง เช่น
         * *********/
//        $sql = "CREATE TABLE `slips` (`id` INT NOT NULL AUTO_INCREMENT , `admin_id` INT NOT NULL , `admin_username` TEXT NOT NULL , `member_id` INT NULL , bank_setting_id INT NOT NULL , `transaction_id` VARCHAR(20) NOT NULL , `img_slip` TEXT NOT NULL , `bank_qr_code` VARCHAR(255) NOT NULL , `trans_ref` VARCHAR(255) NOT NULL, amount DECIMAL(10,2) NOT NULL, datetime_check DATETIME NOT NULL , `sender_bank_id` INT NOT NULL , `sender_bank_name` TEXT NOT NULL , `sender_bank_account_no` TEXT NOT NULL , `sender_name` TEXT NULL , `receiver_bank_account_no` TEXT NOT NULL , `receiver_name` TEXT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL , `deleted_at` DATETIME NULL , PRIMARY KEY (`id`), INDEX (`transaction_id`), INDEX (`member_id`), UNIQUE (`trans_ref`)) ENGINE = InnoDB";
//        $db->query($sql);

        /**********
         * ตัวอย่างการแก้ไขตาราง เช่น
         * *********/
//        $sql = "ALTER TABLE `documents` CHANGE COLUMN `doc_type` `doc_type` ENUM('ใบสำคัญรับ','ใบสำคัญจ่าย','ใบส่วนลด','อื่น ๆ') NOT NULL COLLATE 'utf8_general_ci' AFTER `id`;";
//        $db->query($sql);
    }

    public function down()
    {
        //
    }
}
