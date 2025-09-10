# Laravel Marketplace API Documentation

## Overview
This document provides comprehensive API documentation for the Laravel Marketplace backend system.

## Base URL
```
http://localhost:8000/api
```

## Authentication
The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {token}
```

---

## 🔐 Authentication Endpoints

### Register User
**POST** `/register`

Register a new user with JMBG validation and age verification.

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+381601234567",
    "jmbg": "1234567890123",
    "password": "password123",
    "password_confirmation": "password123",
    "terms_accepted": true
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "User registered successfully. Please verify your email and phone.",
    "data": {
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "+381601234567",
            "is_verified": false,
            "is_sms_verified": false,
            "is_email_verified": false,
            "is_age_verified": true
        },
        "verification_required": {
            "email": true,
            "sms": true
        }
    }
}
```

### Login User
**POST** `/login`

Authenticate user and return access token.

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### Logout User
**POST** `/logout`

Revoke current access token.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "message": "Logout successful"
}
```

### Get User Profile
**GET** `/profile`

Get current user profile with token balance and verification status.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "+381601234567"
        },
        "token_balance": 5,
        "verification_status": {
            "email": true,
            "sms": true,
            "age": true,
            "overall": true
        }
    }
}
```

---

## 📧 Verification Endpoints

### Send Email Verification
**POST** `/verify/email/send`

Send verification code to user's email.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "message": "Verification code sent to your email",
    "data": {
        "verification_code": "123456"
    }
}
```

### Verify Email
**POST** `/verify/email`

Verify email with 6-digit code.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "code": "123456"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Email verified successfully",
    "data": {
        "user": {
            "id": 1,
            "email": "john@example.com"
        },
        "verification_status": {
            "email": true,
            "sms": false,
            "age": true,
            "overall": false
        }
    }
}
```

### Send SMS Verification
**POST** `/verify/sms/send`

Send verification code to user's phone.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "message": "Verification code sent to your phone",
    "data": {
        "verification_code": "123456"
    }
}
```

### Verify SMS
**POST** `/verify/sms`

Verify phone with 6-digit code.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "code": "123456"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Phone verified successfully",
    "data": {
        "user": {
            "id": 1,
            "phone": "+381601234567"
        },
        "verification_status": {
            "email": true,
            "sms": true,
            "age": true,
            "overall": true
        }
    }
}
```

---

## 🪙 Token System Endpoints

### Get Token Balance
**GET** `/tokens/balance`

Get user's current token balance and expiring tokens.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "balance": 5,
        "expiring_soon": 2,
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe"
        }
    }
}
```

### Get Token Transactions
**GET** `/tokens/transactions`

Get user's token transaction history.

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `per_page` (optional): Number of results per page (default: 20)
- `type` (optional): Filter by transaction type (`credit` or `debit`)

**Response (200):**
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "type": "credit",
                "amount": 5,
                "description": "Purchased 5 token(s) for €20",
                "created_at": "2024-01-15T10:00:00Z"
            },
            {
                "id": 2,
                "type": "debit",
                "amount": 1,
                "description": "Listing creation",
                "created_at": "2024-01-15T11:00:00Z"
            }
        ],
        "current_page": 1,
        "total": 2
    }
}
```

### Purchase Tokens
**POST** `/tokens/purchase`

Purchase token packages.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "package": 5,
    "payment_method": "card"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Tokens purchased successfully",
    "data": {
        "transaction": {
            "id": 3,
            "type": "credit",
            "amount": 5,
            "description": "Purchased 5 token(s) for €20"
        },
        "new_balance": 9,
        "payment_reference": "PAY_1642248000_1",
        "package": 5,
        "amount": 5,
        "price": 20
    }
}
```

### Get Token Packages
**GET** `/tokens/packages`

Get available token packages and pricing.

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "tokens": 1,
            "price": 5.00,
            "price_per_token": 5.00,
            "description": "Single token for one listing"
        },
        {
            "id": 5,
            "tokens": 5,
            "price": 20.00,
            "price_per_token": 4.00,
            "description": "5 tokens - 20% savings",
            "popular": true
        },
        {
            "id": 10,
            "tokens": 10,
            "price": 35.00,
            "price_per_token": 3.50,
            "description": "10 tokens - 30% savings"
        },
        {
            "id": 20,
            "tokens": 20,
            "price": 60.00,
            "price_per_token": 3.00,
            "description": "20 tokens - 40% savings",
            "best_value": true
        }
    ]
}
```

