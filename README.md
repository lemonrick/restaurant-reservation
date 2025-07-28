# 🐳 Local Development with DDEV (Docker)

This project uses [**DDEV**](https://ddev.readthedocs.io/en/stable/) for local development. It includes a **Laravel 12 backend** and a **Vue 3 + Vite + Vuetify frontend**, all inside isolated Docker containers.

---

## 📦 First-Time Setup

> ✅ Make sure [DDEV is installed](https://ddev.readthedocs.io/en/stable/#installation) before continuing.

### 1. Start the DDEV environment

```bash
ddev start
```

This will initialize and start the containers.

---

### 2. Backend (Laravel)

#### Install backend dependencies:

```bash
ddev be composer install
```

#### Copy environment files:

```bash
ddev be cp .env.ddev .env
```

```bash
ddev be cp .env.testing.example .env.testing
```

#### Generate application key:

```bash
ddev be php artisan key:generate
```

#### Run database migrations:

```bash
ddev be php artisan migrate:fresh
```

---

### 3. ➕ Add phpMyAdmin (optional)

To visually inspect your database, install the phpMyAdmin DDEV addon:

```bash
ddev phpmyadmin
```

Then open phpMyAdmin in your browser:

```
https://restaurant.ddev.site:8037
```

- **Username:** `db`
- **Password:** `db`

---

### 4. Create the `db_test` database (for testing)

If not already created, you can manually create the `db_test` database:

```bash
ddev mysql
```

Then inside the MySQL shell:

```sql
CREATE DATABASE db_test;
GRANT ALL PRIVILEGES ON db_test.* TO 'db'@'%';
FLUSH PRIVILEGES;
EXIT;
```

---

### 5. Seed the database

```bash
ddev be php artisan db:seed
```

---

### 6. Frontend (Vue 3 + Vite + Vuetify)

#### Install dependencies:

```bash
ddev fe yarn install
```

---

## 🚀 Running the Application

### Laravel backend

```bash
ddev start
```

BE will be available at:

```
https://api.restaurant.ddev.site/
```

### Vite frontend

```bash
ddev vite
```

FE will be available at:

```
https://app.restaurant.ddev.site/
```

> ⚠️ Don’t forget to stop the Vite dev server manually (Ctrl+C) when you're done.<br>
> You can also run `ddev stop` to shut down all containers when finished.

---

## 📚 API Documentation (Swagger)

The project includes interactive API documentation via Swagger. Once the server is running, you can access it at:

```
https://api.restaurant.ddev.site/api/documentation
```

This page provides a complete list of available API endpoints, request/response examples, and schema definitions.

---

## 🧪 Running Tests

### Backend tests (Pest PHP)

```bash
ddev be php artisan test
```

Uses `.env.testing` and the `db_test` database.

### Frontend tests (Vitest)

```bash
ddev fe yarn test
```

---

## ⚙️ Useful DDEV Commands

| Command             | Description                            |
|---------------------|----------------------------------------|
| `ddev start`        | Starts the project containers          |
| `ddev stop`         | Stops and removes containers           |
| `ddev be <cmd>`     | Runs a command in the backend (`be/`)  |
| `ddev fe <cmd>`     | Runs a command in the frontend (`fe/`) |
| `ddev vite`         | Starts the Vite development server     |
| `ddev mysql`        | Opens MySQL shell                      |
| `ddev launch`       | Opens the project in your browser      |
| `ddev describe`     | Displays project info and URLs         |

---

## ✅ Tips

- You can use `ddev launch` to open the project homepage.
- You can use `ddev describe` to see all URLs, container info, and credentials.
- If something fails, try `ddev restart`.
