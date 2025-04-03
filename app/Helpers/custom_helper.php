<?php
use Pusher\Pusher;

if (!function_exists('datetime_compare')) {
    function datetime_compare($date)
    {
        date_default_timezone_set('Asia/Bangkok');

        $datetime_compare = $date;   //ลองแก้ไขค่าวันเวลาที่นี่
        $ts = strtotime($datetime_compare);
        $now = strtotime('now');
        if (!$ts || $ts > $now) {
            exit;
        }

        $diff = $now - $ts;

        $second = 1;
        $minute = 60 * $second;
        $hour = 60 * $minute;
        $day = 24 * $hour;
        $yesterday = 48 * $hour;
        $month = 30 * $day;
        $year = 365 * $day;
        $ago = "";

        if ($diff >= $year) {
            $ago = round($diff / $year) . " ปีที่แล้ว";
        } else if ($diff >= $month) {
            $ago = round($diff / $month) . " เดือนที่แล้ว";
        } else if ($diff > $yesterday) {
            $ago = intval($diff / $day) . " วันที่แล้ว";
        } else if ($diff <= $yesterday && $diff > $day) {
            $ago = " เมื่อวานนี้";
        } else if ($diff >= $hour) {
            $ago = intval($diff / $hour) . " ชั่วโมงที่แล้ว";
        } else if ($diff >= $minute) {
            $ago = intval($diff / $minute) . " นาทีที่แล้ว";
        } else if ($diff >= 5 * $second) {
            $ago = intval($diff / $second) . " วินาทีที่แล้ว";
        } else {
            $ago = "เมื่อสักครู่";
        }

        return $ago;
    }
}

if (!function_exists('dateThai')) {

    function dateThai($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
    }
}

if (!function_exists('dateThaiNoTime')) {

    function dateThaiNoTime($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strDay $strMonthThai $strYear";
    }
}

if (!function_exists('dateThaiDM')) {

    function dateThaiDM($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = ["", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."];
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strDay $strMonthThai $strYear";
    }
}

if (!function_exists('monthThai')) {

    function monthThai($strDate)
    {

        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strMonthThai";
    }
}

if (!function_exists('dayThai')) {

    function dayThai($strDate)
    {

        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strDay";
    }
}

if (!function_exists('yearThai')) {

    function yearThai($strDate)
    {

        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strHour = date("H", strtotime($strDate));
        $strMinute = date("i", strtotime($strDate));
        $strSeconds = date("s", strtotime($strDate));
        $strMonthCut = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strYear";
    }
}

if (!function_exists('textFormat')) {

    function textFormat($text = '', $pattern = '', $ex = '')
    {
        $cid = ($text == '') ? '0000000000000' : $text;
        $pattern = ($pattern == '') ? '_-____-_____-__-_' : $pattern;
        $p = explode('-', $pattern);
        $ex = ($ex == '') ? '-' : $ex;
        $first = 0;
        $last = 0;
        for ($i = 0; $i <= count($p) - 1; $i++) {
            $first = $first + $last;
            $last = strlen($p[$i]);
            $returnText[$i] = substr($cid, $first, $last);
        }

        return implode($ex, $returnText);
    }
}

if (!function_exists('dateDifference')) {

    function dateDifference($date1, $date2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date1);
        $datetime2 = date_create($date2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }
}

if (!function_exists('minutesDifference')) {

    function minutesDifference($date1, $date2)
    {
        $date1 = new \DateTime($date1, new \DateTimeZone('Asia/Bangkok'));
        $date2 = new \DateTime($date2, new \DateTimeZone('Asia/Bangkok'));
        $interval = $date1->diff($date2);
        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');

        return ($hours * 60 + $minutes);
    }
}

if (!function_exists('pr')) {

    function pr($data = [])
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

if (!function_exists('px')) {

    function px($data = [])
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
    }
}

if (!function_exists('dr')) {

    function dr($data = [])
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }
}

if (!function_exists('dx')) {

    function dx($data = [])
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        exit;
    }
}

if (!function_exists('ip_info')) {
    function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode,
                            "ip" => $ip,
                            "geoplugin_latitude" => @$ipdat->geoplugin_latitude,
                            "geoplugin_longitude" => @$ipdat->geoplugin_longitude,
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
}

if (!function_exists('DateThai2')) {
    function DateThai2($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai " . substr($strYear, 2, strlen($strYear));
    }
}

if (!function_exists('calculateAge')) {
    function calculateAge($strDate)
    {

        $dateOfBirth = $strDate;
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dateOfBirth), date_create($today));

        return $diff->format('%y') ?: false;
    }
}

