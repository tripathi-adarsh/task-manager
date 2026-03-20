# Task Manager Pro

## Features
- Add / View / Complete / Delete Tasks
- Static mode (session-based) jab DB na ho
- DB mode (PostgreSQL/MySQL) jab DB available ho — auto-detect karta hai

## Tech Stack
- Laravel 8
- PostgreSQL (optional)
- Bootstrap 5

## Setup

### Without DB (Static Mode)
```bash
php artisan serve
```
Bas itna karo — session mein data store hoga.

### With PostgreSQL
1. `.env` mein DB credentials update karo:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=task_manager
DB_USERNAME=postgres
DB_PASSWORD=your_password
```
2. Migration run karo:
```bash
php artisan migrate
```
3. Server start karo:
```bash
php artisan serve
```

## Author
Your Name
