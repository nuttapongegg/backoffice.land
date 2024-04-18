<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class EmployeeNamesModel {

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getEmployeeAll()
    {
        $builder = $this->db->table('employees');
        

        return $builder
            // ->where('deleted_at', NULL)
            // ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getEmployeeByID($id)
    {
        $builder = $this->db->table('employees');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function getEmployeeByName($data)
    {
        $builder = $this->db->table('employees');

        return $builder->where('name', $data)->get()->getRow();
    }

    public function insertEmployee($data)
    {
        $builder = $this->db->table('employees');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateEmployeeByID($id, $data)
    {
        $builder = $this->db->table('employees');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteEmployeeByID($id)
    {
//        $builder = $this->db->table('employees');
//
//        $builder->where('id', $id);
//        $check = $builder->delete();
//
//        return $check;

        $sql = "DELETE FROM employees WHERE id = $id";

        $builder = $this->db->query($sql);

        return $builder;
    }


    // List Data
    public function getEmployeeAllWithJoin()
    {
        $sql = "
            SELECT employees.id as employee_Id,name,nickname,phone_number,thumbnail,branch_id,position_id,branch.id as branch_Id,branch_name,position.id as position_Id,position_name FROM employees
            JOIN branch ON employees.branch_id = branch.id
            JOIN position ON employees.position_id = position.id
            WHERE employees.username != 'spadmin'
            ORDER BY employee_Id";
        $builder = $this->db->query($sql);
        return $builder->getResult();
    }
}
?>
