<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Aws\S3\S3Client;

use App\Models\DocumentModel;
use App\Models\DocumentTitleListModel;
use App\Models\SettingLandModel;

use Smalot\PdfParser\Parser;

class Document extends BaseController
{
    protected $DocumentModel;
    protected $DocumentTitleListModel;
    protected $SettingLandModel;

    protected $s3_bucket;
    protected $s3_secret_key;
    protected $s3_key;
    protected $s3_endpoint;
    protected $s3_region;

    public function __construct()
    {
        /*
        | -------------------------------------------------------------------------
        | SET ENVIRONMENT
        | -------------------------------------------------------------------------
        */

        /*
        | -------------------------------------------------------------------------
        | SET UTILITIES
        | -------------------------------------------------------------------------
        */

        // Model
        $this->DocumentModel = new DocumentModel();
        $this->DocumentTitleListModel = new DocumentTitleListModel();
        $this->SettingLandModel = new SettingLandModel();

        // S3 Configuration
        $this->s3_bucket = getenv('S3_BUCKET');
        $this->s3_secret_key = getenv('SECRET_KEY');
        $this->s3_key = getenv('KEY');
        $this->s3_endpoint = getenv('ENDPOINT');
        $this->s3_region = getenv('REGION');
    }

    /***************************************************************************
     * MAIN
     */

