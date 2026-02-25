# ShopSphere - Multi-Vendor eCommerce SaaS Platform

A production-grade multi-vendor eCommerce SaaS platform built with Laravel 10, MySQL, Bootstrap 5, AJAX, REST API, JWT Authentication, and dual payment gateway integration (Stripe + Razorpay).

## Tech Stack

- **Backend:** Laravel 10 (PHP 8.1+)
- **Database:** MySQL
- **Frontend:** Bootstrap 5, Vanilla JS (AJAX)
- **Authentication:** Session-based (Web) + JWT (API)
- **Payments:** Stripe Checkout + Razorpay Inline
- **Notifications:** Firebase Cloud Messaging (FCM)
- **PDF Generation:** DomPDF
- **Charts:** Chart.js

## Modules

### 1. Authentication & Roles
- Role-based access control: **Admin**, **Vendor**, **Customer**
- Separate registration flows for customers and vendors
- JWT authentication for REST API

### 2. Vendor System
- Vendor registration with admin approval workflow (pending/approved/rejected/suspended)
- Vendor shop pages with custom slugs
- Per-vendor commission rates
- Vendor dashboard with analytics (sales, orders, earnings charts)

### 3. Subscription Plans (SaaS)
- Tiered plans: Free, Starter, Professional, Enterprise
- Product upload limits per plan
- Variable commission rates based on plan
- Plan management by admin

### 4. Product Management
- Products with images, variants (size/color/etc.), and SKU tracking
- Category system with parent-child hierarchy
- Inventory management with stock tracking
- Full-text search with AJAX live results
- SEO-friendly slugs, featured product flags

### 5. Cart System
- Session-based cart for guests, database cart for logged-in users
- Automatic cart merge on login
- AJAX quantity updates, item removal
- Coupon and reward points application

### 6. Coupon System
- Fixed amount and percentage discount types
- Min cart value, max discount caps
- Usage limits (global + per-user)
- Vendor-specific coupons
- Date-range validity

### 7. Reward Points
- Earn points on order delivery (configurable points-per-dollar)
- Redeem points at checkout (configurable point value)
- Points balance and transaction history

### 8. Order Management
- Full order lifecycle: pending > confirmed > processing > shipped > delivered
- Order tracking with status timeline
- Order items tracked per vendor with commission calculations
- Invoice PDF generation and download

### 9. Payment Integration
- **Stripe:** Hosted checkout sessions with webhook verification
- **Razorpay:** Inline payment modal with server-side signature verification
- Transaction logging with gateway responses
- Refund support for both gateways

### 10. Wallet System
- Vendor wallets auto-credited on order delivery
- Commission automatically deducted
- Wallet transaction history with balance tracking

### 11. Wishlist
- Add/remove products with heart toggle
- Move wishlist items directly to cart

### 12. Reviews & Ratings
- Post-purchase reviews with 1-5 star ratings
- Admin approval workflow
- Average rating display on products

### 13. Cart Abandonment Recovery
- Automated detection of inactive carts (configurable threshold)
- Email reminders using branded templates
- Push notification reminders via Firebase
- Up to 3 reminder attempts per cart
- Hourly cron job: `php artisan shopsphere:cart-abandonment`

### 14. Push Notifications (Firebase)
- Order status updates
- Cart abandonment reminders
- Coupon alerts
- Multi-device support with token management

### 15. CMS Module
- Dynamic pages (About, Privacy Policy, Terms, Contact)
- SEO meta fields (title, description, keywords)
- Admin CRUD for page management

### 16. Admin Dashboard
- Overview stats: total revenue, orders, vendors, customers
- Sales analytics chart (Chart.js)
- Top selling products
- Vendor management (approve/reject/suspend, set commission)
- Order management with refund processing
- Product oversight with featured/status toggles
- Coupon, subscription plan, and CMS page management

### 17. REST API (v1)
- JWT-based authentication (register, login, logout, refresh)
- Product listing and search
- Order history
- Vendor profiles
- Wallet balance and transactions

## Project Structure

```
app/
├── Console/Commands/          # Artisan commands (cart abandonment)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/             # 7 admin controllers
│   │   ├── Api/V1/            # 5 API controllers
│   │   ├── Auth/              # Web authentication
│   │   ├── Customer/          # 8 customer controllers
│   │   └── Vendor/            # 7 vendor controllers
│   └── Middleware/            # Role, VendorApproved, SubscriptionLimit, JWT
├── Models/                    # 26 Eloquent models
├── Services/                  # Cart, Order, Payment, Wallet, Firebase, Invoice
└── Traits/                    # GeneratesSlug, GeneratesSku, UploadFile

database/
├── migrations/                # 30 migration files
└── seeders/                   # Database seeder with sample data

resources/views/
├── admin/                     # Admin panel views
├── auth/                      # Login, register, vendor register
├── cms/                       # CMS page display
├── customer/                  # Shop, cart, checkout, orders, wishlist, account
├── emails/                    # Cart abandonment, invoice templates
└── vendor/                    # Vendor dashboard, products, orders, wallet

public/js/                     # AJAX: app.js, cart.js, checkout.js
routes/
├── web.php                    # Web routes (public, customer, admin, vendor)
└── api.php                    # API v1 routes with JWT
```

## Installation

```bash
# Clone the repository
git clone https://github.com/kunalbasude/shopsphere.git
cd shopsphere

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Configure .env with your database, Stripe, Razorpay, and Firebase credentials

# Run migrations and seed
php artisan migrate
php artisan db:seed

# Start the server
php artisan serve
```

## Default Seeded Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@shopsphere.com | password |
| Vendor | vendor@shopsphere.com | password |
| Customer | customer@shopsphere.com | password |

## Environment Variables

Key variables to configure in `.env`:

```
DB_DATABASE=shopsphere

STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret

RAZORPAY_KEY=your_razorpay_key
RAZORPAY_SECRET=your_razorpay_secret
RAZORPAY_WEBHOOK_SECRET=your_razorpay_webhook_secret

FIREBASE_CREDENTIALS=path/to/firebase-credentials.json

SHOPSPHERE_COMMISSION_RATE=10
SHOPSPHERE_REWARD_POINTS_PER_DOLLAR=10
SHOPSPHERE_REWARD_POINT_VALUE=0.01
SHOPSPHERE_CART_ABANDONMENT_HOURS=24
```

## Scheduled Tasks

Add to your server's crontab:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

This runs the cart abandonment reminder command hourly.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
