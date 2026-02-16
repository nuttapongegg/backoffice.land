<?php

namespace App\Controllers;

date_default_timezone_set('Asia/Jakarta');

use App\Controllers\BaseController;
use App\Models\RebuildModel;
use Aws\S3\S3Client;
use \GuzzleHttp\Client;

use App\Models\EmployeeModel;
use App\Models\EmployeeLogModel;
use App\Models\OwnerLoanModel;
use App\Models\SettingLandModel;
use App\Models\OwnerSettingModel;

use Smalot\PdfParser\Parser;

class OwnerLoan extends BaseController
{
    private EmployeeModel $EmployeeModel;
    private EmployeeLogModel $EmployeeLogModel;
    private OwnerLoanModel $OwnerLoanModel;
    private SettingLandModel $SettingLandModel;
    private OwnerSettingModel $OwnerSettingModel;
    private $http;

    private string $s3_bucket;
    private string $s3_secret_key;
    private string $s3_key;
    private string $s3_endpoint;
    private string $s3_region;
    private string $s3_cdn_img;

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
        $this->EmployeeModel = new EmployeeModel();
        $this->EmployeeLogModel = new EmployeeLogModel();
        $this->OwnerLoanModel = new OwnerLoanModel();
        $this->SettingLandModel = new SettingLandModel();
        $this->OwnerSettingModel = new OwnerSettingModel();
        $this->http = new Client();

