<?php
/**
 * OneNest – All‑in‑One Laravel File (Migrations + Models + FormRequests)
 * ---------------------------------------------------------------
 * Single PHP file containing:
 *  - One big Migration that creates all tables
 *  - All Eloquent Models (with ULIDs, relationships, casts, fillables)
 *  - FormRequest classes for main create/update flows
 *
 * Notes:
 * 1) This is provided as a single file per request. In a real app, split into
 *    separate files under database/migrations, app/Models, app/Http/Requests.
 * 2) Requires Laravel 10/11, PHP 8.2+, MySQL 8. Use ULIDs. Timezone Africa/Gaborone.
 * 3) Money stored as integer Pula (or thebe if you prefer) – field named price_bwp.
 */

declare(strict_types=1);

// ============================================================================
//  MIGRATIONS
// ============================================================================

namespace Database\Migrations {

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            // USERS
            Schema::create('users', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('password');
                $table->json('role_flags')->nullable(); // e.g., ["buyer", "job_seeker", ...]
                $table->boolean('is_agent')->default(false);
                $table->timestamp('last_login_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });

            // PROFILES (1–1 users)
            Schema::create('profiles', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('gender', ['male','female','other'])->nullable();
                $table->date('dob')->nullable();
                $table->string('nationality')->nullable();
                $table->string('id_number')->nullable();
                $table->string('whatsapp_phone')->nullable();
                $table->string('city')->nullable();
                $table->string('area')->nullable();
                $table->string('home_village')->nullable();
                $table->enum('language', ['en','tn','other'])->nullable();
                $table->json('notifications_json')->nullable();
                $table->json('meta_json')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            // BUSINESSES
            Schema::create('businesses', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('name');
                $table->enum('type', ['service_provider','product_vendor','employer']);
                $table->string('registration_number')->nullable(); // CIPA
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

            // CONTACTS (polymorphic)
            Schema::create('contacts', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('contactable_type');
                $table->ulid('contactable_id');
                $table->string('person_name')->nullable();
                $table->json('phones_json')->nullable(); // ["+267...", "+267..."]
                $table->string('email')->nullable();
                $table->json('socials_json')->nullable(); // {facebook:"", instagram:""}
                $table->string('website')->nullable();
                $table->timestamps();
            });
            Schema::create('service_types', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('name')->unique();
                $table->timestamps();
            });
            Schema::create('business_service_types', function (Blueprint $table) {
                $table->foreignUlid('business_id')->constrained('businesses')->cascadeOnDelete();
                $table->foreignUlid('service_type_id')->constrained('service_types')->cascadeOnDelete();
                $table->primary(['business_id','service_type_id']);
            });

            Schema::create('business_services', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('business_id')->constrained('businesses')->cascadeOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('pricing_json')->nullable();
                $table->json('packages_json')->nullable();
                $table->string('specialties')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            // PRODUCTS
            Schema::create('product_categories', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('name');
                $table->foreignUlid('parent_id')->nullable()->constrained('product_categories');
                $table->timestamps();
            });
            Schema::create('products', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('business_id')->constrained('businesses')->cascadeOnDelete();
                $table->foreignUlid('category_id')->nullable()->constrained('product_categories');
                $table->string('name');
                $table->text('description')->nullable();
                $table->unsignedInteger('price_bwp');
                $table->string('packaging_options')->nullable();
                $table->json('features_json')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
                $table->index(['business_id','category_id']);
            });
            Schema::create('product_photos', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignUlid('file_id')->constrained('files'); // forward ref, will create files later
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });

