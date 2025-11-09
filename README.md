# Task Management API

Task Management (CRUD)

A simple Task Management API built with Laravel that supports full CRUD (Create, Read, Update, Delete) operations for managing tasks

## Prerequisites

-   PHP 8.x or higher
-   Composer
-   MySQL/PostgreSQL (or your preferred database)
-   Docker (optional)

## Installation

### Option 1: Local Setup

1. **Clone the repository**

```bash
   git clone https://github.com/SteveEnny/Afrifounders-assessment-task.git
   cd Afrifounders-assessment-task
```

2. **Install dependencies**

```bash
   composer install
```

3. **Configure environment**

```bash
   cp .env.example .env
   php artisan key:generate
```

4. **Set up database**

    Update your `.env` file with your database credentials, then run:

```bash
   php artisan migrate
```

5. **Start the development server**

```bash
   php artisan serve
```

The application will be available at `http://localhost:8000`

### Option 2: Docker Setup

1. **Clone the repository**

```bash
   git clone https://github.com/SteveEnny/Afrifounders-assessment-task.git
   cd Afrifounders-assessment-task.git
```

2. **Configure environment**

```bash
   cp .env.example .env
   php artisan key:generate
```

3. **Start Docker containers**

```bash
   docker-compose up
```

4. **Install dependencies (inside container)**

```bash
   docker-compose exec app composer install
```

5. **Run migrations (inside container)**

```bash
   docker-compose exec app php artisan migrate
```

## API Documentation

Full API documentation is available on [Postman](https://documenter.getpostman.com/view/14679973/2sB3WsQL6B#b028fc23-c9b1-451a-a8fa-c9f8a61b9515).

## Testing

Run the test:

```bash
composer test
```

All tests are passing ✓

## License

[Your license here]
