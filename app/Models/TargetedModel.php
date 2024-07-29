<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class TargetedModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getTargetedAll()
    {
        $builder = $this->db->table('targeted');

        return $builder
            ->get()
            ->getRow();
    }

    public function getTargetedByID($id)
    {
        $builder = $this->db->table('targeted');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertTargeted($data)
    {
        $builder = $this->db->table('targeted');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateTargetedByID($id, $data)
    {
        $builder = $this->db->table('targeted');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteTargetedByID($id)
    {
        $builder = $this->db->table('targeted');

        return $builder->where('id', $id)->delete();
    }
}