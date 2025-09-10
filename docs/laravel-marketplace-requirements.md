

**Task:** Build me a complete **Laravel-based web marketplace** for selling used phones and accessories, similar to Swappie, with these requirements:

---

## 🎯 Core Features

1. **User Registration & Verification**

   * Users must register with **first/last name (Serbian Latin), email, phone, password, JMBG**.
   * Enforce **unique JMBG** with checksum validation + 18+ age check.
   * Require **SMS + email verification codes** before account is fully verified.
   * Only **verified users** can create listings or message others.

2. **Token-Based Listings**

   * Each verified user gets **1 free token per month**.
   * Creating a listing consumes **1 token**.
   * Users can **buy more tokens** (1, 5, 10 packages) through a Serbian payment gateway (stub service).
   * All token actions logged in a **TokenTransaction** model.

3. **Device Catalog & Listings**

   * Pre-seed catalog:

     * **Phones:** Apple (iPhone X and newer), Samsung (S21+), plus 3–5 top brands.
     * **Accessories:** headphones, chargers, protection.
   * Listing fields: title, description, category, brand, model, condition (like\_new/excellent/good/fair), storage, price, color, photos (3–10), battery health, screen/body condition, carrier, contact preference.
   * Reject broken phones (cracked screens, heavy damage).
   * Listings expire after 30 days unless renewed.

4. **Buyer–Seller Communication**

   * Verified buyers can **initiate a conversation** on a listing.
   * Messages are stored in a **Conversation + Message** model.
   * Email notification sent on new inquiries/messages.

5. **Admin Panel**

   * Admin can **approve or reject listings**.
   * Rejected listings automatically refund 1 token.
   * Dashboard shows stats: total users, verified users, tokens sold, revenue, pending listings.

6. **Search & Filters**

   * Buyers can filter by **brand, model, condition, price range, storage, carrier**.
   * Full-text search across titles & descriptions.
   * Sort results by newest, price, or condition.

---

## 🛠 Tech & Stack

* Laravel 11 + PHP 8.2
* MySQL 8
* Redis (queues, cache, tokens)
* TailwindCSS + Blade
* Twilio (or Serbian SMS provider)
* Payment gateway (stub function, ready for integration)
* Admin moderation routes + middleware
* Unit & feature tests with Pest

---

## 🚦 Deliverables

* **Database migrations** for users, devices, listings, tokens, purchases, conversations/messages.
* **Models** with relationships & casts.
* **Rules & Requests** for strong validation (e.g. `ValidJMBG`).
* **Controllers** for listings, tokens, verification, search, conversations, admin panel.
* **Blade views** for registration, listing creation, search results, admin dashboard.
* **Seeders** to prefill catalog (iPhones X+, Samsung S21+, etc.).
* **Jobs & Notifications** for SMS/email verification, new inquiries, approval/rejection notices.
* **Policies & Middleware** to enforce verification & admin rights.
* **Cron job** for monthly free tokens.
* **Tests** for registration, verification, token usage, listing creation.

---

👉 **Instruction for AI:**
Generate all necessary Laravel code (models, migrations, controllers, middleware, requests, views, routes, services, jobs, notifications, seeders, tests) to fully implement this system. Use Laravel conventions, strong validation, and Tailwind for UI. Ensure all sensitive data (JMBG, codes) is handled securely.


## Current Development Status
- Phase: INITIALIZATION  
- Active Agents: None
- Next Actions: System analysis and agent assignment