---

## 📱 Listing Endpoints

### Get All Listings
**GET** `/listings`

Get paginated list of active listings with search and filtering.

**Query Parameters:**
- `search` (optional): Search term for title/description
- `category_id` (optional): Filter by category
- `brand_id` (optional): Filter by brand
- `condition` (optional): Filter by condition
- `min_price` (optional): Minimum price filter
- `max_price` (optional): Maximum price filter
- `sort_by` (optional): Sort field (`created_at`, `price`, `view_count`)
- `sort_order` (optional): Sort direction (`asc`, `desc`)
- `page` (optional): Page number

**Response (200):**
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "title": "iPhone 13 Pro Max 256GB",
                "description": "Excellent condition iPhone...",
                "price": 800.00,
                "condition": "excellent",
                "storage": "256GB",
                "color": "Space Gray",
                "battery_health": 95,
                "view_count": 15,
                "created_at": "2024-01-15T10:00:00Z",
                "user": {
                    "id": 1,
                    "first_name": "John",
                    "last_name": "Doe"
                },
                "category": {
                    "id": 1,
                    "name": "Smartphones"
                },
                "brand": {
                    "id": 1,
                    "name": "Apple"
                },
                "images": [
                    {
                        "id": 1,
                        "image_url": "https://example.com/image1.jpg",
                        "is_primary": true
                    }
                ]
            }
        ],
        "current_page": 1,
        "total": 50
    }
}
```

### Get Single Listing
**GET** `/listings/{id}`

Get detailed information about a specific listing.

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "iPhone 13 Pro Max 256GB",
        "description": "Excellent condition iPhone...",
        "price": 800.00,
        "condition": "excellent",
        "storage": "256GB",
        "color": "Space Gray",
        "battery_health": 95,
        "screen_condition": "Perfect",
        "body_condition": "Perfect",
        "carrier": "Unlocked",
        "contact_preference": "both",
        "status": "active",
        "expires_at": "2024-02-15T10:00:00Z",
        "view_count": 16,
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "phone": "+381601234567"
        },
        "category": {
            "id": 1,
            "name": "Smartphones"
        },
        "brand": {
            "id": 1,
            "name": "Apple"
        },
        "images": [
            {
                "id": 1,
                "image_url": "https://example.com/image1.jpg",
                "is_primary": true
            }
        ]
    }
}
```

### Create Listing
**POST** `/listings`

Create a new listing (requires verification and tokens).

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "title": "iPhone 13 Pro Max 256GB",
    "description": "Excellent condition iPhone 13 Pro Max...",
    "category_id": 1,
    "brand_id": 1,
    "price": 800.00,
    "condition": "excellent",
    "storage": "256GB",
    "color": "Space Gray",
    "battery_health": 95,
    "screen_condition": "Perfect",
    "body_condition": "Perfect",
    "carrier": "Unlocked",
    "contact_preference": "both",
    "images": [
        "image1.jpg",
        "image2.jpg",
        "image3.jpg"
    ]
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Listing created successfully",
    "data": {
        "id": 1,
        "title": "iPhone 13 Pro Max 256GB",
        "status": "pending",
        "expires_at": "2024-02-15T10:00:00Z",
        "category": {
            "id": 1,
            "name": "Smartphones"
        },
        "brand": {
            "id": 1,
            "name": "Apple"
        },
        "images": [
            {
                "id": 1,
                "image_url": "https://example.com/image1.jpg",
                "is_primary": true
            }
        ]
    }
}
```

---

## 💬 Conversation Endpoints

### Get User Conversations
**GET** `/conversations`

Get user's conversations (requires verification).

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "status": "active",
                "last_message_at": "2024-01-15T12:00:00Z",
                "listing": {
                    "id": 1,
                    "title": "iPhone 13 Pro Max 256GB",
                    "price": 800.00
                },
                "buyer": {
                    "id": 2,
                    "first_name": "Jane",
                    "last_name": "Smith"
                },
                "seller": {
                    "id": 1,
                    "first_name": "John",
                    "last_name": "Doe"
                },
                "messages": [
                    {
                        "id": 1,
                        "message": "Is this still available?",
                        "sender_id": 2,
                        "created_at": "2024-01-15T12:00:00Z"
                    }
                ]
            }
        ]
    }
}
```

