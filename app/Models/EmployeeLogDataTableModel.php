<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeLogDataTableModel
{
    protected $db;
    protected $column_order;
    protected $column_search;
    protected $order;
    
    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;

        // Set orderable column fields
        $this->column_order = array(null, 'detail', 'username', 'created_at');
        // Set searchable column fields
        $this->column_search = array('detail', 'username', 'created_at');
        // Set default order
        $this->order = array('created_at' => 'DESC');
    }

    public function getEmployeeLogByEmployeeIDSearch($param)
    {

        $id = $param['id'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT employee_id,employee_logs.detail, CONCAT(DATE_FORMAT(employee_logs.created_at, '%d-%m-'), YEAR(employee_logs.created_at)+543) as formatted_date, TIME_FORMAT(employee_logs.created_at, '%H:%i:%s') as formatted_time 
            from employee_logs 
            WHERE employee_id = $id AND ((detail like '%" . $search_value . "%') OR (created_at like '%" . $search_value . "%')) 
            ORDER BY created_at DESC 
            limit $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogByEmployeeIDSearchcount($param)
    {

        $id = $param['id'];
        $search_value = $param['search_value'];

        $sql = "SELECT employee_id,employee_logs.detail, CONCAT(DATE_FORMAT(employee_logs.created_at, '%d-%m-'), YEAR(employee_logs.created_at)+543) as formatted_date, TIME_FORMAT(employee_logs.created_at, '%H:%i:%s') as formatted_time  
            from employee_logs 
            WHERE employee_id = $id AND ((detail like '%" . $search_value . "%') OR (created_at like '%" . $search_value . "%')) 
            ORDER BY created_at DESC
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogByEmployeeIDAll($param)
    {
        $id = $param['id'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT employee_id,employee_logs.detail, CONCAT(DATE_FORMAT(employee_logs.created_at, '%d-%m-'), YEAR(employee_logs.created_at)+543) as formatted_date, TIME_FORMAT(employee_logs.created_at, '%H:%i:%s') as formatted_time 
                FROM employee_logs 
                WHERE employee_id = $id 
                ORDER BY created_at DESC 
                LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogByEmployeeIDcount($param)
    {
        $id = $param['id'];
        $sql = "SELECT employee_id,employee_logs.detail,employee_logs.created_at 
                    from employee_logs 
                    WHERE employee_id = $id 
                    ORDER BY created_at DESC
                    ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogByEmployeeIDSearchInFooter($param)
    {
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT employee_logs.username,employee_logs.detail,CONCAT(DATE_FORMAT(employee_logs.created_at, '%d-%m-'), YEAR(employee_logs.created_at)+543) as formatted_date, TIME_FORMAT(employee_logs.created_at, '%H:%i:%s') as formatted_time 
            from employee_logs 
            WHERE username like '%" . $search_value . "%' OR detail like '%" . $search_value . "%' OR created_at like '%" . $search_value . "%'
            ORDER BY created_at DESC
            limit $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogByEmployeeIDSearchcountInFooter($param)
    {
        $search_value = $param['search_value'];
        $sql = "SELECT employee_logs.username,employee_logs.detail,CONCAT(DATE_FORMAT(employee_logs.created_at, '%d-%m-'), YEAR(employee_logs.created_at)+543) as formatted_date, TIME_FORMAT(employee_logs.created_at, '%H:%i:%s') as formatted_time 
            from employee_logs 
            WHERE username like '%" . $search_value . "%' OR detail like '%" . $search_value . "%' OR created_at like '%" . $search_value . "%'
            ORDER BY created_at DESC
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogByEmployeeIDAllInFooter($param)
    {
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT employee_logs.username,employee_logs.detail,CONCAT(DATE_FORMAT(employee_logs.created_at, '%d-%m-'), YEAR(employee_logs.created_at)+543) as formatted_date, TIME_FORMAT(employee_logs.created_at, '%H:%i:%s') as formatted_time 
                from employee_logs 
                ORDER BY created_at DESC 
                limit $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getEmployeeLogByEmployeeIDCountInFooter()
    {
        $sql = "SELECT employee_logs.username,employee_logs.detail,employee_logs.created_at 
                    from employee_logs 
                    ORDER BY created_at DESC
                    ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
}
