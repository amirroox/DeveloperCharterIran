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

if (empty($data['report_id']) || empty($data['user_email']) || empty($data['reaction_type'])) {
    sendResponse(false, 'تمام فیلدها الزامی هستند', [], 400);
}

if (!validateEmail($data['user_email'])) {
    sendResponse(false, 'فرمت ایمیل صحیح نیست', [], 400);
}

if (!in_array($data['reaction_type'], ['like', 'dislike'])) {
    sendResponse(false, 'نوع واکنش نامعتبر است', [], 400);
}

$ip = getUserIP();

try {
    $conn = getConnection();

    $stmt = $conn->prepare("SELECT id FROM violation_reports WHERE id = :id");
    $stmt->execute(['id' => (int)$data['report_id']]);
    if (!$stmt->fetch()) {
        sendResponse(false, 'گزارش مورد نظر یافت نشد', [], 404);
    }

    $stmt = $conn->prepare("
        SELECT reaction_type FROM report_reactions 
        WHERE report_id = :report_id AND user_email = :user_email
    ");
    $stmt->execute([
        'report_id' => (int)$data['report_id'],
        'user_email' => sanitizeInput($data['user_email'])
    ]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        if ($existing['reaction_type'] === $data['reaction_type']) {
            $stmt = $conn->prepare("
                DELETE FROM report_reactions 
                WHERE report_id = :report_id AND user_email = :user_email
            ");
            $stmt->execute([
                'report_id' => (int)$data['report_id'],
                'user_email' => sanitizeInput($data['user_email'])
            ]);
            
            sendResponse(true, 'واکنش شما حذف شد', ['action' => 'removed'], 200);
        } else {
            $stmt = $conn->prepare("
                UPDATE report_reactions 
                SET reaction_type = :reaction_type, ip_address = :ip_address
                WHERE report_id = :report_id AND user_email = :user_email
            ");
            $stmt->execute([
                'reaction_type' => $data['reaction_type'],
                'ip_address' => $ip,
                'report_id' => (int)$data['report_id'],
                'user_email' => sanitizeInput($data['user_email'])
            ]);
            
            sendResponse(true, 'واکنش شما به‌روزرسانی شد', ['action' => 'updated'], 200);
        }
    } else {
        $stmt = $conn->prepare("
            INSERT INTO report_reactions (report_id, user_email, reaction_type, ip_address)
            VALUES (:report_id, :user_email, :reaction_type, :ip_address)
        ");
        $stmt->execute([
            'report_id' => (int)$data['report_id'],
            'user_email' => sanitizeInput($data['user_email']),
            'reaction_type' => $data['reaction_type'],
            'ip_address' => $ip
        ]);
        
        sendResponse(true, 'واکنش شما ثبت شد', ['action' => 'added'], 201);
    }
    
} catch(PDOException $e) {
    error_log("Reaction error: " . $e->getMessage());
    sendResponse(false, 'خطا در ثبت واکنش', [], 500);
}
?>