            // MARKETPLACE (second‑hand)
            Schema::create('marketplace_items', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignUlid('category_id')->nullable()->constrained('product_categories');
                $table->string('name');
                $table->text('description')->nullable();
                $table->unsignedInteger('price_bwp');
                $table->unsignedInteger('quantity')->default(1);
                $table->enum('delivery_options', ['pickup','delivery','both'])->default('pickup');
                $table->json('locations_json')->nullable();
                $table->boolean('is_handmade')->default(false);
                $table->json('meta_json')->nullable();
                $table->enum('status', ['pending','approved','rejected'])->default('pending');
                $table->timestamps();
                $table->softDeletes();
                $table->index(['user_id','status']);
            });
            Schema::create('marketplace_photos', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('marketplace_item_id')->constrained('marketplace_items')->cascadeOnDelete();
                $table->foreignUlid('file_id')->constrained('files');
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });

            // PROPERTIES
            Schema::create('properties', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
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
                $table->index(['city','type','price_bwp']);
            });
            Schema::create('property_photos', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('property_id')->constrained('properties')->cascadeOnDelete();
                $table->foreignUlid('file_id')->constrained('files');
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });

            // JOB SEEKERS & JOBS
            Schema::create('job_seekers', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
                $table->text('bio')->nullable();
                $table->json('skills_json')->nullable();
                $table->tinyInteger('experience_years')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
            Schema::create('job_preferences', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('job_seeker_id')->constrained('job_seekers')->cascadeOnDelete();
                $table->json('job_types_json')->nullable(); // e.g., ["nanny","cleaner"]
                $table->enum('availability_type', ['part_time','full_time','both'])->default('both');
                $table->enum('living_preference', ['stay_in','stay_out','both'])->default('both');
                $table->unsignedInteger('salary_min_bwp')->nullable();
                $table->unsignedInteger('salary_max_bwp')->nullable();
                $table->enum('current_employment_status', ['unemployed','employed','notice'])->nullable();
                $table->date('available_from')->nullable();
                $table->enum('work_pattern', ['mon_sat','mon_fri_weekends_2in2out'])->nullable();
                $table->json('tags')->nullable();
                $table->boolean('request_assisted_interview')->default(false);
                $table->timestamps();
            });
            Schema::create('job_posts', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete(); // recruiter
                $table->foreignUlid('business_id')->nullable()->constrained('businesses')->cascadeOnDelete();
                $table->string('title');
                $table->text('description');
                $table->string('city');
                $table->string('area')->nullable();
                $table->enum('employment_type', ['part_time','full_time','temp']);
                $table->enum('living_preference', ['stay_in','stay_out','both'])->default('both');
                $table->unsignedInteger('salary_min_bwp')->nullable();
                $table->unsignedInteger('salary_max_bwp')->nullable();
                $table->json('duties_json')->nullable();
                $table->json('requirements_json')->nullable();
                $table->date('start_date')->nullable();
                $table->date('apply_until')->nullable();
                $table->enum('status', ['open','closed','paused'])->default('open');
                $table->timestamps();
                $table->softDeletes();
                $table->index(['city','employment_type','status']);
            });
            Schema::create('job_applications', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('job_post_id')->constrained('job_posts')->cascadeOnDelete();
                $table->foreignUlid('job_seeker_id')->constrained('job_seekers')->cascadeOnDelete();
                $table->text('cover_note')->nullable();
                $table->enum('status', ['submitted','shortlisted','rejected','hired'])->default('submitted');
                $table->enum('contacted_via', ['none','call','whatsapp','email'])->default('none');
                $table->timestamp('contacted_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->unique(['job_post_id','job_seeker_id']);
            });

            // FILES (polymorphic)
            Schema::create('files', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('owner_type');
                $table->ulid('owner_id');
                $table->enum('kind', ['id_card','passport','license','brochure','portfolio','product_photo','property_photo','resume','other'])->default('other');
                $table->string('disk')->default('public');
                $table->string('path');
                $table->string('original_name');
                $table->string('mime');
                $table->unsignedBigInteger('size_bytes');
                $table->json('meta_json')->nullable();
                $table->timestamps();
            });

            // SAVED ITEMS (universal cart & wishlist)
            Schema::create('saved_items', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('item_type', ['product','marketplace_item','property','job_post']);
                $table->ulid('item_id');
                $table->unsignedInteger('quantity')->nullable();
                $table->string('note')->nullable();
                $table->boolean('is_cart')->default(false);
                $table->timestamps();
                $table->unique(['user_id','item_type','item_id','is_cart']);
            });

            // VERIFICATION
            Schema::create('verification_requests', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->string('owner_type');
                $table->ulid('owner_id');
                $table->enum('status', ['pending','approved','rejected'])->default('pending');
                $table->text('review_notes')->nullable();
                $table->timestamps();
            });

            // REVIEWS
            Schema::create('reviews', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete(); // author
                $table->string('reviewable_type');
                $table->ulid('reviewable_id');
                $table->unsignedTinyInteger('rating'); // 1-5
                $table->text('comment')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('reviews');
            Schema::dropIfExists('verification_requests');
            Schema::dropIfExists('saved_items');
            Schema::dropIfExists('files');
            Schema::dropIfExists('job_applications');
            Schema::dropIfExists('job_posts');
            Schema::dropIfExists('job_preferences');
            Schema::dropIfExists('job_seekers');
            Schema::dropIfExists('property_photos');
            Schema::dropIfExists('properties');
            Schema::dropIfExists('marketplace_photos');
            Schema::dropIfExists('marketplace_items');
            Schema::dropIfExists('product_photos');
            Schema::dropIfExists('products');
            Schema::dropIfExists('product_categories');
            Schema::dropIfExists('business_services');
            Schema::dropIfExists('business_service_types');
            Schema::dropIfExists('service_types');
            Schema::dropIfExists('contacts');
            Schema::dropIfExists('businesses');
            Schema::dropIfExists('profiles');
            Schema::dropIfExists('users');
        }
    };
}

