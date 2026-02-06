# Customer Self-Service Portal (Laravel 12 + Sail + Inertia React)

A simplified customer self-service portal built with **Laravel 12**, **MySQL**, **REST API**, and **Inertia (React) + react-bootstrap**.

The application exposes:
- **REST API** under `/api/*` (protected by Sanctum personal access tokens)
- **Portal UI** served by Laravel using **Inertia React**

Auth approach:
- Portal login uses **Sanctum personal access tokens** stored in an **HttpOnly cookie** (`portal_token`)
- API requests from the portal reuse the same `/api/*` endpoints via a cookie → Bearer middleware bridge
- No tokens stored in localStorage

---

## Requirements

- Docker Desktop
- Node.js 18+
- Git

---

## Tech Stack

- Backend: Laravel 12
- Frontend: Inertia + React + react-bootstrap
- Database: MySQL (Laravel Sail)
- Auth: Laravel Sanctum (Personal Access Tokens)
- Testing: PHPUnit

---

## Project Setup (Laravel Sail)

### 1. Clone the repository
```bash
git clone https://github.com/sidibos/el-customer-portal
cd el-customer-portal
```

### 2. Environment setup
```bash
cp .env.example .env
```

### 3. Install PHP dependencies
```bash
composer install
```

Or via Sail:
```bash
./vendor/bin/sail composer install
```

### 4. Start Sail containers
```bash
./vendor/bin/sail up -d
```

### 5. Generate application key
```bash
./vendor/bin/sail artisan key:generate
```

### 6. Run migrations and seeders
```bash
./vendor/bin/sail artisan migrate --seed
```

To reset completely:
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

---

## Frontend Setup

### 7. Install Node dependencies
```bash
./vendor/bin/sail npm install
```

### 8. Start Vite dev server
```bash
./vendor/bin/sail npm run dev
```

---

## Accessing the App

Always access the portal via Laravel:

- Login: http://localhost/login
- Dashboard: http://localhost/dashboard

Do NOT open the Vite dev server URL directly.

---

## Seeded Test Users

- Primary User  
  Email: primary@example.com  
  Password: password  

- Authorised User  
  Email: authorized@example.com  
  Password: password  

---

## Authentication Flow

- Login via `/portal/login`
- Sanctum personal access token generated
- Token stored in HttpOnly cookie `portal_token`
- API requests authenticated via middleware injecting Bearer token

---

## Core API Endpoints

- GET /api/user
- GET /api/dashboard
- GET /api/sites
- GET /api/sites/{site}/meters
- GET /api/meters/{meter}
- GET /api/meters/{meter}/consumption?months=6
- GET /api/sites/{site}/consumption?months=6
- GET /api/billing-preferences
- PUT /api/billing-preferences
- GET /api/contact-details
- PUT /api/contact-details

---

## Running Tests

```bash
./vendor/bin/sail artisan test
```

Run a specific test:
```bash
./vendor/bin/sail artisan test --filter=ConsumptionEndpointsFeatureTest
```

---

## Notes

- All API calls from React are made explicitly to `/api/*`
- Inertia pages live in `resources/js/Pages`
- Shared layout: `resources/js/Components/AppLayout.jsx`
- Charts use Bootstrap-only rendering

---

