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

    public function getLandlogsAllCreated($month, $years)
    {
        $sql = "SELECT ledger_land_logs.*, DATE_FORMAT(ledger_land_logs.created_at , '%Y-%m-%d') as formatted_date
        FROM ledger_land_logs
        WHERE YEAR(ledger_land_logs.created_at) = $years AND MONTH(ledger_land_logs.created_at) = $month
        ORDER BY ledger_land_logs.created_at ASC ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
}