// ============================================================================
//  MODELS
// ============================================================================

namespace App\Models {

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Database\Eloquent\Concerns\HasUlids;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;

    // ------------------- User -------------------
    class User extends Authenticatable
    {
        use Notifiable, SoftDeletes, HasUlids;
        protected $fillable = ['name','email','phone','password','role_flags','is_agent'];
        protected $hidden = ['password','remember_token'];
        protected $casts = [
            'role_flags' => 'array',
            'is_agent' => 'boolean',
            'last_login_at' => 'datetime',
        ];
        public $incrementing = false;
        protected $keyType = 'string';

        public function profile() { return $this->hasOne(Profile::class); }
        public function businesses() { return $this->hasMany(Business::class); }
        public function marketplaceItems() { return $this->hasMany(MarketplaceItem::class); }
        public function properties() { return $this->hasMany(Property::class); }
        public function jobSeeker() { return $this->hasOne(JobSeeker::class); }
        public function savedItems() { return $this->hasMany(SavedItem::class); }
        public function files() { return $this->morphMany(File::class, 'owner'); }
        public function reviewsAuthored() { return $this->hasMany(Review::class); }
    }

    // ------------------- Profile -------------------
    class Profile extends Model
    {
        use SoftDeletes, HasUlids;
        protected $fillable = [
            'user_id','gender','dob','nationality','id_number','whatsapp_phone','city','area','home_village','language','notifications_json','meta_json'
        ];
        protected $casts = [
            'dob' => 'date',
            'notifications_json' => 'array',
            'meta_json' => 'array',
        ];
        public $incrementing = false; protected $keyType = 'string';
        public function user() { return $this->belongsTo(User::class); }
        public function files() { return $this->morphMany(File::class, 'owner'); }
    }

    // ------------------- Business -------------------
    class Business extends Model
    {
        use SoftDeletes, HasUlids;
        protected $fillable = [
            'user_id','name','type','registration_number','years_in_operation','employees_count','location_text','open_time','close_time','service_area_text','delivery_info_json','sales_mode','profile_json','status'
        ];
        protected $casts = [
            'delivery_info_json' => 'array',
            'profile_json' => 'array',
        ];
        public $incrementing = false; protected $keyType = 'string';
        public function owner() { return $this->belongsTo(User::class,'user_id'); }
        public function serviceTypes() { return $this->belongsToMany(ServiceType::class,'business_service_types'); }
        public function contacts() { return $this->morphMany(Contact::class, 'contactable'); }
        public function products() { return $this->hasMany(Product::class); }
        public function files() { return $this->morphMany(File::class, 'owner'); }
    }

    class Contact extends Model
    {
        use HasUlids;
        public $timestamps = true;
        protected $fillable = ['contactable_type','contactable_id','person_name','phones_json','email','socials_json','website'];
        protected $casts = [ 'phones_json' => 'array', 'socials_json' => 'array' ];
        public function contactable() { return $this->morphTo(); }
    }

    class ServiceType extends Model
    {
        use HasUlids;
        protected $fillable = ['name'];
        public function businesses() { return $this->belongsToMany(Business::class,'business_service_types'); }
    }

    class BusinessService extends Model
    {
        use SoftDeletes, HasUlids;
        protected $fillable = ['business_id','title','description','pricing_json','packages_json','specialties'];
        protected $casts = ['pricing_json'=>'array','packages_json'=>'array'];
        public function business() { return $this->belongsTo(Business::class); }
    }

