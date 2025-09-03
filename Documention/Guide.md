# OneNest App – Developer Specification (Laravel + MySQL)

This document turns the raw requirements into a clean, dev‑ready plan for a Laravel + MySQL build. It includes data model, forms → database mapping, endpoints, validation, and dashboard structure. Payments and full in‑app chat are **out of scope** for v1 (per request). Minimal contact actions (call/WhatsApp/email templates) are included.

---

## 0) Tech stack & conventions

* **Backend:** Laravel 11 (PHP 8.2+), Eloquent ORM, Laravel Sail or Valet for local
* **DB:** MySQL 8 (utf8mb4), timezone `Africa/Gaborone`, currency **BWP**
* **Auth:** Laravel Breeze (API Tokens) or Sanctum (mobile/web)
* **Uploads:** Laravel Filesystem (local → S3/GCS ready), image validation + thumbnails
* **API Style:** REST JSON; Resource classes for responses
* **IDs:** ULIDs (or UUIDs) recommended for public entities
* **Dates & money:** store money as integer cents (e.g., `price_bwp` in thebe) and format in UI
* **Soft deletes:** for all user content tables

---

## 1) Roles & user categories

* **End User / Buyer** – browses, saves to cart/wishlist, contacts sellers, applies to jobs
* **Job Seeker** – profile + preferences, applies to jobs
* **Recruiter/Employer** – posts jobs, reviews applicants
* **Business (Service Provider)** – company profile with services & documents
* **Business (Product Seller / Vendor)** – company + product catalog
* **Freelancer** – skills/services, portfolio
* **Property Lister** – posts rentals/sales
* **Agent** – subscribes for access to lister contact details
* **Admin** – reviews, approves, moderates

> A single `users` table with role flags + related profiles (polymorphic) keeps it flexible; one login can own multiple profiles (e.g., a user can be both a Job Seeker and a Property Lister).

---

## 2) Admin dashboard menu (rename & scope)

**Exclude:** dedicated payment management & message management modules.

1. **Job Seekers Management**
2. **Marketplace Management** (second‑hand items)
3. **Recruiters Management** (job posts & hiring)
4. **Freelancers Management**
5. **Business Registration Management** (services & product vendors)
6. **Property Management** (rent/sale)

Additional admin utilities: Approvals queue, Reports, File moderation, Reviews moderation.

---

## 3) User dashboard (key widgets)

**Header strip (top):**

* **Jobs interested in** (tags), **Preferred location**, **Home village**, **Salary range**
* Quick toggles: *Available to start*, *Looking for full-time/part-time/both*, *Stay‑in/Stay‑out/Both*

**Panels:**

* **Cart & Saved** – multi‑type saved list (marketplace items, products, properties, jobs)
* **My Profiles** – Job Seeker / Freelancer / Business / Lister quick access
* **My Applications** – job applications status
* **My Listings** – marketplace/products/properties
* **Contact Actions** – one‑click **Call** / **WhatsApp** / **Email** templates to confirm details
* **Verification** – upload/manage ID, proof of address, etc.

---

## 4) Forms → data fields mapping

Below: required fields (★) and storage targets. All forms allow attachments via the `files` table (polymorphic).

### 4.1 Service Providers (Business – Services)

* ★ Business name → `businesses.name`
* ★ Type of service (tags) → `service_types` + pivot `business_service_types`
* Location, operating hours → `businesses.{location_text, open_time, close_time}`
* Service area → `businesses.service_area_text`
* Registration no. (CIPA) → `businesses.registration_number`
* Years in operation, employees → `businesses.{years_in_operation, employees_count}`
* Contacts (person, phone(s), email, socials, website) → `contacts` (polymorphic)
* Service details (pricing, packages, specialties) → `business_services` (rows) or rich `businesses.profile_json`
* Files (license, brochure, portfolio, promos) → `files`

### 4.2 Product Sellers (Business – Products)

* Business info fields as above
* Product category → `product_categories` + `products.category_id`
* Delivery options → `businesses.delivery_info_json`
* Physical shop or online only → `businesses.sales_mode` (enum: physical, online, both)
* Product details (name, price\_bwp, packaging, features) → `products` + `product_photos`
* Catalog/promos/certs → `files`

### 4.3 Marketplace Sellers (Second‑hand)