### Start Conversation
**POST** `/conversations`

Start a new conversation about a listing (requires verification).

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
    "listing_id": 1,
    "message": "Is this still available?"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Message sent successfully",
    "data": {
        "id": 1,
        "listing_id": 1,
        "buyer_id": 2,
        "seller_id": 1,
        "status": "active",
        "messages": [
            {
                "id": 1,
                "message": "Is this still available?",
                "sender_id": 2,
                "created_at": "2024-01-15T12:00:00Z"
            }
        ]
    }
}
```

---

## 🏷️ Category & Brand Endpoints

### Get Categories
**GET** `/categories`

Get all active categories with hierarchical structure.

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Smartphones",
            "slug": "smartphones",
            "description": "Mobile phones and smartphones",
            "children": [
                {
                    "id": 2,
                    "name": "iPhone",
                    "slug": "iphone",
                    "parent_id": 1
                }
            ]
        }
    ]
}
```

### Get Brands
**GET** `/brands`

Get all active brands, optionally filtered by category.

**Query Parameters:**
- `category_id` (optional): Filter brands by category

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Apple",
            "slug": "apple",
            "description": "Apple Inc. smartphones",
            "category_id": 1
        },
        {
            "id": 2,
            "name": "Samsung",
            "slug": "samsung",
            "description": "Samsung Electronics smartphones",
            "category_id": 1
        }
    ]
}
```

---

## 🔧 Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

### Authentication Error (401)
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

### Authorization Error (403)
```json
{
    "success": false,
    "message": "Account verification required",
    "data": {
        "verification_status": {
            "email": false,
            "sms": false,
            "age": true,
            "overall": false
        },
        "verification_required": true
    }
}
```

### Not Found Error (404)
```json
{
    "success": false,
    "message": "Listing not found"
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Internal server error. Please try again later."
}
```

---

## 📊 Rate Limiting

- **Authentication endpoints**: 5 requests per minute
- **Verification endpoints**: 3 requests per hour per user
- **Token purchase**: 10 requests per hour per user
- **General API**: 100 requests per hour per user

---

## 🔒 Security Features

1. **JMBG Validation**: Serbian national ID validation with checksum
2. **Age Verification**: 18+ requirement with JMBG age calculation
3. **Token System**: Secure token management with expiration
4. **Rate Limiting**: Protection against abuse
5. **Input Validation**: Comprehensive request validation
6. **SQL Injection Protection**: Eloquent ORM with parameterized queries
7. **XSS Protection**: Output escaping and validation
8. **CSRF Protection**: Laravel's built-in CSRF protection

---

## 🚀 Getting Started

1. **Install Dependencies**: `composer install`
2. **Setup Environment**: Copy `.env.example` to `.env` and configure
3. **Run Migrations**: `php artisan migrate`
4. **Seed Database**: `php artisan db:seed`
5. **Start Server**: `php artisan serve`
6. **Run Tests**: `php artisan test`

---

*API Documentation v1.0 - Laravel Marketplace Backend*
