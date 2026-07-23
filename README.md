# Agency - Real Estate Management System

A full-featured real estate agency management platform built with Laravel 13. Manage properties, clients, agents, deals, invoices, installments, commissions, and more — with a public-facing property listing website.

## Features

- **Public Website**: Property listings with search/filter, hero slider managed via admin, contact form, about page
- **Property Management**: Full CRUD with media uploads, documents, owner/agent assignment
- **Client Management**: Client portal with quotations, invoices, property access
- **Deal Tracking**: Sale and rental deals with token (bayana) management
- **Installment Plans**: Create and track payment plans for deals
- **Invoicing**: Generate invoices with PDF export, payment tracking
- **Agent Management**: Track agents, commissions, payouts
- **Rent Agreements**: Generate rent agreements with PDF
- **Quotations**: Send quotations to clients with approval workflow
- **Property Visits**: Schedule and manage property visits
- **Reports**: Sales, agent performance, commissions, rent roll
- **PDF Generation**: Sale agreements, rent agreements, token receipts, commission invoices, possession letters
- **Contact Inquiries**: Contact form with email notifications
- **Settings**: Configurable business, branding, email, payment, real estate, SMS, and website settings

## Requirements

- PHP 8.3+
- MySQL / MariaDB
- Composer
- GD extension (for image cropping)

## Installation

```bash
# 1. Clone the repository
git clone <repository-url> agency
cd agency

# 2. Install dependencies
composer install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
#    DB_DATABASE=agency_db
#    DB_USERNAME=root
#    DB_PASSWORD=

# 5. Create storage symlink
php artisan storage:link

# 6. Run migrations and seeders
php artisan migrate --seed

# 7. Start development server
php artisan serve
```

**Default admin credentials:** `admin@agency.com` / `password`

## Production Deployment

### Environment Configuration

```bash
# Set these in .env for production:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
LOG_LEVEL=warning
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
```

### Mail Configuration

Configure a real SMTP provider in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### Queue Worker

For email notifications and deferred jobs, run the queue worker:
```bash
# As a daemon (production):
php artisan queue:work --daemon

# Or via scheduler (recommended):
# The scheduler runs every minute via cron:
# * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Web Server

Ensure the web server's document root points to the `public/` directory.

For Apache, the `.htaccess` file handles URL rewriting.
For Nginx:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### HTTPS

Configure SSL/TLS at the web server level. The application trusts proxies automatically for proper URL generation behind load balancers.

## Security

- Session data is encrypted in the database
- CSRF protection on all forms
- Rate limiting on login (5 attempts/minute) and contact form (3 submissions/minute)
- Admin routes require authentication
- Client portal is isolated from admin panel
- Password reset with token expiration
- Email verification flow

## License

Proprietary. All rights reserved.
