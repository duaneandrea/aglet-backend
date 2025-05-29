# Laravel Movie Application

A Laravel-based movie application that imports popular movies and provides user functionality. Built with Laravel Livewire for the frontend interface.

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL database
- Docker (for Docker installation method)
- Apache or Nginx web server (for manual installation)

## Installation Methods

### Method 1: Manual Installation (Apache/Nginx)

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure your database** in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seed demo data**
   ```bash
   php artisan migrate --seed
   ```

6. **Import popular movies**
   ```bash
   php artisan movies:simple-import --pages=5
   ```

7. **Set up your web server** to point to the `public` directory

### Method 2: Docker Installation (Recommended)

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. **Run the Docker setup script**
   ```bash
   chmod +x docker-setup.sh
   ./docker-setup.sh
   ```

The Docker setup will automatically handle all dependencies, database setup, migrations, seeding, and movie imports.

## Demo User

After running the setup, you can log in with the demo user credentials (check the documentation for specific credentials).

## Features

- Movie import from external API
- User authentication and management
- Movie browsing and search functionality
- Built with Laravel Livewire for reactive UI components

## Commands

- **Import movies**: `php artisan movies:simple-import --pages=5`
- **Run migrations with seeding**: `php artisan migrate --seed`

## Tech Stack

- **Backend**: Laravel
- **Frontend**: Laravel Livewire
- **Database**: MySQL/PostgreSQL
- **Containerization**: Docker

## Notes

This application was built primarily focusing on backend functionality. The UI uses Laravel Livewire for simplicity and functionality rather than complex frontend frameworks.

## Support

If you encounter any issues during setup, please check:
- PHP version compatibility
- Database connection settings
- File permissions
- Docker installation (for Docker method)