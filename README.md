<div align="center">
  <h1>🚀 Tarn Framework</h1>
  <p><i>A lightweight, elegant, and blazing fast MVC mini-framework for PHP 8.2+.</i></p>
</div>

---

Tarn is a modern PHP mini-framework designed to be incredibly lightweight while still packing industry-standard features. Built on top of robust components like **Laravel's Eloquent ORM**, **Blade Templating**, and **Vite**, Tarn provides everything you need to build web applications without the bloat of a full-stack framework.

## ✨ Features

- **⚡️ FastRoute Engine:** Incredibly fast and extensible expressive routing.
- **🎨 Blade Templating:** The beautiful and powerful Laravel visual engine.
- **🗄️ Eloquent ORM:** Advanced database abstraction, models, and schema builder.
- **📦 Vite, SCSS & JS:** Lightning-fast frontend tooling with Vite pre-configured for SCSS and ES6 modules.
- **🛡️ Secure Sessions & Flash Data:** Built-in session manager with flash messages and `old()` form repopulation.
- **📝 Automatic Logging:** Intelligent error reporting backed by Monolog.
- **🚦 Smart Error Handling:** Detailed stack traces in development, friendly 404/500 views in production.
- **🏗️ Base Controllers & Models:** Easy-to-extend foundational classes for rapid development.

---

## 📦 Getting Started

### 1. Requirements
* PHP 8.2 or higher
* Composer
* Node.js & NPM

### 2. Installation
Clone the repository and install the dependencies:
```bash
git clone https://github.com/adeelwebify/tarn.git
cd tarn
composer install
npm install
```

### 3. Configuration
Copy the `.env.example` file to create your own environment configuration:
```bash
cp .env.example .env
```
_Note: Out of the box, Tarn defaults to using a local SQLite database located at `storage/database.sqlite`._

### 4. Compiling Assets (Vite & SCSS)
Tarn uses Vite to compile your frontend assets located in `resources/scss` and `resources/js`. 

To run the Vite development server (with hot-module reloading):
```bash
npm run dev
```
To compile assets for production:
```bash
npm run build
```

_Note: If you are using Blade views, Vite currently drops the compiled outputs into `public/build/`. You can reference them in your views like so:_
```html
<link rel="stylesheet" href="/build/style.css">
<script type="module" src="/build/app.js"></script>
```

### 5. Run the Application Server
In a separate terminal tab, boot up the PHP development server:
```bash
php -S localhost:8000 -t public
```
Visit `http://localhost:8000` in your browser!

---

## 🛠️ Usage Guide

### Routing (`routes/web.php`)
Routes are registered using a clean, expressive syntax inside `routes/web.php`.
```php
use Tarn\Controllers\HomeController;
use Tarn\Core\Request;

return function ($router) {
    // Basic closure
    $router->get('/hello', function(Request $request) {
        return 'Hello World';
    });

    // Controller method
    $router->get('/', [HomeController::class, 'index']);
    $router->post('/submit', [HomeController::class, 'store']);
};
```

### Controllers (`app/Controllers`)
Controllers extend the Base `Controller` class, giving you instant access to powerful return helpers:
```php
namespace Tarn\Controllers;

use Tarn\Core\Controller;
use Tarn\Core\Request;
use Tarn\Core\Response;

class HomeController extends Controller {

    public function index(Request $request): Response {
        // Render a Blade View
        return $this->view('home', ['title' => 'Tarn App']);
    }

    public function api(): Response {
        // Return JSON easily
        return $this->json(['status' => 'success']);
    }
}
```

### Database & Models (`app/Models`)
Tarn ships with Laravel's **Eloquent ORM** pre-configured.

**Creating a Model:**
```php
namespace Tarn\Models;

use Tarn\Core\Model;

class Article extends Model {
    protected $table = 'articles';
    protected $fillable = ['title', 'content'];
}
```

**Using the Model:**
```php
// Fetch all
$articles = Article::all();

// Create new
Article::create(['title' => 'Hello', 'content' => 'World!']);
```

### Handling Form Submissions & Flash Errors
The Base Controller makes handling form redirects seamless:
```php
public function store(Request $request): Response {
    $email = $request->post('email');

    if (empty($email)) {
        // Flashes the error, saves form input, and redirects back
        return $this->backWithFlash($request, '/contact', 'error', 'Email is required!');
    }

    return $this->redirect('/success');
}
```

---

## 📁 Directory Structure
```text
tarn/
├── app/
│   ├── Controllers/   # Your application controllers
│   ├── Core/          # The Tarn mini-framework engine
│   └── Models/        # Eloquent data models
├── public/            # Document root (index.php)
├── resources/
│   ├── js/            # Source Javascript (Vite)
│   ├── scss/          # Source SCSS (Vite)
│   └── views/         # Blade templates
├── routes/
│   └── web.php        # Application routing
├── storage/           
│   ├── cache/         # Route & View cache
│   └── logs/          # Application logs (Monolog)
├── vite.config.js     # Frontend build configuration
├── .env               # Environment configuration
└── composer.json
```

---

## 📜 License
The Tarn framework is open-sourced software by [@adeelwebify](https://github.com/adeelwebify), licensed under the MIT license.
