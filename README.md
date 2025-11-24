# Pocketphone
A comprehensive phone inventory management system with a secure admin panel and dynamic product showcase

## 🚀 Live Demo
[Launch Demo](https://yourfiyan.qzz.io/live/pocketphone/admin)

**Demo Credentials:**
- Username: `admin`
- Password: `admin123`

*Note: Demo account has limited permissions for security*

## ✨ Features

### 🔐 Secure Authentication
Robust login system with password encryption and session management for maximum security

### 👥 Role-Based Access
Different access levels for demo and admin users with appropriate permissions

### 📦 Product Management
Complete CRUD operations for phone products with image handling

## 🛠️ Technology Stack

- **PHP** - Server-side scripting
- **MySQL** - Database management
- **JavaScript** - Client-side interactivity
- **HTML5** - Structure
- **CSS3** - Styling
- **Session Management** - User authentication
- **Password Encryption** - Security

## 🏗️ Project Structure

```
pocketphone/
├── index.php              # Main front-end page
├── hashed.php            # Password hashing utility (dev only - see .gitignore)
├── .gitignore            # Excludes sensitive files from version control
├── SECURITY_AUDIT.md     # Security assessment report
├── WORKFLOW_SECURITY_GUIDELINES.md  # GitHub Actions security guide
│
├── admin/                # Admin Panel Directory
│   ├── index.php         # Admin dashboard
│   ├── add_product.php   # Product addition interface
│   ├── edit_product.php  # Product editing interface
│   ├── delete_product.php # Product deletion handler
│   ├── auth_check.php    # Authentication middleware
│   ├── db_config.php     # Database configuration (excluded from git)
│   ├── db_config.example.php  # Database config template
│   ├── login.php         # Admin login interface
│   ├── logout.php        # Session cleanup
│   └── style.css         # Admin panel styling
│
└── uploads/              # Product image storage
```

## ⚙️ Installation & Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/Yourfiyan/Pocketphone.git
   cd Pocketphone
   ```

2. **Configure Database**
   ```bash
   cp admin/db_config.example.php admin/db_config.php
   ```
   Then edit `admin/db_config.php` with your actual database credentials.

3. **Set up MySQL Database**
   - Create a new database
   - Import your database schema
   - Update credentials in `db_config.php`

4. **Configure Web Server**
   - Point your web server to the project directory
   - Ensure PHP and MySQL are properly configured
   - Set proper file permissions for `uploads/` directory

5. **Security Checklist**
   - ✅ Change default database credentials
   - ✅ Update admin password (not `admin123` for production!)
   - ✅ Verify `.gitignore` excludes sensitive files
   - ✅ Never commit `db_config.php` or `hashed.php` with real credentials

## 🔒 Security Implementation

Security is a top priority in this admin panel:

- **Authentication:** Session-based user authentication with secure login/logout
- **Password Security:** All passwords are hashed before storage in the database
- **Data Protection:** Prepared statements prevent SQL injection attacks
- **Input Validation:** All user inputs are validated and sanitized
- **XSS Prevention:** Output escaping prevents cross-site scripting

### Security Documentation
- 📋 [Security Audit Report](SECURITY_AUDIT.md) - Comprehensive security assessment
- 🔐 [Workflow Security Guidelines](WORKFLOW_SECURITY_GUIDELINES.md) - Best practices for GitHub Actions

### Setup Security
1. Copy `admin/db_config.example.php` to `admin/db_config.php`
2. Update database credentials in `db_config.php`
3. **Never commit** `db_config.php` or `hashed.php` with real credentials
4. Review `.gitignore` to ensure sensitive files are excluded

## 📝 License

This project is licensed under the MIT License.

## 👤 Author

**Syed Sufiyan Hamza**
- GitHub: [@Yourfiyan](https://github.com/Yourfiyan)
- Portfolio: [yourfiyan.qzz.io](https://yourfiyan.qzz.io)
