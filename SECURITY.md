# Security Policy

## Reporting a Vulnerability

If you discover a security vulnerability, please report it responsibly.

**Do not open a public issue.** Instead, email:

- **Email:** yourfiyan@proton.me

### Response time

You can expect an initial response within **48 hours**.

## Security Considerations

### Authentication
- Passwords are hashed using PHP's `password_hash()` with bcrypt
- Sessions are used for authentication state — ensure `session.cookie_httponly` and `session.cookie_secure` are enabled in production
- Demo credentials should be changed or removed in production

### Database
- Database credentials are stored in `admin/db_config.php` — in production, move these to environment variables
- Use prepared statements for all database queries to prevent SQL injection

### File Uploads
- The `uploads/` directory accepts user-submitted images — validate file types and sizes server-side
- Ensure uploaded files cannot be executed as PHP

### Production Deployment
- Enable HTTPS
- Set proper PHP error reporting (disable `display_errors`)
- Restrict file permissions on sensitive files (`db_config.php`)
