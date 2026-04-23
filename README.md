```bash
# Clone the repository
git clone https://github.com/your-username/task.git
cd task

# Install dependencies
composer install

# Create .env file from .env.example
cp .env.example .env

# Set database credentials in .env
# Database: laravel_url_shortener
# User: root
# Password: [PASSWORD] (or leave empty if no password)

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Start the development server
php artisan serve

# Run tests
php artisan test
```