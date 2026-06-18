# Customer Payment API

## Tech Stack

- Laravel 12
- MySQL
- Laravel Sanctum
- REST APIs

## Features

- Admin Login
- User Login
- CSV Upload
- Customer Listing
- Search Customers
- Update Payment Status
- Send Notifications
- Reporting API

## Installation

composer install

cp .env.example .env

php artisan key:generate

Update database credentials in .env

php artisan migrate

php artisan db:seed

php artisan serve

## Postman Collection

The Postman collection required for testing all APIs is included in this repository.

Location: /postman/customer-payment-api.postman_collection.json

## Test Credentials

Admin:
admin@test.com
password

User:
user@test.com
password
