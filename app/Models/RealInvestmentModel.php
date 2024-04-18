<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class RealInvestmentModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getRealInvestmentAll()
    {
        $builder = $this->db->table('real_investment');

        return $builder
            ->get()
            ->getRow();
    }

    public function getRealInvestmentByID($id)
    {
        $builder = $this->db->table('real_investment');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertRealInvestment($data)
    {
        $builder = $this->db->table('real_investment');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateRealInvestmentByID($id, $data)
    {
        $builder = $this->db->table('real_investment');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteRealInvestmentByID($id)
    {
        $builder = $this->db->table('real_investment');

        return $builder->where('id', $id)->delete();
    }
}