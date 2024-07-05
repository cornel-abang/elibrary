# Online Book Library

## Overview

The Online Book Library is a Single Page Application (SPA) that allows users to manage a collection of books and authors. This project includes user authentication and authorization, and it provides a RESTful API for interaction. The frontend is built with HTML, CSS, and JavaScript, while the backend is developed using Laravel 11.

## Features

- User Registration and Login
- JWT-based Authentication
- CRUD operations for Books and Authors
- Search functionality for books
- Responsive Design

## Prerequisites

- PHP 7.4 or higher
- Composer
- Laravel 8.x
- MySQL or any other database supported by Laravel

## Installation

### Backend

### From your system's terminal, run the following commands, one after the other:

   ```sh
   git clone https://github.com/cornel-abang/elibrary.git
   
   cd online-book-library 

   composer install - Install dependencies

   cp .env.example .env - Copy the .env.example file to .env

   php artisan key:generate - Generate the appplication key

   Configure your database settings in the .env file:

      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=your_database_name
      DB_USERNAME=your_database_username
      DB_PASSWORD=your_database_password
      
   php artisan migrate - Run the database migrations

   php artisan serve - Start the Laravel development server
   ```
### API Endpoints:
   The API endpoints are documented in the [API Documentation File](api.doc.md).

### Usage:
   Register a new user or log in with an existing user.
   Use the navigation bar to access the Books and Authors sections.
   Add, update, or delete books and authors.
   Use the search functionality to find books.

