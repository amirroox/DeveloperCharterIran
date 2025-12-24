<?php
define('URL', $_SERVER["HTTP_HOST"]);


// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dev_manifesto');

/**
 * Get database connection
 * @return PDO
 */
function getConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'خطا در اتصال به پایگاه داده',
            'error' => $e->getMessage()
        ]);
        exit();
    }
}

/**
 * Get user IP address
 * @return string
 */
function getUserIP() {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Handle multiple IPs in forwarded header
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ips[0]);
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    // Validate IP address
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return $ip;
}

/**
 * Validate email address
 * @param string $email
 * @return bool
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Sanitize input data
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    if ($data === null || $data === '') {
        return '';
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Check rate limit for IP address
 * @param string $ip
 * @param string $action - 'sign', 'report', or 'comment'
 * @param int $limit
 * @param int $timeWindow - in seconds
 * @return bool
 */
function checkRateLimit($ip, $action, $limit = 5, $timeWindow = 3600) {
    try {
        $conn = getConnection();
        
        $table = match($action) {
            'sign' => 'signatures',
            'report' => 'violation_reports',
            'comment' => 'report_comments',
            default => null
        };
        
        if (!$table) {
            return true;
        }
        
        $stmt = $conn->prepare("
            SELECT COUNT(*) as count 
            FROM {$table}
            WHERE ip_address = :ip 
            AND created_at > DATE_SUB(NOW(), INTERVAL :window SECOND)
        ");
        $stmt->execute(['ip' => $ip, 'window' => $timeWindow]);
        $result = $stmt->fetch();
        
        return $result['count'] < $limit;
    } catch(PDOException $e) {
        // If rate limit check fails, allow the request
        error_log("Rate limit check error: " . $e->getMessage());
        return true;
    }
}

/**
 * Send JSON response
 * @param bool $success
 * @param string $message
 * @param array $data
 * @param int $httpCode
 */
function sendResponse($success, $message, $data = [], $httpCode = 200) {
    http_response_code($httpCode);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit();
}
?>