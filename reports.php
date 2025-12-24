<?php
require_once 'api/config.php';
include_once 'helper/jdf.php'; 

// Get filter parameters
$status = $_GET['status'] ?? 'all';
$violation_type = $_GET['type'] ?? 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

try {
    $conn = getConnection();

    $where = ["1=1"];
    $params = [];
    
    if ($status !== 'all') {
        $where[] = "vr.status = :status";
        $params['status'] = $status;
    }
    
    if ($violation_type !== 'all') {
        $where[] = "vr.violation_type = :violation_type";
        $params['violation_type'] = $violation_type;
    }
    
    $where_clause = implode(' AND ', $where);

    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM violation_reports vr WHERE $where_clause");
    $stmt->execute($params);
    $total = $stmt->fetch()['total'];
    $total_pages = ceil($total / $per_page);

    $stmt = $conn->prepare("
        SELECT 
            vr.*,
            (SELECT COUNT(*) FROM report_reactions WHERE report_id = vr.id AND reaction_type = 'like') as likes,
            (SELECT COUNT(*) FROM report_reactions WHERE report_id = vr.id AND reaction_type = 'dislike') as dislikes,
            (SELECT COUNT(*) FROM report_comments WHERE report_id = vr.id) as comments_count
        FROM violation_reports vr
        WHERE $where_clause
        ORDER BY vr.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    
    foreach ($params as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $reports = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $reports = [];
    $total_pages = 0;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش های تخلف - منشور</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/reports.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>گزارش های تخلف قیمت گذاری</h1>
            <p class="subtitle">مشاهده و بررسی گزارش های ثبت شده</p>
            <a href="index.php" class="back-link">← بازگشت به صفحه اصلی</a>
        </div>
    </header>

    <div class="container">
        <div class="filters">
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label>وضعیت:</label>
                    <select name="status" onchange="this.form.submit()">
                        <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>همه</option>
                        <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>در انتظار بررسی</option>
                        <option value="verified" <?= $status === 'verified' ? 'selected' : '' ?>>تایید شده</option>
                        <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>رد شده</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>نوع:</label>
                    <select name="type" onchange="this.form.submit()">
                        <option value="all" <?= $violation_type === 'all' ? 'selected' : '' ?>>همه</option>
                        <option value="underprice" <?= $violation_type === 'underprice' ? 'selected' : '' ?>>قیمت پایین</option>
                        <option value="overprice" <?= $violation_type === 'overprice' ? 'selected' : '' ?>>قیمت بالا</option>
                        <option value="unfair_practice" <?= $violation_type === 'unfair_practice' ? 'selected' : '' ?>>رویه غیرمنصفانه</option>
                        <option value="other" <?= $violation_type === 'other' ? 'selected' : '' ?>>سایر</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="reports-list">
            <?php if (empty($reports)): ?>
                <p style="text-align: center; color: #ffffffff; padding: 40px;">هیچ گزارشی یافت نشد</p>
            <?php else: ?>
                <?php foreach ($reports as $report): ?>
                    <div class="report-card" data-report-id="<?= $report['id'] ?>">
                        <div class="report-header">
                            <div class="report-meta">
                                <span class="status-badge status-<?= $report['status'] ?>"><?= getStatusLabel($report['status']) ?></span>
                                <span class="type-badge type-<?= $report['violation_type'] ?>"><?= getTypeLabel($report['violation_type']) ?></span>
                                <span class="report-date"><?= jdate('d-F-Y', strtotime($report['created_at'])) ?></span>
                            </div>
                        </div>

                        <div class="report-body">
                            <div class="report-types">
                                <span class="user-type">گزارش دهنده: <?= getReporterTypeLabel($report['reporter_type']) ?></span>
                                <span class="user-type">متخلف: <?= getReporterTypeLabel($report['violator_type']) ?></span>
                            </div>

                             <h3>متخلف: <?= htmlspecialchars($report['violator_name']) ?></h3>
    
                            <?php if ($report['violator_contact']): ?>
                                <p class="contact-info"><strong>اطلاعات تماس:</strong> <?= htmlspecialchars($report['violator_contact']) ?></p>
                            <?php endif; ?>

                            <?php if ($report['company_name']): ?>
                                <h3>شرکت: <?= htmlspecialchars($report['company_name']) ?></h3>
                            <?php endif; ?>
                            
                            <?php if ($report['project_description']): ?>
                                <p class="project-desc"><strong>شرح پروژه:</strong> <?= htmlspecialchars($report['project_description']) ?></p>
                            <?php endif; ?>
                            
                            <p class="report-description"><?= nl2br(htmlspecialchars($report['description'])) ?></p>
                            
                            <?php if ($report['estimated_fair_price'] || $report['offered_price']): ?>
                                <div class="price-info">
                                    <?php if ($report['estimated_fair_price']): ?>
                                        <span>قیمت منصفانه: <strong><?= number_format($report['estimated_fair_price']) ?> تومان</strong></span>
                                    <?php endif; ?>
                                    <?php if ($report['offered_price']): ?>
                                        <span>قیمت پیشنهادی: <strong><?= number_format($report['offered_price']) ?> تومان</strong></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="report-footer">
                            <div class="reactions">
                                <button class="reaction-btn like-btn" onclick="reactToReport(<?= $report['id'] ?>, 'like')">
                                    👍 <span class="count"><?= $report['likes'] ?></span>
                                </button>
                                <button class="reaction-btn dislike-btn" onclick="reactToReport(<?= $report['id'] ?>, 'dislike')">
                                    👎 <span class="count"><?= $report['dislikes'] ?></span>
                                </button>
                            </div>
                            
                            <button class="comments-btn" onclick="toggleComments(<?= $report['id'] ?>)">
                                💬 نظرات (<?= $report['comments_count'] ?>)
                            </button>
                        </div>

                        <div class="comments-section" id="comments-<?= $report['id'] ?>" style="display: none;">
                            <div class="comments-list"></div>
                            <form class="comment-form" onsubmit="submitComment(event, <?= $report['id'] ?>)">
                                <input type="text" name="user_name" placeholder="نام شما *" required>
                                <input type="email" name="user_email" placeholder="ایمیل شما *" required>
                                <textarea name="comment" placeholder="نظر خود را بنویسید..." required></textarea>
                                <button type="submit">ارسال نظر</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php
                $start = max(1, $page - 2);
                $end = min($total_pages, $page + 2);

                if ($start > 1) {
                    echo '<a href="?page=1&status='.$status.'&type='.$violation_type.'" class="page-link">1</a>';
                    if ($start > 2) echo '<span class="dots">...</span>';
                }

                for ($i = $start; $i <= $end; $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    echo '<a href="?page='.$i.'&status='.$status.'&type='.$violation_type.'" class="page-link '.$active.'">'.$i.'</a>';
                }

                if ($end < $total_pages) {
                    if ($end < $total_pages - 1) echo '<span class="dots">...</span>';
                    echo '<a href="?page='.$total_pages.'&status='.$status.'&type='.$violation_type.'" class="page-link">'.$total_pages.'</a>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="./assets/reports.js"></script>
</body>
</html>

<?php
function getStatusLabel($status) {
    $labels = [
        'pending' => 'در انتظار بررسی',
        'verified' => 'تایید شده',
        'rejected' => 'رد شده'
    ];
    return $labels[$status] ?? $status;
}

function getTypeLabel($type) {
    $labels = [
        'underprice' => 'قیمت پایین',
        'overprice' => 'قیمت بالا',
        'unfair_practice' => 'رویه غیرمنصفانه',
        'other' => 'سایر'
    ];
    return $labels[$type] ?? $type;
}

function getReporterTypeLabel($type) {
    $labels = [
        'employer' => 'کارفرما',
        'contractor' => 'پیمانکار/فریلنسر'
    ];
    return $labels[$type] ?? $type;
}
?>