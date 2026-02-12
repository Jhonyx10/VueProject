# ProjectX - Healthcare Management System

ProjectX is a modern healthcare management platform built with Laravel 11, Vue 3, and MongoDB. It facilitates seamless interaction between patients (users), doctors, and administrators.

## Features

- **User Roles**: Admin, Doctor, and Patient/User.
- **Appointments**: Schedule and manage medical appointments.
- **Diagnosis**: Doctors can create and manage patient diagnoses.
- **Chat**: Real-time communication between users.
- **Doctor Profiles**: View and manage doctor information.
- **Admin Dashboard**: Comprehensive management of doctors and system data.

## Tech Stack

- **Backend**: [Laravel 11](https://laravel.com)
- **Frontend**: [Vue 3](https://vuejs.org) with [Inertia.js](https://inertiajs.com)
- **Database**: [MongoDB](https://www.mongodb.com) (via `jenssegers/mongodb`)
- **Styling**: [Tailwind CSS](https://tailwindcss.com)
- **Real-time**: [Laravel Reverb](https://laravel.com/docs/11.x/reverb)

## Setup Instructions

### Prerequisites

- PHP 8.2+
- Node.js & NPM
- MongoDB
- Composer

### Installation

1.  **Clone the repository**:
    ```bash
    git clone <repository-url>
    cd projectx
    ```

2.  **Install dependencies**:
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Note: Configure your MongoDB connection in `.env`.*

4.  **Database Migration**:
    ```bash
    php artisan migrate
    ```

5.  **Compile Assets**:
    ```bash
    npm run dev
    ```

6.  **Run the application**:
    ```bash
    php artisan serve
    ```

## Documentation

Detailed documentation can be found in the `docs/` directory:

- [Architecture](docs/architecture.md)
- [Business Logic & Workflows](docs/logic.md)
- [Setup Guide](docs/setup.md)
- [Coding Standards](docs/standards.md)

## License

This project is licensed under the [MIT license](LICENSE).