    // ------------------- Products -------------------
    class ProductCategory extends Model
    {
        use HasUlids;
        protected $fillable = ['name','parent_id'];
        public function parent() { return $this->belongsTo(ProductCategory::class,'parent_id'); }
        public function children() { return $this->hasMany(ProductCategory::class,'parent_id'); }
    }

    class Product extends Model
    {
        use SoftDeletes, HasUlids;
        protected $fillable = ['business_id','category_id','name','description','price_bwp','packaging_options','features_json','is_active'];
        protected $casts = ['features_json'=>'array','is_active'=>'boolean'];
        public function business() { return $this->belongsTo(Business::class); }
        public function category() { return $this->belongsTo(ProductCategory::class,'category_id'); }
        public function photos() { return $this->hasMany(ProductPhoto::class); }
        public function files() { return $this->morphMany(File::class,'owner'); }
    }

    class ProductPhoto extends Model
    {
        use HasUlids;
        protected $fillable = ['product_id','file_id','is_primary'];
        protected $casts = ['is_primary'=>'boolean'];
        public function product() { return $this->belongsTo(Product::class); }
        public function file() { return $this->belongsTo(File::class); }
    }

    // ------------------- Marketplace -------------------
    class MarketplaceItem extends Model
    {
        use SoftDeletes, HasUlids;
        protected $fillable = ['user_id','category_id','name','description','price_bwp','quantity','delivery_options','locations_json','is_handmade','meta_json','status'];
        protected $casts = [ 'locations_json'=>'array','is_handmade'=>'boolean','meta_json'=>'array' ];
        public function user() { return $this->belongsTo(User::class); }
        public function category() { return $this->belongsTo(ProductCategory::class,'category_id'); }
        public function photos() { return $this->hasMany(MarketplacePhoto::class); }
        public function files() { return $this->morphMany(File::class,'owner'); }
        public function reviews() { return $this->morphMany(Review::class,'reviewable'); }
    }

    class MarketplacePhoto extends Model
    {
        use HasUlids;
        protected $fillable = ['marketplace_item_id','file_id','is_primary'];
        protected $casts = ['is_primary'=>'boolean'];
        public function item() { return $this->belongsTo(MarketplaceItem::class, 'marketplace_item_id'); }
        public function file() { return $this->belongsTo(File::class); }
    }

    // ------------------- Properties -------------------
    class Property extends Model
    {
        use SoftDeletes, HasUlids;
        protected $fillable = ['user_id','type','city','area','plot_no','price_bwp','price_type','bedrooms','bathrooms','has_living_room','has_kitchen','is_furnished','yard_type','has_gate','has_parking','pet_friendly','utilities_included','available_from','features_json','meta_json','status'];
        protected $casts = [
            'has_living_room'=>'boolean','has_kitchen'=>'boolean','is_furnished'=>'boolean','has_gate'=>'boolean','has_parking'=>'boolean','pet_friendly'=>'boolean','utilities_included'=>'boolean','available_from'=>'date','features_json'=>'array','meta_json'=>'array'
        ];
        public function user() { return $this->belongsTo(User::class); }
        public function photos() { return $this->hasMany(PropertyPhoto::class); }
        public function files() { return $this->morphMany(File::class,'owner'); }
        public function reviews() { return $this->morphMany(Review::class,'reviewable'); }
    }

    class PropertyPhoto extends Model
    {
        use HasUlids;
        protected $fillable = ['property_id','file_id','is_primary'];
        protected $casts = ['is_primary'=>'boolean'];
        public function property() { return $this->belongsTo(Property::class); }
        public function file() { return $this->belongsTo(File::class); }
    }

    // ------------------- Jobs -------------------
    class JobSeeker extends Model
    {
        use SoftDeletes, HasUlids;
        protected $fillable = ['user_id','bio','skills_json','experience_years','verified_at'];
        protected $casts = ['skills_json'=>'array','verified_at'=>'datetime'];
        public function user() { return $this->belongsTo(User::class); }
        public function preferences() { return $this->hasOne(JobPreference::class); }
        public function applications() { return $this->hasMany(JobApplication::class); }
        public function files() { return $this->morphMany(File::class,'owner'); }
    }

    class JobPreference extends Model
    {
        use HasUlids;
        protected $fillable = ['job_seeker_id','job_types_json','availability_type','living_preference','salary_min_bwp','salary_max_bwp','current_employment_status','available_from','work_pattern','tags','request_assisted_interview'];
        protected $casts = ['job_types_json'=>'array','available_from'=>'date','tags'=>'array','request_assisted_interview'=>'boolean'];
        public function jobSeeker() { return $this->belongsTo(JobSeeker::class); }
    }

