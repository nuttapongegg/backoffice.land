<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class EmployeeLoginDetailModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function insertEmployeeLoginDetail($data)
    {
        $builder = $this->db->table('employee_login_details');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateEmployeeLoginDetailByID($id, $data)
    {
        $builder = $this->db->table('employee_login_details');

        return $builder->where('id', $id)->update($data);
    }

    public function getEmployeeOnline($employeeID)
    {
        $sql = "
            SELECT *
            FROM employee_login_details  
            WHERE last_activity > DATE_SUB(NOW(), INTERVAL 3600 SECOND) AND employee_id = '$employeeID' AND active = '1' ORDER BY id DESC LIMIT 1
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow();
    }

    public function getEmployeeOffline($employeeID)
    {
        $sql = "
            SELECT *
            FROM employee_login_details  
            WHERE employee_id = '$employeeID' ORDER BY id DESC LIMIT 1
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow();
    }

}