* Seller info → `users` + `profiles`
* For **each item**: name, category, description, price, quantity, delivery/pickup, handmade? → `marketplace_items` + `marketplace_photos`
* Return/refund policy, event promotions interest, notes → `marketplace_items.meta_json`

### 4.4 Property Listings (Rent/Sale)

* Owner/Agent info → `users` + `profiles` (+ flag `is_agent`)
* Type (house/apartment/room/sale/plot/commercial/other) → `properties.type`
* Location (city/area/plot no.) → `properties.{city, area, plot_no}`
* Price (rent/month or sale) → `properties.price_bwp` + `properties.price_type` (rent/sale)
* Beds, baths, living room, kitchen, furnished, yard, gate, parking, pet friendly, utilities incl., availability date, features → `properties` columns + `properties.features_json`
* Photos → `property_photos`
* Viewing times, occupancy, negotiation, conditions → `properties.meta_json`

### 4.5 Job Seeker (profile + additional questions)

* Personal: name, gender, DOB, nationality, ID/passport → `profiles`
* Contacts: phone (WhatsApp), email → `profiles`
* Location: city/town, address/area, **home village** → `profiles.{city, area, home_village}`
* Language preference → `profiles.language`
* Family profile (optional) → `profiles.meta_json`
* Looking for: daycare/babysitter/nanny/cleaner/landscaper/tutor/elderly care/etc. → `job_preferences.job_types[]`
* **Additional questions**:

  * Strictly part‑time / full‑time / both → `job_preferences.availability_type`
  * Stay‑in / stay‑out / both → `job_preferences.living_preference`
  * Salary range desired (min/max BWP) → `job_preferences.salary_min_bwp`, `salary_max_bwp`
  * Currently employed/on notice? → `job_preferences.current_employment_status`
  * Available start date → `job_preferences.available_from`
  * Duty preference: Mon–Sat OR Mon–Fri (2 weekends in/out pattern) → `job_preferences.work_pattern`
  * Current location (for job apps) → `profiles.city`
  * Freelancer maintenance photos/portfolio → `files`
  * List jobs interested in (tags) → `job_preferences.tags`
  * “Have our team conduct the interview?” → `job_preferences.request_assisted_interview` (bool)

### 4.6 Recruiters / Employers

* Company profile (if applicable) → `businesses` (type employer)
* Job posts: title, description, location, salary range, employment type, stay‑in/out, duties, requirements, start date, application close, contact person → `job_posts`
* Application reviews → `job_applications`

### 4.7 End User Profile (general)

* Name, gender, DOB, nationality, ID/passport (optional), phone (WhatsApp), email → `profiles`
* Interests & opt‑ins (restaurants/events/tutoring/nanny training/health & safety/local sellers) → `profiles.notifications_json`
* Verification docs (optional) → `files` + `verification_requests`

---

## 5) Data model (ER overview)

**Core**

* `users` 1–1 `profiles`
* `users` 1–N `files` (polymorphic: also attachable to businesses, products, marketplace items, properties, job posts, job seekers)
* `users` 1–N `saved_items` (polymorphic – works as universal *Cart & Saved*)

**Business & products**

* `businesses` (owned by `user_id`)
* `service_types` ⇄ `business_service_types`
* `business_services` (optional structured services/pricing)
* `products` (owned by `business_id`) ⇄ `product_photos`

**Marketplace**

* `marketplace_items` (owned by `user_id`) ⇄ `marketplace_photos`

**Property**

* `properties` (owned by `user_id`) ⇄ `property_photos`

**Jobs**

* `job_seekers` (1–1 with `profiles` via `user_id`)
* `job_preferences` (1–1 `job_seekers`)
* `job_posts` (owned by recruiter `user_id` or `business_id`)
* `job_applications` (M–N between `job_seekers` and `job_posts`)

**Aux**

* `contacts` (polymorphic: business/contact person, etc.)
* `reviews` (user → business/product/property)
* `verification_requests` (user or business)

---

## 6) Tables (proposed schema)

> Not exhaustive; representative columns + types. All tables include: `id`, timestamps, `deleted_at` for soft deletes.

### 6.1 users

* `id` (ULID), `name`, `email` (unique), `phone`, `password`, `role_flags` (json or bitmask), `is_agent` (bool), `last_login_at`