    class JobPost extends Model
    {
        use SoftDeletes, HasUlids;
        protected $fillable = ['user_id','business_id','title','description','city','area','employment_type','living_preference','salary_min_bwp','salary_max_bwp','duties_json','requirements_json','start_date','apply_until','status'];
        protected $casts = [ 'duties_json'=>'array','requirements_json'=>'array','start_date'=>'date','apply_until'=>'date' ];
        public function recruiter() { return $this->belongsTo(User::class,'user_id'); }
        public function business() { return $this->belongsTo(Business::class); }
        public function applications() { return $this->hasMany(JobApplication::class); }
        public function reviews() { return $this->morphMany(Review::class,'reviewable'); }
    }

    class JobApplication extends Model
    {
        use SoftDeletes, HasUlids;
        protected $fillable = ['job_post_id','job_seeker_id','cover_note','status','contacted_via','contacted_at'];
        protected $casts = ['contacted_at'=>'datetime'];
        public function jobPost() { return $this->belongsTo(JobPost::class); }
        public function jobSeeker() { return $this->belongsTo(JobSeeker::class); }
    }

    // ------------------- Files / Saved / Verification / Reviews -------------------
    class File extends Model
    {
        use HasUlids;
        protected $fillable = ['owner_type','owner_id','kind','disk','path','original_name','mime','size_bytes','meta_json'];
        protected $casts = ['meta_json'=>'array'];
        public function owner() { return $this->morphTo(); }
    }

    class SavedItem extends Model
    {
        use HasUlids;
        protected $fillable = ['user_id','item_type','item_id','quantity','note','is_cart'];
        protected $casts = ['is_cart'=>'boolean'];
        public function user() { return $this->belongsTo(User::class); }
        public function item() { return $this->morphTo(null,'item_type','item_id'); }
    }

    class VerificationRequest extends Model
    {
        use HasUlids;
        protected $fillable = ['owner_type','owner_id','status','review_notes'];
        public function owner() { return $this->morphTo(); }
    }

    class Review extends Model
    {
        use HasUlids;
        protected $fillable = ['user_id','reviewable_type','reviewable_id','rating','comment','approved_at'];
        protected $casts = ['approved_at'=>'datetime'];
        public function author() { return $this->belongsTo(User::class,'user_id'); }
        public function reviewable() { return $this->morphTo(); }
    }
}

// ============================================================================
//  FORM REQUESTS
// ============================================================================

namespace App\Http\Requests {

    use Illuminate\Foundation\Http\FormRequest;

