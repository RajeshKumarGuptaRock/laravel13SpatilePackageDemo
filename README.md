# Laravel 13 Role & Permission Management Demo

A complete Role & Permission Management system built with Laravel 12 and Spatie Laravel Permission package.

---

# Features

- Role-based access control (RBAC)
- Permission-based middleware authorization
- Admin, Editor, Viewer roles
- Article CRUD management
- Authentication system
- TailwindCSS UI
- Pest testing support
- Laravel 12 middleware attributes support

---

# Tech Stack

- PHP 8.2+
- Laravel 13
- MySQL
- Spatie Laravel Permission
- TailwindCSS
- Pest PHP

---

# Step 1: Create a New Project

```bash
composer create-project laravel/laravel --prefer-dist permission-demo

cd permission-demo
```

---

# Configure Database

Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

Run initial migrations:

```bash
php artisan migrate
```

---

# Step 2: Install Spatie Laravel Permission

Install package via composer:

```bash
composer require spatie/laravel-permission
```

Publish migrations and configuration:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Run migrations:

```bash
php artisan migrate
```

---

# Add HasRoles Trait to User Model

File: `app/Models/User.php`

```php
<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

---

# Register Middleware Aliases

File: `bootstrap/app.php`

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

Now you can use:

```php
#[Middleware('role:admin')]
#[Middleware('permission:edit articles')]
#[Middleware('role_or_permission:admin|edit articles')]
```

---

# Step 3: Create the Article Model

Create model and migration:

```bash
php artisan make:model Article -m
```

Update migration:

```php
public function up(): void
{
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->enum('status', ['draft', 'published'])->default('draft');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}
```

Run migration:

```bash
php artisan migrate
```

---

# Configure Article Model

File: `app/Models/Article.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'content', 'status', 'user_id'])]
class Article extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

---

# Add Relationship in User Model

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

public function articles(): HasMany
{
    return $this->hasMany(Article::class);
}
```

---

# Create Article Factory

```bash
php artisan make:factory ArticleFactory --model=Article
```

File: `database/factories/ArticleFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'user_id' => User::factory(),
        ];
    }
}
```

---

# Step 4: Seed Roles, Permissions, and Users

Create seeder:

```bash
php artisan make:seeder RolePermissionSeeder
```

File: `database/seeders/RolePermissionSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [
            'view articles',
            'create articles',
            'edit articles',
            'delete articles',
            'publish articles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'editor'])
            ->givePermissionTo([
                'view articles',
                'create articles',
                'edit articles',
                'publish articles',
            ]);

        Role::create(['name' => 'viewer'])
            ->givePermissionTo(['view articles']);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('editor');

        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('viewer');
    }
}
```

Run Seeder:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

---

# Step 5: Create Controller

```bash
php artisan make:controller ArticleController --resource
```

Example middleware usage:

```php
#[Middleware('auth')]
#[Middleware('permission:view articles')]
class ArticleController extends Controller
{
    //
}
```

---

# Step 6: Create Views

Create views directory:

```bash
mkdir -p resources/views/articles
```

Create these files:

```text
resources/views/articles/index.blade.php
resources/views/articles/create.blade.php
resources/views/articles/show.blade.php
resources/views/articles/edit.blade.php
```

Features included:

- TailwindCSS UI
- Pagination
- Permission-based actions
- Validation handling
- CRUD functionality

---

# Step 7: Authentication Setup

Create Login Controller:

```bash
php artisan make:controller Auth/LoginController
```

Create login view:

```text
resources/views/auth/login.blade.php
```

---

# Step 8: Register Routes

File: `routes/web.php`

```php
<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('articles.index');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::resource('articles', ArticleController::class);
```

---

# Step 9: Install Pest

Remove PHPUnit:

```bash
composer remove phpunit/phpunit
```

Install Pest:

```bash
composer require pestphp/pest --dev --with-all-dependencies
```

Initialize Pest:

```bash
./vendor/bin/pest --init
```

Run Pest:

```bash
./vendor/bin/pest
```

---

# Step 10: Create Feature Tests

Create test file:

```bash
php artisan make:test ArticlePermissionTest --pest
```

File:

```text
tests/Feature/ArticlePermissionTest.php
```

Tests include:

- Authentication testing
- Role testing
- Permission testing
- CRUD authorization testing

---

# Step 11: Run Tests

```bash
php artisan test --filter=ArticlePermissionTest
```

---

# Default Users

| Role | Email | Password |
|------|--------|----------|
| Admin | admin@example.com | password |
| Editor | editor@example.com | password |
| Viewer | viewer@example.com | password |

---

# Permissions

| Permission | Description |
|------------|-------------|
| view articles | View articles |
| create articles | Create articles |
| edit articles | Edit articles |
| delete articles | Delete articles |
| publish articles | Publish articles |

---

# Roles Overview

| Role | Access |
|------|--------|
| Admin | Full Access |
| Editor | View, Create, Edit, Publish |
| Viewer | View Only |

---

# Run the Application

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

---

# Useful Commands

```bash
php artisan migrate:fresh --seed

php artisan optimize:clear

php artisan route:list

php artisan test
```

---

# License

This project is open-source and available under the MIT License.