    public function getLists()
    {
        $data = $row = array();

        // Fetch member's records
        $date = $this->request->getVar('date');
        $docType = $this->request->getVar('docType');

        $memData = $this->DocumentModel->getRows($_POST, $docType, $date);

        $i = $_POST['start'];

        foreach ($memData as $document) {

            $i++;

            if ($document->filePath != '') {
                $filePath = '<a target="_blank" href="' . getenv('CDN_IMG') . '/uploads/file_loan/' . $document->filePath . '" class="dropdown-item"><i class="fe fe-file"></i> ดูไฟล์ที่แนบ</a>';
            } else {
                $filePath = '';
            }

            if ($document->doc_file != '') {
                $doc_file = '<a target="_blank" href="' . getenv('CDN_IMG') . '/uploads/file_loan/' . $document->doc_file . '" class="dropdown-item"><i class="fe fe-file"></i> ดูไฟล์ Ai AutoInput</a> ';
            } else {
                $doc_file = '';
            }

            $button = '
                    <button data-bs-toggle="dropdown" class="btn btn-primary mb-1 btn-sm">จัดการ <i class="icon ion-ios-arrow-down tx-11 mg-s-3"></i></button>
                    <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item btnEdit" data-doc-type-="' . $document->doc_type . '" data-doc-id="' . hashidsEncrypt($document->id) . '"><i class="fe fe-edit"></i> แก้ไขข้อมูล</a>
                        ' . $doc_file . '
                        ' . $filePath . '
                        <a href="javascript:void(0)" class="dropdown-item btnDelete" data-doc-type-="' . $document->doc_type . '" data-doc-id="' . hashidsEncrypt($document->id) . '" data-url="' . base_url('/document/delete/' . hashidsEncrypt($document->id)) . '" data-document-number="' . $document->doc_number . '"><i class="fe fe-trash"></i> ลบข้อมูล</a>
                    </div>
                ';
            $price = '<span class="tx-success">' . number_format($document->price, 2) . '</span>';

            $data[] = array(
                $document->doc_number,
                $document->doc_date,
                $document->title,
                $document->note,
                $price,
                $document->username,
                $button
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->DocumentModel->countAll(),
            "recordsFiltered" => $this->DocumentModel->countFiltered($_POST, $docType, $date),
            "data" => $data,
        );

        // Output to JSON format
        echo json_encode($output);
    }

    public function store()
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            // HANDLE REQUEST
            $docType = $this->request->getVar('doc_type');
            $landAccountName = $this->request->getVar('land_account_name');
            $inputTitle = $this->request->getVar('title');
            $file = '';
            $imgFile = '';

            // เช็ครายการว่ามีในดาต้าเบสหรือป่าว ถ้ามีนำมาใช้งาน
            $title = $this->DocumentTitleListModel->getDocumentTitleListByDocTypeAndTitle($docType, $inputTitle);
            $settinglands =  $this->SettingLandModel->getSettingLandAll();

            // กรณีที่ไม่มี ให้สร้างใหม่
            if (!$title) {

                $lastID = $this->DocumentTitleListModel->insertDocumentTitleList([
                    'doc_type' => $docType,
                    'title' => $inputTitle
                ]);

                $title = $this->DocumentTitleListModel->getDocumentTitleListByID($lastID);
            }

            if (isset($_FILES['file']) && $_FILES['file']['name'] != '') {
                $file = $this->ddoo_upload_file($this->request->getFile('file'));
            }

            if (isset($_FILES['imageFileInvoice']) && $_FILES['imageFileInvoice']['name'] != '') {
                $imgFile = $this->ddoo_upload_file($this->request->getFile('imageFileInvoice'));
            }

            $data = [
                'doc_type' => $this->request->getVar('doc_type'),
                'doc_number' => $this->request->getVar('doc_number'),
                'doc_date' => $this->request->getVar('doc_date'),
                'title' => $title->title,
                'price' => str_replace(',', '', $this->request->getVar('price')),
                'cash_flow_name' => $this->request->getVar('land_account_name'),
                'employee_id' => session()->get('employeeID'),
                'username' => session()->get('username'),
                'doc_file' => $imgFile,
                'filePath' => $file,
                'note' => $this->request->getVar('note'),
            ];

            switch ($docType) {
                case 'ใบสำคัญรับ':
                    foreach ($settinglands as $settingland) {
                        if ($landAccountName == $settingland->land_account_name) {
                            $price = str_replace(',', '', $this->request->getVar('price'));
                            $land_account_cash_receipt = $settingland->land_account_cash + floatval($price);

                            $this->SettingLandModel->updateSettingLandByName($settingland->land_account_name, [
                                'land_account_cash' => $land_account_cash_receipt,
                                // 'updated_at' => date('Y-m-d H:i:s'),
                            ]);

                            $detail = 'ใบสำคัญรับ' . '(' . $title->title . ')';
                            $this->SettingLandModel->insertSettingLandReport([
                                'setting_land_id' => $settingland->id,
                                'setting_land_report_detail' => $detail,
                                'setting_land_report_money' => $price,
                                'setting_land_report_note' => $this->request->getVar('note'),
                                'setting_land_report_account_balance' => $land_account_cash_receipt,
                                'employee_id' => session()->get('employeeID'),
                                'employee_name' => session()->get('employee_fullname')
                            ]);
                        }
                    }
                    break;

                case 'ใบสำคัญจ่าย':
                    foreach ($settinglands as $settingland) {
                        if ($landAccountName == $settingland->land_account_name) {
                            $price = str_replace(',', '', $this->request->getVar('price'));
                            $land_account_cash_receipt = $settingland->land_account_cash - floatval($price);
                            $this->SettingLandModel->updateSettingLandByName($settingland->land_account_name, [
                                'land_account_cash' => $land_account_cash_receipt,
                                // 'updated_at' => date('Y-m-d H:i:s'),
                            ]);

                            $detail = 'ใบสำคัญจ่าย' . '(' . $title->title . ')';
                            $this->SettingLandModel->insertSettingLandReport([
                                'setting_land_id' => $settingland->id,
                                'setting_land_report_detail' => $detail,
                                'setting_land_report_money' => $price,
                                'setting_land_report_note' => $this->request->getVar('note'),
                                'setting_land_report_account_balance' => $land_account_cash_receipt,
                                'employee_id' => session()->get('employeeID'),
                                'employee_name' => session()->get('employee_fullname')
                            ]);
                        }
                    }
                    break;
            }
            
            $create = $this->DocumentModel->insertDocument($data);
            if ($create) {
                
                // //pusher add
                // $pusher = getPusher();
                // $pusher->trigger('color_Status', 'event', [
                //     'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                //     'event' => 'status_Blue',
                //     'title' => session()->get('username') . " : " . 'ทำการเพิ่ม' . $docType
                // ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'เพิ่ม',
                    'detail' => '[เพิ่ม] ' . $docType,
                    'ip' => $this->request->getIPAddress()
                ]);

                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'เพิ่ม '  . $docType . ' สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'เพิ่ม ' . $docType . ' ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function edit($id)
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            // HANDLE REQUEST
            $document = $this->DocumentModel->getDocumentByID(hashidsDecrypt($id));

