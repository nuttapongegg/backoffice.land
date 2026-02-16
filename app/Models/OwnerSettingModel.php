<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class OwnerSettingModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getOwnerSettingAll()
    {
        $builder = $this->db->table('owner_setting');

        return $builder
            ->get()
            ->getRow();
    }

    public function getOwnerSettingByID($id)
    {
        $builder = $this->db->table('owner_setting');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertOwnerSetting($data)
    {
        $builder = $this->db->table('owner_setting');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateOwnerSettingByID($id, $data)
    {
        $builder = $this->db->table('owner_setting');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteOwnerSettingByID($id)
    {
        $builder = $this->db->table('owner_setting');

        return $builder->where('id', $id)->delete();
    }
}