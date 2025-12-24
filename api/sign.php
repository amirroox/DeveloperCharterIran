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

if (empty($data['full_name']) || empty($data['email'])) {
    sendResponse(false, 'نام و ایمیل الزامی است', [], 400);
}

if (!validateEmail($data['email'])) {
    sendResponse(false, 'فرمت ایمیل صحیح نیست', [], 400);
}

$ip = getUserIP();

// Check rate limit (3 signatures per hour)
if (!checkRateLimit($ip, 'sign', 3, 3600)) {
    sendResponse(false, 'شما بیش از حد مجاز درخواست ارسال کرده‌اید. لطفا بعدا تلاش کنید', [], 429);
}

try {
    $conn = getConnection();

    $stmt = $conn->prepare("SELECT id FROM signatures WHERE email = :email");
    $stmt->execute(['email' => $data['email']]);
    
    if ($stmt->fetch()) {
        sendResponse(false, 'این ایمیل قبلا منشور را امضا کرده است', [], 409);
    }

    $stmt = $conn->prepare("
        INSERT INTO signatures 
        (full_name, email, job_title, company, experience_years, city, ip_address) 
        VALUES 
        (:full_name, :email, :job_title, :company, :experience_years, :city, :ip_address)
    ");

    $result = $stmt->execute([
        'full_name' => sanitizeInput($data['full_name']),
        'email' => sanitizeInput($data['email']),
        'job_title' => sanitizeInput($data['job_title'] ?? ''),
        'company' => sanitizeInput($data['company'] ?? ''),
        'experience_years' => isset($data['experience_years']) && $data['experience_years'] !== '' 
            ? (int)$data['experience_years'] 
            : null,
        'city' => sanitizeInput($data['city'] ?? ''),
        'ip_address' => $ip
    ]);
    
    if ($result) {
        $conn->exec("
            INSERT INTO daily_stats (stat_date, total_signatures) 
            VALUES (CURDATE(), 1)
            ON DUPLICATE KEY UPDATE total_signatures = total_signatures + 1
        ");
        
        sendResponse(
            true, 
            'امضای شما با موفقیت ثبت شد',
            ['signature_id' => $conn->lastInsertId()],
            201
        );
    } else {
        sendResponse(false, 'خطا در ثبت امضا', [], 500);
    }
    
} catch(PDOException $e) {
    error_log("Sign error: " . $e->getMessage());
    sendResponse(false, 'خطا در ثبت امضا. لطفا دوباره تلاش کنید', [], 500);
}
?>