            if ($document) {

                $document->id = hashidsEncrypt($document->id);
                $response['data'] = $document;

                $status = 200;
                $response['success'] = 1;
                $response['message'] = '';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function update()
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            // HANDLE REQUEST
            $doc = $this->DocumentModel->getDocumentByID(hashidsDecrypt($this->request->getVar('doc_id')));
            $settinglands =  $this->SettingLandModel->getSettingLandAll();
            $docType = $this->request->getVar('doc_type');
            $landAccountName = $this->request->getVar('land_account_name');
            $inputTitle = $this->request->getVar('title');
            $docfile = $doc->doc_file;
            $file = $doc->filePath;
            $doc_price = $doc->price;
            $doc_setting_land_name = $doc->cash_flow_name;

            if ($doc->cash_flow_name != $landAccountName || $doc_price != str_replace(',', '', $this->request->getVar('price'))) {

                //ลบ setting_land ก่อนหน้า เพื่ออัพเดทใหม่
                switch ($doc->doc_type) {
                    case 'ใบสำคัญรับ':
                        foreach ($settinglands as $settingland) {
                            if ($doc_setting_land_name == $settingland->land_account_name) {
                                $land_account_cash_receipt_edit = $settingland->land_account_cash - floatval($doc_price);

                                $this->SettingLandModel->updateSettingLandByName($settingland->land_account_name, [
                                    'land_account_cash' => $land_account_cash_receipt_edit,
                                ]);

                                $detail = 'ลบใบสำคัญรับ' . '(' . $doc->title . ')';
                                $note = 'ทำการแก้ไขรายการ ใบสำคัญรับ' . '(' . $doc->title . ') ทำการแก้ไขจากบัญชี ' . $doc->cash_flow_name . ' จำนวนเงิน ' . number_format($doc_price, 2) . ' แก้ไขเป็น บัญชี ' . $landAccountName . ' จำนวนเงิน ' . number_format(str_replace(',', '', $this->request->getVar('price')), 2);
                                $this->SettingLandModel->insertSettingLandReport([
                                    'setting_land_id' => $settingland->id,
                                    'setting_land_report_detail' => $detail,
                                    'setting_land_report_money' => $doc_price,
                                    'setting_land_report_note' => $note,
                                    'setting_land_report_account_balance' => $land_account_cash_receipt_edit,
                                    'employee_id' => session()->get('employeeID'),
                                    'employee_name' => session()->get('employee_fullname')
                                ]);
                            }
                        }
                        sleep(1);
                        break;
                    case 'ใบสำคัญจ่าย':
                        foreach ($settinglands as $settingland) {
                            if ($doc_setting_land_name == $settingland->land_account_name) {
                                $land_account_cash_receipt_edit = $settingland->land_account_cash + floatval($doc_price);

                                $this->SettingLandModel->updateSettingLandByName($settingland->land_account_name, [
                                    'land_account_cash' => $land_account_cash_receipt_edit,
                                ]);

                                $detail = 'ลบใบสำคัญจ่าย' . '(' . $doc->title . ')';
                                $note = 'แก้ไขรายการ ใบสำคัญจ่าย' . '(' . $doc->title . ') ทำการแก้ไขจากบัญชี ' . $doc->cash_flow_name . 'จำนวนเงิน ' . number_format($doc_price, 2) . ' แก้ไขเป็น บัญชี ' . $landAccountName . ' จำนวนเงิน ' . number_format(str_replace(',', '', $this->request->getVar('price')), 2);
                                $this->SettingLandModel->insertSettingLandReport([
                                    'setting_land_id' => $settingland->id,
                                    'setting_land_report_detail' => $detail,
                                    'setting_land_report_money' => $doc_price,
                                    'setting_land_report_note' => $note,
                                    'setting_land_report_account_balance' => $land_account_cash_receipt_edit,
                                    'employee_id' => session()->get('employeeID'),
                                    'employee_name' => session()->get('employee_fullname')
                                ]);
                            }
                        }
                        sleep(1);
                        break;
                }
            }

            // เช็ครายการว่ามีในดาต้าเบสหรือป่าว ถ้ามีนำมาใช้งาน
            $title = $this->DocumentTitleListModel->getDocumentTitleListByDocTypeAndTitle($docType, $inputTitle);
            $settinglands_edit =  $this->SettingLandModel->getSettingLandAll();

            // กรณีที่ไม่มี ให้สร้างใหม่
            if (!$title) {

                $lastID = $this->DocumentTitleListModel->insertDocumentTitleList([
                    'doc_type' => $docType,
                    'title' => $inputTitle
                ]);

                $title = $this->DocumentTitleListModel->getDocumentTitleListByID($lastID);
            }

            if (isset($_FILES['file']) && $_FILES['file']['name'] != '') {
                $file = $this->ddoo_upload_file($this->request->getFile('file'));
            }

            if (isset($_FILES['imageFileInvoice']) && $_FILES['imageFileInvoice']['name'] != '') {
                // ถ้ามีการอัปโหลดไฟล์ใหม่ ใช้ไฟล์ที่อัปโหลด
                $docfile = $this->ddoo_upload_file($this->request->getFile('imageFileInvoice'));
            }

            $data = [
                'doc_type' => $this->request->getVar('doc_type'),
                //                'doc_number' => $this->request->getVar('doc_number'),
                'doc_date' => $this->request->getVar('doc_date'),
                'title' => $title->title,
                'price' => str_replace(',', '', $this->request->getVar('price')),
                'cash_flow_name' => $this->request->getVar('land_account_name'),
                'doc_file' => $docfile,
                'filePath' => $file,
                'note' => $this->request->getVar('note'),
                'note' => $this->request->getVar('note'),
            ];

            switch ($docType) {
                case 'ใบสำคัญรับ':
                    if ($doc->cash_flow_name != $landAccountName || $doc_price != str_replace(',', '', $this->request->getVar('price'))) {
                        foreach ($settinglands_edit as $settingland_edit) {
                            if ($landAccountName == $settingland_edit->land_account_name) {
                                $price = str_replace(',', '', $this->request->getVar('price'));
                                $land_account_cash_receipt = $settingland_edit->land_account_cash + floatval($price);

                                $this->SettingLandModel->updateSettingLandByName($settingland_edit->land_account_name, [
                                    'land_account_cash' => $land_account_cash_receipt,
                                    // 'updated_at' => date('Y-m-d H:i:s'),
                                ]);

                                $detail = 'เพิ่มรายการแก้ไขใบสำคัญรับ' . '(' . $title->title . ') แก้ไขจากบัญชี ' . $doc->cash_flow_name . 'จำนวนเงิน ' . number_format($doc_price, 2) . ' แก้ไขเป็น บัญชี ' . $landAccountName . ' จำนวนเงิน ' . number_format($price, 2);
                                $this->SettingLandModel->insertSettingLandReport([
                                    'setting_land_id' => $settingland_edit->id,
                                    'setting_land_report_detail' => $detail,
                                    'setting_land_report_money' => $price,
                                    'setting_land_report_note' => $this->request->getVar('note'),
                                    'setting_land_report_account_balance' => $land_account_cash_receipt,
                                    'employee_id' => session()->get('employeeID'),
                                    'employee_name' => session()->get('employee_fullname')
                                ]);
                            }
                        }
                    }
                    break;

                case 'ใบสำคัญจ่าย':
                    if ($doc->cash_flow_name != $landAccountName || $doc_price != str_replace(',', '', $this->request->getVar('price'))) {
                        foreach ($settinglands_edit as $settingland_edit) {
                            if ($landAccountName == $settingland_edit->land_account_name) {
                                $price = str_replace(',', '', $this->request->getVar('price'));
                                $land_account_cash_receipt = $settingland_edit->land_account_cash - floatval($price);

                                $this->SettingLandModel->updateSettingLandByName($settingland_edit->land_account_name, [
                                    'land_account_cash' => $land_account_cash_receipt,
                                    // 'updated_at' => date('Y-m-d H:i:s'),
                                ]);

                                $detail = 'เพิ่มรายการแก้ไขใบสำคัญจ่าย' . '(' . $title->title . ') แก้ไขจากบัญชี ' . $doc->cash_flow_name . 'จำนวนเงิน ' . number_format($doc_price, 2) . ' แก้ไขเป็น บัญชี ' . $landAccountName . ' จำนวนเงิน ' . number_format($price, 2);
                                $this->SettingLandModel->insertSettingLandReport([
                                    'setting_land_id' => $settingland_edit->id,
                                    'setting_land_report_detail' => $detail,
                                    'setting_land_report_money' => $price,
                                    'setting_land_report_note' => $this->request->getVar('note'),
                                    'setting_land_report_account_balance' => $land_account_cash_receipt,
                                    'employee_id' => session()->get('employeeID'),
                                    'employee_name' => session()->get('employee_fullname')
                                ]);
                            }
                        }
                    }
                    break;
            }

            $update = $this->DocumentModel->updateDocumentByID($doc->id, $data);

            if ($update) {

                // //pusher edit
                // $pusher = getPusher();
                // $pusher->trigger('color_Status', 'event', [
                //     'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                //     'event' => 'status_Yellow',
                //     'title' => session()->get('username') . " : " . 'ทำการแก้ไข' . $docType
                // ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] ' . $docType,
                    'ip' => $this->request->getIPAddress()
                ]);

                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข '  . $docType . ' สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข ' . $docType . ' ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function delete($id)
    {
        $status = 500;
        $response['success'] = 0;

        $document = $this->DocumentModel->getDocumentByID(hashidsDecrypt($id));
        $settinglands =  $this->SettingLandModel->getSettingLandAll();

        if ($document) {

            //ลบ setting_land
            $doc_price = $document->price;
            $doc_setting_land_name = $document->cash_flow_name;
            switch ($document->doc_type) {
                case 'ใบสำคัญรับ':
                    foreach ($settinglands as $settingland) {
                        if ($doc_setting_land_name == $settingland->land_account_name) {
                            $land_account_cash_receipt_edit = $settingland->land_account_cash - floatval($doc_price);

                            $this->SettingLandModel->updateSettingLandByName($settingland->land_account_name, [
                                'land_account_cash' => $land_account_cash_receipt_edit,
                            ]);

                            $detail = 'ลบใบสำคัญรับ' . '(' . $document->title . ')';
                            $this->SettingLandModel->insertSettingLandReport([
                                'setting_land_id' => $settingland->id,
                                'setting_land_report_detail' => $detail,
                                'setting_land_report_money' => $doc_price,
                                'setting_land_report_note' => $document->note,
                                'setting_land_report_account_balance' => $land_account_cash_receipt_edit,
                                'employee_id' => session()->get('employeeID'),
                                'employee_name' => session()->get('employee_fullname')
                            ]);
                        }
                    }
                    break;
                case 'ใบสำคัญจ่าย':
                    foreach ($settinglands as $settingland) {
                        if ($doc_setting_land_name == $settingland->land_account_name) {
                            $land_account_cash_receipt_edit = $settingland->land_account_cash + floatval($doc_price);

                            $this->SettingLandModel->updateSettingLandByName($settingland->land_account_name, [
                                'land_account_cash' => $land_account_cash_receipt_edit,
                            ]);

                            $detail = 'ลบใบสำคัญจ่าย' . '(' . $document->title . ')';
                            $this->SettingLandModel->insertSettingLandReport([
                                'setting_land_id' => $settingland->id,
                                'setting_land_report_detail' => $detail,
                                'setting_land_report_money' => $doc_price,
                                'setting_land_report_note' => $document->note,
                                'setting_land_report_account_balance' => $land_account_cash_receipt_edit,
                                'employee_id' => session()->get('employeeID'),
                                'employee_name' => session()->get('employee_fullname')
                            ]);
                        }
                    }
                    break;
            }

            $this->DocumentModel->deleteDocumentByID($document->id);

            // // pusher Delete
            // $pusher = getPusher();
            // $pusher->trigger('color_Status', 'event', [
            //     'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
            //     'event' => 'status_Red',
            //     'title' => session()->get('username') . " : " . 'ทำการลบ' . $document->doc_type
            // ]);

            logger_store([
                'employee_id' => session()->get('employeeID'),
                'username' => session()->get('username'),
                'event' => 'ลบ',
                'detail' => '[ลบ] ' . $document->doc_type . '#' . ' ' . $document->doc_number,
                'ip' => $this->request->getIPAddress()
            ]);

            $status = 200;
            $response['success'] = 1;
            $response['message'] = 'ลบ ' . $document->doc_type . ' สำเร็จ';
        } else {
            $status = 200;
            $response['success'] = 0;
            $response['message'] = 'ลบ ' . $document->doc_type . ' ไม่สำเร็จ';
        }

        return $this->response
            ->setStatusCode($status)
            ->setContentType('application/json')
            ->setJSON($response);
    }

