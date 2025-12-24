<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['report_id'])) {
        sendResponse(false, 'شناسه گزارش الزامی است', [], 400);
    }
    
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("
            SELECT 
                user_name,
                comment,
                created_at
            FROM report_comments
            WHERE report_id = :report_id
            ORDER BY created_at DESC
        ");
        $stmt->execute(['report_id' => (int)$_GET['report_id']]);
        $comments = $stmt->fetchAll();
        
        sendResponse(true, 'نظرات با موفقیت دریافت شد', $comments, 200);
        
    } catch(PDOException $e) {
        error_log("Fetch comments error: " . $e->getMessage());
        sendResponse(false, 'خطا در دریافت نظرات', [], 500);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendResponse(false, 'داده‌های ارسالی نامعتبر است', [], 400);
    }
    
    if (empty($data['report_id']) || empty($data['user_name']) || empty($data['user_email']) || empty($data['comment'])) {
        sendResponse(false, 'تمام فیلدها الزامی هستند', [], 400);
    }
    
    if (!validateEmail($data['user_email'])) {
        sendResponse(false, 'فرمت ایمیل صحیح نیست', [], 400);
    }
    
    $ip = getUserIP();
    
    // Rate limit: 5 comments per hour
    if (!checkRateLimit($ip, 'comment', 5, 3600)) {
        sendResponse(false, 'شما بیش از حد مجاز نظر ارسال کرده‌اید', [], 429);
    }
    
    try {
        $conn = getConnection();

        $stmt = $conn->prepare("SELECT id FROM violation_reports WHERE id = :id");
        $stmt->execute(['id' => (int)$data['report_id']]);
        if (!$stmt->fetch()) {
            sendResponse(false, 'گزارش مورد نظر یافت نشد', [], 404);
        }
        
        $stmt = $conn->prepare("
            INSERT INTO report_comments (report_id, user_name, user_email, comment, ip_address)
            VALUES (:report_id, :user_name, :user_email, :comment, :ip_address)
        ");
        
        $result = $stmt->execute([
            'report_id' => (int)$data['report_id'],
            'user_name' => sanitizeInput($data['user_name']),
            'user_email' => sanitizeInput($data['user_email']),
            'comment' => sanitizeInput($data['comment']),
            'ip_address' => $ip
        ]);
        
        if ($result) {
            sendResponse(true, 'نظر شما با موفقیت ثبت شد', ['comment_id' => $conn->lastInsertId()], 201);
        }
        
    } catch(PDOException $e) {
        error_log("Add comment error: " . $e->getMessage());
        sendResponse(false, 'خطا در ثبت نظر', [], 500);
    }
}
?>