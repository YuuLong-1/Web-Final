# User Management System (Laravel 13)
> **Final Exam Project** &bull; Comprehensive User Management System built with PHP 8.5 & Laravel Framework 13

---

## 📋 Project Overview & Exam Requirements Checklist

This project was built to satisfy the **Final Exam Requirements for Laravel 13**:

- [x] **Requirement 1: Authentication System (Login & Register Form)**
  - Clean and responsive authentication UI with modern cards and gradient styling.
  - Registration form with validation, department/role assignment, and profile creation.
  - Login form with remember-me functionality, session regeneration, and demo auto-fill.
  - Secure logout mechanism with session invalidation and CSRF regeneration.
  - Route middleware protection (`guest` for auth routes, `auth` for user management).

- [x] **Requirement 2: Database Migrations & Eloquent Relationships (Laravel 13)**
  - **Departments Table** (`departments`): Department entity with `code`, `name`, `description`.
  - **Roles Table** (`roles`): Role entity with `name`, `slug`, `description`.
  - **Users Table** (`users`): Foreign key relationships to `department_id` and `role_id`, plus account `status`.
  - **User Profiles Table** (`user_profiles`): One-to-One relationship storing `phone`, `city`, `address`, `bio`, and `birth_date` with cascade deletion.
  - **Connected Models**:
    - `User`: `belongsTo(Department)`, `belongsTo(Role)`, `hasOne(UserProfile)`.
    - `Department`: `hasMany(User)`.
    - `Role`: `hasMany(User)`.
    - `UserProfile`: `belongsTo(User)`.

- [x] **Requirement 3: Complete CRUD System with Filter & Search Name**
  - **Search**: Real-time search query matching User **Name** and **Email**.
  - **Filter**: Multi-parameter filtering by:
    - **Department** (IT, HR, Finance, Marketing, Operations)
    - **Role** (Administrator, Manager, Team Lead, Staff, Member)
    - **Status** (Active, Inactive, Suspended)
  - **Sorting & Pagination**: Dynamic sorting by name, email, or date; pagination preserving active filter queries.
  - **Create**: Add new user with credentials, department/role assignment, and profile details.
  - **Read (Show)**: Comprehensive detail view displaying relationships, profile metadata, and activity timestamps.
  - **Update (Edit)**: Edit existing user data with unique email validation ignoring current user, optional password updating, and profile syncing.
  - **Delete**: Soft/hard deletion with interactive confirmation modal and prevention of self-deletion for the authenticated user.
  - **Dashboard Metrics**: Real-time statistics counters for Total Users, Active Users, Inactive Users, Suspended Users, and Total Departments.

- [x] **Requirement 4: GitHub Repository & Version Control**
  - Git repository initialized with clean commit history, `.gitignore`, and step-by-step push guidelines.

---

## 🛠️ Technology Stack

- **Framework**: Laravel 13.x
- **PHP Version**: PHP 8.5.x
- **Database**: SQLite (or MySQL / PostgreSQL)
- **Frontend / Styling**: Tailwind CSS, Alpine.js, Font Awesome 6 Icons, Google Inter Font
- **Testing**: PHPUnit 12 (25 Automated Tests, 74 Assertions)

---

## 🚀 Getting Started

### 1. Prerequisites
- PHP >= 8.2 (PHP 8.5 tested)
- Composer >= 2.x
- Git

### 2. Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd myproject
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Migrations & Seed Database:**
   ```bash
   php artisan migrate --seed
   ```

5. **Start Local Development Server:**
   ```bash
   php artisan serve
   ```
   Access the application at: `http://localhost:8000`

---

## 🔑 Default Credentials (Seeded)

The database seeder pre-populates several realistic user accounts across different departments and roles. You can log in using:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@example.com` | `password` |
| **Project Manager** | `michael.chen@example.com` | `password` |
| **Team Lead** | `alex.johnson@example.com` | `password` |
| **Staff** | `david.kim@example.com` | `password` |

*(Tip: The login page also features an "Auto-fill Admin" button for immediate one-click testing.)*

---

## 🧪 Automated Testing

Run the full suite of unit and feature tests:

```bash
php artisan test
```

Test coverage includes:
- Authentication flows (login, register, logout, invalid credentials).
- User CRUD operations (create, read, update, delete, prevent self-deletion).
- Search by Name and filtering by Department, Role, and Status.
- Eloquent model relationships (One-to-Many & One-to-One).

---

## 📂 Project Structure Highlights

```
app/
├── Http/
│   └── Controllers/
│       ├── AuthController.php      # Login, Register, Logout
│       └── UserController.php      # CRUD, Search, Filter, Stats
├── Models/
│   ├── Department.php              # Department model (hasMany Users)
│   ├── Role.php                    # Role model (hasMany Users)
│   ├── User.php                    # User model (belongsTo Dept/Role, hasOne Profile)
│   └── UserProfile.php             # UserProfile model (belongsTo User)
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2026_11_05_161158_create_departments_table.php
│   ├── 2026_11_05_161203_create_roles_table.php
│   ├── 2026_11_05_161204_add_relationships_and_status_to_users_table.php
│   └── 2026_11_05_161205_create_user_profiles_table.php
└── seeders/
    └── DatabaseSeeder.php          # Seeds Departments, Roles, Admin & Sample Users
resources/views/
├── layouts/
│   ├── app.blade.php               # Main authenticated layout (Tailwind + Alpine.js)
│   └── auth.blade.php              # Auth layout (Login & Register)
├── auth/
│   ├── login.blade.php             # Login form
│   └── register.blade.php          # Register form
└── users/
    ├── index.blade.php             # User listing, search, filters & stats
    ├── create.blade.php            # Add user form
    ├── edit.blade.php              # Edit user form
    └── show.blade.php              # User profile & relationship view
tests/
├── Feature/
│   ├── AuthTest.php                # Authentication test cases
│   └── UserCrudTest.php            # CRUD, search & filter test cases
└── Unit/
    └── RelationshipTest.php        # Eloquent relationship test cases
```
