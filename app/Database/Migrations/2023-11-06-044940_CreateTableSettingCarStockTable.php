<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableSettingCarStockTable extends Migration
{
    public function up()
    {
        //
        $db = \Config\Database::connect();
        $sql = "CREATE TABLE `setting_car_stock_table` (`id` INT NOT NULL AUTO_INCREMENT , `setting_car_stock_code` INT NOT NULL , `setting_car_stock_car_build_status` INT NOT NULL , `setting_car_stock_car_brand` INT NOT NULL , `setting_car_stock_car_sub_model` INT NOT NULL , `setting_car_stock_car_gear` INT NOT NULL , `setting_car_stock_car_vin` INT NOT NULL , `setting_car_stock_finance_ttb` INT NOT NULL , `setting_car_stock_sale_on_web` INT NOT NULL , `setting_car_stock_price_out` INT NOT NULL , `setting_car_stock_location_date_at` INT NOT NULL , `setting_car_stock_branch` INT NOT NULL , `setting_car_stock_parking_period` INT NOT NULL , `setting_car_stock_location` INT NOT NULL , `setting_car_stock_car_document_status` INT NOT NULL , `setting_car_stock_count_booking` INT NOT NULL , `updated_by` TEXT NULL , `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
        $db->query($sql);
        $db->query("INSERT INTO `setting_car_stock_table` (`id`, `setting_car_stock_code`, `setting_car_stock_car_build_status`, `setting_car_stock_car_brand`, `setting_car_stock_car_sub_model`, `setting_car_stock_car_gear`, `setting_car_stock_car_vin`, `setting_car_stock_finance_ttb`, `setting_car_stock_sale_on_web`, `setting_car_stock_price_out`, `setting_car_stock_location_date_at`, `setting_car_stock_branch`, `setting_car_stock_parking_period`, `setting_car_stock_location`, `setting_car_stock_car_document_status`, `setting_car_stock_count_booking`) VALUES (NULL, '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1')");
    }

    public function down()
    {
        //
    }
}
