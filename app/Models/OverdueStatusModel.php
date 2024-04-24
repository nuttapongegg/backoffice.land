<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class OverdueStatusModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getOverdueStatusAll()
    {
        $builder = $this->db->table('overdue_status');

        return $builder->get()->getRow();
    }

    public function getOverdueStatusByID($id)
    {
        $builder = $this->db->table('overdue_status');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertOverdueStatus($data)
    {
        $builder = $this->db->table('overdue_status');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateOverdueStatus($data)
    {
        $builder = $this->db->table('overdue_status');

        return $builder->update($data);
    }

    public function deleteOverdueStatus($id)
    {
        $builder = $this->db->table('overdue_status');

        return $builder->where('id', $id)->delete();
    }
}