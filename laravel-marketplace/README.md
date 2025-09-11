# Laravel Marketplace

A subscription-based phone marketplace platform built with Laravel 11.

## Features

- **User Authentication & Verification**: Phone verification system
- **Subscription Management**: Multiple pricing tiers
- **Product Listings**: Buy and sell phones
- **Real-time Chat**: Direct communication between users
- **Admin Dashboard**: Manage users and listings
- **API-First Design**: RESTful API for all operations

## Tech Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Database**: MySQL 8
- **Frontend**: Blade templates, TailwindCSS, Alpine.js
- **Payments**: Stripe integration
- **SMS**: Twilio integration
- **Testing**: PHPUnit, Pest

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd laravel-marketplace
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start development server**
   ```bash
   php artisan serve
   ```

## Configuration

### Environment Variables

Key environment variables to configure:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_marketplace
DB_USERNAME=root
DB_PASSWORD=

# Stripe
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Twilio
TWILIO_SID=AC...
TWILIO_TOKEN=...
TWILIO_FROM_NUMBER=+1234567890

# SMS
SMS_DRIVER=twilio
SMS_FROM_NUMBER=+1234567890
```

## API Documentation

The API documentation is available at `/api/documentation` when running the application.

### Key Endpoints

- `POST /api/register` - User registration
- `POST /api/login` - User login
- `GET /api/plans` - Get subscription plans
- `POST /api/subscription/subscribe` - Subscribe to a plan
- `GET /api/listings` - Get listings
- `POST /api/listings` - Create listing
- `POST /api/phone-verification/send` - Send SMS verification

## Subscription Plans

- **Free**: 2 listings per month, 30-day duration
- **Tier 1 (€5/month)**: 10 listings per month, 60-day duration
- **Tier 2 (€15/month)**: 50 listings per month, 90-day duration
- **Tier 3 (€30/month)**: Unlimited listings, 120-day duration

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

### Database Seeding

```bash
php artisan db:seed
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

This project is licensed under the MIT License.