<?php

namespace App\Models;

class SettingLandModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getSettingLandAll()
    {
        $sql = "
            SELECT setting_land.*
            FROM setting_land
            WHERE deleted_at IS NULL";
        $builder = $this->db->query($sql);
        return $builder->getResult();
    }

    public function getSettingLandByID($id)
    {
        $builder = $this->db->table('setting_land');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function getSettingLandByName($name)
    {
        $builder = $this->db->table('setting_land');

        return $builder->where('land_account_name', $name)->get()->getRow();
    }

    public function insertSettingLand($data)
    {
        $builder = $this->db->table('setting_land');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateSettingLandByID($id, $data)
    {
        $builder = $this->db->table('setting_land');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteSettingLandByID($id)
    {
        $sql = "DELETE FROM setting_land WHERE id = $id";

        $builder = $this->db->query($sql);

        return $builder;
    }

    public function updateSettingLandByName($land_account_name, $data)
    {
        $builder = $this->db->table('setting_land');

        return $builder->where('land_account_name', $land_account_name)->update($data);
    }

    public function insertSettingLandLogs($data)
    {
        $builder = $this->db->table('setting_land_logs');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function getSettingLandLogsAll($param)
    {
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT setting_land_logs.*,setting_land.land_account_name
        FROM setting_land_logs
        JOIN setting_land ON setting_land.id  = setting_land_logs.setting_land_id
        ORDER BY setting_land_logs.created_at_logs DESC
        limit $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getSettingLandLogsAllCount()
    {
        $sql = "SELECT setting_land_logs.*,setting_land.land_account_name
        FROM setting_land_logs
        JOIN setting_land ON setting_land.id  = setting_land_logs.setting_land_id
        ORDER BY setting_land_logs.created_at_logs DESC";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getSettingLandLogsSearchcount($param)
    {
        $search_value = $param['search_value'];
        $sql = "SELECT setting_land_logs.*,setting_land.land_account_name
        FROM setting_land_logs
        JOIN setting_land ON setting_land.id  = setting_land_logs.setting_land_id
        WHERE (land_account_name like '%" . $search_value . "%') OR (setting_land_detail like '%" . $search_value . "%') OR (setting_land_money like '%" . $search_value . "%')
        OR (setting_land_note like '%" . $search_value . "%') OR (employee_name like '%" . $search_value . "%') OR (created_at_logs like '%" . $search_value . "%')
        ORDER BY setting_land_logs.created_at_logs DESC";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getSettingLandLogsSearch($param)
    {
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT setting_land_logs.*,setting_land.land_account_name
        FROM setting_land_logs
        JOIN setting_land ON setting_land.id  = setting_land_logs.setting_land_id
        WHERE (land_account_name like '%" . $search_value . "%') OR (setting_land_detail like '%" . $search_value . "%') OR (setting_land_money like '%" . $search_value . "%') 
            OR (setting_land_note like '%" . $search_value . "%') OR (employee_name like '%" . $search_value . "%') OR (created_at_logs like '%" . $search_value . "%')
            ORDER BY setting_land_logs.created_at_logs DESC
            limit $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function insertSettingLandReport($data)
    {
        $builder = $this->db->table('setting_land_report');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function getSettingLandReportAll($param)
    {
        $start = $param['start'];
        $length = $param['length'];
        $id = $param['id'];

        $sql = "SELECT setting_land_report.*,setting_land.land_account_name
        FROM setting_land_report
        JOIN setting_land ON setting_land.id  = setting_land_report.setting_land_id
        WHERE setting_land_report.setting_land_id = $id
        ORDER BY setting_land_report.created_at DESC
        limit $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getSettingLandReportAllCount($param)
    {
        $id = $param['id'];

        $sql = "SELECT setting_land_report.*,setting_land.land_account_name
        FROM setting_land_report
        JOIN setting_land ON setting_land.id  = setting_land_report.setting_land_id
        WHERE setting_land_report.setting_land_id = $id
        ORDER BY setting_land_report.created_at DESC";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getSettingLandReportSearchcount($param)
    {
        $id = $param['id'];
        $search_value = $param['search_value'];

        $sql = "SELECT setting_land_report.*,setting_land.land_account_name
        FROM setting_land_report
        JOIN setting_land ON setting_land.id  = setting_land_report.setting_land_id
        WHERE ((land_account_name like '%" . $search_value . "%') OR (setting_land_report_detail like '%" . $search_value . "%') OR (setting_land_report_money like '%" . $search_value . "%')
        OR (setting_land_report_note like '%" . $search_value . "%') OR (employee_name like '%" . $search_value . "%') OR (setting_land_report.created_at like '%" . $search_value . "%')) AND setting_land_report.setting_land_id = $id 
        ORDER BY setting_land_report.created_at DESC";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getSettingLandReportSearch($param)
    {
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];
        $id = $param['id'];

        $sql = "SELECT setting_land_report.*,setting_land.land_account_name
        FROM setting_land_report
        JOIN setting_land ON setting_land.id  = setting_land_report.setting_land_id
        WHERE ((land_account_name like '%" . $search_value . "%') OR (setting_land_report_detail like '%" . $search_value . "%') OR (setting_land_report_money like '%" . $search_value . "%') 
            OR (setting_land_report_note like '%" . $search_value . "%') OR (employee_name like '%" . $search_value . "%') OR (setting_land_report.created_at like '%" . $search_value . "%') OR (setting_land_report.setting_land_report_account_balance like '%" . $search_value . "%')) AND setting_land_report.setting_land_id = $id 
            ORDER BY setting_land_report.created_at DESC
            limit $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDashboardlogReportAll($param)
    {
        $start = $param['start'];
        $length = $param['length'];
        $date = $param['date'];

        $sql = "SELECT setting_land_report.*,setting_land.land_account_name
        FROM setting_land_report
        JOIN setting_land ON setting_land.id  = setting_land_report.setting_land_id
        WHERE DATE(setting_land_report.created_at) = '$date'
        ORDER BY setting_land_report.created_at ASC
        limit $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDashboardlogReportAllCount($param)
    {
        $date = $param['date'];

        $sql = "SELECT setting_land_report.*,setting_land.land_account_name
        FROM setting_land_report
        JOIN setting_land ON setting_land.id  = setting_land_report.setting_land_id
        WHERE DATE_FORMAT(setting_land_report.created_at, '%Y-%m-%d') = '$date'
        ORDER BY setting_land_report.created_at ASC";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDashboardlogReportSearchcount($param)
    {
        $date = $param['date'];
        $search_value = $param['search_value'];

        $sql = "SELECT setting_land_report.*,setting_land.land_account_name
        FROM setting_land_report
        JOIN setting_land ON setting_land.id  = setting_land_report.setting_land_id
        WHERE ((land_account_name like '%" . $search_value . "%') OR (setting_land_report_detail like '%" . $search_value . "%') OR (setting_land_report_money like '%" . $search_value . "%')
        OR (setting_land_report_note like '%" . $search_value . "%') OR (employee_name like '%" . $search_value . "%') OR (setting_land_report.created_at like '%" . $search_value . "%')) AND DATE_FORMAT(setting_land_report.created_at, '%Y-%m-%d') = '$date'
        ORDER BY setting_land_report.created_at ASC";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDashboardlogReportSearch($param)
    {
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];
        $date = $param['date'];

        $sql = "SELECT setting_land_report.*,setting_land.land_account_name
        FROM setting_land_report
        JOIN setting_land ON setting_land.id  = setting_land_report.setting_land_id
        WHERE ((land_account_name like '%" . $search_value . "%') OR (setting_land_report_detail like '%" . $search_value . "%') OR (setting_land_report_money like '%" . $search_value . "%') 
            OR (setting_land_report_note like '%" . $search_value . "%') OR (employee_name like '%" . $search_value . "%') OR (setting_land_report.created_at like '%" . $search_value . "%') OR (setting_land_report.setting_land_report_account_balance like '%" . $search_value . "%')) AND DATE_FORMAT(setting_land_report.created_at, '%Y-%m-%d') = '$date'
            ORDER BY setting_land_report.created_at ASC
            limit $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
}
