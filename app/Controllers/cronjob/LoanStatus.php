<?php

namespace App\Controllers\cronjob;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\OverdueStatusModel;
use CURLFile;
use Error;

class LoanStatus extends BaseController
{

    public function run()
    {
        set_time_limit(180);
        $this->line_message_api();
    }

    private function line_message_api()
    {
        $OverdueStatusModel = new OverdueStatusModel();
        $LoanModel = new LoanModel();

        try {

            $nofity_Day = $OverdueStatusModel->getOverdueStatusAll();
            if ($nofity_Day->token_loan_status == 1) {

                // **ดึง Token ล่าสุดจากฐานข้อมูล**
                $token = $nofity_Day->token_loan;
                $loanMessages = [];

                foreach ($LoanModel->getAllDataLoanMessageAPI() as $dataLoan) {


                    $loan_date_fix = $dataLoan->loan_payment_date_fix;
                    $months = $dataLoan->loan_period - 1;

                    $date = str_replace('-', '/', $loan_date_fix);
                    $tomorrow = date('Y-m-d', strtotime($date . "+$months months"));

                    $now = time(); // or your date as well
                    $your_date = strtotime($tomorrow);

                    $datediff = $now - $your_date;

                    $date_sum = round($datediff / (60 * 60 * 24));

                    if ($date_sum == 0) {
                        // กรณีครบกำหนดวันนี้
                        $loanMessages[] = [
                            "loan_code" => $dataLoan->loan_code,
                            "customer" => $dataLoan->loan_customer,
                            "due_date" => dateThaiDM($tomorrow),
                            "amount" => number_format($dataLoan->loan_payment_month, 2),
                            "status" => "due_today",  // เพิ่มสถานะ
                            "url" => 'https://land.evxspst.com/loan/detail' . '/' . $dataLoan->loan_code
                        ];
                    } elseif ($date_sum >= $nofity_Day->token_overdue_loan && $nofity_Day->token_overdue_loan != 0) {
                        // กรณีเลยกำหนดชำระ
                        $loanMessages[] = [
                            "loan_code" => $dataLoan->loan_code,
                            "customer" => $dataLoan->loan_customer,
                            "due_date" => dateThaiDM($tomorrow),
                            "amount" => number_format($dataLoan->loan_payment_month, 2),
                            "status" => "overdue",  // เพิ่มสถานะ
                            "overdue_days" => $date_sum, // จำนวนวันที่เลยกำหนด
                            "url" => 'https://land.evxspst.com/loan/detail' . '/' . $dataLoan->loan_code
                        ];
                    }
                }

                // ถ้ามีข้อความที่ต้องส่ง
                if (!empty($loanMessages)) {
                    // แบ่งข้อความออกเป็นหลายกลุ่ม หากข้อมูลมากเกินไป
                    $chunkedMessages = array_chunk($loanMessages, 12); // แบ่งข้อมูลออกเป็นกลุ่มละ 5 รายการ

                    // ส่งข้อความแต่ละกลุ่ม
                    foreach ($chunkedMessages as $messageGroup) {
                        $messagePayload = $this->createFlexMessage($messageGroup);  // สร้าง Flex Message
                        $response = send_line_message($token, $messagePayload); // ส่ง Flex Message
                        // $payloadSize = strlen(json_encode($messagePayload));
                        // px($response); exit();

                        // ตรวจสอบกรณี Token หมดอายุ
                        if ($response['status'] === 401) {
                            log_message('info', 'Refreshing LINE Token...');
                            $newToken = get_line_access_token();
                            if ($newToken) {
                                $token = $newToken;
                                $OverdueStatusModel->updateOverdueStatus(['token_loan' => $newToken]);

                                // พยายามส่งข้อความใหม่ด้วย Token ใหม่
                                $retryResponse = send_line_message($token, $messagePayload);
                                if ($retryResponse['status'] !== 200) {
                                    log_message('error', 'Failed to send LINE message after refreshing token.');
                                }
                            } else {
                                log_message('error', 'Failed to refresh LINE token.');
                            }
                        } elseif ($response['status'] !== 200) {
                            log_message('error', 'Failed to send LINE message.');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in LoanStatus: ' . $e->getMessage() . ' on line ' . $e->getLine());
        }
    }

    private function createFlexMessage($loanMessages)
    {
        $bodyContents = [];
    
        foreach ($loanMessages as $index => $loan) {
            $overdueText = null;
            if ($loan['status'] === "overdue" && isset($loan['overdue_days'])) {
                $overdueText = [
                    "type" => "text", 
                    "text" => "⚠️ เกินกำหนดชำระ: " . $loan['overdue_days'] . " วัน", 
                    "size" => "sm", 
                    "color" => "#FF0000",
                    "weight" => "bold"
                ];
            }
    
            $loanContent = [];
    
            // Loan Code พร้อมลิงก์
            if (isset($loan['loan_code'])) {
                $loanContent[] = [
                    "type" => "text",
                    "text" => "สินเชื่อ: " . $loan['loan_code'],
                    "weight" => "bold", 
                    "size" => "lg",
                    "color" => "#333333"
                ];
                $loanContent[] = [
                    "type" => "text",
                    "text" => "🔗 ชำระได้ที่นี่",
                    "size" => "sm",
                    "color" => "#0000EE",
                    "action" => [
                        "type" => "uri",
                        "label" => "View Details",
                        "uri" => $loan['url']
                    ]
                ];
            }
    
            $loanContent[] = [
                "type" => "separator",
                "margin" => "md"
            ];
    
            if (isset($loan['customer'])) {
                $loanContent[] = [
                    "type" => "text",
                    "text" => "👤 ลูกค้า: " . $loan['customer'],
                    "size" => "sm",
                    "color" => "#444444"
                ];
            }
    
            if (isset($loan['due_date'])) {
                $loanContent[] = [
                    "type" => "text",
                    "text" => "📅 วันครบกำหนด: " . $loan['due_date'],
                    "size" => "sm",
                    "color" => "#444444"
                ];
            }

            if (isset($loan['amount'])) {
                $loanContent[] = [
                    "type" => "text",
                    "text" => "💰 ยอดชำระ : " . $loan['amount'] . " บาท",
                    "size" => "sm",
                    "color" => "#444444"
                ];
            }
    
            if ($overdueText !== null) {
                $loanContent[] = [
                    "type" => "separator",
                    "margin" => "md"
                ];
                $loanContent[] = $overdueText;
            }
    
            // ✅ เพิ่มเส้นคั่นใหญ่ระหว่างสินเชื่อแต่ละรายการ
            if ($index > 0) {
                $bodyContents[] = [
                    "type" => "separator",
                    "margin" => "xl",
                    "color" => "#AAAAAA"
                ];
            }
    
            $bodyContents[] = [
                "type" => "box",
                "layout" => "vertical",
                "spacing" => "sm",
                "paddingAll" => "10px",
                "contents" => $loanContent,
                "backgroundColor" => "#FFFFFF",
                "cornerRadius" => "md"
            ];
        }
    
        return [
            "type" => "flex",
            "altText" => "📢 แจ้งเตือนสินเชื่อ",
            "contents" => [
                "type" => "bubble",
                "body" => [
                    "type" => "box",
                    "layout" => "vertical",
                    "contents" => $bodyContents,
                    "paddingAll" => "10px",
                    "backgroundColor" => "#F5F5F5"
                ]
            ]
        ];
    }
}
