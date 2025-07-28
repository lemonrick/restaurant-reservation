<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

This project uses **Laravel 12**. Below are the steps to run the project locally.

---

## 💻 Requirements

- PHP 8.2+
- Composer
- MySQL

---

## ⚙️ Installation

```bash
composer install
```

```bash
cp .env.example .env
```

```bash
cp .env.testing.example .env.testing
```

```bash
php artisan key:generate
```

```bash
php artisan migrate:fresh
```

```bash
php artisan db:seed
```

```bash
php artisan serve
```

The application will be available at [http://127.0.0.1:8000/](http://127.0.0.1:8000/)

---

## 📚 API Documentation (Swagger)

The project includes interactive API documentation via Swagger. Once the server is running, you can access it at:

```
http://127.0.0.1:8000/api/documentation
```

This page provides a complete list of available API endpoints, request/response examples, and schema definitions.

---

## 🧪 Testing with Pest PHP

```bash
php artisan test
```
