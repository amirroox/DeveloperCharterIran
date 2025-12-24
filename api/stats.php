<?php
// Set JSON headers for API response
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Method not allowed', [], 405);
}

try {
    $conn = getConnection();
    
    // Get summary statistics
    $stmt = $conn->query("
        SELECT 
            (SELECT COUNT(*) FROM signatures) as total_signatures,
            (SELECT COUNT(*) FROM violation_reports) as total_reports,
            (SELECT COUNT(*) FROM violation_reports WHERE status = 'verified') as verified_reports,
            (SELECT COUNT(DISTINCT city) FROM signatures WHERE city != '') as cities_count
    ");
    $stats = $stmt->fetch();
    
    // Get daily statistics for last 30 days
    $stmt = $conn->query("
        SELECT 
            stat_date,
            total_signatures,
            total_reports
        FROM daily_stats
        WHERE stat_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ORDER BY stat_date DESC
    ");
    $daily_stats = $stmt->fetchAll();
    
    // Get recent signatures
    $stmt = $conn->query("
        SELECT 
            full_name,
            job_title,
            company,
            city,
            DATE_FORMAT(created_at, '%Y-%m-%d') as date
        FROM signatures
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $recent_signatures = $stmt->fetchAll();
    
    // Get top cities
    $stmt = $conn->query("
        SELECT 
            city,
            COUNT(*) as count
        FROM signatures
        WHERE city != ''
        GROUP BY city
        ORDER BY count DESC
        LIMIT 10
    ");
    $cities = $stmt->fetchAll();
    
    sendResponse(
        true,
        'آمار با موفقیت دریافت شد',
        [
            'summary' => $stats,
            'daily_stats' => $daily_stats,
            'recent_signatures' => $recent_signatures,
            'top_cities' => $cities
        ],
        200
    );
    
} catch(PDOException $e) {
    error_log("Stats error: " . $e->getMessage());
    sendResponse(false, 'خطا در دریافت آمار', [], 500);
}
?>