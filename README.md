<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Project Overview
<a href="https://youtu.be/ONSVYdaEJAA">Youtube: https://youtu.be/ONSVYdaEJAA</a>

## Developer

**Amit Kumar Biswas**
akbdeveloper01@gmail.com

Project Name: PROJECT, TASK, AND WORKLOG MANAGEMENT SYSTEM USING LARAVEL AND FILAMENT <br>
Submitted To: Amity Online

# Laravel Application Installation Guide

## Prerequisites

1. **PHP**: Ensure you have PHP (version 8.2 or higher) installed.
2. **Composer**: Install Composer, a dependency manager for PHP.
3. **Database**: You need a database (MySQL, SQLite, etc.) installed and running.
4. **Git**: Make sure you have Git installed to clone the repository.

## Clone the Repository

Open your terminal and run the following command to clone the repository:

```bash
git clone https://github.com/devakb/worklog-management-system.git
```

## Navigate into the cloned directory:
```bash
cd worklog-management-system
```

## Install Dependencies
Run Composer to install the necessary PHP packages:
```bash
composer install
```

## Configure the Environment
Copy the .env.example file to create your .env file:
```bash
cp .env.example .env
```

## Open the .env file in a text editor and configure your database settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```


## Generate Application Key
Generate a new application key:
```bash
php artisan key:generate
```


## Run Migrations
Run the database migrations to set up your database structure:
```bash
php artisan migrate --seed
```

## Install Frontend Assets
```bash
npm insall

npm run dev
```

## Serve the Application
You can start the Laravel development server with:
```bash
php artisan serve
```

The application will be available at http://localhost:8000