    // ------------------- Profiles -------------------
    class ProfileUpdateRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'gender' => 'nullable|in:male,female,other',
                'dob' => 'nullable|date',
                'nationality' => 'nullable|string|max:100',
                'id_number' => 'nullable|string|max:50',
                'whatsapp_phone' => 'nullable|string|max:30',
                'city' => 'nullable|string|max:120',
                'area' => 'nullable|string|max:120',
                'home_village' => 'nullable|string|max:120',
                'language' => 'nullable|in:en,tn,other',
                'notifications_json' => 'nullable|array',
                'meta_json' => 'nullable|array',
            ];
        }
    }

    // ------------------- Business (Service Providers / Vendors) -------------------
    class BusinessStoreRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'name' => 'required|string|max:255',
                'type' => 'required|in:service_provider,product_vendor,employer',
                'registration_number' => 'nullable|string|max:50',
                'years_in_operation' => 'nullable|integer|min:0|max:60',
                'employees_count' => 'nullable|integer|min:0|max:5000',
                'location_text' => 'nullable|string|max:255',
                'open_time' => 'nullable|date_format:H:i',
                'close_time' => 'nullable|date_format:H:i|after:open_time',
                'service_area_text' => 'nullable|string|max:255',
                'delivery_info_json' => 'nullable|array',
                'sales_mode' => 'nullable|in:physical,online,both',
                'profile_json' => 'nullable|array',
            ];
        }
    }

    // ------------------- Products -------------------
    class ProductStoreRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'business_id' => 'required|exists:businesses,id',
                'category_id' => 'nullable|exists:product_categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price_bwp' => 'required|integer|min:0',
                'packaging_options' => 'nullable|string|max:255',
                'features_json' => 'nullable|array',
                'is_active' => 'boolean',
            ];
        }
    }

    // ------------------- Marketplace Items -------------------
    class MarketplaceItemStoreRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'category_id' => 'nullable|exists:product_categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price_bwp' => 'required|integer|min:0',
                'quantity' => 'nullable|integer|min:1',
                'delivery_options' => 'nullable|in:pickup,delivery,both',
                'locations_json' => 'nullable|array',
                'is_handmade' => 'boolean',
                'meta_json' => 'nullable|array',
            ];
        }
    }

    // ------------------- Properties -------------------
    class PropertyStoreRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'type' => 'required|in:house_rent,apartment_rent,room_rent,house_sale,plot_sale,commercial,other',
                'city' => 'required|string|max:120',
                'area' => 'nullable|string|max:120',
                'plot_no' => 'nullable|string|max:120',
                'price_bwp' => 'required|integer|min:0',
                'price_type' => 'required|in:rent,sale',
                'bedrooms' => 'nullable|integer|min:0|max:20',
                'bathrooms' => 'nullable|integer|min:0|max:20',
                'has_living_room' => 'boolean',
                'has_kitchen' => 'boolean',
                'is_furnished' => 'boolean',
                'yard_type' => 'nullable|in:private,shared,none',
                'has_gate' => 'boolean',
                'has_parking' => 'boolean',
                'pet_friendly' => 'boolean',
                'utilities_included' => 'boolean',
                'available_from' => 'nullable|date',
                'features_json' => 'nullable|array',
                'meta_json' => 'nullable|array',
            ];
        }
    }

    // ------------------- Job Posts -------------------
    class JobPostStoreRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'business_id' => 'nullable|exists:businesses,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'city' => 'required|string|max:120',
                'area' => 'nullable|string|max:120',
                'employment_type' => 'required|in:part_time,full_time,temp',
                'living_preference' => 'nullable|in:stay_in,stay_out,both',
                'salary_min_bwp' => 'nullable|integer|min:0',
                'salary_max_bwp' => 'nullable|integer|gte:salary_min_bwp',
                'duties_json' => 'nullable|array',
                'requirements_json' => 'nullable|array',
                'start_date' => 'nullable|date',
                'apply_until' => 'nullable|date|after_or_equal:start_date',
                'status' => 'nullable|in:open,closed,paused',
            ];
        }
    }

    // ------------------- Job Preferences -------------------
    class JobPreferenceUpdateRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'job_types_json' => 'nullable|array',
                'availability_type' => 'nullable|in:part_time,full_time,both',
                'living_preference' => 'nullable|in:stay_in,stay_out,both',
                'salary_min_bwp' => 'nullable|integer|min:0',
                'salary_max_bwp' => 'nullable|integer|gte:salary_min_bwp',
                'current_employment_status' => 'nullable|in:unemployed,employed,notice',
                'available_from' => 'nullable|date',
                'work_pattern' => 'nullable|in:mon_sat,mon_fri_weekends_2in2out',
                'tags' => 'nullable|array',
                'request_assisted_interview' => 'boolean',
            ];
        }
    }

    // ------------------- Files -------------------
    class FileUploadRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'owner_type' => 'required|string',
                'owner_id' => 'required|string',
                'kind' => 'nullable|in:id_card,passport,license,brochure,portfolio,product_photo,property_photo,resume,other',
                'file' => 'required|file|max:10240', // 10MB
            ];
        }
    }

    // ------------------- Saved / Cart -------------------
    class SavedItemStoreRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'item_type' => 'required|in:product,marketplace_item,property,job_post',
                'item_id' => 'required|string',
                'quantity' => 'nullable|integer|min:1',
                'note' => 'nullable|string|max:255',
                'is_cart' => 'boolean',
            ];
        }
    }

    // ------------------- Job Application status/contact log -------------------
    class JobApplicationUpdateRequest extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'status' => 'nullable|in:submitted,shortlisted,rejected,hired',
                'contacted_via' => 'nullable|in:none,call,whatsapp,email',
                'contacted_at' => 'nullable|date',
            ];
        }
    }

    // ------------------- Verification Requests -------------------
    class VerificationRequestStore extends FormRequest
    {
        public function authorize(): bool { return true; }
        public function rules(): array {
            return [
                'owner_type' => 'required|string',
                'owner_id' => 'required|string',
            ];
        }
    }
}
