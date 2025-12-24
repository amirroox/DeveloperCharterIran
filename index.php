<?php
require_once 'api/config.php';
include_once 'helper/jdf.php'; 

try {
    $conn = getConnection();
    
    $stmt = $conn->query("
        SELECT 
            (SELECT COUNT(*) FROM signatures) as total_signatures,
            (SELECT COUNT(*) FROM violation_reports) as total_reports,
            (SELECT COUNT(DISTINCT city) FROM signatures WHERE city != '') as cities_count
    ");
    $stats = $stmt->fetch();
    
    $stmt = $conn->query("
        SELECT 
            full_name,
            experience_years,
            job_title,
            company,
            city,
            DATE_FORMAT(created_at, '%Y-%m-%d') as date
        FROM signatures
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $recentSignatures = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $stats = ['total_signatures' => 0, 'total_reports' => 0, 'cities_count' => 0];
    $recentSignatures = [];
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منشور توسعه دهندگان ایران</title>
    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>منشور توسعه دهندگان ایران</h1>
            <p class="subtitle">برای احترام به ارزش کار، شفافیت در قیمت گذاری ها و ارتقای صنعت نرم افزار</p>
        </div>
    </header>

    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <span class="stat-number"><?= number_format($stats['total_signatures']) ?></span>
                <div class="stat-label">امضا کنندگان</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= number_format($stats['total_reports']) ?></span>
                <div class="stat-label">گزارش تخلف</div>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= number_format($stats['cities_count']) ?></span>
                <div class="stat-label">شهرهای فعال</div>
            </div>
        </div>

        <div class="content-section">
            <h2>متن منشور</h2>
            <div class="manifesto-text">
                <p><strong>ما، توسعه دهندگان نرم‌افزار ایران،</strong> با هدف احترام به ارزش کار و تلاش همکاران، شفافیت در قیمت گذاری پروژه‌ ها و ارتقای کیفیت صنعت نرم‌افزار کشورمان، این منشور را امضا می‌کنیم.</p>
            </div>
            
            <h2 style="margin-top: 40px;">اصول اساسی</h2>
            <ul class="principles">
                <li><strong>۱. احترام به ارزش کار:</strong> هر پروژه باید بر اساس زمان، تخصص و پیچیدگی واقعی قیمت گذاری شود، نه بر اساس نیاز مالی فوری توسعه دهنده.</li>
                
                <li><strong>۲. شفافیت در قیمت گذاری:</strong> ما متعهد میشویم که قیمت گذاری خود را بر اساس استانداردهای معقول و شفاف انجام دهیم و از قیمت‌های غیر منصفانه که به صنعت آسیب میزند، اجتناب کنیم.</li>
                
                <li><strong>۳. حمایت جمعی:</strong> ما از یکدیگر در برابر قیمت گذاری های ناعادلانه حمایت میکنیم و تخلفات را گزارش میدهیم تا بازار سالم تری داشته باشیم.</li>
                
                <li><strong>۴. آموزش و آگاهی سازی:</strong> ما به کارفرمایان کمک میکنیم تا ارزش واقعی کار نرم‌افزاری را درک کنند و از هزینه های واقعی توسعه نرم‌افزار آگاه شوند.</li>
                
                <li><strong>۵. مسئولیت حرفه‌ای:</strong> ما متعهد میشویم که پروژه هایی را بپذیریم که میتوانیم با کیفیت و در زمان مناسب تحویل دهیم و از تعهدات غیر واقعی برای جذب مشتری خودداری کنیم.</li>
            </ul>
        </div>

        <div class="content-section">
            <h2>راهنمای قیمت گذاری پروژه ها</h2>
            <div class="pricing-guide">
                <p>قیمت گذاری باید بر اساس فرمول زیر محاسبه شود:</p>
                <p>
                    قیمت کل = (ساعات کار × نرخ ساعتی) + هزینه های جانبی
                </p>
                
                <ul>
                    <li><b>برای توسعه دهندگان جونیور (بین 0 تا 2 سال کاری):</b> ساعتی بین 150 تا 300 هزار تومان - قیمت جهانی بین 15 تا 35 دلار در ساعت است</li>
                    <li><b>برای توسعه دهندگان میدلول (بین 2 تا 5 سال کاری):</b> ساعتی بین 300 تا 600 هزار تومان - قیمت جهانی بین 35 تا 70 دلار در ساعت است</li>
                    <li><b>برای توسعه دهندگان سنیور (بالاتر از 5 سال کاری):</b> ساعتی بین 600 تا 1 میلیون و 200 هزار تومان - قیمت جهانبی بین 70 تا 130 دلار در دساعت است</li>
                    <li><b>برای معماران و متخصصین (Expert/Architect):</b> ساعتی بیش از 1 میلیون و 200 هزار تومان - قیمت جهانی بین 130 تا 190 دلار در ساعت است</li>
                </ul>
                
                <p>
                    <strong>نکته:</strong> این نرخ ها پیشنهادی هستند و بر اساس شرایط بازار (قیمت دلار)، پیچیدگی پروژه و موقعیت جغرافیایی ممکن است تغییر کنند.
                </p>

                <button>
                    <a href="/calculator" target="_blank"> محاسبه گر پیشرفته </a>
                </button>
            </div>
        </div>

        <div class="content-section">
            <div class="tabs">
                <button class="tab active" onclick="switchTab('sign')">امضای منشور</button>
                <button class="tab" onclick="switchTab('report')">گزارش تخلف</button>
                <button class="tab" onclick="switchTab('recent')">آخرین امضاها</button>
                <a href="reports.php" class="tab" style="text-decoration: none;">مشاهده گزارش ها</a>
            </div>

            <div id="signTab" class="tab-content active">
                <h2>امضای منشور</h2>
                <p style="margin-bottom: 20px;">با امضای این منشور، شما متعهد میشوید که به اصول حرفه‌ای قیمت گذاری پایبند باشید و از بازار سالم حمایت کنید.</p>
                
                <div class="form-container">
                    <form id="signForm">
                        <div class="form-group">
                            <label for="fullName">نام و نام خانوادگی *</label>
                            <input type="text" id="fullName" required placeholder="لطفا به فارسی بنویسید">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">ایمیل *</label>
                            <input type="email" id="email" required placeholder="amirroox@yahoo.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="jobTitle">عنوان شغلی</label>
                            <input type="text" id="jobTitle" placeholder="توسعه دهنده فول استک">
                        </div>
                        
                        <div class="form-group">
                            <label for="company">شرکت/سازمان - فریلنسری</label>
                            <input type="text" id="company" placeholder="نام شرکت/سازمان یا کار به صورت فریلنسری">
                        </div>
                        
                        <div class="form-group">
                            <label for="experience">سابقه کار (سال)</label>
                            <input type="number" id="experience" min="0" max="50" placeholder="10">
                        </div>
                        
                        <div class="form-group">
                            <label for="city">شهر</label>
                            <input type="text" id="city" placeholder="تهران">
                        </div>
                        
                        <div id="signMessage"></div>
                        
                        <button type="submit" id="signButton">امضای منشور</button>
                    </form>
                </div>
            </div>

            <div id="reportTab" class="tab-content">
                <h2>گزارش تخلف قیمت گذاری</h2>
                <p style="margin-bottom: 20px;">اگر شاهد قیمت گذاری غیر منصفانه بودید که به صنعت آسیب میزند، میتوانید آن را گزارش دهید.</p>
                
                <div class="form-container">
                    <form id="reportForm">
                        <div class="form-group">
                            <label for="reporterType">شما چه نقشی دارید؟ *</label>
                            <select id="reporterType" required>
                                <option value="">انتخاب کنید</option>
                                <option value="employer">کارفرما</option>
                                <option value="contractor">پیمانکار/توسعه دهنده/فریلنسر</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="reporterEmail">ایمیل شما *</label>
                            <input type="email" id="reporterEmail" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="violatorType">گزارش شما علیه چه کسی است؟ *</label>
                            <select id="violatorType" required>
                                <option value="">انتخاب کنید</option>
                                <option value="employer">کارفرما</option>
                                <option value="contractor">پیمانکار/توسعه دهنده/فریلنسر</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="violatorName">نام فرد یا شرکت متخلف *</label>
                            <input type="text" id="violatorName" required placeholder="نام کامل فرد یا شرکت">
                        </div>
                        
                        <div class="form-group">
                            <label for="violatorContact">شماره تماس، آیدی تلگرام یا اطلاعات تماس متخلف *</label>
                            <input type="text" id="violatorContact" placeholder="برای مثال: شماره تماس 09123456789 با آیدی تلگرام telegram_id">
                        </div>
                        
                        <div class="form-group">
                            <label for="projectDesc">شرح پروژه</label>
                            <textarea id="projectDesc" placeholder="توضیح مختصری از پروژه"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="fairPrice">قیمت منصفانه تخمینی (تومان)</label>
                            <input type="number" id="fairPrice" placeholder="مثال: 40000000">
                        </div>
                        
                        <div class="form-group">
                            <label for="offeredPrice">قیمت پیشنهادی/دریافتی (تومان)</label>
                            <input type="number" id="offeredPrice" placeholder="مثال: 15000000">
                        </div>
                        
                        <div class="form-group">
                            <label for="violationType">نوع تخلف</label>
                            <select id="violationType">
                                <option value="underprice">قیمت گذاری پایین تر از حد معقول</option>
                                <option value="overprice">قیمت گذاری بالاتر از حد معقول</option>
                                <option value="unfair_practice">رویه های غیر منصفانه</option>
                                <option value="other">سایر موارد</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">توضیحات کامل *</label>
                            <textarea id="description" required placeholder="لطفا جزئیات کامل موضوع را شرح دهید"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="evidenceUrl">لینک مدرک (اختیاری)</label>
                            <input type="url" id="evidenceUrl" placeholder="https://...">
                        </div>
                        
                        <div id="reportMessage"></div>
                        
                        <button type="submit" id="reportButton">ارسال گزارش</button>
                    </form>
                </div>
            </div>

            <div id="recentTab" class="tab-content">
                <h2>آخرین امضا کنندگان (<?= number_format($stats['total_signatures']) ?>)</h2>
                <div class="recent-signatures">
                    <?php if (empty($recentSignatures)): ?>
                        <p style="text-align: center; color: #666;">هنوز امضایی ثبت نشده است</p>
                    <?php else: ?>
                        <?php foreach ($recentSignatures as $sig): ?>
                            <div class="signature-item">
                                <div class="signature-name"><?= htmlspecialchars($sig['full_name']) . ' - ' . jdate('d F Y', strtotime($sig['date'])) ?></div>
                                <div class="signature-info">
                                    <p>عنوان شغلی: <b><?= $sig['job_title'] ? htmlspecialchars($sig['job_title']) : 'هنوان نشده' ?></b></p>
                                    <p>شرکت / فریلنسر: <b><?= $sig['company'] ? htmlspecialchars($sig['company']) : 'فریلنسر' ?></b></p>
                                    <p>سابقه کار: <b><?= $sig['experience_years'] ? htmlspecialchars($sig['experience_years']) : 'عنوان نشده' ?></b></p>
                                    <p>شهر: <b><?= $sig['city'] ?  htmlspecialchars($sig['city']) : 'عنوان نشده' ?></b></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p style="margin-top: 10px; opacity: 0.8;">برای ساخت صنعتی بهتر، با هم متحد شویم 💪</p>
        </div>
    </footer>

    <script src="./assets/script.js"></script>
</body>
</html>