    /***************************************************************************
     * Helper
     */

    public function listTitle()
    {
        $actionBy = $this->request->getVar('actionBy') ?? '';
        $formKey = $this->request->getVar('formKey') ?? '';

        $docType = $this->request->getVar('docType') ?? '';

        $data['title'] = 'รายชื่อรายการ';
        $data['css_critical'] = '';
        $data['js_critical'] = ' ';
        $data['docType'] = $docType;

        $data['documentTitleLists'] = $this->DocumentTitleListModel->getDocumentTitleListByDocType($docType);

        $data['actionBy'] = $actionBy;
        $data['formKey'] = $formKey;

        echo view('/document/list_title', $data);
    }

    public function listDoc()
    {
        $actionBy = $this->request->getVar('actionBy') ?? '';
        $formKey = $this->request->getVar('formKey') ?? '';

        $docID = $this->request->getVar('docID') ?? '';
        $docType = $this->request->getVar('docType') ?? '';
        $inputTitle = $this->request->getVar('inputTitle') ?? '';
        $inputPrice = $this->request->getVar('inputPrice') ?? '';

        $data['title'] = $docType;
        $data['css_critical'] = '';
        $data['js_critical'] = ' ';
        $data['docType'] = $docType;

        $data['docID'] = $docID;

        // มีข้อมูลรายการ
        if ($inputTitle != '') {
            $title = $this->DocumentTitleListModel->getDocumentTitleListByDocTypeAndTitle($docType, $inputTitle);

            // กรณีที่ไม่มี ให้สร้างใหม่
            if (!$title) {

                $lastID = $this->DocumentTitleListModel->insertDocumentTitleList([
                    'doc_type' => $docType,
                    'title' => $inputTitle
                ]);

                $title = $this->DocumentTitleListModel->getDocumentTitleListByID($lastID);
            }

            $data['inputTitle'] = $title;
        }

        // มีข้อมูลราคา
        if ($inputPrice != '') {
            $data['inputPrice'] = $inputPrice;
        }

        $data['actionBy'] = $actionBy;
        $data['formKey'] = $formKey;

        echo view('/document/list_doc', $data);
    }

