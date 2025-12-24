# 📜 Iranian Software Developers Charter

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)](https://www.php.net/)
[![Contributions Welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg)](CONTRIBUTING.md)

**A platform for fair pricing, transparency, and elevating Iran's software industry**

[فارسی](README.fa.md) | [Live Demo](https://DevCharter.ir) | [Report Bug](https://github.com/amirroox/DeveloperCharterIran/issues)

---

## 🎯 About The Project

The Iranian Developers Charter is an open-source platform designed to:

- 🤝 **Respect Work Value**: Establish fair standards for project pricing
- 💡 **Transparency**: Create clear guidelines for calculating software project costs
- 🛡️ **Collective Support**: Report and track unfair pricing violations
- 📊 **Awareness**: Educate employers about the true value of software development

This platform helps developers and employers collaborate with transparent and fair standards.

---

## ✨ Key Features

### 📝 Charter Signing System
- Professional developer registration
- Real-time signer statistics
- Support for Persian (Jalali) calendar

### 🚨 Violation Reporting System
- Report unfair pricing practices
- Categorized by violation type
- Verification and review system
- Privacy protection for reporters

### 💬 Comments & Reactions System
- Comment on reports
- Like/Dislike functionality
- Rate limiting to prevent spam

### 🧮 Advanced Pricing Calculator
- Accurate cost calculation based on:
  - Expertise level (Junior to Expert)
  - Technologies used (130+ technologies)
  - Project architecture complexity
  - Increasing and decreasing factors
- Automatic USD exchange rate updates
- Pricing in both IRR and USD
- Global standard comparisons

### 🔐 Admin Panel
- Manage signatures and reports
- Secure authentication system
- Complete statistical dashboard
- Comment and reaction management

---

## 🚀 Installation & Setup

### Prerequisites
```bash
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.2+
- Apache/Nginx
- Composer (optional)
```

### Installation Steps

#### 1️⃣ Clone the Repository
```bash
git clone https://github.com/yourusername/DeveloperCharterIran.git
cd DeveloperCharterIran
```

#### 2️⃣ Database Setup
```bash
# Create database
mysql -u root -p

# In MySQL environment:
CREATE DATABASE dev_manifesto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Import schema
mysql -u root -p dev_manifesto < database.db
```

#### 3️⃣ PHP Configuration
```bash
# Edit config file
nano api/config.php
```

```php
// Database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'dev_manifesto');

// Site URL
define('URL', 'yourdomain.com');
```

#### 4️⃣ Web Server Configuration

**Apache (.htaccess):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
}
```

#### 5️⃣ Set Permissions
```bash
chmod 755 -R ./
chmod 777 calculator/cache.json
chown -R www-data:www-data ./
```

---

## 📁 Project Structure

```
DeveloperCharterIran/
├── 📂 admin/                 # Admin panel
│   ├── index.php            # Admin dashboard
│   ├── login.php            # Login page
│   └── logout.php           # Logout
│
├── 📂 api/                   # Backend APIs
│   ├── config.php           # Main configuration
│   ├── sign.php             # Sign charter
│   ├── report.php           # Submit report
│   ├── comments.php         # Comment management
│   ├── reactions.php        # Like/Dislike
│   └── stats.php            # Statistics
│
├── 📂 assets/                # Static files
│   ├── style.css            # Main styles
│   ├── calculator.css       # Calculator styles
│   ├── admin.css            # Admin panel styles
│   ├── reports.css          # Reports styles
│   ├── script.js            # Main JavaScript
│   ├── calculator.js        # Calculator logic
│   └── reports.js           # Reports logic
│
├── 📂 calculator/            # Pricing calculator
│   ├── index.php            # Main page
│   ├── cache.json           # Exchange rate cache
│   └── update_rate.php      # Rate updater
│
├── 📂 helper/                # Helper functions
│   └── jdf.php              # Jalali date converter
│
├── index.php                 # Homepage
├── reports.php               # Reports list
├── database.db               # Database schema
└── .gitignore               # Ignored files
```

---

## 💻 Usage

### Sign the Charter
1. Go to the homepage
2. Select "Sign Charter" tab
3. Enter your information
4. Click "Sign Charter"

### Report Violation
1. Select "Report Violation" tab
2. Specify your role (Employer/Contractor)
3. Enter violator info and details
4. Submit report

### Calculate Project Price
1. Navigate to `/calculator`
2. Enter basic info (hours, expertise level)
3. Select technologies and tools used
4. Specify complexity and additional factors
5. View suggested pricing

### Admin Panel Access
```
URL: /admin
Default username: admin
Default password: admin123
⚠️ Change password after first login!
```

---

## 🤝 Contributing

We welcome contributions!

### Ways to Contribute:
1. 🐛 **Report Bugs**: Via [Issues](https://github.com/amirroox/DeveloperCharterIran/issues)
2. 💡 **Suggest Features**: Create Feature Request
3. 🔧 **Submit Pull Request**: 
   - Fork the project
   - Create a new branch (`git checkout -b feature/AmazingFeature`)
   - Commit changes (`git commit -m 'Add some AmazingFeature'`)
   - Push to branch (`git push origin feature/AmazingFeature`)
   - Create Pull Request

---

## 📊 Project Stats

```
📝 Lines of Code: ~5,000+ lines
🗂️ Files: 24 files
🔧 Technologies: PHP, MySQL, JavaScript, CSS (Pure)
📅 Project Start: 2024
👥 Contributors: Open to all
```

---

## 📄 License

This project is released under the MIT License - see [LICENSE](LICENSE) file for details.

```
MIT License - Free for personal and commercial use
```

---

## 🙏 Acknowledgments

- Iranian developer community who inspired this project
- [jdf.php](http://jdf.scr.ir) library for Jalali date conversion
- Iranian open-source community

---

## 📞 Contact

- 🌐 Website: [amirroox.ir](https://amirroox.ir)
- 📧 Email: amirroox@yahoo.com
- 💬 Telegram: [@you_113](https://t.me/you_113)

---

## 📸 Screenshots

### Homepage
![Homepage](screenshots/home.png)

### Pricing Calculator
![Calculator](screenshots/calculator.png)

### Admin Panel
![Admin Panel](screenshots/admin.png)

---

<div align="center">

**Made with ❤️ for Iranian Developer Community**

[⭐ Star this project](https://github.com/amirroox/DeveloperCharterIran) if you find it useful!

</div>