### 6.2 profiles (1–1 users)

* `user_id` (FK), `gender` (enum), `dob` (date), `nationality`, `id_number`, `whatsapp_phone`, `city`, `area`, `home_village`, `language` (enum: en, tn, other), `notifications_json` (json), `meta_json` (json)

### 6.3 businesses

* `user_id` (FK owner), `name`, `type` (enum: service\_provider, product\_vendor, employer), `registration_number`, `years_in_operation` (tinyint), `employees_count` (smallint), `location_text`, `open_time` (time), `close_time` (time), `service_area_text`, `delivery_info_json` (json), `sales_mode` (enum: physical, online, both), `profile_json` (json), `status` (enum: pending, approved, rejected)

### 6.4 contacts (polymorphic)

* `contactable_type`, `contactable_id`, `person_name`, `phones_json`, `email`, `socials_json`, `website`

### 6.5 service\_types & business\_service\_types (pivot)

* `service_types`: `id`, `name`
* `business_service_types`: `business_id`, `service_type_id`

### 6.6 business\_services (optional granular services)

* `business_id`, `title`, `description`, `pricing_json`, `packages_json`, `specialties`

### 6.7 product\_categories

* `id`, `name`, `parent_id` (nullable)

### 6.8 products

* `business_id`, `category_id`, `name`, `description`, `price_bwp` (int), `packaging_options`, `features_json`, `is_active`

### 6.9 product\_photos

* `product_id`, `file_id`, `is_primary`

### 6.10 marketplace\_items

* `user_id`, `category_id` (nullable), `name`, `description`, `price_bwp` (int), `quantity` (int), `delivery_options` (enum: pickup, delivery, both), `locations_json`, `is_handmade` (bool), `meta_json`, `status` (enum: pending, approved, rejected)

### 6.11 marketplace\_photos

* `marketplace_item_id`, `file_id`, `is_primary`

### 6.12 properties

* `user_id`, `type` (enum), `city`, `area`, `plot_no`, `price_bwp` (int), `price_type` (enum: rent, sale), `bedrooms` (tinyint), `bathrooms` (tinyint), `has_living_room` (bool), `has_kitchen` (bool), `is_furnished` (bool), `yard_type` (enum: private, shared, none), `has_gate` (bool), `has_parking` (bool), `pet_friendly` (bool), `utilities_included` (bool), `available_from` (date), `features_json` (json), `meta_json` (json), `status` (enum: pending, approved, rejected)

### 6.13 property\_photos

* `property_id`, `file_id`, `is_primary`

### 6.14 job\_seekers

* `user_id`, `bio`, `skills_json`, `experience_years` (tinyint), `verified_at` (nullable)

### 6.15 job\_preferences (1–1 job\_seekers)

* `job_seeker_id`, `job_types_json`, `availability_type` (enum: part\_time, full\_time, both), `living_preference` (enum: stay\_in, stay\_out, both), `salary_min_bwp` (int), `salary_max_bwp` (int), `current_employment_status` (enum: unemployed, employed, notice), `available_from` (date), `work_pattern` (enum: mon\_sat, mon\_fri\_weekends\_2in2out), `tags` (json), `request_assisted_interview` (bool)

### 6.16 job\_posts

* `user_id` (recruiter) or `business_id`, `title`, `description`, `city`, `area`, `employment_type` (enum: part\_time, full\_time, temp), `living_preference` (enum), `salary_min_bwp` (int), `salary_max_bwp` (int), `duties_json`, `requirements_json`, `start_date` (date), `apply_until` (date), `status` (enum: open, closed, paused)

### 6.17 job\_applications

* `job_post_id`, `job_seeker_id`, `cover_note`, `status` (enum: submitted, shortlisted, rejected, hired), `contacted_via` (enum: none, call, whatsapp, email), `contacted_at` (datetime)

### 6.18 files (polymorphic)

* `owner_type`, `owner_id`, `kind` (enum: id\_card, passport, license, brochure, portfolio, product\_photo, property\_photo, resume, other), `disk`, `path`, `original_name`, `mime`, `size_bytes`, `meta_json`

### 6.19 saved\_items (universal cart & wishlist)