    public function billHistory()
    {
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';

            // HANDLE REQUEST
            $requestPayload = $this->request->getJSON();
            $docType = $requestPayload->docType;


            $bills = $this->DocumentModel->getDocumentByDocTypeAndCarID($docType);

            $html = '
                <ul class="list-unstyled mb-0 mt-3">
            ';

            if ($bills) {

                $listCount = count($bills);

                if ($listCount >= 6) {
                    $html = '
                        <ul class="list-unstyled mb-0 mt-3" style="height: 300px; overflow: scroll; padding-right: 20px;">
                    ';
                }

                foreach ($bills as $bill) {

                    switch ($bill->doc_type) {
                        case 'ใบสำคัญรับ':
                            $bg = 'bg-green';
                            $icon = '<i class="fe fe-corner-right-down"></i>';
                            $price = '<span class="tx-success">+' . number_format($bill->price, 2) . '</span>';
                            //                        $clientTitle = $bill->customer_title;
                            $clientTitle = $bill->doc_number;
                            break;

                        case 'ใบสำคัญจ่าย':
                            $bg = 'bg-red';
                            $icon = '<i class="fe fe-check-square"></i>';
                            $price = '<span class="tx-danger">-' . number_format($bill->price, 2) . '</span>';
                            //                        $clientTitle = $bill->seller_title;
                            $clientTitle = $bill->doc_number;
                            break;

                        case 'ใบส่วนลด':
                            $bg = 'bg-yellow';
                            $icon = '<i class="fe fe-check-square"></i>';
                            $price = '<span class="tx-warning">-' . number_format($bill->price, 2) . '</span>';
                            //                        $clientTitle = $bill->seller_title;
                            $clientTitle = $bill->doc_number;
                            break;
                    }

                    $html .= '
                        <li>
                            <div class="d-flex">
                                <div class="avatar avatar-sm rounded-circle ' . $bg . ' shadow">
                                    ' . $icon . '
                                </div>
                                <div class="flex-1 ms-3">
                                    <p class="tx-14 font-weight-semibold mb-0 d-flex align-items-center justify-content-between">' . $bill->title . ' ' . $price . '</p>
                                    <p class="mb-0 d-flex align-items-center justify-content-between"><a class="tx-primary tx-11 openDoc" data-doc-type="ใบสำคัญรับ" data-doc-id="' . hashidsEncrypt($bill->id) . '" style="cursor: pointer;">' . $clientTitle . '</a><span class="tx-muted tx-11">' . dateThai($bill->created_at) . '</span></p>
                                </div>
                            </div>
                        </li>							
                    ';
                }
            } else {
                $html .= '
                   <li class="text-center">--- ไม่มีรายการ ---</li>
                ';
            }

            $html .= '</ul>';

            $response['html'] = $html;
            $response['totalCounterBills'] = $this->DocumentModel->getDocumentCounterByDocTypeAndCarID('ทั้งหมด') ?: 0; // จำนวนใบสำคัญ
            $response['summarizeBillType1'] = $this->DocumentModel->getDocumentSummarizeByDocTypeAndCarID('ใบสำคัญรับ') ?: 0; // รวมรับเงินทั้งสิ้น (ใบสำคัญรับ)
            $response['summarizeBillType2'] = $this->DocumentModel->getDocumentSummarizeByDocTypeAndCarID('ใบสำคัญจ่าย') ?: 0; // รวมต้นทุนทั้งสิ้น (ใบสำคัญจ่าย)
            $response['summarizeBillType3'] = $this->DocumentModel->getDocumentSummarizeByDocTypeAndCarID('ใบส่วนลด') ?: 0; // รวมส่วนลดทั้งสิ้น (ใบส่วนลด)

            $status = 200;
            $response['success'] = 1;
            $response['message'] = '';

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function search()
    {
        if (isset($_POST['query'])) {
            $inputText = $_POST['query'];
            $docType = $_POST['type'];
            $result = $this->DocumentTitleListModel->getDocumentNoetListByDocType($docType, $inputText);

            if ($result) {
                foreach ($result as $row) {
                    echo '<a class="list-group-item list-group-item-action border-1">' . $row->note . '</a>';
                }
            }
        }
    }

    /***************************************************************************
     * HELPER
     */

    private function ddoo_upload_file($file)
    {
        $allowMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        $mimeType = $file->getClientMimeType();

        if (!in_array($mimeType, $allowMimeTypes)) throw new \Exception();

        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads/file_loan/', $newName);

        $file_Path = 'uploads/file_loan/' . $newName;

        try {

            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => $this->s3_region,
                'endpoint' => $this->s3_endpoint,
                'use_path_style_endpoint' => false,
                'credentials' => [
                    'key'    => $this->s3_key,
                    'secret' => $this->s3_secret_key
                ]
            ]);

            $result = $s3Client->putObject([
                'Bucket' => $this->s3_bucket,
                'Key'    => 'uploads/file_loan/' . $newName,
                'Body'   => fopen($file_Path, 'r'),
                'ACL'    => 'public-read', // make file 'public'
            ]);


            if ($result['ObjectURL'] != "") {
                unlink('uploads/file_loan/' . $newName);
            }
        } catch (Aws\S3\Exception\S3Exception $e) {
            echo $e->getMessage();
        }

