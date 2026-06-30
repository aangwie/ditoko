# DiToko - E-Commerce App

Laravel-based e-commerce platform with multi-role auth (admin/buyer), product management, shopping cart, Midtrans payment gateway, WhatsApp notifications, and real-time admin-buyer chat.

## Features

- **Multi-Role Auth**: Register/login with email, Google OAuth
- **Admin Dashboard**: Manage products, orders, settings
- **Buyer Dashboard**: Browse products, manage cart, checkout
- **Shopping Cart**: Add/remove items, update quantities
- **Payment Gateway**: Midtrans integration (snap, bank transfer, etc.)
- **WhatsApp Notifications**: Order status updates via WhatsApp
- **Admin-Buyer Chat**: Real-time messaging per order
- **Order Management**: Track status (pending, paid, processing, shipped, completed, cancelled)
- **Profile Management**: Update profile, password

## Requirements

- PHP 8.2+
- Composer 2
- MySQL/MariaDB
- Node.js & NPM (for Vite asset compilation)
- Web server (Apache/Nginx) or Laravel Valet/Artisan serve

## Installation

```bash
# 1. Clone
git clone https://github.com/aangwie/ditoko.git
cd ditoko

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Configure .env — edit database, mail, Midtrans, WhatsApp, Google OAuth
#    DB_DATABASE=ditoko
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Database
php artisan migrate --seed

# 7. Storage link (for product images)
php artisan storage:link

# 8. Build assets
npm run build
# or for dev: npm run dev

# 9. Start server
php artisan serve
```

## Default Accounts (Seeder)

| Role  | Email              | Password |
|-------|--------------------|----------|
| Admin | admin@ditoko.test  | password |
| Buyer | buyer@ditoko.test  | password |

## Configuration

### .env Variables

| Key | Description |
|-----|-------------|
| `MIDTRANS_SERVER_KEY` | Midtrans server key |
| `MIDTRANS_IS_PRODUCTION` | `true`/`false` |
| `WHATSAPP_API_URL` | WhatsApp API endpoint |
| `WHATSAPP_API_TOKEN` | WhatsApp API token |
| `GOOGLE_CLIENT_ID` | Google OAuth client ID |
| `GOOGLE_CLIENT_SECRET` | Google OAuth secret |
| `GOOGLE_REDIRECT_URI` | e.g. `http://localhost/auth/google/callback` |

### Admin Settings (via Web UI)

- Site name, logo, favicon, meta description
- WhatsApp number
- Midtrans configuration
- About/contact info
- Privacy policy, terms

## Tech Stack

- **Backend**: Laravel 11, PHP 8.2
- **Frontend**: Blade, Tailwind CSS, Alpine.js, Vite
- **Database**: MySQL
- **Payment**: Midtrans (Snap API)
- **Auth**: Laravel Breeze, Google OAuth (Socialite)
- **Notifications**: WhatsApp API (custom service)
- **Chat**: Database-driven messaging (polling)

## License

MIT