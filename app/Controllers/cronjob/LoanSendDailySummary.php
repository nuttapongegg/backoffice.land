<?php

namespace App\Controllers\cronjob;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\OverdueStatusModel;
use CURLFile;
use Error;

class LoanSendDailySummary extends BaseController
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

        $SettingLandModel = new \App\Models\SettingLandModel();
        $land_accounts = $SettingLandModel->getSettingLandAll();

        $OpenLoans = $LoanModel->getOpenLoanMonthlySummary();

        try {

            $nofity_Day = $OverdueStatusModel->getOverdueStatusAll();
            if ($nofity_Day->token_loan_status == 1) {

                // **ดึง Token ล่าสุดจากฐานข้อมูล**
                $token = $nofity_Day->token_loan;
                $loanMessages = [];
                $dataLoanDailys = $LoanModel->getDataLoanPaymentsDaily();

                // ตัวแปรยอดเงินสดคงเหลือ กับ ยอดเปิดบัญชีสินเชื่อ (สมมติเรียก Model หรือคำนวณเพิ่ม)
                $loanOpen = $OpenLoans->totalOpenLoan;    // ต้องมีฟังก์ชันนี้ใน Model
                $cashBalance = 0;
                foreach ($land_accounts as $land_account) {
                    $cashBalance = $cashBalance + $land_account->land_account_cash;
                }

                foreach ($dataLoanDailys as $dataLoanDaily) {

                    // กรณีครบกำหนดวันนี้
                    $loanMessages[] = [
                        "detail" => $dataLoanDaily->setting_land_report_detail,
                        "amount" => number_format($dataLoanDaily->setting_land_report_money, 2),
                        "note" => $dataLoanDaily->setting_land_report_note,
                        "balance" => number_format($dataLoanDaily->setting_land_report_account_balance, 2),
                        "date" => dateThaiDM($dataLoanDaily->created_at) . ' ' . date('H:i', strtotime($dataLoanDaily->created_at)),
                        "user" => $dataLoanDaily->employee_name
                    ];
                }

                // ถ้ามีข้อความที่ต้องส่ง
                if (!empty($loanMessages)) {
                    // แบ่งข้อความออกเป็นหลายกลุ่ม หากข้อมูลมากเกินไป
                    $chunkedMessages = array_chunk($loanMessages, 12);

                    // คำนวณสรุป
                    $totalItems = count($loanMessages);
                    $totalAmountIn = 0;
                    $totalAmountOut = 0;

                    // คำนวณยอดรวม
                    foreach ($loanMessages as $item) {
                        $amountNumber = floatval(str_replace(',', '', $item['amount']));
                        if (strpos($item['detail'], 'เปิดสินเชื่อ') !== false || strpos($item['detail'], 'ลบสินเชื่อ LOA') !== false || strpos($item['detail'], 'ลบเงินออกจากบัญชี') !== false) {
                            $totalAmountOut += $amountNumber;
                        } else {
                            $totalAmountIn += $amountNumber;
                        }
                    }

                    // ส่งทีละ chunk
                    foreach ($chunkedMessages as $index => $messageGroup) {
                        // ถ้าเป็นกลุ่มสุดท้าย ให้แนบสรุปท้ายสุดด้วย
                        if ($index === count($chunkedMessages) - 1) {
                            $messagePayload = $this->createDailySummaryMessage(
                                $messageGroup,
                                $cashBalance,
                                $loanOpen,
                                true,
                                [
                                    'totalItems' => $totalItems,
                                    'totalAmountIn' => $totalAmountIn,
                                    'totalAmountOut' => $totalAmountOut
                                ]
                            );
                        } else {
                            $messagePayload = $this->createDailySummaryMessage($messageGroup, $cashBalance, $loanOpen, false);
                        }

                        // $messagePayload = $this->createDailySummaryMessage($messageGroup);  // สร้าง Flex Message
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

    private function createDailySummaryMessage($summaryItems, $cashBalance, $loanOpen, $isLastGroup = false, $summary = [])
    {
        $contents = [];

        foreach ($summaryItems as $item) {
            $amountNumber = floatval(str_replace(',', '', $item['amount']));

            // กำหนดสีหัวข้อ detail เป็นน้ำเงินเข้มเหมือนเดิม
            $colorDetail = "#1A237E";

            // กำหนดสีจำนวนเงิน ตามเงินเข้า/ออก
            if (
                strpos($item['detail'], 'เปิดสินเชื่อ') !== false ||
                strpos($item['detail'], 'ลบสินเชื่อ LOA') !== false ||
                strpos($item['detail'], 'ลบเงินออกจากบัญชี') !== false
            ) {
                // เงินออก - สีแดง
                $colorAmount = "#C62828";
            } else {
                // เงินเข้า - สีเขียว
                $colorAmount = "#2E7D32";
            }

            if (strpos($item['detail'], 'ชำระปิดสินเชื่อ') !== false) {
                // เงินออก - สีแดง
                $weight = "regular";
            } else {
                // เงินเข้า - สีเขียว
                $weight = "bold";
            }


            $contents[] = [
                "type" => "box",
                "layout" => "vertical",
                "margin" => "md",
                "spacing" => "sm",
                "borderWidth" => "1px",
                "borderColor" => "#EEEEEE",
                "cornerRadius" => "5px",
                "paddingAll" => "2px",
                "contents" => [
                    [
                        "type" => "text",
                        "text" => "🔹" . $item['detail'],
                        "size" => "sm",
                        "weight" => $weight,
                        "color" => $colorDetail,  // หัวข้อสีน้ำเงินเข้ม
                        "wrap" => true
                    ],
                    [
                        "type" => "box",
                        "layout" => "vertical",
                        "contents" => [],
                        "height" => "1px"
                    ],
                    $this->buildInfoRow("💰 จำนวน", $item['amount'] . " บาท", $colorAmount, true),  // สีจำนวนเงินเปลี่ยนตามเงื่อนไข
                    $this->buildInfoRow("🏦 คงเหลือ", $item['balance'] . " บาท", "#EF6C00", true), // เปลี่ยนเป็นน้ำเงินเข้ม
                    $this->buildInfoRow("👤 โดย", !empty($item['user']) ? $item['user'] : "-", "#555555", false),
                    $this->buildInfoRow("💬 หมายเหตุ", !empty($item['note']) ? $item['note'] : "-", "#555555", false),
                    [
                        "type" => "text",
                        "text" => "📅 เวลา: " . $item['date'],
                        "size" => "xs",
                        "color" => "#9E9E9E",
                        "align" => "end"
                    ],
                    [
                        "type" => "box",
                        "layout" => "vertical",
                        "contents" => [],
                        "height" => "2px"
                    ]
                ]
            ];

            $contents[] = [
                "type" => "separator",
                "margin" => "md",
                "color" => "#DDDDDD"
            ];
        }


        if ($isLastGroup) {

            // กำหนดเป้าหมายยอดสินเชื่อ 50 ล้านบาท
            $goal = 50000000; // 50,000,000 บาท

            // คำนวณเปอร์เซ็นต์ความคืบหน้าของยอดสินเชื่อที่เปิดบัญชีแล้ว
            $progressPercent = ($goal > 0) ? ($loanOpen / $goal) * 100 : 0;
            if ($progressPercent > 100) $progressPercent = 100; // จำกัดไม่เกิน 100%

            // คำนวณยอดที่ยังขาดอีก
            $remaining = max(0, $goal - $loanOpen);

            // แปลงเปอร์เซ็นต์เป็นจำนวนเต็ม และฟอร์แมตยอดที่ขาดให้ดูสวยงาม
            $progressPercentInt = intval($progressPercent);
            $remainingFormatted = number_format($remaining, 2);

            $summaryBox = [
                "type" => "box",
                "layout" => "vertical",
                "spacing" => "md",
                "margin" => "lg",
                "contents" => [
                    [
                        "type" => "text",
                        "text" => "📊 สรุปรายการประจำวัน",
                        "weight" => "bold",
                        "size" => "lg",
                        "color" => "#000000",
                        "align" => "center",
                    ],
                    [
                        "type" => "box",
                        "layout" => "vertical",
                        "contents" => [],
                        "height" => "2px"
                    ],
                    [
                        "type" => "text",
                        "text" => "📅 วันที่: " . dateThaiDM(date('Y-m-d')),
                        "size" => "sm",
                        "color" => "#666666",
                    ],
                    $this->buildInfoRow("🧾 จำนวนรายการ", ($summary['totalItems'] ?? 0) . " รายการ", "#000000", true),
                    $this->buildInfoRow("📥 เงินเข้า", number_format($summary['totalAmountIn'] ?? 0, 2) . " บาท", "#2E7D32", true),
                    $this->buildInfoRow("📤 เงินออก", number_format($summary['totalAmountOut'] ?? 0, 2) . " บาท", "#C62828", true),
                    $this->buildInfoRow("💼 เงินสดคงเหลือ", number_format($cashBalance, 2) . " บาท", "#EF6C00", true),
                    $this->buildInfoRow("💳 ยอดเปิดสินเชื่อ", number_format($loanOpen, 2) . " บาท", "#1565C0", true),

                    [
                        "type" => "box",
                        "layout" => "vertical",
                        "margin" => "md",
                        "contents" => [
                            [
                                "type" => "box",
                                "layout" => "horizontal",
                                "contents" => [
                                    [
                                        "type" => "text",
                                        "text" => "เป้าหมาย 50,000,000.00 บาท",
                                        "size" => "xs",
                                        "color" => "#999999",
                                        "flex" => 6,
                                        "wrap" => true
                                    ],
                                    [
                                        "type" => "text",
                                        "text" => $progressPercentInt . "%",
                                        "size" => "xs",
                                        "color" => "#999999",
                                        "align" => "end",
                                        "flex" => 1
                                    ]
                                ]
                            ],
                            [
                                "type" => "box",
                                "layout" => "horizontal",
                                "margin" => "sm",
                                "height" => "8px",
                                "contents" => [
                                    [
                                        "type" => "box",
                                        "layout" => "vertical",
                                        "backgroundColor" => "#1565C0",
                                        "flex" => intval($progressPercent),
                                        "height" => "8px",
                                        "cornerRadius" => "4px",
                                        "contents" => []
                                    ],
                                    [
                                        "type" => "box",
                                        "layout" => "vertical",
                                        "backgroundColor" => "#EEEEEE",
                                        "flex" => 100 - intval($progressPercent),
                                        "height" => "8px",
                                        "contents" => []
                                    ]
                                ]
                            ],

                            [
                                "type" => "text",
                                "text" => "ขาดอีก " . $remainingFormatted . " บาท",
                                "size" => "xs",
                                "color" => "#999999",
                                "margin" => "sm"
                            ]
                        ]
                    ]

                ]
            ];

            $contents[] = $summaryBox;
        }


        return [
            "type" => "flex",
            "altText" => "📊 สรุปรายการประจำวัน",
            "contents" => [
                "type" => "bubble",
                "body" => [
                    "type" => "box",
                    "layout" => "vertical",
                    "contents" => $contents,
                    "paddingAll" => "15px",
                    "backgroundColor" => "#FFFFFF"
                ]
            ]
        ];
    }

    private function buildInfoRow($label, $value, $valueColor = "#555555", $bold = false)
    {
        return [
            "type" => "box",
            "layout" => "horizontal",
            "spacing" => "sm",
            "contents" => [
                [
                    "type" => "text",
                    "text" => $label,
                    "size" => "sm",
                    "color" => "#757575",
                    "flex" => 5
                ],
                [
                    "type" => "text",
                    "text" => $value,
                    "size" => "sm",
                    "color" => $valueColor,
                    "weight" => $bold ? "bold" : "regular",
                    "flex" => 5,
                    "wrap" => true
                ]
            ]
        ];
    }
}