        return $file->getName();
    }

    public function aiScriptVehicleRegistrationBook()
    {
        try {
            $KEY = getenv('KEY_OCR');

            $image = false;

            if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
                $file  = $this->request->getFile('image');
                $data = file_get_contents($file);
                $image = base64_encode($data);
            }

            if ($image) {

                $curl = curl_init();

                $headers  = [
                    'Content-Type: application/json',
                    'X-AIGEN-KEY: ' . $KEY
                ];

                $payload = json_encode([
                    'image' => $image
                ]);

                curl_setopt($curl, CURLOPT_URL, 'https://apis.aigen.online/aiscript/vehicle-registration-book/v1');
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $data = curl_exec($curl);
                curl_close($curl);

                return $this->response
                    ->setStatusCode(200)
                    ->setContentType('application/json')
                    ->setJSON($data);
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    public function docNumber($type)
    {
        $year = date('Y');
        $month = date('m');
        $DocumentType = $this->DocumentModel->getDocumentByType($type);
        // echo ($DocumentType);

        if ($DocumentType == null) {
            $number_year = date('Y');
            $number_month = date('m');
            $number[1] = date('Ym') . '0000';
        } elseif ($DocumentType->doc_number != '') {
            $number = explode("-", $DocumentType->doc_number);
            // echo substr($number[1] , -4);
            $number_year = substr($number[1], 0, -6);
            $number_month = substr($number[1], 4, -4);
        } else {
            $number_year = date('Y');
            $number_month = date('m');
            $number[1] = date('Ym') . '0000';
        }

        if ($number_year == $year && $number_month == $month) {
            $number_count = substr($number[1], -4) + 1;
            if ($number_count <= 9) {
                $number_count = '000' . $number_count;
            } elseif ($number_count <= 99) {
                $number_count = '00' . $number_count;
            } elseif ($number_count <= 999) {
                $number_count = '0' . $number_count;
            } else {
                $number_count = $number_count;
            }
            // echo $number_count;
        } else {
            $number_count = '0001';
            // echo $number_count;
        }

        // $json_data = array($Type);

        // return $this->response
        //     ->setStatusCode(200)
        //     ->setContentType('application/json')
        //     ->setJSON($number_count);

        return json_encode($number_count);
    }

    public function ocrInvoice()
    {
        // ตรวจสอบไฟล์ที่อัปโหลด
        $uploadedFile = $this->request->getFile('image');
        if (!$uploadedFile->isValid() || !in_array($uploadedFile->getClientMimeType(), ['image/jpeg', 'image/png', 'application/pdf'])) {
            return $this->response->setJSON([
                'status' => 'fail',
                'message' => 'กรุณาอัปโหลดไฟล์ที่เป็น JPEG, PNG หรือ PDF เท่านั้น'
            ]);
        }

        // เช็ค Google API Key
        $vision_api_key = getenv('GOOGLE_CLOUD_API_KEY');
        if (!$vision_api_key) {
            return $this->response->setJSON([
                'status' => 'fail',
                'message' => 'ไม่พบ API Key ในไฟล์ .env โปรดตรวจสอบการตั้งค่าของคุณ'
            ]);
        }

        // กำหนดตำแหน่งไฟล์
        $filePath = $uploadedFile->getTempName();
        $ocrResults = [];

        // ตรวจสอบและดำเนินการตามชนิดของไฟล์ (PDF หรือ รูปภาพ)
        if ($uploadedFile->getClientMimeType() === 'application/pdf') {
            // ใช้ฟังก์ชัน extractTextFromPdf ถ้าเป็น PDF
            $ocrResults[] = $this->extractTextFromPdf($filePath);
        } else {
            // ถ้าเป็นไฟล์รูปภาพ ให้แปลงเป็น base64 และประมวลผลด้วย Google Vision API
            $imageContent = base64_encode(file_get_contents($filePath));

            // ประมวลผลข้อความ OCR จากแต่ละภาพ
            $url = "https://vision.googleapis.com/v1/images:annotate?key=$vision_api_key";
            $data = [
                'requests' => [
                    [
                        'image' => ['content' => $imageContent],
                        'features' => [['type' => 'DOCUMENT_TEXT_DETECTION']]
                    ]
                ]
            ];

            // ส่งข้อมูลไปยัง Google Vision API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                $result = json_decode($response, true);
                $ocrResults[] = $result['responses'][0]['fullTextAnnotation']['text'] ?? '';
            }
        }

        // ถ้าไม่มีข้อความจาก OCR
        if (empty($ocrResults)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อความในไฟล์ หรือ API ไม่สามารถประมวลผลได้'
            ]);
        }

        // รวมข้อความจากทุกหน้า (ถ้าเป็น PDF) หรือ ทุกไฟล์รูปภาพ
        $text = implode("\n", $ocrResults);

        // เชื่อมต่อกับ OpenAI API
        $openai_api_key = getenv('OPENAI_API_KEY');
        if (!$openai_api_key) {
            return $this->response->setJSON([
                'status' => 'fail',
                'message' => 'ไม่พบ OpenAI API Key ในไฟล์ .env โปรดตรวจสอบการตั้งค่าของคุณ'
            ]);
        }

        // ตรงจำนวนเงินให้แปลงค่าเงินเป็น usd โดยอิงจากค่าเงินวันนี้
        $openai_url = "https://api.openai.com/v1/chat/completions";
        $prompt = "Input $text จาก Input จงแยกแยะข้อมูลชุดนี้โดยข้อมูลที่ต้องการออกมาคือ จำนวนเงิน, สกุลเงิน, วันที่
                        เสร็จแล้วทำข้อมูลให้อยู่ในรูปแบบ json เท่านั้น โดยไม่ต้องเพิ่มคำอธิบายเพิ่มเติม
                        นี่คือรูปแบบที่ฉันต้องการ {\"amount\":__,\"type\":__,\"date\":__}
                        ในส่วนสกุลเงิน ตรวจสอบจำนวนเงินว่าเป็นเงินบาท เงินกีบ หรือ ดอลล่า หากเป็น บาท หรือ กีบ  เงินกีบ = LAK, เงินบาท = THB, เงินดอลล่า = USD
                        ตรงจำนวนเงินให้ส่งกลับมาแค่จำนวนเงินเท่านั้น 
                        
                        **กติกา:**
                        **amount**:
                        - ให้ดึงเฉพาะตัวเลขจำนวนเงินที่แสดงสุดท้ายในข้อมูล (หมายถึงจำนวนเงินที่ต้องชำระ หรือยอดรวมสุดท้าย)  
                        
                        **date**:
                        - ให้เลือก 'วันที่แรกที่พบ' เท่านั้น
                        - วันที่อาจมาในหลายรูปแบบ เช่น:
                        - 21 เม.ย. 68
                        - 2025-03-11
                        - 11 มีนาคม 2565
                        - March 11, 2023
                        - 11/03/25
                        - หากเป็นเลขปี **สองหลัก** (เช่น 68, 25, 40 ฯลฯ):
                        - หากมากกว่า 30 → ให้ถือว่าเป็น **พ.ศ.** (เช่น 68 → พ.ศ. 2568 → ค.ศ. 2025)
                        - หากน้อยกว่าหรือเท่ากับ 30 → ให้ถือว่าเป็น **ค.ศ.** (เช่น 25 → ค.ศ. 2025)
                        - หากระบุเป็นปี พ.ศ. โดยตรง (เช่น 2565) ให้แปลงเป็น ค.ศ. โดยลบ 543
                        - วันที่ให้อยู่ในรูปแบบ **YYYY-MM-DD**
                        - หากวันที่อยู่ในรูปแบบที่ไม่ชัดเจน เช่น 11.3.25 หรือ 11-3.25 ให้แปลงเป็นปี 2025 เดือน 3 วันที่ 11 
                        - คืนค่าเฉพาะ JSON เท่านั้น ห้ามมีคำอธิบายอื่นหรือข้อความประกอบ";

        $openai_data = [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'คุณต้องส่งคืนเฉพาะ JSON เท่านั้น ห้ามเพิ่มคำอธิบายหรือข้อความใดๆ เพิ่มเติม'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $openai_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $openai_api_key,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($openai_data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // เพิ่ม Timeout
        $openai_response = curl_exec($ch);
        $openai_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($openai_http_code == 200) {
            $openai_result = json_decode($openai_response, true);
            $json_output = $openai_result['choices'][0]['message']['content'] ?? 'ไม่พบผลลัพธ์';

            // ส่งข้อมูล JSON ที่พร้อมใช้งานไปยัง JavaScript
            return $this->response->setJSON([
                'status' => 'success',
                'ocr_text' => $text,
                'output' => $json_output,
                'json_output' => json_decode($json_output, true) // แปลงเป็น array เพื่อใช้งานใน JS ได้ทันที
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่สามารถติดต่อ OpenAI API ได้',
                'error' => json_decode($openai_response, true)
            ]);
        }
    }

    private function extractTextFromPdf($pdfFilePath)
    {
        // ใช้ Parser เพื่อดึงข้อความจาก PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfFilePath);
        return $pdf->getText();
    }
}