        // Environment Variables
        $this->s3_bucket = getenv('S3_BUCKET') ?: '';
        $this->s3_secret_key = getenv('SECRET_KEY') ?: '';
        $this->s3_key = getenv('KEY') ?: '';
        $this->s3_endpoint = getenv('ENDPOINT') ?: '';
        $this->s3_region = getenv('REGION') ?: '';
        $this->s3_cdn_img = getenv('CDN_IMG') ?: '';
    }

    public function list()
    {
        $data['employee_logs'] = $this->EmployeeLogModel->getEmployeeLogToday();

        $data['content'] = 'ownerloan/list';
        $data['title'] = 'เปิดสินเชื่อ';
        $data['css_critical'] = '';
        $data['js_critical'] = ' 
            <script src="' . base_url('/assets/plugins/notify/js/notifIt.js') . '"></script>
            <script src="' . base_url('/assets/plugins/jquery.maskedinput/jquery.maskedinput.js') . '"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="' . base_url('/assets/plugins/jquery-steps/jquery.steps.min.js') . '"></script>
            <script src="' . base_url('/assets/plugins/parsleyjs/parsley.min.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.ui.widget.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fileupload.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.iframe-transport.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fancy-fileupload.js') . '"></script>
            <script src="' . base_url('/assets/plugins/fancyuploder/fancy-uploader.js') . '"></script>
            <script src="' . base_url('/assets/app/js/ownerloan/list.js?v=' . time()) . '"></script> 
            <script src="' . base_url('/assets/app/js/ownerloan/list_history.js?v=' . time()) . '"></script> 
        ';
        // 
        $data['employee'] = $this->EmployeeModel->getEmployeeByID(session()->get('employeeID'));
        $data['land_accounts'] = $this->SettingLandModel->getSettingLandAll();

        echo view('/app', $data);
    }

    // public function fetchAllOwnerLoanOn()
    // {
    //     $date = $this->request->getGet('date') ?? '';

    //     $data_loanOn = $this->OwnerLoanModel->getAllDataOwnerLoanOn($date);

    //     return $this->response->setJSON([
    //         'status' => 200,
    //         'error' => false,
    //         'message' => json_encode($data_loanOn)
    //     ]);
    // }

    public function fetchAllOwnerLoanOn()
    {
        $date = $this->request->getGet('date') ?? '';

        $data = $this->OwnerLoanModel->getAllDataOwnerLoanOn($date);

        return $this->response->setJSON([
            'status' => 200,
            'error' => false,
            'message' => json_encode($data)
        ]);
    }

    public function tableOwnerLoanHistory()
    {
        $post = $this->request->getPost();

        $rows            = $this->OwnerLoanModel->getAllDataOwnerLoanHistory($post);
        $recordsTotal    = (int) $this->OwnerLoanModel->countAllDataOwnerLoanHistory();
        $recordsFiltered = (int) $this->OwnerLoanModel->countFilteredDataOwnerLoanHistory($post);

        return $this->response->setJSON([
            'draw'            => (int)($post['draw'] ?? 1),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }

    public function ajaxSummarizeOwnerLoan()
    {
        try {
            $response = ['success' => 0, 'message' => ''];

            // ถ้าหน้า list มี date filter และอยากให้สรุปตามช่วงวัน
            $date = $this->request->getPost('date') ?? '';
            // แต่ JS ของคุณไม่ได้ส่ง date มา -> ปล่อยว่างก็รวมทั้งหมด

            $sum = $this->OwnerLoanModel->getOwnerLoanSummary($date);

            $loan_all    = (float)($sum->loan_amount_all ?? 0);
            $loan_open   = (float)($sum->loan_amount_open ?? 0);
            $loan_closed = (float)($sum->loan_amount_closed ?? 0);

            $paid_total     = (float)($sum->paid_total ?? 0);
            $paid_principal = (float)($sum->paid_principal ?? 0);
            $paid_interest  = (float)($sum->paid_interest ?? 0);

            $outstanding_open = (float)($sum->outstanding_principal_open ?? 0);

            $count_all    = (int)($sum->count_all ?? 0);
            $count_open   = (int)($sum->count_open ?? 0);
            $count_closed = (int)($sum->count_closed ?? 0);

            $html = '
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-body mt-2 mb-3">
                <div class="row">

                  <div class="col" style="flex-grow:1;">
                    <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                      <p class="mb-0 font-weight-semibold tx-18">ยอดยืมทั้งหมด</p>
                      <div class="mt-2">
                        <span class="mb-0 font-weight-semibold tx-15">' . number_format($loan_all, 2) . '</span>
                      </div>
                      <div class="small text-muted">ทั้งหมด ' . $count_all . ' รายการ</div>
                    </div>
                  </div>

                  <div class="col" style="flex-grow:1;">
                    <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                      <p class="mb-0 font-weight-semibold tx-18">ยอดยืม (OPEN)</p>
                      <div class="mt-2">
                        <span class="mb-0 font-weight-semibold tx-15">' . number_format($loan_open, 2) . '</span>
                      </div>
                      <div class="small text-muted">OPEN ' . $count_open . ' รายการ</div>
                    </div>
                  </div>

                  <div class="col" style="flex-grow:1;">
                    <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                      <p class="mb-0 font-weight-semibold tx-18">จ่ายครบแล้ว (ปิดแล้ว)</p>
                      <div class="mt-2">
                        <span class="mb-0 font-weight-semibold tx-15">' . number_format($loan_closed, 2) . '</span>
                      </div>
                      <div class="small text-muted">ปิดแล้ว ' . $count_closed . ' รายการ</div>
                    </div>
                  </div>

                  <div class="col" style="flex-grow:1;">
                    <div class="tx-center pd-y-7 pd-sm-y-0-f bd-sm-e bd-e-0 bd-b bd-sm-b-0 bd-b-dashed bd-e-dashed">
                      <p class="mb-0 font-weight-semibold tx-18">ชำระแล้ว (รวม)</p>
                      <div class="mt-2">
                        <span class="mb-0 font-weight-semibold tx-15">' . number_format($paid_total, 2) . '</span>
                      </div>
                      <div class="small text-muted">ตัดต้น ' . number_format($paid_principal, 2) . ' | ดอก ' . number_format($paid_interest, 2) . '</div>
                    </div>
                  </div>

                  <div class="col" style="flex-grow:1;">
                    <div class="tx-center pd-y-7 pd-sm-y-0-f">
                      <p class="mb-0 font-weight-semibold tx-18">ค้างเงินต้น (OPEN)</p>
                      <div class="mt-2">
                        <span class="mb-0 font-weight-semibold tx-15">' . number_format($outstanding_open, 2) . '</span>
                      </div>
                      <div class="small text-muted">&nbsp;</div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>';

            $response['data_summarizeOwnerLoan'] = $html;
            $response['success'] = 1;

            return $this->response->setJSON($response);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => 0, 'message' => $e->getMessage()]);
        }
    }


    public function insertOwnerLoan()
    {
        try {

            // --- รับค่า ---
            $owner_loan_date = trim((string) $this->request->getPost('owner_loan_date'));
            $amount_raw      = (string) $this->request->getPost('amount');
            $land_account_id = (int) $this->request->getPost('land_account_id');
            $note            = trim((string) $this->request->getPost('note'));

            // session
            $employee_id = (int) (session()->get('employeeID') ?? 0);
            $username    = (string) (session()->get('employee_fullname') ?? '');

            // --- validate ---
            if ($employee_id <= 0) {
                return $this->response->setJSON(['status' => 200, 'error' => true, 'message' => 'Session หลุด กรุณาเข้าสู่ระบบใหม่']);
            }
            if ($owner_loan_date === '') {
                return $this->response->setJSON(['status' => 200, 'error' => true, 'message' => 'กรุณาเลือกวันที่ยืม']);
            }

            $amount = (float) str_replace(',', '', $amount_raw);
            if ($amount <= 0) {
                return $this->response->setJSON(['status' => 200, 'error' => true, 'message' => 'จำนวนเงินต้องมากกว่า 0']);
            }
            if ($land_account_id <= 0) {
                return $this->response->setJSON(['status' => 200, 'error' => true, 'message' => 'กรุณาเลือกบัญชีรับโอน']);
            }

            // --- ดึงบัญชี ---
            $land_account = $this->SettingLandModel->getSettingLandByID($land_account_id);
            if (!$land_account) {
                return $this->response->setJSON(['status' => 200, 'error' => true, 'message' => 'ไม่พบบัญชีรับโอน']);
            }

            // ----------------------------
            // upload file (optional)
            // ----------------------------
            $file_name = null;
            $upload = $this->request->getFile('owner_loan_file');
            if ($upload && $upload->isValid() && !$upload->hasMoved()) {
                $file_name = $this->ddoo_upload_file($upload); // คืน "ชื่อไฟล์บน Spaces"
            }

            // ----------------------------
            // 1) insert owner_loan (ให้ Model ทำ DB)
            // ----------------------------
            $ownerLoanData = [
                'owner_loan_date' => $owner_loan_date,
                'amount'          => $amount,
                'note'            => ($note !== '' ? $note : null),
                'status'          => 'OPEN',
                'land_account_id' => $land_account_id,
                'employee_id'     => $employee_id,
                'username'        => ($username !== '' ? $username : null),
                'owner_loan_file' => $file_name,
            ];

            $res = $this->OwnerLoanModel->createOwnerLoan($ownerLoanData);
            $owner_loan_id = (int) ($res['id'] ?? 0);
            $owner_code    = (string) ($res['owner_code'] ?? '');

            if ($owner_loan_id <= 0 || $owner_code === '') {
                return $this->response->setJSON([
                    'status' => 200,
                    'error'  => true,
                    'message' => 'เพิ่มรายการไม่สำเร็จ (createOwnerLoan)'
                ]);
            }

            // ----------------------------
            // 2) เงินเข้า "บัญชี"
            // ----------------------------
            $land_account_cash_new = (float) $land_account->land_account_cash + $amount;

            $this->SettingLandModel->updateSettingLandByID($land_account->id, [
                'land_account_cash' => $land_account_cash_new,
            ]);

            // ----------------------------
            // 3) เก็บรายงานบัญชี
            // ----------------------------
            $detail = 'ยืมเงินจากเจ้าของ' . '(' . $owner_code . ')';
            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $land_account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $amount,
                'setting_land_report_note' => ($note !== '' ? $note : null),
                'setting_land_report_account_balance' => $land_account_cash_new,
                'employee_id' => $employee_id,
                'employee_name' => $username,
            ]);

            return $this->response->setJSON([
                'status'          => 200,
                'error'           => false,
                'message'         => 'เพิ่มรายการยืมสำเร็จ',
                'owner_loan_id'   => $owner_loan_id,
                'owner_code'      => $owner_code,
                'file_name'       => $file_name,
                'amount'          => $amount,
                'owner_loan_date' => $owner_loan_date,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'insertOwnerLoan error: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 200, 'error' => true, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }

    private function ddoo_upload_file($file)
    {
        $allowMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        $mimeType = $file->getClientMimeType();

        if (!in_array($mimeType, $allowMimeTypes)) throw new \Exception();

        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads/file_owner_loan/', $newName);

        $file_Path = 'uploads/file_owner_loan/' . $newName;

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

            $fullPath = ROOTPATH . 'public/' . $file_Path;

            $result = $s3Client->putObject([
                'Bucket' => $this->s3_bucket,
                'Key'    => 'uploads/file_owner_loan/' . $newName,
                'Body'   => fopen($fullPath, 'r'),
                'ACL'    => 'public-read',
            ]);

            if (!empty($result['ObjectURL'])) {
                unlink($fullPath);
            }
        } catch (Aws\S3\Exception\S3Exception $e) {
            echo $e->getMessage();
        }

        return $file->getName();
    }

    public function detail($ownerCode = null)
    {
        $data['employee_logs'] = $this->EmployeeLogModel->getEmployeeLogToday();
        $data['owner_setting'] = $this->OwnerSettingModel->getOwnerSettingAll();

        $data['content'] = 'ownerloan/detail';
        $data['title'] = 'รายละเอียดรายการยืม';
        $data['css_critical'] = '';
        $data['js_critical'] = ' 
        <script src="' . base_url('/assets/plugins/notify/js/notifIt.js') . '"></script>
        <script src="' . base_url('/assets/plugins/jquery.maskedinput/jquery.maskedinput.js') . '"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="' . base_url('/assets/plugins/jquery-steps/jquery.steps.min.js') . '"></script>
        <script src="' . base_url('/assets/plugins/parsleyjs/parsley.min.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/jquery.ui.widget.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fileupload.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/jquery.iframe-transport.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/jquery.fancy-fileupload.js') . '"></script>
        <script src="' . base_url('/assets/plugins/fancyuploder/fancy-uploader.js') . '"></script>
        <script src="' . base_url('/assets/plugins/chart.js/Chart.bundle.min.js') . '"></script>
        <script src="' . base_url('/assets/js/image-uploader.min.js') . '"></script>
        <script src="' . base_url('/assets/app/js/ownerloan/detail.js?v=' . time()) . '"></script> 
        ';

        $data['employee'] = $this->EmployeeModel->getEmployeeByID(session()->get('employeeID'));
        $data['ownerLoanData'] = $this->OwnerLoanModel->getAllDataOwnerLoanByCode($ownerCode);
        $data['employees'] = $this->EmployeeModel->getEmployeeAllNoSpAdmin();
        $data['land_accounts'] = $this->SettingLandModel->getSettingLandAll();

        echo view('/app', $data);
    }

    public function ajaxPayments($owner_code)
    {
        $loan = $this->OwnerLoanModel->getAllDataOwnerLoanByCode($owner_code);
        if (!$loan) {
            return $this->response->setJSON([
                'ok' => false,
                'message' => 'ไม่พบรายการยืม',
                'rows' => [],
                'summary' => [
                    'loan_amount'           => 0,
                    'paid_total'            => 0,
                    'paid_principal_total'  => 0,
                    'paid_interest_total'   => 0,
                    'remain_total'          => 0,
                    'last_pay_date'         => null,
                ],
                'meta' => [
                    'last_active_payment_id' => 0,
                ],
            ]);
        }

        $ownerLoanId = (int)$loan->id;

        // rows
        $rows = $this->OwnerLoanModel->getPaymentsByOwnerLoanId($ownerLoanId);

        // summary (แยกต้น/ดอก)
        $summary = $this->OwnerLoanModel->getPaymentSummaryNew($ownerLoanId);

        $loanAmount          = (float)$loan->amount;
        $paidTotal           = (float)($summary->paid_total ?? 0);
        $paidPrincipalTotal  = (float)($summary->paid_principal_total ?? 0);
        $paidInterestTotal   = (float)($summary->paid_interest_total ?? 0);

        // ✅ คงเหลือ “เงินต้น” เท่านั้น
        $remainTotal = max(0, $loanAmount - $paidPrincipalTotal);

        // ✅✅ เพิ่มตรงนี้: หา ACTIVE ล่าสุด
        $lastActive = $this->OwnerLoanModel->getLastPaymentActive($ownerLoanId);
        $lastActiveId = (int)($lastActive->id ?? 0);

        return $this->response->setJSON([
            'ok' => true,
            'message' => '',
            'rows' => $rows,
            'summary' => [
                'loan_amount'           => $loanAmount,
                'paid_total'            => $paidTotal,
                'paid_principal_total'  => $paidPrincipalTotal,
                'paid_interest_total'   => $paidInterestTotal,
                'remain_total'          => $remainTotal,
                'last_pay_date'         => $summary->last_pay_date ?? null,
            ],
            // ✅✅ ส่งไปให้ JS ใช้
            'meta' => [
                'last_active_payment_id' => $lastActiveId,
                'loan_status' => $loan->status ?? 'OPEN',
            ],
        ]);
    }

    public function ajaxPayoffToday()
    {
        try {
            $owner_loan_id   = (int)$this->request->getPost('owner_loan_id');
            $pay_date        = trim((string)$this->request->getPost('pay_date'));
            $pay_amount_raw  = (string)$this->request->getPost('pay_amount');
            $note            = trim((string)$this->request->getPost('note'));
            $land_account_id = (int)$this->request->getPost('land_account_id');

            $employee_id = (int)(session()->get('employeeID') ?? 0);
            $username    = (string)(session()->get('employee_fullname') ?? '');

            if ($employee_id <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'Session หลุด']);
            if ($owner_loan_id <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'owner_loan_id ไม่ถูกต้อง']);
            if ($land_account_id <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'กรุณาเลือกบัญชีโอนออก']);

            $pay_amount_input = (float)str_replace(',', '', $pay_amount_raw);
            if ($pay_amount_input <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'ยอดชำระต้องมากกว่า 0']);

            if ($pay_date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $pay_date)) {
                $pay_date = date('Y-m-d');
            }

            // 1) loan
            $loan = $this->OwnerLoanModel->getOwnerLoanById($owner_loan_id);
            if (!$loan) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบรายการยืม']);

            $loanStatus = strtoupper((string)($loan->status ?? 'OPEN'));
            if (in_array($loanStatus, ['CANCEL', 'CANCELLED', 'CLOSED', 'PAID'], true)) {
                return $this->response->setJSON(['ok' => false, 'message' => 'รายการนี้ถูกปิด/ยกเลิกแล้ว']);
            }

            $owner_setting = $this->OwnerSettingModel->getOwnerSettingAll();

            // 2) last active payment
            $last = $this->OwnerLoanModel->getLastPaymentActive($owner_loan_id);

            $startDate = $last->pay_date ?? $loan->owner_loan_date;
            $principalStart = isset($last->principal_balance) ? (float)$last->principal_balance : (float)$loan->amount;
            if ($principalStart < 0) $principalStart = 0;

            if ($principalStart <= 0) {
                // ปิดสถานะให้ตรง
                $this->OwnerLoanModel->closeOwnerLoanById($owner_loan_id, $employee_id, 'Auto close: principal already 0');
                return $this->response->setJSON(['ok' => false, 'message' => 'รายการนี้ปิดยอดเงินต้นแล้ว']);
            }

            // 3) days diff
            $d1 = new \DateTime($startDate);
            $d2 = new \DateTime($pay_date);
            $days = (int)$d1->diff($d2)->format('%r%a');
            if ($days < 0) $days = 0;

            // 4) interest due
            $ratePercent = $loan->interest_rate ?? $owner_setting->default_interest_rate; // ดอกเบี้ยต่อปี
            $ratePerYear = $ratePercent / 100;
            $interestDue = round($principalStart * $ratePerYear * $days / 365, 2);

            // ✅ ยอดปิดวันนี้
            $totalDueToday = round($principalStart + $interestDue, 2);

            // ✅ กันจ่ายเกิน: รับจริงไม่เกินยอดปิด (ยอดเกินเอาไปแจ้ง user)
            // ✅ กันจ่ายเกิน: ยอดที่นำไปตัดหนี้จริง (ไม่เกินยอดปิดวันนี้)
            $pay_amount_apply = min($pay_amount_input, $totalDueToday);
            $overpay_amount   = round(max(0, $pay_amount_input - $pay_amount_apply), 2);

            // ... allocate โดยใช้ $pay_amount_apply
            $interestAmount  = round(min($pay_amount_apply, $interestDue), 2);
            $principalAmount = round(max(0, $pay_amount_apply - $interestAmount), 2);


            if ($principalAmount > $principalStart) $principalAmount = $principalStart;

            $principalBalance = round($principalStart - $principalAmount, 2);
            if ($principalBalance < 0) $principalBalance = 0;

            // 6) upload file
            $file_name = null;
            $upload = $this->request->getFile('pay_file');
            if ($upload && $upload->isValid() && !$upload->hasMoved()) {
                $file_name = $this->ddoo_upload_file($upload);
            }

            $now = date('Y-m-d H:i:s');

            // ----------------------------
            // 6.5) เช็คเงินบัญชีโอนออกก่อน (สำคัญมาก)
            // ----------------------------
            $land_account = $this->SettingLandModel->getSettingLandByID($land_account_id);
            if (!$land_account) {
                return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบบัญชีโอนออก']);
            }

            $land_account_cash_new = (float)$land_account->land_account_cash - (float)$pay_amount_apply;

            if ($land_account_cash_new < 0) {
                return $this->response->setJSON([
                    'ok' => false,
                    'message' => 'ยอดเงินในบัญชีไม่พอ',
                ]);
            }

            // 7) insert
            $ok = $this->OwnerLoanModel->insertPayment([
                'owner_loan_id'     => $owner_loan_id,
                'pay_date'          => $pay_date,
                'pay_amount'        => $pay_amount_apply,
                'interest_amount'   => $interestAmount,
                'interest_rate_used' => $ratePercent,
                'principal_amount'  => $principalAmount,
                'principal_balance' => $principalBalance,
                'days_diff'         => $days,
                'owner_loan_pay_file' => $file_name,
                'note'              => ($note !== '' ? $note : null),
                'status'            => 'ACTIVE',
                'land_account_id'   => $land_account_id,
                'employee_id'       => $employee_id,
                'username'          => ($username !== '' ? $username : null),
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
            if (!$ok) {
                return $this->response->setJSON(['ok' => false, 'message' => 'บันทึกไม่สำเร็จ']);
            }

            // ----------------------------
            // 8) เงินออกจาก "บัญชีโอนออก" + เก็บรายงานบัญชี
            // ----------------------------
            $this->SettingLandModel->updateSettingLandByID($land_account->id, [
                'land_account_cash' => $land_account_cash_new,
            ]);

            $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $land_account_id,
                'setting_land_report_detail' =>
                'ชำระคืนเงินกู้เจ้าของ(' . $loan->owner_code . ')',
                'setting_land_report_money' => -1 * (float)$pay_amount_apply,
                'setting_land_report_note' => ($note !== '' ? $note : null),
                'setting_land_report_account_balance' => $land_account_cash_new,
                'employee_id' => $employee_id,
                'employee_name' => $username,
            ]);

            // ✅ ปิดรายการถ้าเงินต้นหมด
            $closed = 0;
            if ($principalBalance <= 0.00) {
                $this->OwnerLoanModel->closeOwnerLoanById($owner_loan_id, $employee_id, 'Auto close: principal paid off');
                $closed = 1;
            }

            return $this->response->setJSON([
                'ok' => true,
                'message' => $overpay_amount > 0 ? 'บันทึกสำเร็จ (มีเงินเกิน)' : 'บันทึกสำเร็จ',
                'calc' => [
                    'principal_start'   => $principalStart,
                    'days_diff'         => $days,
                    'interest_due'      => $interestDue,
                    'total_due_today'   => $totalDueToday,
                    'pay_amount_input'  => $pay_amount_input,
                    'pay_amount_apply'   => $pay_amount_apply,
                    'overpay_amount'    => $overpay_amount,
                    'interest_amount'   => $interestAmount,
                    'principal_amount'  => $principalAmount,
                    'principal_balance' => $principalBalance,
                    'closed'            => $closed,
                ]
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'OwnerLoan pay error: ' . $e->getMessage());
            return $this->response->setJSON(['ok' => false, 'message' => 'ผิดพลาด: ' . $e->getMessage()]);
        }
    }

    public function cancelPayment()
    {
        try {
            $employee_id = (int)(session()->get('employeeID') ?? 0);
            $username    = (string)(session()->get('employee_fullname') ?? '');
            if ($employee_id <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'Session หลุด']);

            $id = (int)$this->request->getPost('id');
            if ($id <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบ id']);

            $row = $this->OwnerLoanModel->getPaymentById($id);
            if (!$row) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบรายการชำระ']);
            if (($row->status ?? '') === 'CANCEL') return $this->response->setJSON(['ok' => false, 'message' => 'รายการนี้ถูกยกเลิกไปแล้ว']);

            $ownerLoanId = (int)($row->owner_loan_id ?? 0);
            if ($ownerLoanId <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'owner_loan_id ไม่ถูกต้อง']);

            $lastActiveId = (int)$this->OwnerLoanModel->getLastActivePaymentId($ownerLoanId);
            if ($lastActiveId <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่มีรายการ ACTIVE ให้ยกเลิก']);
            if ((int)$row->id !== (int)$lastActiveId) {
                return $this->response->setJSON(['ok' => false, 'message' => 'ยกเลิกได้เฉพาะรายการล่าสุดเท่านั้น']);
            }

            $land_account_id = (int)($row->land_account_id ?? 0);
            $pay_amount      = (float)($row->pay_amount ?? 0);
            if ($land_account_id <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบ land_account_id ของรายการชำระ']);
            if ($pay_amount <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'ยอดชำระไม่ถูกต้อง']);

            $loan = $this->OwnerLoanModel->getOwnerLoanById($ownerLoanId);
            $owner_code = (string)($loan->owner_code ?? '');

            // ✅ A) คืนเงินบัญชีก่อน (ถ้าพังจะไม่ cancel)
            $land_account = $this->SettingLandModel->getSettingLandByID($land_account_id);
            if (!$land_account) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบบัญชีที่จะคืนเงิน']);

            $land_account_cash_new = (float)$land_account->land_account_cash + $pay_amount;

            $okAcc = $this->SettingLandModel->updateSettingLandByID($land_account->id, [
                'land_account_cash' => $land_account_cash_new,
            ]);
            if (!$okAcc) return $this->response->setJSON(['ok' => false, 'message' => 'คืนเงินเข้าบัญชีไม่สำเร็จ']);

            $detail = 'ยกเลิกชำระเงินกู้เจ้าของ' . '(' . $owner_code . ')';
            $okRep = $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $land_account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => $pay_amount,
                'setting_land_report_note' => 'ยกเลิกรายการชำระ',
                'setting_land_report_account_balance' => $land_account_cash_new,
                'employee_id' => $employee_id,
                'employee_name' => $username,
            ]);
            if (!$okRep) return $this->response->setJSON(['ok' => false, 'message' => 'บันทึกรายงานบัญชี (คืนเงิน) ไม่สำเร็จ']);

            // ✅ B) แล้วค่อย cancel payment
            $ok = $this->OwnerLoanModel->cancelPaymentById($id);
            if (!$ok) return $this->response->setJSON(['ok' => false, 'message' => 'ยกเลิกไม่สำเร็จ']);

            // ✅ C) reopen สถานะ loan
            $this->OwnerLoanModel->reopenOwnerLoanById($ownerLoanId);

            return $this->response->setJSON(['ok' => true, 'message' => 'ยกเลิกสำเร็จ']);
        } catch (\Throwable $e) {
            log_message('error', 'OwnerLoan cancelPayment error: ' . $e->getMessage());
            return $this->response->setJSON(['ok' => false, 'message' => 'ผิดพลาด: ' . $e->getMessage()]);
        }
    }

    public function cancelOwnerLoan()
    {
        try {
            $employee_id = (int)(session()->get('employeeID') ?? 0);
            $username    = (string)(session()->get('employee_fullname') ?? '');
            if ($employee_id <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'Session หลุด']);

            $owner_code = trim((string)$this->request->getPost('owner_code'));
            if ($owner_code === '') return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบ owner_code']);

            $loan = $this->OwnerLoanModel->getAllDataOwnerLoanByCode($owner_code);
            if (!$loan) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบรายการยืม']);

            $hasActivePay = (int)$this->OwnerLoanModel->countActivePayments((int)$loan->id);
            if ($hasActivePay > 0) {
                return $this->response->setJSON([
                    'ok' => false,
                    'message' => 'รายการนี้มีการชำระแล้ว ไม่สามารถยกเลิกรายการยืมได้ (โปรดยกเลิกการชำระทั้งหมดก่อน)',
                ]);
            }

            $land_account_id = (int)($loan->land_account_id ?? 0);
            $amount          = (float)($loan->amount ?? 0);
            if ($land_account_id <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบบัญชีรับโอนของรายการยืม']);
            if ($amount <= 0) return $this->response->setJSON(['ok' => false, 'message' => 'ยอดเงินกู้ไม่ถูกต้อง']);

            // ✅ A) cancel loan ก่อน (ถ้าพังจะไม่แตะบัญชี)
            $ok = $this->OwnerLoanModel->cancelOwnerLoanById((int)$loan->id);
            if (!$ok) return $this->response->setJSON(['ok' => false, 'message' => 'ยกเลิกไม่สำเร็จ']);

            // ✅ B) แล้วค่อยตัดเงินออกจากบัญชีรับโอน + report
            $land_account = $this->SettingLandModel->getSettingLandByID($land_account_id);
            if (!$land_account) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบบัญชีรับโอน']);

            $land_account_cash_new = (float)$land_account->land_account_cash - $amount;
            if ($land_account_cash_new < 0) {
                // ถ้าคุณอยาก “rollback” จริง ต้องใช้ transaction
                return $this->response->setJSON(['ok' => false, 'message' => 'ยอดเงินในบัญชีไม่พอสำหรับการยกเลิกรายการ']);
            }

            $okAcc = $this->SettingLandModel->updateSettingLandByID($land_account->id, [
                'land_account_cash' => $land_account_cash_new,
            ]);
            if (!$okAcc) return $this->response->setJSON(['ok' => false, 'message' => 'ตัดยอดบัญชีไม่สำเร็จ']);

            $detail = 'ยกเลิกรายการยืมเงินจากเจ้าของ' . '(' . $owner_code . ')';
            $okRep = $this->SettingLandModel->insertSettingLandReport([
                'setting_land_id' => $land_account_id,
                'setting_land_report_detail' => $detail,
                'setting_land_report_money' => -$amount,
                'setting_land_report_note' => null,
                'setting_land_report_account_balance' => $land_account_cash_new,
                'employee_id' => $employee_id,
                'employee_name' => $username,
            ]);
            if (!$okRep) return $this->response->setJSON(['ok' => false, 'message' => 'บันทึกรายงานบัญชี (เงินออก) ไม่สำเร็จ']);

            return $this->response->setJSON(['ok' => true, 'message' => 'ยกเลิกสำเร็จ']);
        } catch (\Throwable $e) {
            log_message('error', 'cancelOwnerLoan error: ' . $e->getMessage());
            return $this->response->setJSON(['ok' => false, 'message' => 'ผิดพลาด: ' . $e->getMessage()]);
        }
    }

    public function ajaxCalcPayoffToday($owner_code)
    {
        $loan = $this->OwnerLoanModel->getAllDataOwnerLoanByCode($owner_code);
        $owner_setting = $this->OwnerSettingModel->getOwnerSettingAll();
        if (!$loan) return $this->response->setJSON(['ok' => false, 'message' => 'ไม่พบรายการยืม']);

        $pay_date = date('Y-m-d'); // บังคับเป็นวันนี้

        $ownerLoanId = (int)$loan->id;

        // ✅ เงินต้นคงเหลือจริง (หลัง cancel ก็ถูก)
        $paidPrincipal = $this->OwnerLoanModel->getPaidPrincipalTotalActive($ownerLoanId);
        $principalRemain = max(0, (float)$loan->amount - (float)$paidPrincipal);

        if ($principalRemain <= 0) {
            return $this->response->setJSON([
                'ok' => true,
                'data' => [
                    'pay_date' => $pay_date,
                    'principal_remain' => 0,
                    'interest' => 0,
                    'total_due' => 0,
                    'start_date' => null,
                    'days_diff' => 0,
                ]
            ]);
        }

        // ✅ วันเริ่มคิดดอก = วัน ACTIVE ล่าสุดจริงๆ
        $startDate = $this->OwnerLoanModel->getLastActivePayDate($ownerLoanId);
        if (!$startDate) $startDate = $loan->owner_loan_date;

        $d1 = new \DateTime($startDate);
        $d2 = new \DateTime($pay_date);
        $days = (int)$d1->diff($d2)->format('%r%a');
        if ($days < 0) $days = 0;

        $ratePercent = $loan->interest_rate ?? $owner_setting->default_interest_rate; // ดอกเบี้ยต่อปี
        $ratePerYear = $ratePercent / 100;

        $interest = round($principalRemain * $ratePerYear * $days / 365, 2);
        $totalDue = round($principalRemain + $interest, 2);

        return $this->response->setJSON([
            'ok' => true,
            'data' => [
                'pay_date' => $pay_date,
                'start_date' => $startDate,
                'days_diff' => $days,
                'principal_remain' => $principalRemain,
                'interest' => $interest,
                'total_due' => $totalDue,
            ]
        ]);
    }

    public function ajaxLandAccounts()
    {
        $accounts = $this->SettingLandModel->getSettingLandAll(); // ของเดิมคุณมีอยู่แล้ว
        return $this->response->setJSON([
            'ok' => true,
            'rows' => $accounts,
        ]);
    }

    // show Modal editInterestRate
    public function editInterestRate()
    {
        $OwnerSettingModel = new OwnerSettingModel();

        if ($OwnerSettingModel->getOwnerSettingAll()) {
            echo json_encode(array("status" => true, 'data' => $OwnerSettingModel->getOwnerSettingAll()));
        } else {
            echo json_encode(array("status" => false));
        }
    }

    //updateInterestRate
    public function updateInterestRate()
    {
        $OwnerSettingModel = new \App\Models\OwnerSettingModel();

        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';
            $id = $this->request->getVar('OwnerSettingId');
            $interest_Rate = floatval(str_replace(',', '', $this->request->getVar('interest_Rate')));

            // HANDLE REQUEST
            $update = $OwnerSettingModel->updateOwnerSettingByID($id, [
                'default_interest_rate' => $interest_Rate,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($update) {

                // pusherEdit
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Yellow',
                    'title' => session()->get('username') . " : " . 'ทำการแก้ไขอัตราดอกเบี้ย'
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] อัตราดอกเบี้ย',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข อัตราดอกเบี้ย สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข อัตราดอกเบี้ย ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }

    //updateOwnerLoanInterest
    public function updateOwnerLoanInterest()
    {
        $OwnerLoanModel = new \App\Models\OwnerLoanModel();
        try {
            // SET CONFIG
            $status = 500;
            $response['success'] = 0;
            $response['message'] = '';
            $id = $this->request->getVar('owner_loan_id');
            $interest_rate = floatval(str_replace(',', '', $this->request->getVar('loan_interest_rate')));

            // HANDLE REQUEST
            $update = $OwnerLoanModel->updateOwnerLoanByID($id, [
                'interest_rate' => $interest_rate,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($update) {

                // pusherEdit
                $pusher = getPusher();
                $pusher->trigger('color_Status', 'event', [
                    'img' => '/uploads/img/' . session()->get('thumbnail') != '' ? session()->get('thumbnail') : 'nullthumbnail.png',
                    'event' => 'status_Yellow',
                    'title' => session()->get('username') . " : " . 'ทำการแก้ไขอัตราดอกเบี้ย'
                ]);

                logger_store([
                    'employee_id' => session()->get('employeeID'),
                    'username' => session()->get('username'),
                    'event' => 'อัพเดท',
                    'detail' => '[อัพเดท] อัตราดอกเบี้ย',
                    'ip' => $this->request->getIPAddress()
                ]);
                $status = 200;
                $response['success'] = 1;
                $response['message'] = 'แก้ไข อัตราดอกเบี้ย สำเร็จ';
            } else {
                $status = 200;
                $response['success'] = 0;
                $response['message'] = 'แก้ไข อัตราดอกเบี้ย ไม่สำเร็จ';
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ' . $e->getLine();
        }
    }
}
