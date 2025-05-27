<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class DocumentModel
{
    protected $db;
    protected $column_order;
    protected $column_search;
    protected $order;
    protected $column_order_account;
    protected $column_search_account;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;

        // Set orderable column fields
        $this->column_order = array('doc_number', 'doc_date',  'title', 'note', 'price', 'username', null);
        // Set searchable column fields
        $this->column_search = array('doc_number', 'doc_date', 'title', 'note', 'price', 'username');
        // Set default order
        $this->order = array('created_at' => 'DESC');

        $this->column_order_account = array(null, 'doc_type', 'doc_number','doc_date', 'title', 'note', 'land_account_name', 'price', 'doc_file', 'username');
        $this->column_search_account = array('doc_type', 'doc_number','doc_date', 'title', 'note', 'land_account_name', 'price', 'doc_file', 'username');
    }

    public function getDocumentAll()
    {
        $builder = $this->db->table('documents');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getDocumentByID($id)
    {
        $builder = $this->db->table('documents');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertDocument($data)
    {
        $builder = $this->db->table('documents');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateDocumentByID($id, $data)
    {
        $builder = $this->db->table('documents');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteDocumentByID($id)
    {
        $builder = $this->db->table('documents');

        return $builder->where('id', $id)->delete();
    }

    /*
     * Fetch admin_deposit_transfers data from the database
     * @param $_POST filter data based on the posted parameters
     */
    public function getRows($postData, $docType, $date)
    {
        $builder = $this->_get_datatables_query($postData, $docType, $date);
        if ($postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }

        return $builder->get()->getResult();
    }

    public function getRowsAccount($postData, $filter = [])
    {
        $builder = $this->_get_datatables($postData, $filter);
        if ($postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }

        return $builder->get()->getResult();
    }

    public function getSumAccount($postData, $filter = [])
    {
        // pr($postData['order'][0][0]);

        $builder = $this->_get_datatable_sum($postData, $filter);
        // if ($postData['length'] != -1) {
        //     $builder->limit($postData['length'], $postData['start']);
        // }

        return $builder->get()->getResult();
    }


    /*
     * Count all records
     */
    public function countAll()
    {
        $builder = $this->db->table('documents');

        return  $builder->countAllResults();
    }

    /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData, $docType, $date)
    {
        $builder = $this->_get_datatables_query($postData, $docType, $date);

        return $builder->countAllResults();
    }

    public function countFilteredAccount($postData, $filter = [])
    {
        $builder = $this->_get_datatables($postData, $filter);

        return $builder->countAllResults();
    }

    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    private function _get_datatables_query($postData, $docType, $date)
    {
        $builder = $this->db->table('documents');

        // อันนีเแก้วันที่ 31/12/2024 แก้วันที่จากวันที่สร้างรายการเป็นวันที่เลือก
        if ($date) {
            $dateExplode = explode(" to ", $date);
            if (array_key_exists(1, $dateExplode)) {
                $dateStart = $dateExplode[0];
                $dateEnd = $dateExplode[1];
                $builder->where('documents.doc_date >=', $dateStart);
                $builder->where('documents.doc_date <=', $dateEnd);
            } else {
                $date = $dateExplode[0];
                $builder->where('DATE(documents.doc_date) =', $date);
            }
        }

        $builder->where('documents.doc_type', $docType);

        $i = 0;
        // loop searchable columns
        foreach ($this->column_search as $item) {

            // if datatable send POST for search
            if ($postData['search']['value']) {
                // first loop
                if ($i === 0) {
                    // open bracket
                    $builder->groupStart();
                    $builder->like($item, $postData['search']['value']);
                } else {
                    $builder->orLike($item, $postData['search']['value']);
                }

                // last loop
                if (count($this->column_search) - 1 == $i) {
                    $builder->like($item, $postData['search']['value']);
                    // close bracket
                    $builder->groupEnd();
                }
            }
            $i++;
        }

        if (isset($postData['order'])) {
            $builder->orderBy($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        return $builder;
    }

    private function _get_datatables($postData, $filter = [])
    {
        $status = $filter['status'] ?? false;
        $account = $filter['account'] ?? false;
        $date = $filter['date'] ?? false;

        $builder = $this->db->table('documents');
        $sqlSelect = "
        documents.*
        ";

        $builder->select($sqlSelect);

        if ($status) {
            $where = '(';
            foreach ($status as $value) {
                $where .= "documents.doc_type = '$value' OR ";
            };
            $where = substr($where, 0, -4);
            $where .= ')';
            $builder->where($where);
        }

        if ($account) {
            $where = '(';
            foreach ($account as $value) {
                $where .= "documents.land_account_name = '$value' OR ";
            };
            $where = substr($where, 0, -4);
            $where .= ')';
            $builder->where($where);
        }

        if ($date) {
            $dateExplode = explode(" to ", $date);
            if (array_key_exists(1, $dateExplode)) {
                $dateStart = $dateExplode[0];
                $dateEnd = $dateExplode[1];
                $dateEnd = date('Y-m-d', strtotime($dateEnd . " +1 day"));
                $builder->where('documents.doc_date >=', $dateStart);
                $builder->where('documents.doc_date <=', $dateEnd);
            } else {
                $date = $dateExplode[0];
                $builder->where('DATE(documents.doc_date) =', $date);
            }
        } else {
            $builder->where('DATE(documents.doc_date) = CURDATE()');
        }

        $i = 0;
        // loop searchable columns
        foreach ($this->column_search_account as $item) {

            // if datatable send POST for search
            if ($postData['search']['value']) {
                // first loop
                if ($i === 0) {
                    // open bracket
                    $builder->groupStart();
                    $builder->like($item, $postData['search']['value']);
                } else {
                    $builder->orLike($item, $postData['search']['value']);
                }

                // last loop
                if (count($this->column_search_account) - 1 == $i) {
                    $builder->like($item, $postData['search']['value']);
                    // close bracket
                    $builder->groupEnd();
                }
            }
            $i++;
        }

        if (isset($postData['order'])) {
            $builder->orderBy($this->column_order_account[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        return $builder;
    }

    private function _get_datatable_sum($postData, $filter = [])
    {
        $status = $filter['status'] ?? false;
        $account = $filter['account'] ?? false;
        $date = $filter['date'] ?? false;

        $builder = $this->db->table('documents');

        $sqlSelect = "
        documents.*";

        $builder->select($sqlSelect);

        if ($status) {
            $where = '(';
            foreach ($status as $value) {
                $where .= "documents.doc_type = '$value' OR ";
            };
            $where = substr($where, 0, -4);
            $where .= ')';
            $builder->where($where);
        }

        if ($account) {
            $where = '(';
            foreach ($account as $value) {
                $where .= "documents.land_account_name = '$value' OR ";
            };
            $where = substr($where, 0, -4);
            $where .= ')';
            $builder->where($where);
        }

        if ($date) {
            $dateExplode = explode(" to ", $date);
            if (array_key_exists(1, $dateExplode)) {
                $dateStart = $dateExplode[0];
                $dateEnd = $dateExplode[1];
                $dateEnd = date('Y-m-d', strtotime($dateEnd . " +1 day"));
                $builder->where('documents.doc_date >=', $dateStart);
                $builder->where('documents.doc_date <=', $dateEnd);
            } else {
                $date = $dateExplode[0];
                $builder->where('DATE(documents.doc_date) =', $date);
            }
        } else {
            $builder->where('DATE(documents.doc_date) = CURDATE()');
        }

        // // อันนีเแก้วันที่ 31/12/2024 แก้วันที่จากวันที่สร้างรายการเป็นวันที่เลือก
        // if ($date) {
        //     $dateExplode = explode(" to ", $date);
        //     if (array_key_exists(1, $dateExplode)) {
        //         $dateStart = $dateExplode[0];
        //         $dateEnd = $dateExplode[1];
        //         $dateEnd = date('Y-m-d', strtotime($dateEnd . " +1 day"));
        //         $builder->where('documents.created_at >=', $dateStart);
        //         $builder->where('documents.created_at <=', $dateEnd);
        //     } else {
        //         $date = $dateExplode[0];
        //         $builder->where('DATE(documents.created_at) =', $date);
        //     }
        // } else {
        //     $builder->where('DATE(documents.created_at) = CURDATE()');
        // }

        $i = 0;
        // loop searchable columns
        foreach ($this->column_search_account as $item) {

            // if datatable send POST for search
            if ($postData['search']['value']) {
                // first loop
                if ($i === 0) {
                    // open bracket
                    $builder->groupStart();
                    $builder->like($item, $postData['search']['value']);
                } else {
                    $builder->orLike($item, $postData['search']['value']);
                }

                // last loop
                if (count($this->column_search_account) - 1 == $i) {
                    $builder->like($item, $postData['search']['value']);
                    // close bracket
                    $builder->groupEnd();
                }
            }
            $i++;
        }

        if ($postData['order'] != []) {
            $builder->orderBy($this->column_order_account[$postData['order'][0][0]], $postData['order'][0][1]);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        return $builder;
    }

    public function getDocumentByDocTypeAndCarID($docType)
    {
        $condition = '';

        if ($docType != 'ทั้งหมด') $condition = "AND doc_type = '$docType'";

        $sql = "
            SELECT *
            FROM documents
            WHERE $condition
            ORDER BY created_at DESC
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDocumentCounterByDocTypeAndCarID($docType)
    {

        $sql = "
            SELECT COUNT(id) AS counter
            FROM documents
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow()->counter;
    }

    public function getDocumentSummarizeByDocTypeAndCarID($docType)
    {
        $sql = "
            SELECT SUM(price) AS summarize
            FROM documents
            WHERE doc_type = '$docType'
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow()->summarize;
    }

    public function getDocumentCounterByDocTypeAndCarIDAndCustomerID($docType)
    {

        $sql = "
            SELECT COUNT(id) AS counter
            FROM documents
            WHERE doc_type = '$docType'
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow()->counter;
    }

    public function getDocumentSummarize($data)
    {

        $sql = "
            SELECT SUM(price) AS summarize
            FROM documents
            WHERE doc_type = '{$data['doc_type']}' AND title != 'ราคาตัวรถ'
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow()->summarize;
    }

    public function getDocumentSummarizeSpecifically($data)
    {

        $condition = '';

        if (array_key_exists("client_id", $data)) $condition = " AND customer_id = {$data['client_id']}";

        $sql = "
            SELECT SUM(price) AS summarize
            FROM documents
            WHERE doc_type = '{$data['doc_type']}' AND title = '{$data['title']}' AND $condition
        ";

        $builder = $this->db->query($sql);

        return $builder->getRow()->summarize;
    }

    public function getCapitalReportTimeSearch($param)
    {
        // $data_min_capotal = $param['data_min_capotal'];
        // $data_max_capotal = $param['data_max_capotal'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];
        $date = $param['data'];
        if ($date) {
            $dateExplode = explode(" to ", $date);
            if (array_key_exists(1, $dateExplode)) {
                $dateStart = $dateExplode[0];
                $dateEnd = $dateExplode[1];
            } else {
                $dateStart = $dateExplode[0];
                $dateEnd = $dateExplode[0];
            }
        }

        $sql = "SELECT 
        car_stock.id,car_stock.car_stock_code,car_stock.car_stock_img, car_stock_detail_buy.car_stock_detail_buy_fiance_total, car_stock_detail_buy.car_stock_detail_buy_sale_on_web, 
        CONCAT(car_stock_owner.car_stock_owner_car_name,'/',car_stock_owner.car_stock_owner_car_vin,' ',car_stock_owner.car_stock_owner_car_brand,' ',car_stock_owner.car_stock_owner_car_model) as car_name,
        DATE_FORMAT(car_stock.car_stock_created_at , '%Y-%m-%d') as formatted_date,
        (SELECT SUM(documents.price) FROM documents WHERE documents.doc_type = 'ใบสำคัญจ่าย' AND documents.car_stock_id = car_stock.id AND car_stock_detail_buy.car_stock_detail_buy_car_build_status != 'ตัดปล่อยรถ') AS totalcost ,
        (SELECT SUM(documents.price) FROM documents WHERE documents.doc_type = 'ใบส่วนลด' AND documents.car_stock_id = car_stock.id ) AS totaldiscount,
        (SELECT SUM(documents.price) FROM documents WHERE documents.doc_type = 'ใบสำคัญรับ' AND documents.car_stock_id = car_stock.id AND car_stock_detail_buy.car_stock_detail_buy_car_build_status != 'ตัดปล่อยรถ') AS accountcost
        FROM car_stock
        JOIN car_stock_owner ON car_stock.car_stock_code = car_stock_owner.car_stock_owner_code
        JOIN car_stock_detail_buy on car_stock.car_stock_code = car_stock_detail_buy.car_stock_detail_buy_code
        WHERE (SELECT SUM(documents.price) FROM documents WHERE documents.doc_type = 'ใบสำคัญจ่าย' AND documents.car_stock_id = car_stock.id) != 'NULL' 
        AND car_stock_detail_buy.car_stock_detail_buy_car_build_status != 'ตัดปล่อยรถ' AND car_stock_detail_buy.car_stock_detail_buy_car_build_status != 'ยกเลิก'
        AND car_stock_created_at >= '$dateStart' AND car_stock_created_at <= '$dateEnd' AND 
        ((car_stock_owner_car_name like '%" . $search_value . "%') OR (car_stock_owner_car_vin like '%" . $search_value . "%') OR (car_stock_owner_car_brand like '%" . $search_value . "%') OR (car_stock_owner_car_model like '%" . $search_value . "%') OR (car_stock_car_year like '%" . $search_value . "%') OR (car_stock_created_at like '%" . $search_value . "%'))
        limit $start, $length
        ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getCapitalReportTimeSearchcount($param)
    {
        // $data_min_capotal = $param['data_min_capotal'];
        // $data_max_capotal = $param['data_max_capotal'];
        $search_value = $param['search_value'];
        $date = $param['data'];
        if ($date) {
            $dateExplode = explode(" to ", $date);
            if (array_key_exists(1, $dateExplode)) {
                $dateStart = $dateExplode[0];
                $dateEnd = $dateExplode[1];
            } else {
                $dateStart = $dateExplode[0];
                $dateEnd = $dateExplode[0];
            }
        }

        $sql = "SELECT 
        car_stock.id,car_stock.car_stock_code,car_stock.car_stock_img, car_stock_detail_buy.car_stock_detail_buy_fiance_total, car_stock_detail_buy.car_stock_detail_buy_sale_on_web, 
        CONCAT(car_stock_owner.car_stock_owner_car_name,'/',car_stock_owner.car_stock_owner_car_vin,' ',car_stock_owner.car_stock_owner_car_brand,' ',car_stock_owner.car_stock_owner_car_model) as car_name,
        DATE_FORMAT(car_stock.car_stock_created_at , '%Y-%m-%d') as formatted_date,
        (SELECT SUM(documents.price) FROM documents WHERE documents.doc_type = 'ใบสำคัญจ่าย' AND documents.car_stock_id = car_stock.id AND car_stock_detail_buy.car_stock_detail_buy_car_build_status != 'ตัดปล่อยรถ') AS totalcost ,
        (SELECT SUM(documents.price) FROM documents WHERE documents.doc_type = 'ใบส่วนลด' AND documents.car_stock_id = car_stock.id ) AS totaldiscount,
        (SELECT SUM(documents.price) FROM documents WHERE documents.doc_type = 'ใบสำคัญรับ' AND documents.car_stock_id = car_stock.id AND car_stock_detail_buy.car_stock_detail_buy_car_build_status != 'ตัดปล่อยรถ') AS accountcost
        FROM car_stock
        JOIN car_stock_owner ON car_stock.car_stock_code = car_stock_owner.car_stock_owner_code
        JOIN car_stock_detail_buy on car_stock.car_stock_code = car_stock_detail_buy.car_stock_detail_buy_code
        WHERE (SELECT SUM(documents.price) FROM documents WHERE documents.doc_type = 'ใบสำคัญจ่าย' AND documents.car_stock_id = car_stock.id) != 'NULL' 
        AND car_stock_detail_buy.car_stock_detail_buy_car_build_status != 'ตัดปล่อยรถ' AND car_stock_detail_buy.car_stock_detail_buy_car_build_status != 'ยกเลิก'
        AND car_stock_created_at >= '$dateStart' AND car_stock_created_at <= '$dateEnd' AND 
        ((car_stock_owner_car_name like '%" . $search_value . "%') OR (car_stock_owner_car_vin like '%" . $search_value . "%') OR (car_stock_owner_car_brand like '%" . $search_value . "%') OR (car_stock_owner_car_model like '%" . $search_value . "%') OR (car_stock_car_year like '%" . $search_value . "%') OR (car_stock_created_at like '%" . $search_value . "%'))
        ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getprofit($year)
    {
        $sql = "SELECT MONTH(car_stock.date_to_sold_out) as doc_month , documents.doc_type , SUM(price) as doc_sum_price,
           
        (SELECT SUM(price) FROM documents 
         JOIN bookings ON documents.car_stock_id = bookings.car_stock_id
         JOIN car_stock ON bookings.car_stock_id = car_stock.id
         WHERE documents.doc_type = 'ใบสำคัญจ่าย' AND YEAR(car_stock.date_to_sold_out) = $year AND bookings.status = 'ตัดปล่อยรถ')AS total_capital,
         (SELECT SUM(price) FROM documents 
         JOIN bookings ON documents.car_stock_id = bookings.car_stock_id
         JOIN car_stock ON bookings.car_stock_id = car_stock.id
         WHERE documents.doc_type = 'ใบส่วนลด'  AND YEAR(car_stock.date_to_sold_out) = $year AND bookings.status = 'ตัดปล่อยรถ')AS total_discount,
         (SELECT SUM(price) FROM documents
         JOIN bookings ON documents.car_stock_id = bookings.car_stock_id
         JOIN car_stock ON bookings.car_stock_id = car_stock.id
         WHERE documents.doc_type = 'ใบสำคัญรับ' AND YEAR(car_stock.date_to_sold_out) = $year AND bookings.status = 'ตัดปล่อยรถ') AS total_revenue
         
         FROM bookings
         JOIN documents ON bookings.car_stock_id = documents.car_stock_id
         JOIN car_stock ON bookings.car_stock_id = car_stock.id
         WHERE YEAR(car_stock.date_to_sold_out) = $year AND bookings.status = 'ตัดปล่อยรถ'
         GROUP BY MONTH(car_stock.date_to_sold_out), documents.doc_type;";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDocumentsTypeMonth($year)
    {
        $sql = "SELECT MONTH(car_stock.date_to_sold_out) as doc_month , documents.doc_type , SUM(price) as doc_sum_price
         FROM bookings
         JOIN documents ON bookings.car_stock_id = documents.car_stock_id
         JOIN car_stock ON bookings.car_stock_id = car_stock.id
         WHERE YEAR(car_stock.date_to_sold_out) = $year AND bookings.status = 'ตัดปล่อยรถ'
         GROUP BY MONTH(car_stock.date_to_sold_out), documents.doc_type;";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDocumentspaymonth($year)
    {
        $sql = "
        SELECT MONTH(documents.doc_date) as doc_month_pay,documents.doc_type ,SUM(price) as doc_sum_pay
        FROM documents
        WHERE YEAR(documents.doc_date) = $year AND documents.doc_type = 'ใบสำคัญจ่าย'
        GROUP BY MONTH(documents.doc_date), documents.doc_type
        ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getrevenue($year)
    {
        $sql = "SELECT MONTH(doc_date) as doc_month , doc_type , SUM(price) as doc_sum_price
        FROM `documents`
        WHERE YEAR(documents.doc_date) = $year AND (doc_type = 'ใบสำคัญรับ' OR doc_type = 'ใบสำคัญจ่าย')
        GROUP BY MONTH(doc_date), doc_type
        ORDER BY MONTH(doc_date)";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDocumentsPayMonthAll($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "
        SELECT DATE_FORMAT(documents.doc_date, '%d-%m-%Y') as formatted_date ,documents.doc_type , documents.price , documents.title
        FROM documents
        WHERE YEAR(documents.doc_date) = $years AND MONTH(documents.doc_date) = $month AND documents.doc_type = 'รายจ่าย'
        ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDocumentsPayMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT *
        FROM documents
        WHERE YEAR(documents.doc_date) = $years AND MONTH(documents.doc_date) = $month AND documents.doc_type = 'ใบสำคัญจ่าย'
        ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDocumentsPayMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT *
        FROM documents
        WHERE YEAR(documents.doc_date) = $years AND MONTH(documents.doc_date) = $month AND documents.doc_type = 'ใบสำคัญจ่าย'
        ORDER BY documents.doc_date ASC LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDocumentsPayMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT *
        FROM documents
        WHERE YEAR(documents.doc_date) = $years AND MONTH(documents.doc_date) = $month AND documents.doc_type = 'ใบสำคัญจ่าย'
        and ((documents.doc_number like '%" . $search_value . "%') OR (documents.doc_date like '%" . $search_value . "%') OR (documents.username like '%" . $search_value . "%')
           OR (documents.title like '%" . $search_value . "%') OR (documents.price like '%" . $search_value . "%') OR (documents.cash_flow_name like '%" . $search_value . "%') OR (documents.note like '%" . $search_value . "%')) 
           ORDER BY documents.doc_date ASC LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDocumentsPayMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT *
        FROM documents
        WHERE YEAR(documents.doc_date) = $years AND MONTH(documents.doc_date) = $month AND documents.doc_type = 'ใบสำคัญจ่าย'
        and ((documents.doc_number like '%" . $search_value . "%') OR (documents.doc_date like '%" . $search_value . "%') OR (documents.username like '%" . $search_value . "%')
           OR (documents.title like '%" . $search_value . "%') OR (documents.price like '%" . $search_value . "%') OR (documents.cash_flow_name like '%" . $search_value . "%') OR (documents.note like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDocumentID($id)
    {
        $sql = "SELECT *,DATE_FORMAT(documents.doc_date, '%d/%m/%Y') as formatted_date_doc,DATE_FORMAT(documents.cheque_bank_date, '%d/%m/%Y') as formatted_date_rebate
        FROM documents
        WHERE documents.id = $id
        ";
        $builder = $this->db->query($sql);

        return $builder->getRow();
    }

    public function getDocumentByType($type)
    {
        $sql = "SELECT *,YEAR(documents.created_at) as years , MONTH(documents.created_at) as months
        FROM `documents`
        WHERE documents.doc_type = '$type'
        ORDER BY documents.created_at DESC LIMIT 1";
        $builder = $this->db->query($sql);

        return $builder->getRow();
    }

    public function getDocumentsTypePay()
    {
        $sql = "
        SELECT SUM(price) as doc_sum_price_pay
        FROM documents
        WHERE documents.doc_type = 'รายจ่าย'
        ";
        $builder = $this->db->query($sql);

        return $builder->getRow();
    }

    public function getSumAccountAPI()
    {
        $sql = "SELECT documents.*
                FROM documents
                WHERE DATE(documents.created_at) = CURDATE()";

        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDocumentsReceiptMonthCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];

        $sql = "SELECT *
        FROM documents
        WHERE YEAR(documents.doc_date) = $years AND MONTH(documents.doc_date) = $month AND documents.doc_type = 'ใบสำคัญรับ'
        ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDocumentsReceiptMonth($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT *
        FROM documents
        WHERE YEAR(documents.doc_date) = $years AND MONTH(documents.doc_date) = $month AND documents.doc_type = 'ใบสำคัญรับ'
        ORDER BY documents.doc_date ASC LIMIT $start, $length";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDocumentsReceiptMonthSearch($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $start = $param['start'];
        $length = $param['length'];

        $sql = "SELECT *
        FROM documents
        WHERE YEAR(documents.doc_date) = $years AND MONTH(documents.doc_date) = $month AND documents.doc_type = 'ใบสำคัญรับ'
        and ((documents.doc_number like '%" . $search_value . "%') OR (documents.doc_date like '%" . $search_value . "%') OR (documents.username like '%" . $search_value . "%')
           OR (documents.title like '%" . $search_value . "%') OR (documents.price like '%" . $search_value . "%') OR (documents.cash_flow_name like '%" . $search_value . "%') OR (documents.note like '%" . $search_value . "%')) 
           ORDER BY documents.doc_date ASC LIMIT $start, $length
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }

    public function getDataTableDocumentsReceiptMonthSearchCount($param)
    {
        $month = $param['month'];
        $years = $param['years'];
        $search_value = $param['search_value'];
        $sql = "SELECT *
        FROM documents
        WHERE YEAR(documents.doc_date) = $years AND MONTH(documents.doc_date) = $month AND documents.doc_type = 'ใบสำคัญรับ'
        and ((documents.doc_number like '%" . $search_value . "%') OR (documents.doc_date like '%" . $search_value . "%') OR (documents.username like '%" . $search_value . "%')
           OR (documents.title like '%" . $search_value . "%') OR (documents.price like '%" . $search_value . "%') OR (documents.cash_flow_name like '%" . $search_value . "%') OR (documents.note like '%" . $search_value . "%'))
            ";
        $builder = $this->db->query($sql);

        return $builder->getResult();
    }
}
