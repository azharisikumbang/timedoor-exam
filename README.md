# TIMEDOOR PROJECT EXAM

This project was created to fulfill the exam needs of the Timedoor team for the Backend Programmer position. 

## Expected Features

-   List of books with filter
-   Top 10 most famous author
-   Input rating

## Tech Stack

-   PHP 8.1
-   Laravel 10.x
-   MySQL

## Installation

Clone the repository and install the dependencies

```bash
git clone https://github.com/azharisikumbang/timedoor-exam
cd timedoor-exam
composer install
```

run migration and seeds

```bash
# Please dont forget to config the database at .env file
php artisan migrate --seed
```

Run test

```bash
php artisan test
```

Run the app

```bash
php artisan serve
```