* `user_id`, `item_type` (enum: product, marketplace\_item, property, job\_post), `item_id`, `quantity` (int, nullable), `note` (nullable), `is_cart` (bool)

> Treat `is_cart=false` as **Saved**; `is_cart=true` as **Cart** (for buyable items).

### 6.20 verification\_requests

* `owner_type`, `owner_id`, `status` (enum: pending, approved, rejected), `review_notes`

### 6.21 reviews

* `user_id` (author), `reviewable_type`, `reviewable_id`, `rating` (1–5), `comment`, `approved_at`

---

## 7) Example Laravel migrations (snippets)

```php
// 2025_01_01_000001_create_businesses_table.php
Schema::create('businesses', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->enum('type', ['service_provider','product_vendor','employer']);
    $table->string('registration_number')->nullable();
    $table->tinyInteger('years_in_operation')->nullable();
    $table->smallInteger('employees_count')->nullable();
    $table->string('location_text')->nullable();
    $table->time('open_time')->nullable();
    $table->time('close_time')->nullable();
    $table->string('service_area_text')->nullable();
    $table->json('delivery_info_json')->nullable();
    $table->enum('sales_mode', ['physical','online','both'])->nullable();
    $table->json('profile_json')->nullable();
    $table->enum('status', ['pending','approved','rejected'])->default('pending');
    $table->timestamps();
    $table->softDeletes();
});
```

```php
// 2025_01_01_000010_create_marketplace_items_table.php
Schema::create('marketplace_items', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
    $table->foreignUlid('category_id')->nullable()->constrained('product_categories');
    $table->string('name');
    $table->text('description')->nullable();
    $table->unsignedInteger('price_bwp'); // store in thebe if needed, else whole pula
    $table->unsignedInteger('quantity')->default(1);
    $table->enum('delivery_options', ['pickup','delivery','both'])->default('pickup');
    $table->json('locations_json')->nullable();
    $table->boolean('is_handmade')->default(false);
    $table->json('meta_json')->nullable();
    $table->enum('status', ['pending','approved','rejected'])->default('pending');
    $table->timestamps();
    $table->softDeletes();
});
```

```php
// 2025_01_01_000020_create_properties_table.php
Schema::create('properties', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['house_rent','apartment_rent','room_rent','house_sale','plot_sale','commercial','other']);
    $table->string('city');
    $table->string('area')->nullable();
    $table->string('plot_no')->nullable();
    $table->unsignedInteger('price_bwp');
    $table->enum('price_type', ['rent','sale']);
    $table->tinyInteger('bedrooms')->nullable();
    $table->tinyInteger('bathrooms')->nullable();
    $table->boolean('has_living_room')->default(true);
    $table->boolean('has_kitchen')->default(true);
    $table->boolean('is_furnished')->default(false);
    $table->enum('yard_type', ['private','shared','none'])->default('none');
    $table->boolean('has_gate')->default(false);
    $table->boolean('has_parking')->default(false);
    $table->boolean('pet_friendly')->default(false);
    $table->boolean('utilities_included')->default(false);
    $table->date('available_from')->nullable();
    $table->json('features_json')->nullable();
    $table->json('meta_json')->nullable();
    $table->enum('status', ['pending','approved','rejected'])->default('pending');
    $table->timestamps();
    $table->softDeletes();
});
```

```php
// 2025_01_01_000030_create_job_posts_and_applications.php
Schema::create('job_posts', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
    $table->foreignUlid('business_id')->nullable()->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('description');
    $table->string('city');
    $table->string('area')->nullable();
    $table->enum('employment_type',['part_time','full_time','temp']);
    $table->enum('living_preference',['stay_in','stay_out','both'])->default('both');
    $table->unsignedInteger('salary_min_bwp')->nullable();
    $table->unsignedInteger('salary_max_bwp')->nullable();
    $table->json('duties_json')->nullable();
    $table->json('requirements_json')->nullable();
    $table->date('start_date')->nullable();
    $table->date('apply_until')->nullable();
    $table->enum('status',['open','closed','paused'])->default('open');
    $table->timestamps();
    $table->softDeletes();
});

Schema::create('job_applications', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('job_post_id')->constrained()->cascadeOnDelete();
    $table->foreignUlid('job_seeker_id')->constrained()->cascadeOnDelete();
    $table->text('cover_note')->nullable();
    $table->enum('status',['submitted','shortlisted','rejected','hired'])->default('submitted');
    $table->enum('contacted_via',['none','call','whatsapp','email'])->default('none');
    $table->timestamp('contacted_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
    $table->unique(['job_post_id','job_seeker_id']);
});
```

