# PHP Product API

This project is a simple PHP REST API for managing products. It uses a custom autoloader, PDO for database access, and outputs JSON responses.

## Features

- RESTful endpoints for products
- JSON error handling
- PDO-based MySQL connection

## Requirements

- PHP 7.4 or higher (PHP 8+ recommended for constructor property promotion)
- MySQL
- Composer (optional, for dependency management)

## Setup

1. **Clone the repository**  
   `git clone <your-repo-url>`

2. **Configure the database**  
   Edit `config.php` with your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'your_db');
   define('DB_USER', 'your_user');
   define('DB_PASSWORD', 'your_password');
   define('DB_PORT', '3306');
   ```

3. **Run the API**  
   Place the project in your web server's root directory and access via browser or `curl`:
   ```
   curl -s http://localhost/api/products
   ```

## Error Handling

All errors are returned as JSON with the following structure:
```json
{
  "code": 500,
  "message": "Error message",
  "file": "/path/to/file.php",
  "line": 42
}
```

## License

MIT License
