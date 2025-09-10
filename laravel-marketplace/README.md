# Laravel Marketplace - Used Phone Marketplace

A Laravel-based marketplace for buying and selling used phones and accessories in Serbia.

## Features

- **User Registration & Verification**: JMBG validation, SMS and email verification
- **Token-Based Listings**: Users get 1 free token per month, can purchase more
- **Product Management**: Comprehensive listing system with images and details
- **Communication**: Buyer-seller messaging system
- **Admin Panel**: Listing approval and user management
- **Search & Filtering**: Advanced search and filtering capabilities

## Technology Stack

- **Laravel 11** with PHP 8.2
- **MySQL 8** for database
- **Redis** for caching and queues
- **Laravel Sanctum** for API authentication
- **TailwindCSS** for frontend styling

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy environment file: `cp .env.example .env`
4. Generate application key: `php artisan key:generate`
5. Configure database in `.env` file
6. Run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Start development server: `php artisan serve`

## API Endpoints

### Authentication
- `POST /api/register` - User registration
- `POST /api/verify-email` - Email verification
- `POST /api/verify-sms` - SMS verification

### Listings
- `GET /api/listings` - Get all listings
- `GET /api/listings/{id}` - Get specific listing
- `POST /api/listings` - Create listing (authenticated)
- `PUT /api/listings/{id}` - Update listing (authenticated)
- `DELETE /api/listings/{id}` - Delete listing (authenticated)

### Tokens
- `GET /api/tokens/balance` - Get token balance
- `GET /api/tokens/transactions` - Get token transactions
- `POST /api/tokens/purchase` - Purchase tokens

### Conversations
- `GET /api/conversations` - Get user conversations
- `POST /api/conversations` - Start new conversation
- `POST /api/conversations/{id}/messages` - Send message

### Admin
- `GET /api/admin/dashboard` - Admin dashboard
- `GET /api/admin/listings` - Manage listings
- `POST /api/admin/listings/{id}/approve` - Approve listing
- `POST /api/admin/listings/{id}/reject` - Reject listing

## Database Schema

### Core Tables
- `users` - User accounts with verification status
- `listings` - Product listings
- `categories` - Product categories
- `brands` - Product brands
- `token_transactions` - Token balance and transactions
- `conversations` - Buyer-seller conversations
- `messages` - Conversation messages
- `listing_images` - Product images

## Key Features

### JMBG Validation
- Serbian national ID validation with checksum
- Age verification (18+ requirement)
- Secure hashing of sensitive data

### Token System
- Monthly free token distribution
- Token purchase with payment integration
- Token consumption for listing creation
- Automatic refund for rejected listings

### Admin Management
- Listing approval/rejection system
- User verification management
- Statistics and analytics dashboard
- Token transaction monitoring

## Security Features

- JMBG data encryption
- Secure password hashing
- API rate limiting
- CSRF protection
- Input validation and sanitization

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

### Monthly Token Distribution
```bash
php artisan tokens:distribute-monthly
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
