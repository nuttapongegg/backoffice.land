<?php

namespace App\Models;

class LandlogsModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getLandlogsAll()
    {
        $builder = $this->db->table('ledger_land_logs');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getLandlogsByID($id)
    {
        $builder = $this->db->table('ledger_land_logs');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertLandlogs($data)
    {
        $builder = $this->db->table('ledger_land_logs');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateLandlogsByID($id, $data)
    {
        $builder = $this->db->table('ledger_land_logs');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteLandlogsByID($id)
    {
        $sql = "DELETE FROM ledger_land_logs WHERE id = $id";

        $builder = $this->db->query($sql);

        return $builder;
    }
}
