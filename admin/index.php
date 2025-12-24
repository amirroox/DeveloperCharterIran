<?php
session_start();

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: $URL/admin/login.php");
    exit();
}

require_once '../api/config.php';
include_once '../helper/jdf.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $conn = getConnection();

    if ($_POST['action'] === 'update_status') {
        $stmt = $conn->prepare("UPDATE violation_reports SET status = :status WHERE id = :id");
        $stmt->execute([
            'status' => $_POST['status'],
            'id' => (int)$_POST['report_id']
        ]);
    } elseif ($_POST['action'] === 'delete_report') {
        $stmt = $conn->prepare("DELETE FROM violation_reports WHERE id = :id");
        $stmt->execute(['id' => (int)$_POST['report_id']]);
    } elseif ($_POST['action'] === 'delete_signature') {
        $stmt = $conn->prepare("DELETE FROM signatures WHERE id = :id");
        $stmt->execute(['id' => (int)$_POST['signature_id']]);
    } elseif ($_POST['action'] === 'delete_comment') {
        $stmt = $conn->prepare("DELETE FROM report_comments WHERE id = :id");
        $stmt->execute(['id' => (int)$_POST['comment_id']]);
    }

    header('Location: index.php?tab=' . ($_GET['tab'] ?? 'reports'));
    exit();
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Get statistics
try {
    $conn = getConnection();

    $stmt = $conn->query("
        SELECT 
            (SELECT COUNT(*) FROM signatures) as total_signatures,
            (SELECT COUNT(*) FROM violation_reports) as total_reports,
            (SELECT COUNT(*) FROM violation_reports WHERE status = 'pending') as pending_reports,
            (SELECT COUNT(*) FROM violation_reports WHERE status = 'verified') as verified_reports,
            (SELECT COUNT(*) FROM report_comments) as total_comments
    ");
    $stats = $stmt->fetch();

    # Reports
    $total = $conn->query("SELECT COUNT(*) FROM violation_reports")->fetchColumn();
    $total_pages = ceil($total / $per_page);
    $stmt = $conn->prepare("
        SELECT 
            vr.*,
            (SELECT COUNT(*) FROM report_reactions WHERE report_id = vr.id AND reaction_type = 'like') as likes,
            (SELECT COUNT(*) FROM report_reactions WHERE report_id = vr.id AND reaction_type = 'dislike') as dislikes,
            (SELECT COUNT(*) FROM report_comments WHERE report_id = vr.id) as comments_count
        FROM violation_reports vr
        ORDER BY vr.created_at DESC
        LIMIT $per_page OFFSET $offset
    ");
    $stmt->execute();
    $reports = $stmt->fetchAll();

    # Signatures
    $total_sign = $conn->query("SELECT COUNT(*) FROM signatures")->fetchColumn();
    $total_pages_sign = ceil($total_sign / $per_page);
    $stmt = $conn->prepare("SELECT * FROM signatures ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
    $stmt->execute();
    $signatures = $stmt->fetchAll();

    # Comments
    $total_comments = $conn->query("SELECT COUNT(*) FROM report_comments")->fetchColumn();
    $total_pages_comments = ceil($total_comments / $per_page);
    $stmt = $conn->prepare("
    SELECT rc.*, vr.violator_name
    FROM report_comments rc
    JOIN violation_reports vr ON rc.report_id = vr.id
    ORDER BY rc.created_at DESC
    LIMIT $per_page OFFSET $offset
");

    $stmt->execute();
    $comments = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error $e");
}

$current_tab = $_GET['tab'] ?? 'reports';

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیریت</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/admin.css">
</head>

<body>
    <div class="admin-header">
        <div class="container">
            <h1>پنل مدیریت - <a href="/">صفحه اصلی</a></h1>
            <div class="admin-nav">
                <span>خوش آمدید، <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                <a href="/admin/logout.php" class="logout-btn">خروج</a>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px;">
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value"><?= number_format($stats['total_signatures']) ?></div>
                <div class="stat-label">کل امضاها</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?= number_format($stats['total_reports']) ?></div>
                <div class="stat-label">کل گزارش‌ها</div>
            </div>
            <div class="stat-box pending">
                <div class="stat-value"><?= number_format($stats['pending_reports']) ?></div>
                <div class="stat-label">در انتظار بررسی</div>
            </div>
            <div class="stat-box verified">
                <div class="stat-value"><?= number_format($stats['verified_reports']) ?></div>
                <div class="stat-label">تایید شده</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?= number_format($stats['total_comments']) ?></div>
                <div class="stat-label">کل نظرات</div>
            </div>
        </div>

        <div class="admin-tabs">
            <a href="?tab=reports" class="admin-tab <?= $current_tab === 'reports' ? 'active' : '' ?>">
                گزارش‌ها (<?= count($reports) ?>)
            </a>
            <a href="?tab=signatures" class="admin-tab <?= $current_tab === 'signatures' ? 'active' : '' ?>">
                امضاها (<?= count($signatures) ?>)
            </a>
            <a href="?tab=comments" class="admin-tab <?= $current_tab === 'comments' ? 'active' : '' ?>">
                نظرات (<?= count($comments) ?>)
            </a>
        </div>

        <?php if ($current_tab === 'reports'): ?>
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>شناسه</th>
                            <th>شرکت/فرد</th>
                            <th>نوع تخلف</th>
                            <th>وضعیت</th>
                            <th>لایک/دیسلایک</th>
                            <th>نظرات</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td>#<?= $report['id'] ?></td>
                                <td><?= htmlspecialchars($report['violator_name'] ?? 'نامشخص') ?></td>
                                <td><?= getTypeLabel($report['violation_type']) ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" class="status-select status-<?= $report['status'] ?>">
                                            <option value="pending" <?= $report['status'] === 'pending' ? 'selected' : '' ?>>در انتظار</option>
                                            <option value="verified" <?= $report['status'] === 'verified' ? 'selected' : '' ?>>تایید</option>
                                            <option value="rejected" <?= $report['status'] === 'rejected' ? 'selected' : '' ?>>رد</option>
                                        </select>
                                    </form>
                                </td>
                                <td><?= $report['likes'] ?> / <?= $report['dislikes'] ?></td>
                                <td><?= $report['comments_count'] ?></td>
                                <td><?= jdate('d-F-Y', strtotime($report['created_at'])) ?></td>
                                <td>
                                    <button onclick="viewReport(<?= $report['id'] ?>)" class="btn-view">&#10038;</button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('آیا مطمئن هستید؟')">
                                        <input type="hidden" name="action" value="delete_report">
                                        <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                                        <button type="submit" class="btn-delete">&#10006;</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php
                    $start = max(1, $page - 2);
                    $end = min($total_pages, $page + 2);

                    if ($start > 1) {
                        echo '<a href="?tab=reports&page=1" class="page-link">1</a>';
                        if ($start > 2) echo '<span class="dots">...</span>';
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        $active = $i == $page ? 'active' : '';
                        echo '<a href="?tab=reports&page=' . $i . '" class="page-link ' . $active . '">' . $i . '</a>';
                    }

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) echo '<span class="dots">...</span>';
                        echo '<a href="?tab=reports&page=' . $total_pages . '" class="page-link">' . $total_pages . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($current_tab === 'signatures'): ?>
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>شناسه</th>
                            <th>نام</th>
                            <th>ایمیل</th>
                            <th>عنوان شغلی</th>
                            <th>شرکت</th>
                            <th>شهر</th>
                            <th>سابقه</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($signatures as $sig): ?>
                            <tr>
                                <td>#<?= $sig['id'] ?></td>
                                <td><?= htmlspecialchars($sig['full_name']) ?></td>
                                <td><?= htmlspecialchars($sig['email']) ?></td>
                                <td><?= htmlspecialchars($sig['job_title'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($sig['company'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($sig['city'] ?? '-') ?></td>
                                <td><?= $sig['experience_years'] ? $sig['experience_years'] . ' سال' : '-' ?></td>
                                <td><?= jdate('d-F-Y', strtotime($sig['created_at'])) ?></td>
                                <td>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('آیا مطمئن هستید؟')">
                                        <input type="hidden" name="action" value="delete_signature">
                                        <input type="hidden" name="signature_id" value="<?= $sig['id'] ?>">
                                        <button type="submit" class="btn-delete">&#10006;</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($total_pages_sign > 1): ?>
                <div class="pagination">
                    <?php
                    $start = max(1, $page - 2);
                    $end = min($total_pages_sign, $page + 2);

                    if ($start > 1) {
                        echo '<a href="?tab=signatures&page=1" class="page-link">1</a>';
                        if ($start > 2) echo '<span class="dots">...</span>';
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        $active = $i == $page ? 'active' : '';
                        echo '<a href="?tab=signatures&page=' . $i . '" class="page-link ' . $active . '">' . $i . '</a>';
                    }

                    if ($end < $total_pages_sign) {
                        if ($end < $total_pages_sign - 1) echo '<span class="dots">...</span>';
                        echo '<a href="?tab=signatures&page=' . $total_pages_sign . '" class="page-link">' . $total_pages_sign . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($current_tab === 'comments'): ?>
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>شناسه</th>
                            <th>نام</th>
                            <th>گزارش مربوطه</th>
                            <th>نظر</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comments as $comment): ?>
                            <tr>
                                <td>#<?= $comment['id'] ?></td>
                                <td><?= htmlspecialchars($comment['user_name']) ?></td>
                                <td><?= htmlspecialchars($comment['company_name'] ?? 'گزارش #' . $comment['report_id']) ?></td>
                                <td><?= htmlspecialchars(substr($comment['comment'], 0, 50)) ?>...</td>
                                <td><?= jdate('d-F-Y', strtotime($comment['created_at'])) ?></td>
                                <td>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('آیا مطمئن هستید؟')">
                                        <input type="hidden" name="action" value="delete_comment">
                                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                        <button type="submit" class="btn-delete">&#10006;</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($total_pages_comments > 1): ?>
                <div class="pagination">
                    <?php
                    $start = max(1, $page - 2);
                    $end = min($total_pages_comments, $page + 2);

                    if ($start > 1) {
                        echo '<a href="?tab=comments&page=1" class="page-link">1</a>';
                        if ($start > 2) echo '<span class="dots">...</span>';
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        $active = $i == $page ? 'active' : '';
                        echo '<a href="?tab=comments&page=' . $i . '" class="page-link ' . $active . '">' . $i . '</a>';
                    }

                    if ($end < $total_pages_comments) {
                        if ($end < $total_pages_comments - 1) echo '<span class="dots">...</span>';
                        echo '<a href="?tab=comments&page=' . $total_pages_comments . '" class="page-link">' . $total_pages_comments . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        function viewReport(reportId) {
            window.open('../reports.php#report-' + reportId, '_blank');
        }
    </script>
</body>

</html>

<?php
function getTypeLabel($type)
{
    $labels = [
        'underprice' => 'قیمت پایین',
        'overprice' => 'قیمت بالا',
        'unfair_practice' => 'رویه غیرمنصفانه',
        'other' => 'سایر'
    ];
    return $labels[$type] ?? $type;
}
?>