if (!function_exists('numToThaiBath')) {
    function numToThaiBath($number)
    {
        $txtnum1 =
            array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า', 'สิบ');
        $txtnum2 =
            array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
        $number = str_replace(",", "", $number);
        $number = str_replace(" ", "", $number);
        $number = str_replace("บาท", "", $number);
        $number = explode(".", $number);
        if (sizeof($number) > 2) {
            return 'ทศนิยมหลายตัวนะจ๊ะ';
            exit;
        }
        $strlen = strlen($number[0]);
        $convert = '';
        for ($i = 0; $i < $strlen; $i++) {
            $n = substr($number[0], $i, 1);
            if ($n != 0) {
                if ($i == ($strlen - 1) and $n == 1) {
                    $convert .=
                        'เอ็ด';
                } elseif ($i == ($strlen - 2) and $n == 2) {
                    $convert .= 'ยี่';
                } elseif ($i == ($strlen - 2) and $n == 1) {
                    $convert .= '';
                } else {
                    $convert .= $txtnum1[$n];
                }
                $convert .= $txtnum2[$strlen - $i - 1];
            }
        }
        $convert .= 'บาท';
        if ($number[1] == '0' or $number[1] == '00' or
            $number[1] == '') {
            $convert .= 'ถ้วน';
        } else {
            $strlen = strlen($number[1]);
            for ($i = 0; $i < $strlen; $i++) {
                $n = substr($number[1], $i, 1);
                if ($n != 0) {
                    if ($i == ($strlen - 1) and $n == 1) {
                        $convert
                            .= 'เอ็ด';
                    } elseif ($i == ($strlen - 2) and
                        $n == 2) {
                        $convert .= 'ยี่';
                    } elseif ($i == ($strlen - 2) and
                        $n == 1) {
                        $convert .= '';
                    } else {
                        $convert .= $txtnum1[$n];
                    }
                    $convert .= $txtnum2[$strlen - $i - 1];
                }
            }
            $convert .= 'สตางค์';
        }
        return $convert;
    }
}
//PUSHER
function getPusher()
{
    return new Pusher(
        getenv('PUSHER_KEY'),
        getenv('PUSHER_SECRET'),
        getenv('PUSHER_APP_ID'),
        ['cluster' => getenv('PUSHER_CLUSTER')]
    );
}

//DocumentSetUp
function getDocumentSetUp()
{
    $DocumentSetUpModel = new \App\Models\DocumentSetUpModel();
    // $data['DocumentSetUp'] = $DocumentSetUpModel->getDocumentSetUpAll();
    // print_r($data['DocumentSetUp']); exit();
    return $data['DocumentSetUp'] = $DocumentSetUpModel->getDocumentSetUpAll();
}

//CashFlow
function getCashFlow()
{
    $CashFlowModel = new \App\Models\CashFlowModel();
    return $data['cashflows'] = $CashFlowModel->getCashFlowAll();
}

//car_logger_store
function car_logger_store($data)
{
    $db = \Config\Database::connect();

    $builder = $db->table('car_logs');

    $builder->insert([
        'car_event_id' => get_car_event_id($data['event']),
        'car_detail' => $data['detail'],
        'ip' => $data['ip'],
        'car_log_code' => $data['car_stock_code'],
        'username' => $data['username']
    ]);
}

//car_event
function get_car_event_id($event)
{
    $car_event_id = 0;

    switch ($event) {

        case 'เพิ่ม':
            $car_event_id = 1;
            break;

        case 'อัพเดท':
            $car_event_id = 2;
            break;

        case 'ลบ':
            $car_event_id = 3;
            break;

        case 'ขาย':
            $car_event_id = 4;
            break;

        default:
            $car_event_id = 5;
    }

    return $car_event_id;
}

function notify($token,$data)
{
    $chOne = curl_init();
    curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($chOne, CURLOPT_POST, 1);
    curl_setopt($chOne, CURLOPT_POSTFIELDS, $data);
    curl_setopt($chOne, CURLOPT_FOLLOWLOCATION, 1);
    $headers = array('Content-type: multipart/form-data', 'Authorization: Bearer ' . $token,);
    curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($chOne);
    //Check error
    // if (curl_error($chOne)) {
    //     echo 'error:' . curl_error($chOne);
    // } else {
    //     $result_ = json_decode($result, true);
    //     echo "status : " . $result_['status'];
    //     echo "message : " . $result_['message'];
    // }
    //Close connection
    curl_close($chOne);
}

function send_line_message($token, $message)
{
    $data = [
        'to' => 'C42b75bab9b01cb8e0a78d76002f81fe0', // ใส่ ID ของผู้ใช้หรือกลุ่ม
        'messages' => [[
            'type' => 'text',
            'text' => $message
        ]]
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ];

    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    if (!$result) {
        log_message('error', 'cURL Error in send_line_message: ' . curl_error($ch));
        curl_close($ch);
        return false; // ส่งข้อความล้มเหลว
    }

    curl_close($ch);

    // แปลงผลลัพธ์เป็น JSON
    $response = json_decode($result, true);

    // ตรวจสอบว่ามีข้อผิดพลาดใน Response หรือไม่
    if (isset($response['message']) && strpos($response['message'], 'Invalid access token') !== false) {
        log_message('error', 'LINE API Error: Invalid access token detected.');
        return false; // Token ไม่ valid
    }

    return true; // ส่งข้อความสำเร็จ
}

function get_line_access_token()
{
    $clientId = getenv('LINE_CLIENT_ID');
    $clientSecret = getenv('LINE_CLIENT_SECRET');

    $client = \Config\Services::curlrequest();
    $response = $client->post("https://api.line.me/v2/oauth/accessToken", [
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ],
        'form_params' => [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret
        ]
    ]);

    if ($response->getStatusCode() == 200) {
        $tokenData = json_decode($response->getBody(), true);
        return $tokenData['access_token']; // คืนค่า Token ไปใช้
    }

    return false;
}
