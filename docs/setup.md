# Setup Guide

This guide provides detailed instructions for setting up the ProjectX development environment.

## Prerequisites

Ensure you have the following installed on your system:

- **PHP 8.2 or higher**: [Download PHP](https://www.php.net/downloads.php)
- **Composer**: [Download Composer](https://getcomposer.org/download/)
- **Node.js (LTS recommended) & NPM**: [Download Node.js](https://nodejs.org/en/download/)
- **MongoDB**: [Download MongoDB Community Server](https://www.mongodb.com/try/download/community)
- **Git**: [Download Git](https://git-scm.com/downloads)

## Installation Steps

### 1. Clone the Repository

```bash
git clone <repository-url>
cd projectx
```

### 2. Backend Setup

Install PHP dependencies using Composer:

```bash
composer install
```

### 3. Frontend Setup

Install JavaScript dependencies using NPM:

```bash
npm install
```

### 4. Configuration

Create the environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

Open the `.env` file and configure your MongoDB connection:

```env
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27101
DB_DATABASE=projectx
DB_USERNAME=
DB_PASSWORD=
```

### 5. Database Migrations

Run the migrations to set up your MongoDB collections:

```bash
php artisan migrate
```

### 6. Development Server

Start the Vite development server for the frontend:

```bash
npm run dev
```

In a separate terminal, start the Laravel local development server:

```bash
php artisan serve
```

The application should now be accessible at `http://localhost:8000`.

## Common Issues

### MongoDB Connection Failed
- Check if the MongoDB service is running.
- Verify the connection details in `.env`.
- Ensure the `mongodb` extension is enabled in your `php.ini`.

### Assets Not Loading
- Ensure `npm run dev` is running.
- Check the browser console for any errors related to Vite.
```
