# 🧪 TEST USERS - MARKETPLACE TESTING ACCOUNTS

## **Available Test Accounts**

### **1. Free Plan User**
- **Email:** `free@test.com`
- **Password:** `free123`
- **Phone:** `+38164995920`
- **Plan:** Free Plan (1 listing/month)
- **Status:** Email & SMS Verified

### **2. Tier 1 User**
- **Email:** `premium@test.com`
- **Password:** `premium123`
- **Phone:** `+38168084633`
- **Plan:** Tier 1 (5 listings/month)
- **Status:** Email & SMS Verified

### **3. Tier 2 User**
- **Email:** `test@example.com`
- **Password:** `password`
- **Phone:** `+38160123456`
- **Plan:** Tier 2 (30 listings/month)
- **Status:** Email & SMS Verified

### **4. Tier 3 User (Admin)**
- **Email:** `admin@test.com`
- **Password:** `admin123`
- **Phone:** `+38169637097`
- **Plan:** Tier 3 (Unlimited listings)
- **Status:** Email & SMS Verified

## **How to Use**

### **Login Testing:**
1. Visit: `http://127.0.0.1:8003/login`
2. Use any of the above credentials
3. Test different subscription levels

### **Create New Test Users:**
```bash
# Create a new test user
php artisan test:user --email=newuser@test.com --password=password123 --plan=tier-2

# Available plans: free, tier-1, tier-2, tier-3
```

### **Test Different Features:**
- **Free User:** Test listing limits (1 per month)
- **Tier 1:** Test moderate usage (5 per month)
- **Tier 2:** Test regular usage (30 per month)
- **Tier 3:** Test unlimited listings

## **Database Verification:**
```bash
# Check all test users
php artisan tinker --execute="
App\Models\User::whereIn('email', ['test@example.com', 'admin@test.com', 'free@test.com', 'premium@test.com'])->get()->each(function(\$user) {
    echo \$user->email . ' - ' . \$user->subscriptions->first()->plan->name . PHP_EOL;
});
"
```

## **Features to Test:**
- ✅ User authentication
- ✅ Subscription-based listing limits
- ✅ Phone verification status
- ✅ Email verification status
- ✅ Different subscription tiers
- ✅ Marketplace access permissions
