<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class DocumentTitleListModel
{
    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getDocumentTitleListAll()
    {
        $builder = $this->db->table('document_title_lists');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getDocumentTitleListByID($id)
    {
        $builder = $this->db->table('document_title_lists');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function getDocumentTitleListByDocTypeAndTitle($docType, $title)
    {
        $builder = $this->db->table('document_title_lists');

        return $builder
            ->where('doc_type', $docType)
            ->where('title', $title)
            ->get()
            ->getRow();
    }

    public function insertDocumentTitleList($data)
    {
        $builder = $this->db->table('document_title_lists');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateDocumentTitleListByID($id, $data)
    {
        $builder = $this->db->table('document_title_lists');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteDocumentTitleListByID($id)
    {
        $builder = $this->db->table('document_title_lists');

        return $builder->where('id', $id)->delete();
    }

    public function getDocumentTitleListByDocType($docType)
    {
        $builder = $this->db->table('document_title_lists');

        return $builder
            ->where('doc_type', $docType)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getDocumentNoetListByDocType($docType,$search_value)
    {
        $sql = "SELECT note 
        FROM documents 
        WHERE doc_type = '$docType' AND note != '' AND note LIKE '%" . $search_value . "%' 
        GROUP BY note";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
}