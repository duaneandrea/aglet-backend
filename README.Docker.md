# 🐳 Docker Setup for Aglet Backend

This project comes with a complete Docker setup that allows you to run the entire Laravel application with all its dependencies using a single command.

## 🚀 Quick Start

### Prerequisites
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### One-Command Setup
```bash
./docker-setup.sh
```

This script will:
- Create a `.env` file with Docker-friendly configurations
- Build and start all Docker containers
- Install PHP and Node.js dependencies
- Generate application key
- Run database migrations and seeds
- Build frontend assets
- Clear all caches

## 🏗️ Architecture

The Docker setup includes the following services:

- **app**: PHP 8.4-FPM container running the Laravel application
- **webserver**: Nginx container serving the application
- **db**: MySQL 8.0 database
- **redis**: Redis for caching and sessions
- **node**: Node.js container for frontend asset compilation

## 🌐 Access Points

After running the setup:

- **Application**: http://localhost:8000
- **Database**: localhost:3306
  - Database: `aglet_backend`
  - Username: `aglet_user`
  - Password: `password`
- **Redis**: localhost:6379

## 📋 Common Commands

### Container Management
```bash
# Start all containers
docker-compose up -d

# Stop all containers
docker-compose down

# Rebuild and start containers
docker-compose up -d --build

# View logs
docker-compose logs -f

# View logs for specific service
docker-compose logs -f app
```

### Laravel Commands
```bash
# Run artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker
docker-compose exec app php artisan queue:work

# Access the app container shell
docker-compose exec app bash
```

### Frontend Development
```bash
# Install npm packages
docker-compose exec node npm install

# Build assets for development
docker-compose exec node npm run dev

# Build assets for production
docker-compose exec node npm run build

# Watch for changes (development)
docker-compose exec node npm run watch
```

### Database Operations
```bash
# Run migrations
docker-compose exec app php artisan migrate

# Run seeds
docker-compose exec app php artisan db:seed

# Reset database
docker-compose exec app php artisan migrate:fresh --seed

# Access MySQL directly
docker-compose exec db mysql -u aglet_user -p aglet_backend
```

## 🔧 Configuration

### Environment Variables
The setup script creates a `.env` file with Docker-specific configurations. Key variables:

- `DB_HOST=db` (points to the MySQL container)
- `REDIS_HOST=redis` (points to the Redis container)
- `APP_URL=http://localhost:8000`

### TMDB API Configuration
Don't forget to add your TMDB API key to the `.env` file:
```env
TMDB_API_KEY=your_actual_tmdb_api_key_here
```

### Custom PHP Configuration
PHP settings can be modified in `docker/php/local.ini`.

### Custom Nginx Configuration
Nginx settings can be modified in `docker/nginx/default.conf`.

## 🗂️ File Structure
```
├── docker/
│   ├── nginx/
│   │   └── default.conf      # Nginx configuration
│   ├── php/
│   │   └── local.ini         # PHP configuration
│   └── mysql/
│       └── my.cnf            # MySQL configuration
├── docker-compose.yml        # Docker services definition
├── Dockerfile               # Laravel app container
└── docker-setup.sh         # One-command setup script
```

## 🐛 Troubleshooting

### Container Issues
```bash
# Check container status
docker-compose ps

# Restart specific service
docker-compose restart app

# Remove all containers and volumes (clean slate)
docker-compose down -v
docker system prune -a
```

### Permission Issues
```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Database Connection Issues
```bash
# Check if database is ready
docker-compose exec db mysqladmin ping -h localhost

# Verify database exists
docker-compose exec db mysql -u root -p -e "SHOW DATABASES;"
```

## 🔄 Development Workflow

### Making Code Changes
1. Edit files in your local editor
2. Changes are automatically reflected (volumes are mounted)
3. For PHP changes: Clear cache if needed
   ```bash
   docker-compose exec app php artisan config:clear
   ```
4. For frontend changes: Rebuild assets
   ```bash
   docker-compose exec node npm run dev
   ```

### Running Tests
```bash
# Run PHPUnit tests
docker-compose exec app php artisan test

# Run specific test
docker-compose exec app php artisan test --filter=TestName
```

## 📦 Production Deployment

For production deployment, modify:
1. Change `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Use production database credentials
4. Run `npm run build` for optimized assets
5. Use proper SSL certificates
6. Consider using Docker Swarm or Kubernetes for orchestration

## 💡 Tips

- Use `docker-compose logs -f` to monitor real-time logs
- The database data persists in a Docker volume, so it survives container restarts
- For faster rebuilds, use `docker-compose build --no-cache app` if needed
- Monitor resource usage with `docker stats` 