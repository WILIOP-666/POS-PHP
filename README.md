# PHP Point of Sale System

A comprehensive Point of Sale (POS) system built with PHP and MySQL.

## Features

- User Authentication (Admin, Cashier)
- Product Management
- Category Management
- Inventory Management
- Sales Processing
- Customer Management
- Reporting and Analytics
- Receipt Generation

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

## Installation

1. Clone or download this repository to your web server directory
2. Import the database schema from `database/pos_db.sql`
3. Configure database connection in `config/database.php`
4. Access the system through your web browser
5. Default login credentials:
   - Username: admin
   - Password: admin123

## Project Structure

```
/
├── assets/           # CSS, JS, images, and other static files
├── config/           # Configuration files
├── controllers/      # Controller files
├── database/         # Database schema and migrations
├── includes/         # Reusable PHP components
├── models/           # Model files
├── uploads/          # Uploaded files (product images, etc.)
├── views/            # View files
├── index.php         # Entry point
└── README.md         # Project documentation
```

## License

MIT License