```php
// 2025_01_01_000040_create_files_and_saved_items.php
Schema::create('files', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->string('owner_type');
    $table->ulid('owner_id');
    $table->enum('kind',['id_card','passport','license','brochure','portfolio','product_photo','property_photo','resume','other'])->default('other');
    $table->string('disk')->default('public');
    $table->string('path');
    $table->string('original_name');
    $table->string('mime');
    $table->unsignedBigInteger('size_bytes');
    $table->json('meta_json')->nullable();
    $table->timestamps();
});

Schema::create('saved_items', function (Blueprint $table) {
    $table->ulid('id')->primary();
    $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
    $table->enum('item_type',['product','marketplace_item','property','job_post']);
    $table->ulid('item_id');
    $table->unsignedInteger('quantity')->nullable();
    $table->string('note')->nullable();
    $table->boolean('is_cart')->default(false);
    $table->timestamps();
    $table->unique(['user_id','item_type','item_id','is_cart']);
});
```

---

## 8) Eloquent model relationships (examples)

```php
class User extends Model {
    public function profile() { return $this->hasOne(Profile::class); }
    public function businesses() { return $this->hasMany(Business::class); }
    public function marketplaceItems() { return $this->hasMany(MarketplaceItem::class); }
    public function properties() { return $this->hasMany(Property::class); }
    public function jobSeeker() { return $this->hasOne(JobSeeker::class); }
    public function savedItems() { return $this->hasMany(SavedItem::class); }
}

class Business extends Model {
    public function owner() { return $this->belongsTo(User::class,'user_id'); }
    public function serviceTypes() { return $this->belongsToMany(ServiceType::class,'business_service_types'); }
    public function contacts() { return $this->morphMany(Contact::class,'contactable'); }
    public function products() { return $this->hasMany(Product::class); }
    public function files() { return $this->morphMany(File::class,'owner'); }
}

class JobSeeker extends Model {
    public function user() { return $this->belongsTo(User::class); }
    public function preferences() { return $this->hasOne(JobPreference::class); }
    public function applications() { return $this->hasMany(JobApplication::class); }
}

class JobPost extends Model {
    public function recruiter() { return $this->belongsTo(User::class,'user_id'); }
    public function business() { return $this->belongsTo(Business::class); }
    public function applications() { return $this->hasMany(JobApplication::class); }
}

class SavedItem extends Model {
    public function user() { return $this->belongsTo(User::class); }
    public function item() { return $this->morphTo(null,'item_type','item_id'); }
}
```

---

## 9) REST endpoints (high‑level)

Use route groups, auth middleware, and policy/ability checks.

```
Auth
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout

Profiles & Verification
GET    /api/me
PUT    /api/me/profile
POST   /api/verification/requests

Businesses (services & vendors)
GET    /api/businesses
POST   /api/businesses
GET    /api/businesses/{id}
PUT    /api/businesses/{id}
POST   /api/businesses/{id}/contacts
POST   /api/businesses/{id}/files

Products (vendor catalogs)
GET    /api/products
POST   /api/businesses/{id}/products
GET    /api/products/{id}
PUT    /api/products/{id}
POST   /api/products/{id}/photos

Marketplace (second‑hand)
GET    /api/marketplace/items
POST   /api/marketplace/items
GET    /api/marketplace/items/{id}
PUT    /api/marketplace/items/{id}
POST   /api/marketplace/items/{id}/photos

Properties
GET    /api/properties
POST   /api/properties
GET    /api/properties/{id}
PUT    /api/properties/{id}
POST   /api/properties/{id}/photos

Jobs
GET    /api/jobs
POST   /api/jobs
GET    /api/jobs/{id}
PUT    /api/jobs/{id}
POST   /api/jobs/{id}/apply
GET    /api/jobs/{id}/applications (recruiter only)
PUT    /api/applications/{id}

Job Seeker
GET    /api/me/job-seeker
PUT    /api/me/job-seeker
PUT    /api/me/job-preferences

Saved / Cart (universal)
GET    /api/saved
POST   /api/saved  // {item_type,item_id,is_cart,quantity}
DELETE /api/saved/{id}

Files
POST   /api/files  // owner_type, owner_id, kind, file
DELETE /api/files/{id}

Admin (moderation)
GET    /api/admin/approvals?type=business|marketplace|property
PUT    /api/admin/approvals/{id}  // approve/reject
```

