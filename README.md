# Booking API

A Laravel-based booking API designed to manage and handle property bookings efficiently. Built with modern development practices and a clean architecture.

---

## 🚀 Features

- User authentication (registration, login, logout) with Laravel Sanctum
- Property management for owners
- Booking management for users
- Integration with Spatie Media Library for photo handling
- Simple property searching functionality
- RESTful API with structured responses
- End-to-end API documentation at `/docs/api`

---

## 🛠️ Requirements

- PHP 8.1+
- Composer
- MySQL 5.7+
- Node.js and npm (for front-end or asset handling via Vite)

---

## 🔧 Installation

Follow the steps below to install and set up the project locally:

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd booking-api
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js Dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   Copy the `.env.example` file to `.env` and adjust the necessary settings:
   ```bash
   cp .env.example .env
   ```

    - Set up your database credentials
    - Configure Mail & Queue settings if applicable (`MAIL_*`, `QUEUE_CONNECTION`)

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the Database (If Applicable)**
   ```bash
   php artisan db:seed
   ```

8. **Run the Application**
   ```bash
   php artisan serve
   ```

   Navigate to `http://127.0.0.1:8000` in your browser.

---

## 📖 API Endpoints

This application provides a comprehensive set of RESTful API endpoints scoped for public and owner-specific operations. Below is a summary of the key endpoints:

### **Authentication**
| HTTP Method | Endpoint                          | Description         |
|-------------|-----------------------------------|---------------------|
| `POST`      | `/api/v1/auth/register`           | User registration   |
| `POST`      | `/api/v1/auth/login`              | User login          |
| `POST`      | `/api/v1/logout`                  | User logout         |

---

### **Public**
| HTTP Method | Endpoint                          | Description                                 |
|-------------|-----------------------------------|---------------------------------------------|
| `GET`       | `/api/v1/properties/{property}`   | View a specific property's details         |
| `GET`       | `/api/v1/apartments/{apartment}`  | View a specific apartment's details (if applicable) |
| `GET`       | `/api/v1/search`                 | Search properties based on filters         |

---

### **Owner Portal**
| HTTP Method | Endpoint                          | Description                                 |
|-------------|-----------------------------------|---------------------------------------------|
| `GET`       | `/api/v1/owner/properties`        | List all properties owned by the user      |
| `POST`      | `/api/v1/owner/properties`        | Add a new property                         |
| `POST`      | `/api/v1/owner/{property}/photos` | Add photos to a property                   |
| `POST`      | `/api/v1/owner/{property}/photos/{photo}/reorder/{newPosition}` | Reorder photos for a specific property     |

---

### **User Bookings**
| HTTP Method | Endpoint                          | Description                                 |
|-------------|-----------------------------------|---------------------------------------------|
| `GET`       | `/api/v1/user/bookings`           | List all bookings for the authenticated user |
| `POST`      | `/api/v1/user/bookings`           | Create a new booking                       |
| `GET`       | `/api/v1/user/bookings/{booking}` | View a specific booking's details          |
| `PUT`       | `/api/v1/user/bookings/{booking}` | Update a specific booking                  |
| `DELETE`    | `/api/v1/user/bookings/{booking}` | Cancel/delete a specific booking           |

---

### **API Documentation**
Full documentation for all API endpoints, input parameters, and response structures is available at:

[http://127.0.0.1:8000/docs/api](http://127.0.0.1:8000/docs/api)

You can directly view the documentation in your browser.

---

## 🧪 Running Tests

The project comes with a PHPUnit test suite. To run tests:

```bash
php artisan test
```

*Ensure the `.env.testing` file is properly configured before testing.*

---

## ✨ Key Packages

This API utilizes several industry-standard Laravel packages:

| Package                                  | Description                                                      |
|------------------------------------------|------------------------------------------------------------------|
| [spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary) | Media uploads and handling                                       |
| [laravel/sanctum](https://laravel.com/docs/10.x/sanctum)             | Authentication via tokens                                        |
| [nunomaduro/larastan](https://github.com/nunomaduro/larastan)       | Static analysis for Laravel (improves code quality)              |
| [barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper) | Auto-generating PHPDoc for autocomplete support                 |
| [dedoc/scramble](https://github.com/dedoc/scramble)                | API documentation generator for Laravel                         |

---

## 🛡️ Security Vulnerabilities

If you discover any security vulnerability, please submit it [here](<security-email-or-link>) or contact the team directly.

---

## 📜 License

This project is open-source software licensed under the **MIT License**.
