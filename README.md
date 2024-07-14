# Blog Backend with Laravel and Stripe Subscriptions

This is the backend for a personal blog application built with Laravel. It includes user authentication, post management, and subscription management using [Stripe](https://stripe.com) via Laravel Cashier.

## Table of Contents

- [Blog Backend with Laravel and Stripe Subscriptions](#blog-backend-with-laravel-and-stripe-subscriptions)
  - [Table of Contents](#table-of-contents)
  - [Features](#features)
  - [Requirements](#requirements)
  - [Installation](#installation)
    - [1. Clone the repository](#1-clone-the-repository)
    - [2. Install PHP dependencies](#2-install-php-dependencies)
    - [3. Generate Laravel app secret key](#3-generate-laravel-app-secret-key)
    - [Configuration](#configuration)
      - [1. Environment Variables](#1-environment-variables)
  - [Database Migrations](#database-migrations)
  - [Running the Application](#running-the-application)
  - [API Endpoints](#api-endpoints)
    - [Authentication](#authentication)
    - [Blog Posts](#blog-posts)
    - [Comments](#comments)
    - [User Profile](#user-profile)
    - [Stripe](#stripe)
    - [Miscellaneous](#miscellaneous)

## Features

- API authentication with Laravel Sanctum
- CRUD operations for blog posts
- Subscription management with Stripe
- Access control for premium content
- Comments on posts
- Logging on Papertrail

## Requirements

- PHP >= 8.0
- Composer
- MySQL
- Stripe account

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/aoamusat/blogapi.git
cd blogapi
```
### 2. Install PHP dependencies
```bash
composer install
```
### 3. Generate Laravel app secret key
```bash
php artisan key:generate
```
### Configuration
#### 1. Environment Variables
Copy the .env.example file to .env and fill in the necessary environment variables:
```bash
cp .env.example .env
```
Edit the .env file to set up your database and Stripe credentials.

## Database Migrations
Run the following command to create the necessary tables:
```bash
php artisan migrate
```

## Running the Application
Start the Laravel development server:
```bash
php artisan serve
```

## API Endpoints

### Authentication

- `POST /api/v1/login` - Log in a user
- `POST /api/v1/logout` - Log out the current user
- `POST /api/v1/register` - Register a new user 

### Blog Posts

- `GET /api/v1/posts` - List all posts 
- `POST /api/v1/posts` - Create a new post 
- `PUT /api/v1/posts/{id}` - Update a post 
- `GET /api/v1/posts/{id}` - Get a specific post 
- `DELETE /api/v1/posts/{id}` - Delete a post 

### Comments

- `GET /api/v1/posts/{postId}/comments` - List comments for a post
- `POST /api/v1/posts/{postId}/comments` - Add a comment to a post

### User Profile

- `GET /api/v1/profile` - Get authenticated user's profile 

### Stripe

- `POST /api/v1/stripe/webhook` - Handle Stripe webhook 

### Miscellaneous

- `GET /health` - Health check endpoint
- `GET /sanctum/csrf-cookie` - Get CSRF token