---

## 10) Validation rules (selected)

**Common:** phone (E.164), emails unique, max sizes, allowed mime

**Service Provider (business)**

* `name` required, `type` in list
* `registration_number` string|max:50
* `years_in_operation` integer|min:0|max:60
* `employees_count` integer|min:0|max:5000
* `open_time` before `close_time`

**Product**

* `name` required, `price_bwp` integer|min:0
* `category_id` exists

**Marketplace item**

* `name`, `price_bwp` required; photos min 1 for approval

**Property**

* `type` in enum, `city` required, `price_bwp` integer|min:0

**Job post**

* `title`, `description`, `city` required
* salary min ≤ max; `employment_type` in enum

**Job preferences**

* enums for availability, living preference, work pattern
* salary range integers

**Files**

* images: max 10MB; pdf/doc: 10MB; count limits per entity (e.g., max 12 photos/property)

---

## 11) Search & filters (examples)

* Businesses: by service type, city, open now
* Products: category, price range, vendor verified
* Marketplace: category, price range, city, condition (meta)
* Properties: type, city/area, price range, bedrooms, furnished, features
* Jobs: city, employment type, stay‑in/out, salary, start date

Use Eloquent scopes + DB indexes on (`city`, `type`, `price_bwp`), JSON path indexes where needed.

---

## 12) Approvals & verification flow

* New businesses, marketplace items, properties: default **pending** → admin approves
* Users can submit verification docs; admin marks **approved/rejected** with notes
* Simple **Contact Actions** even if messaging module is out of scope: store `contacted_via` + timestamp on applications

---

## 13) Minimal contact actions (no full chat)

* **Call**: tel: link
* **WhatsApp**: `https://wa.me/<phone>?text=<templated message>`
* **Email**: `mailto:` with subject + body template
* Log the action in `job_applications.contacted_via/at` or a simple `contact_logs` table if broader.

---

## 14) Admin review screens

* **Unified Approvals Queue** with tabs: Businesses, Marketplace, Properties
* Row shows: owner, title, city, created\_at, photo thumb, **Approve/Reject**
* On reject → capture `review_notes`

---

## 15) Security & privacy

* Only owners/admins can edit/delete their records
* PII and verification docs accessible to owner + admins
* Rate limit uploads; scan images if feasible
* Audit log (optional): who approved/edited

---

## 16) Seeders & fixtures (suggested)

* Service types: catering, entertainment, cleaning, healthcare, tutoring, etc.
* Product categories: clothing, toys, beauty, food, electronics, furniture
* Job types: nanny, babysitter, cleaner, gardener, tutor, elderly\_care, driver, cook, housekeeper
* Property types enum prefilled

---

## 17) What’s purposely out of scope for v1

* Payment processing, commissions, credits (“units”)
* Full real‑time messaging/inbox management
* Advanced analytics/dashboards

---

## 18) Acceptance checklist (go/no‑go)

* [ ] User can create profile, upload verification docs
* [ ] Business (services) can register + get approved
* [ ] Product vendor can add products with photos
* [ ] Marketplace item can be listed (pending → approved)
* [ ] Property can be listed with photos (pending → approved)
* [ ] Recruiter can post job; Job Seeker can set preferences + apply
* [ ] Admin can approve/reject everything
* [ ] User dashboard shows **jobs interested**, **location**, **home village**, **salary range**, and **Cart & Saved** with cross‑type items
* [ ] Contact actions (call/WhatsApp/email) work from review screens and dashboard

---

## 19) Notes for the UI dev

* Keep forms short with progressive disclosure (e.g., show “Packages” only when needed)
* Multi‑upload with drag & drop; show first photo as primary
* Use tag chips for **jobs interested** and **service types**
* Confirmation dialogs on approve/reject
* Display currency symbol **P** for BWP; format with thousands separator
