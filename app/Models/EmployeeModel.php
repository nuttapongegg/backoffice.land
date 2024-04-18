<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class EmployeeModel
{

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
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getEmployeeAllNoSpAdmin()
    {
        $builder = $this->db->table('employees');

        return $builder
            ->where('id !=' , 1)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getEmployeeByID($id)
    {
        $builder = $this->db->table('employees');

        return $builder->where('id', $id)->get()->getRow();
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
        $builder = $this->db->table('employees');

        return $builder->where('id', $id)->delete();
    }

    public function getEmployee($username)
    {
        $builder = $this->db->table('employees');
        return $builder->where('username', $username)->get()->getResult();
    }
}