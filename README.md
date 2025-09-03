# OneNest Botswana - CSS Structure & OneNest Dashboards

This project is a complete PHP implementation of the OneNest platform with MySQL database. It uses a modular CSS architecture and includes comprehensive dashboard systems for multiple user types.

## 🚀 Quick Setup (No Apache Config Required)

This implementation includes a root `index.php` that serves the application without needing to modify Apache's DocumentRoot:

1. **Install Dependencies:**
   ```bash
   composer install
   ```

2. **Configure Database:**
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   ```

3. **Create Database & Run Migrations:**
   ```sql
   CREATE DATABASE onenest;
   ```
   ```bash
   mysql -u root -p onenest < database/migrations/001_create_tables.sql
   ```

4. **Set Permissions:**
   ```bash
   chmod -R 755 .
   chmod -R 775 uploads/
   ```

5. **Access Your Site:**
   - Visit: `https://onenest.ihostcp.com`
   - The root `index.php` handles all routing automatically!

## 🔄 How the Routing Works

### Without Apache Config Changes:
```
Request: https://onenest.ihostcp.com/dashboard/user
↓
Root index.php: Detects this is not a static file
↓
Forwards to: public/index.php (the actual application)
↓
Router: Matches route → DashboardController::userDashboard()
↓
View: Renders dashboard HTML
```

### Static Files (CSS, JS, Images):
```
Request: https://onenest.ihostcp.com/css/base.css
↓
Root index.php: Detects static file extension
↓
Serves: public/css/base.css with proper headers
```

## 📁 Project Structure

```
onenest/
├── index.php              ← Root entry point (NEW)
├── composer.json          ← Dependencies
├── .env                   ← Configuration
├── database/migrations/   ← SQL schema
├── src/
│   ├── Core/             ← Database, Router
│   ├── Models/           ← Data models
│   └── Controllers/      ← Request handlers
├── views/                ← HTML templates
└── public/               ← Static assets + app entry
    ├── index.php         ← Application entry point
    ├── css/              ← Your existing CSS
    └── .htaccess         ← URL rewriting (backup)
```
## CSS File Structure

### `css/base.css`
Contains fundamental styles and variables:
- CSS Custom Properties (variables)
- Reset/normalize styles
- Base typography
- Utility classes (buttons, containers)
- Top bar styles and responsive design
- Global responsive breakpoints

### `css/layout.css`
Contains layout and structural styles:
- Page navigation system
- Header and navigation (with top bar positioning)
- Hero sections
- Filter bars and grids
- Authentication pages
- Dashboard layout (sidebar, content grid)
- Footer
- Layout-specific responsive design

### `css/components.css`
Contains reusable component styles:
- Card components
- Trust bar items
- Pillar cards
- Final CTA sections
- Dashboard components (cards, progress bars, forms)
- Component-specific responsive design

## OneNest Dashboard System

The project now includes 7 comprehensive dashboard HTML files, each containing full functionality for a specific user type:

### Dashboard Files

1. **`onenest-service-providers-dashboard.html`**
   - Business information registration
   - Contact details
   - Service details and pricing
   - Additional files upload
   - Complete sidebar navigation within single file

2. **`onenest-product-sellers-dashboard.html`**
   - Business information
   - Product categories and details
   - Contact information
   - Product photos upload
   - Additional files (catalogues, flyers)

3. **`onenest-property-owners-dashboard.html`**
   - Property owner/agent information
   - Property type selection
   - Detailed property information
   - Photo submission
   - Additional property details

4. **`onenest-app-users-dashboard.html`**
   - Personal information
   - Location details
   - Family profile
   - Interests and preferences
   - Verification documents

5. **`onenest-job-seekers-dashboard.html`**
   - Job preferences (part-time/full-time, stay-in/out)
   - Availability and salary expectations
   - Location information
   - Portfolio photos (for maintenance freelancers)

6. **`onenest-recruiters-dashboard.html`**
   - Company information
   - Job posting forms
   - Candidate management
   - Subscription management

7. **`onenest-freelancers-dashboard.html`**
   - Service information and experience
   - Availability preferences
   - Portfolio and work samples
   - Pricing and packages
   - Subscription management

### Dashboard Features

Each dashboard includes:
- **Single-File Architecture**: All sections and functionality contained in one HTML file
- **Responsive Design**: Mobile-friendly with collapsible sidebar
- **Sidebar Navigation**: Anchor-based navigation to sections within the same file
- **Consistent UI**: Uses existing CSS framework for uniformity
- **Form Validation**: HTML5 validation with proper accessibility
- **Interactive Elements**: JavaScript for smooth scrolling and dynamic form fields

## Benefits of This Structure

1. **Maintainability**: Each file has a specific purpose, making it easier to find and modify styles
2. **Reusability**: Components are separated and can be easily reused across pages
3. **Performance**: CSS can be cached separately and loaded in parallel
4. **Collaboration**: Multiple developers can work on different CSS files without conflicts
5. **Scalability**: New components can be added without affecting existing styles
6. **User-Specific Experience**: Each dashboard is tailored to its user type's needs

## Usage

The CSS files are loaded in the following order in all HTML files:
1. `base.css` - Foundation styles
2. `layout.css` - Layout and structure
3. `components.css` - Reusable components

This order ensures that base styles are established first, followed by layout, and finally component-specific styles that may override or extend the base styles.

## Responsive Design

Each CSS file contains its own responsive design rules to keep related styles together. The breakpoints used are:
- `@media (max-width: 992px)` - Tablet and smaller
- `@media (max-width: 576px)` - Mobile devices

### Mobile Responsiveness Features

The website and dashboards are fully mobile responsive with the following features:
- **Top Bar**: Fixed sticky bar with contact info and social links
- **Header**: Sticky header with backdrop blur and smooth transitions
- **Navigation**: Mobile menu toggle with slide-down animation
- **Dashboard Sidebar**: Collapsible navigation on mobile
- **Grid Layouts**: Responsive grid systems that adapt to screen size
- **Typography**: Scalable font sizes for different screen sizes
- **Touch-Friendly**: Appropriate button and link sizes for mobile interaction

### Sticky Header Features

The website includes an advanced sticky header implementation:
- **Dual Sticky Elements**: Both top bar and main header are fixed
- **Smooth Transitions**: Enhanced scroll behavior with shadow effects
- **Mobile Menu**: Slide-down mobile navigation with proper positioning
- **Scroll Behavior**: Optional header hide/show on scroll
- **Backdrop Blur**: Modern glass-morphism effect with browser compatibility
- **Responsive Positioning**: Proper z-index layering and positioning across all devices 