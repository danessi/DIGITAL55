# 🎓 Course Management Platform API

Professional Laravel-based Course Management System implementing SOLID principles, Repository Pattern, and Service Layer architecture.

## 📋 Table of Contents

- [Features](#features)
- [Architecture](#architecture)
- [Requirements](#requirements)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Running Tests](#running-tests)
- [API Documentation](#api-documentation)
- [Project Structure](#project-structure)
- [Design Patterns](#design-patterns)

---

## ✨ Features

- **Course Management**: Full CRUD operations for courses
- **Instructor Management**: Optimized instructor listing (handles millions of records)
- **Rating System**: Automated average rating calculation service
- **User Reviews**: Comments and ratings for courses
- **Favorite Courses**: Users can mark courses as favorites
- **Multi-level Courses**: Beginner, Intermediate, Advanced
- **Lesson Management**: Video-based lessons per course
- **Soft Deletes**: Safe data deletion with recovery options
- **API-first Design**: RESTful JSON API
- **Validation**: Robust request validation
- **Optimized Queries**: Efficient database operations with caching
- **Comprehensive Tests**: Unit and Feature tests included

---

## 🏗️ Architecture

This project follows **Clean Architecture** principles adapted for Laravel:

### Layers
```
┌─────────────────────────────────────┐
│         HTTP Layer (Controllers)     │  ← Thin controllers
├─────────────────────────────────────┤
│         Service Layer                │  ← Business logic orchestration
├─────────────────────────────────────┤
│         Repository Layer             │  ← Data persistence abstraction
├─────────────────────────────────────┤
│         Entity Layer                 │  ← Business rules & validation
├─────────────────────────────────────┤
│         Model Layer (Eloquent)       │  ← Database structure only
└─────────────────────────────────────┘
```

### SOLID Principles Applied

- **Single Responsibility Principle (SRP)**: Each class has one reason to change
- **Open/Closed Principle**: Extensible through interfaces
- **Liskov Substitution Principle**: Repository implementations are interchangeable
- **Interface Segregation Principle**: Specific interfaces for specific needs
- **Dependency Inversion Principle**: Depend on abstractions, not concretions

---

## 📦 Requirements

- PHP 8.2+
- Laravel 11.31
- MySQL 8.0+ or MariaDB 10.3+
- Composer 2.0+
- Redis (optional, for caching)

---

## 🚀 Installation

### 1. Clone the repository
```bash
git clone <repository-url>
cd course-management-api
```

### 2. Install dependencies
```bash
composer install
```

### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database

Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=course_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Configure cache (optional but recommended)
```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## 🗄️ Database Setup

### Run migrations
```bash
php artisan migrate
```

### Seed database with test data
```bash
php artisan db:seed
```

This will create:
- 50,000 instructors
- 100,000 users
- 200,000 courses
- ~2,000,000 lessons (10 per course average)
- 500,000 reviews
- 300,000 favorite course relations

**⚠️ Note**: Seeding will take 10-30 minutes depending on your hardware.

### Quick seed (for testing)

If you want to test quickly with less data:
```bash
php artisan db:seed --class=InstructorSeeder
php artisan db:seed --class=UserSeeder
```

Then manually adjust the counts in seeders.

---

## 🧪 Running Tests

### Run all tests
```bash
php artisan test
```

### Run specific test suites
```bash
# Unit tests only
php artisan test --testsuite=Unit

# Feature tests only
php artisan test --testsuite=Feature
```

### Run with coverage (requires Xdebug)
```bash
php artisan test --coverage
```

### Test specific files
```bash
php artisan test tests/Unit/Entities/CourseEntityTest.php
php artisan test tests/Feature/Api/CourseApiTest.php
```

---

## 📖 API Documentation

### Base URL
```
http://localhost/api/v1
```

### Endpoints

#### Courses

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/courses` | List all courses (paginated) |
| POST | `/courses` | Create a new course |
| GET | `/courses/{id}` | Get course details with rating |
| PUT | `/courses/{id}` | Update a course |
| DELETE | `/courses/{id}` | Delete a course (soft delete) |
| GET | `/courses/published` | List published courses only |
| GET | `/courses/{id}/rating` | Get course rating details |

#### Instructors

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/instructors` | List all instructors (optimized, cached) |
| GET | `/instructors/{id}` | Get instructor details |

### Example Requests

#### Create Course
```bash
curl -X POST http://localhost/api/v1/courses \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "instructor_id": 1,
    "title": "Advanced Laravel Development",
    "description": "Master advanced Laravel techniques including repositories, services, and SOLID principles",
    "price": 149.99,
    "level": "advanced",
    "duration_hours": 60,
    "is_published": true
  }'
```

#### Get Course with Rating
```bash
curl -X GET http://localhost/api/v1/courses/1 \
  -H "Accept: application/json"
```

#### List Instructors (Optimized)
```bash
curl -X GET http://localhost/api/v1/instructors \
  -H "Accept: application/json"
```

### Validation Rules

#### Course Creation

- `instructor_id`: required, must exist in instructors table
- `title`: required, 3-255 characters
- `description`: required, minimum 10 characters
- `price`: required, 0.00-9999.99
- `level`: required, must be: `beginner`, `intermediate`, or `advanced`
- `duration_hours`: required, 0-500
- `is_published`: optional, boolean

---

## 📁 Project Structure
```
app/
├── Entities/                          # Business entities with validation logic
│   └── CourseEntity.php
├── Repositories/
│   ├── Contracts/                     # Repository interfaces
│   │   ├── CourseRepositoryInterface.php
│   │   ├── InstructorRepositoryInterface.php
│   │   └── ReviewRepositoryInterface.php
│   └── Eloquent/                      # Eloquent implementations
│       ├── EloquentCourseRepository.php
│       ├── EloquentInstructorRepository.php
│       └── EloquentReviewRepository.php
├── Services/                          # Business logic services
│   ├── CourseManagementService.php
│   ├── CourseRatingService.php
│   └── InstructorService.php
├── Models/                            # Eloquent models (DB structure only)
│   ├── Course.php
│   ├── Instructor.php
│   ├── Lesson.php
│   ├── Review.php
│   └── User.php
├── Http/
│   ├── Controllers/Api/
│   │   ├── CourseController.php
│   │   └── InstructorController.php
│   └── Requests/
│       ├── StoreCourseRequest.php
│       └── UpdateCourseRequest.php
└── Providers/
    ├── AppServiceProvider.php
    └── RepositoryServiceProvider.php  # DI bindings

database/
├── factories/                         # Model factories
├── migrations/                        # Database migrations
└── seeders/                          # Database seeders

tests/
├── Unit/
│   ├── Entities/
│   │   └── CourseEntityTest.php
│   └── Services/
│       ├── CourseManagementServiceTest.php
│       └── CourseRatingServiceTest.php
└── Feature/
    └── Api/
        ├── CourseApiTest.php
        └── InstructorApiTest.php
```

---

## 🎨 Design Patterns

### 1. Repository Pattern

Abstracts data access logic from business logic.
```php
interface CourseRepositoryInterface
{
    public function findById(int $id): ?CourseEntity;
    public function create(CourseEntity $course): CourseEntity;
}
```

### 2. Service Layer Pattern

Orchestrates business logic and coordinates between repositories.
```php
class CourseManagementService
{
    public function __construct(
        CourseRepositoryInterface $courseRepository,
        InstructorRepositoryInterface $instructorRepository
    ) {}
}
```

### 3. Dependency Injection

All dependencies are injected through constructors, configured in `RepositoryServiceProvider`.

### 4. Entity Pattern

Business entities contain validation logic and business rules.
```php
class CourseEntity
{
    public function setPrice(float $price): void
    {
        if ($price < 0) {
            throw new InvalidArgumentException('Price cannot be negative');
        }
        $this->price = round($price, 2);
    }
}
```

---

## 🔄 Switching Data Sources

Want to switch from MySQL to MongoDB? Just modify the binding:

**File:** `app/Providers/RepositoryServiceProvider.php`
```php
public function register(): void
{
    // MySQL (default)
    $this->app->bind(
        CourseRepositoryInterface::class,
        EloquentCourseRepository::class
    );
    
    // To switch to MongoDB, change to:
    // $this->app->bind(
    //     CourseRepositoryInterface::class,
    //     MongoCourseRepository::class
    // );
}
```

No changes needed in Controllers, Services, or Tests!

---

## 🎯 Key Features Explained

### Optimized Instructor Listing

The system handles millions of instructors efficiently:
```php
public function getAllInstructorsOptimized(): Collection
{
    return Cache::remember('all_instructors_optimized', 3600, function () {
        return Instructor::select('id', 'name', 'email', 'specialization')
            ->orderBy('name')
            ->chunk(1000, function ($instructors) {
                return $instructors;
            });
    });
}
```

- Uses selective columns
- Implements chunking
- Caches results for 1 hour
- Returns only necessary data

### Rating Calculation Service

Calculates course ratings in real-time:
```php
public function calculateAverageRating(int $courseId): array
{
    $average = $this->reviewRepository->getAverageRatingByCourse($courseId);
    $total = $this->reviewRepository->getTotalReviewsByCourse($courseId);
    
    return [
        'average_rating' => $average,
        'total_reviews' => $total,
        'rating_display' => $this->formatRatingDisplay($average, $total),
    ];
}
```

---

## 🔧 Troubleshooting

### Database connection issues
```bash
# Test database connection
php artisan db:show

# Clear config cache
php artisan config:clear
```

### Seeder taking too long

Reduce the counts in seeders or use database transactions:
```php
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
// your seeding logic
DB::statement('SET FOREIGN_KEY_CHECKS=1;');
```

### Cache issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 📝 License

This project is open-sourced software licensed under the MIT license.

---

## 👨‍💻 Development

Built with ❤️ using Laravel 11.31 and SOLID principles.

For questions or issues, please open an issue in the repository.