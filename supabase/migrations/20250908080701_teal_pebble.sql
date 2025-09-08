/*
  # OneNest Database Schema - Complete Implementation
  
  1. New Tables
    - Complete user management system with profiles
    - Business registration (service providers & product vendors)
    - Marketplace for second-hand items
    - Property listings (rent/sale)
    - Job posting and application system
    - File management and verification system
    
  2. Security
    - Soft deletes on all user content
    - Proper foreign key constraints
    - Index optimization for performance
    
  3. Features
    - Universal cart/saved items system
    - Polymorphic file attachments
    - Review and rating system
    - Verification workflow
*/

SET FOREIGN_KEY_CHECKS = 0;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(30),
    password VARCHAR(255) NOT NULL,
    role_flags JSON,
    is_agent BOOLEAN DEFAULT FALSE,
    last_login_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- Profiles table (1-1 with users)
CREATE TABLE IF NOT EXISTS profiles (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    gender ENUM('male','female','other'),
    dob DATE,
    nationality VARCHAR(100),
    id_number VARCHAR(50),
    whatsapp_phone VARCHAR(30),
    city VARCHAR(120),
    area VARCHAR(120),
    home_village VARCHAR(120),
    language ENUM('en','tn','other'),
    notifications_json JSON,
    meta_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Service types
CREATE TABLE IF NOT EXISTS service_types (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Businesses table
CREATE TABLE IF NOT EXISTS businesses (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('service_provider','product_vendor','employer') NOT NULL,
    registration_number VARCHAR(50),
    years_in_operation TINYINT UNSIGNED,
    employees_count SMALLINT UNSIGNED,
    location_text VARCHAR(255),
    open_time TIME,
    close_time TIME,
    service_area_text VARCHAR(255),
    delivery_info_json JSON,
    sales_mode ENUM('physical','online','both'),
    profile_json JSON,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Business service types (many-to-many)
CREATE TABLE IF NOT EXISTS business_service_types (
    business_id CHAR(36) NOT NULL,
    service_type_id CHAR(36) NOT NULL,
    PRIMARY KEY (business_id, service_type_id),
    FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE CASCADE,
    FOREIGN KEY (service_type_id) REFERENCES service_types(id) ON DELETE CASCADE
);

-- Business services (detailed services)
CREATE TABLE IF NOT EXISTS business_services (
    id CHAR(36) PRIMARY KEY,
    business_id CHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    pricing_json JSON,
    packages_json JSON,
    specialties VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE CASCADE
);

-- Contacts table (polymorphic)
CREATE TABLE IF NOT EXISTS contacts (
    id CHAR(36) PRIMARY KEY,
    contactable_type VARCHAR(100) NOT NULL,
    contactable_id CHAR(36) NOT NULL,
    person_name VARCHAR(255),
    phones_json JSON,
    email VARCHAR(255),
    socials_json JSON,
    website VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_contactable (contactable_type, contactable_id)
);

-- Product categories
CREATE TABLE IF NOT EXISTS product_categories (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id CHAR(36),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES product_categories(id)
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id CHAR(36) PRIMARY KEY,
    business_id CHAR(36) NOT NULL,
    category_id CHAR(36),
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price_bwp INT UNSIGNED NOT NULL,
    packaging_options VARCHAR(255),
    features_json JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES product_categories(id),
    INDEX idx_business_category (business_id, category_id)
);

-- Marketplace items (second-hand)
CREATE TABLE IF NOT EXISTS marketplace_items (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    category_id CHAR(36),
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price_bwp INT UNSIGNED NOT NULL,
    quantity INT UNSIGNED DEFAULT 1,
    delivery_options ENUM('pickup','delivery','both') DEFAULT 'pickup',
    locations_json JSON,
    is_handmade BOOLEAN DEFAULT FALSE,
    meta_json JSON,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES product_categories(id),
    INDEX idx_user_status (user_id, status)
);

-- Properties table
CREATE TABLE IF NOT EXISTS properties (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    type ENUM('house_rent','apartment_rent','room_rent','house_sale','plot_sale','commercial','other') NOT NULL,
    city VARCHAR(120) NOT NULL,
    area VARCHAR(120),
    plot_no VARCHAR(120),
    price_bwp INT UNSIGNED NOT NULL,
    price_type ENUM('rent','sale') NOT NULL,
    bedrooms TINYINT UNSIGNED,
    bathrooms TINYINT UNSIGNED,
    has_living_room BOOLEAN DEFAULT TRUE,
    has_kitchen BOOLEAN DEFAULT TRUE,
    is_furnished BOOLEAN DEFAULT FALSE,
    yard_type ENUM('private','shared','none') DEFAULT 'none',
    has_gate BOOLEAN DEFAULT FALSE,
    has_parking BOOLEAN DEFAULT FALSE,
    pet_friendly BOOLEAN DEFAULT FALSE,
    utilities_included BOOLEAN DEFAULT FALSE,
    available_from DATE,
    features_json JSON,
    meta_json JSON,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_city_type_price (city, type, price_bwp)
);

-- Job seekers table
CREATE TABLE IF NOT EXISTS job_seekers (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    bio TEXT,
    skills_json JSON,
    experience_years TINYINT UNSIGNED,
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Job preferences table
CREATE TABLE IF NOT EXISTS job_preferences (
    id CHAR(36) PRIMARY KEY,
    job_seeker_id CHAR(36) NOT NULL,
    job_types_json JSON,
    availability_type ENUM('part_time','full_time','both') DEFAULT 'both',
    living_preference ENUM('stay_in','stay_out','both') DEFAULT 'both',
    salary_min_bwp INT UNSIGNED,
    salary_max_bwp INT UNSIGNED,
    current_employment_status ENUM('unemployed','employed','notice'),
    available_from DATE,
    work_pattern ENUM('mon_sat','mon_fri_weekends_2in2out'),
    tags JSON,
    request_assisted_interview BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE
);

-- Job posts table
CREATE TABLE IF NOT EXISTS job_posts (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    business_id CHAR(36),
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    city VARCHAR(120) NOT NULL,
    area VARCHAR(120),
    employment_type ENUM('part_time','full_time','temp') NOT NULL,
    living_preference ENUM('stay_in','stay_out','both') DEFAULT 'both',
    salary_min_bwp INT UNSIGNED,
    salary_max_bwp INT UNSIGNED,
    duties_json JSON,
    requirements_json JSON,
    start_date DATE,
    apply_until DATE,
    status ENUM('open','closed','paused') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE CASCADE,
    INDEX idx_city_type_status (city, employment_type, status)
);

-- Job applications table
CREATE TABLE IF NOT EXISTS job_applications (
    id CHAR(36) PRIMARY KEY,
    job_post_id CHAR(36) NOT NULL,
    job_seeker_id CHAR(36) NOT NULL,
    cover_note TEXT,
    status ENUM('submitted','shortlisted','rejected','hired') DEFAULT 'submitted',
    contacted_via ENUM('none','call','whatsapp','email') DEFAULT 'none',
    contacted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (job_post_id) REFERENCES job_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (job_seeker_id) REFERENCES job_seekers(id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (job_post_id, job_seeker_id)
);

-- Files table (polymorphic)
CREATE TABLE IF NOT EXISTS files (
    id CHAR(36) PRIMARY KEY,
    owner_type VARCHAR(100) NOT NULL,
    owner_id CHAR(36) NOT NULL,
    kind ENUM('id_card','passport','license','brochure','portfolio','product_photo','property_photo','resume','other') DEFAULT 'other',
    disk VARCHAR(50) DEFAULT 'public',
    path VARCHAR(500) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime VARCHAR(100) NOT NULL,
    size_bytes BIGINT UNSIGNED NOT NULL,
    meta_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_owner (owner_type, owner_id)
);

-- Product photos
CREATE TABLE IF NOT EXISTS product_photos (
    id CHAR(36) PRIMARY KEY,
    product_id CHAR(36) NOT NULL,
    file_id CHAR(36) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (file_id) REFERENCES files(id) ON DELETE CASCADE
);

-- Marketplace photos
CREATE TABLE IF NOT EXISTS marketplace_photos (
    id CHAR(36) PRIMARY KEY,
    marketplace_item_id CHAR(36) NOT NULL,
    file_id CHAR(36) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (marketplace_item_id) REFERENCES marketplace_items(id) ON DELETE CASCADE,
    FOREIGN KEY (file_id) REFERENCES files(id) ON DELETE CASCADE
);

-- Property photos
CREATE TABLE IF NOT EXISTS property_photos (
    id CHAR(36) PRIMARY KEY,
    property_id CHAR(36) NOT NULL,
    file_id CHAR(36) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (file_id) REFERENCES files(id) ON DELETE CASCADE
);

-- Saved items table (universal cart & wishlist)
CREATE TABLE IF NOT EXISTS saved_items (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    item_type ENUM('product','marketplace_item','property','job_post') NOT NULL,
    item_id CHAR(36) NOT NULL,
    quantity INT UNSIGNED,
    note VARCHAR(255),
    is_cart BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_saved_item (user_id, item_type, item_id, is_cart)
);

-- Verification requests
CREATE TABLE IF NOT EXISTS verification_requests (
    id CHAR(36) PRIMARY KEY,
    owner_type VARCHAR(100) NOT NULL,
    owner_id CHAR(36) NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    review_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_owner_verification (owner_type, owner_id)
);

-- Reviews table
CREATE TABLE IF NOT EXISTS reviews (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    reviewable_type VARCHAR(100) NOT NULL,
    reviewable_id CHAR(36) NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_reviewable (reviewable_type, reviewable_id)
);

-- Insert default service types
INSERT IGNORE INTO service_types (id, name) VALUES 
(UUID(), 'Catering'),
(UUID(), 'Entertainment'),
(UUID(), 'Cleaning'),
(UUID(), 'Healthcare'),
(UUID(), 'Tutoring'),
(UUID(), 'Childcare'),
(UUID(), 'Elderly Care'),
(UUID(), 'Home Maintenance'),
(UUID(), 'Transportation'),
(UUID(), 'Security'),
(UUID(), 'Photography'),
(UUID(), 'Event Planning');

-- Insert default product categories
INSERT IGNORE INTO product_categories (id, name) VALUES 
(UUID(), 'Clothing'),
(UUID(), 'Toys'),
(UUID(), 'Electronics'),
(UUID(), 'Furniture'),
(UUID(), 'Baby Products'),
(UUID(), 'Household Items'),
(UUID(), 'Vehicles'),
(UUID(), 'Books & Education'),
(UUID(), 'Sports & Recreation'),
(UUID(), 'Health & Beauty'),
(UUID(), 'Local Crafts'),
(UUID(), 'Food & Beverages');

SET FOREIGN_KEY_CHECKS = 1;