# Restaurant Management System Backend

Welcome to the Restaurant Management System backend repository. This project is built using Laravel, a PHP framework, and provides APIs to manage the various functionalities of a restaurant, including orders, bookings, menu management, and more.

## Table of Contents

- [Getting Started](#getting-started)
- [Controllers](#controllers)
- [Models](#models)
- [Migrations](#migrations)
- [API Routes](#api-routes)
- [Gate Authorization](#gate-authorization)
- [Middlewares](#middlewares)
- [API Documentation](#api-documentation)
- [API Testing](#api-testing)
- [Contributors](#contributors)

## Getting Started

### Prerequisites

- PHP >= 7.4
- Composer
- MySQL


### Installation



1. **Install dependencies:**

```bash
composer install
npm install
```

2. **Environment setup:**

   - Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

   - Update the `.env` file with your database credentials and other settings:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

3. **Generate application key:**

```bash
php artisan key:generate
```

4. **Run migrations:**

```bash
php artisan migrate
```

5. **Start the server:**

```bash
php artisan serve
```

Your server should now be running at `http://localhost:8000`.

## Controllers

### Auth Controllers

- `UserController`: Handles user authentication, login, logout, and registration.

### Admin Controllers


- `MenuController`: Handles restaurant menu management by admin.
- `StaffController`: Handles restaurant staff management by admin.
- `SupplierController`: Handles restaurant supplier management by admin.
- `TableController` : Handles restaurant table management by admin.
  

### User Controllers

- `OrderController`: Manages user orders.
- `BookingController`: Manages user bookings.
- `ReviewController` : Manages user reviews.


## Models

- `User`: Represents a user in the system.
- `Order`: Represents an order placed by a user.
- `Booking`: Represents a booking made by a user.
- `Menu`: Represents a menu item.
- `Table`: Represents a table in the restaurant.
- `Staff` : Represents staff information in admin dashboard page.
- `Shift` : Represents the schedule of staff members.
- `Category` : Represents Restaurant categories that have many menu items.
- `Review` : Represents a review made by the user.

## Migrations

Migrations are located in the `database/migrations` directory. Some important migrations include:

- `create_users_table`: Creates the users table.
- `create_orders_table`: Creates the orders table.
- `create_bookings_table`: Creates the bookings table.
- `create_menus_table`: Creates the menus table.
- `create_tables_table`: Creates the tables table.
- `create_shifts_table` : Creates the shift table.
- `create_staff_table` : Creates the staff table.
- `create_category_table` : Creates the category table.
- `create_reviews_table` : Creates the reviews table.

## API Routes

API routes are defined in the `routes/api.php` file. Some key routes include:

### Auth Routes

- `POST /api/login`: Login a user.
- `POST /api/register`: Register a new user.
- `POST /api/logout`: Logout the current user.

### User Routes

- `GET /api/user`: Get authenticated user details.
- `GET /api/admin`: Get authenticated admin details.

### Order Routes

- `GET /api/orders`: Get all orders.
- `POST /api/orders`: Create a new order.
- `GET /api/orders/{id}`: Get a specific order.
- `PATCH /api/orders/{id}`: Update an order.
- `DELETE /api/orders/{id}`: Delete an order.
- `GET /api/index` : Get authenticated user orders.

### Booking Routes

- `GET /api/bookings`: Get all bookings.
- `POST /api/bookings`: Create a new booking.
- `GET /api/bookings/{id}`: Get a specific booking.
- `PATCH /api/bookings/{id}`: Update a booking.
- `DELETE /api/bookings/{id}`: Delete a booking.
- `GET /api/index1` : Get authenticated user bookings.

### Menu Routes

- `GET /api/menus`: Get all menu items.
- `POST /api/menus`: Create a new menu item.
- `GET /api/menus/{id}`: Get a specific menu item.
- `PATCHT /api/menus/{id}`: Update a menu item.
- `DELETE /api/menus/{id}`: Delete a menu item.
- `GET /api/menu/{categoryName}` : Get menu items by category name.

## Gate Authorization

Gate authorization is used to control access to certain actions based on user roles. Gates are defined in the `AppServiceProvider`.

Example of defining a gate:

```php
use Illuminate\Support\Facades\Gate;

Gate::define('manage-staff', function ($user) {
    return $user->role === 'admin';
});
```

## Middlewares

Middlewares are used to handle request filtering and preprocessing. Key middlewares include:

- `auth`: Ensures the user is authenticated.
- `admin`: Ensures the user is an admin.


Middleware registration is done in the `app/Http/Kernel.php` file.

## API Documentation

### Swagger

To document the APIs, Swagger can be used. Swagger annotations are used in the controllers to define the API documentation.

Install Swagger:

```bash
composer require "darkaonline/swagger-lume"
```

Publish the configuration:

```bash
php artisan swagger-lume:publish
```

Access the Swagger documentation at `http://localhost:8000/api/documentation`.

## API Testing

For API testing we have used as a tool Postman which was very efficient and very easy to use.

## Contributors

 [Festim Krasniqi](https://github.com/FestimKrasniqi)
 [Dominik Pllashniku](https://github.com/pllasha)


