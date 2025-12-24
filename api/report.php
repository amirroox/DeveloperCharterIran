<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed', [], 405);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    sendResponse(false, 'داده‌های ارسالی نامعتبر است', [], 400);
}

if (empty($data['reporter_email'])) {
    sendResponse(false, 'ایمیل الزامی است', [], 400);
}

if (empty($data['description'])) {
    sendResponse(false, 'توضیحات کامل پروژه الزامی است', [], 400);
}

if (empty($data['reporter_type'])) {
    sendResponse(false, 'نقش شما الزامی است', [], 400);
}

if (empty($data['violator_type'])) {
    sendResponse(false, 'نقش متخلف الزامی است', [], 400);
}

if (empty($data['violator_name'])) {
    sendResponse(false, 'نام متخلف الزامی است', [], 400);
}

if (empty($data['violator_contact'])) {
    sendResponse(false, 'اطلاعات تماس متخلف الزامی است', [], 400);
}

if (!validateEmail($data['reporter_email'])) {
    sendResponse(false, 'فرمت ایمیل صحیح نیست', [], 400);
}

$ip = getUserIP();

// Check rate limit (5 reports per hour)
if (!checkRateLimit($ip, 'report', 5, 3600)) {
    sendResponse(false, 'شما بیش از حد مجاز گزارش ارسال کرده‌اید. لطفا بعدا تلاش کنید', [], 429);
}

try {
    $conn = getConnection();

    $validTypes = ['underprice', 'overprice', 'unfair_practice', 'other'];
    $validUserTypes = ['employer', 'contractor'];
    
    $violationType = in_array($data['violation_type'] ?? '', $validTypes) 
        ? $data['violation_type'] 
        : 'underprice';
    
    if (!in_array($data['reporter_type'] ?? '', $validUserTypes)) {
        sendResponse(false, 'نوع گزارش دهنده نامعتبر است', [], 400);
    }
    
    if (!in_array($data['violator_type'] ?? '', $validUserTypes)) {
        sendResponse(false, 'نوع متخلف نامعتبر است', [], 400);
    }

    $stmt = $conn->prepare("
        INSERT INTO violation_reports 
        (reporter_email, reporter_type, violator_name, violator_type, violator_contact,
         project_description, estimated_fair_price, offered_price, 
         violation_type, description, evidence_url, ip_address) 
        VALUES 
        (:reporter_email, :reporter_type, :violator_name, :violator_type, :violator_contact,
         :project_description, :estimated_fair_price, :offered_price,
         :violation_type, :description, :evidence_url, :ip_address)
    ");

    $evidenceUrl = '';
    if (!empty($data['evidence_url'])) {
        $evidenceUrl = filter_var($data['evidence_url'], FILTER_SANITIZE_URL);
        if (!filter_var($evidenceUrl, FILTER_VALIDATE_URL)) {
            $evidenceUrl = '';
        }
    }

    $result = $stmt->execute([
        'reporter_email' => sanitizeInput($data['reporter_email']),
        'reporter_type' => $data['reporter_type'],
        'violator_name' => sanitizeInput($data['violator_name']),
        'violator_type' => $data['violator_type'],
        'violator_contact' => sanitizeInput($data['violator_contact'] ?? ''),
        'project_description' => sanitizeInput($data['project_description'] ?? ''),
        'estimated_fair_price' => isset($data['estimated_fair_price']) && $data['estimated_fair_price'] !== '' 
            ? floatval($data['estimated_fair_price']) 
            : null,
        'offered_price' => isset($data['offered_price']) && $data['offered_price'] !== '' 
            ? floatval($data['offered_price']) 
            : null,
        'violation_type' => $violationType,
        'description' => sanitizeInput($data['description']),
        'evidence_url' => $evidenceUrl,
        'ip_address' => $ip
    ]);
    
    if ($result) {
        $conn->exec("
            INSERT INTO daily_stats (stat_date, total_reports) 
            VALUES (CURDATE(), 1)
            ON DUPLICATE KEY UPDATE total_reports = total_reports + 1
        ");
        
        sendResponse(
            true,
            'گزارش شما با موفقیت ثبت شد و در حال بررسی است',
            ['report_id' => $conn->lastInsertId()],
            201
        );
    } else {
        sendResponse(false, 'خطا در ثبت گزارش', [], 500);
    }
    
} catch(PDOException $e) {
    error_log("Report error: " . $e->getMessage());
    sendResponse(false, 'خطا در ثبت گزارش. لطفا دوباره تلاش کنید', [], 500);
}
?>