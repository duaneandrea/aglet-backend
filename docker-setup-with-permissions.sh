#!/bin/bash

echo "🚀 Setting up Aglet Backend with Docker..."
echo "⚠️  Note: You were added to the docker group. After this setup, you may need to log out and back in for full effect."

# Check if .env exists, if not create it
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file from example..."
    cp .env.example .env 2>/dev/null || cat > .env << 'EOF'
APP_NAME="Aglet Backend"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=aglet_backend
DB_USERNAME=aglet_user
DB_PASSWORD=password

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=redis
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

TMDB_API_KEY=your_tmdb_api_key_here
TMDB_BASE_URL=https://api.themoviedb.org/3
TMDB_IMAGE_BASE_URL=https://image.tmdb.org/t/p/w500
EOF
fi

# Function to run docker-compose with appropriate permissions
run_docker_compose() {
    if docker-compose version >/dev/null 2>&1; then
        docker-compose "$@"
    elif docker compose version >/dev/null 2>&1; then
        docker compose "$@"
    else
        sudo docker-compose "$@"
    fi
}

echo "🐳 Building and starting Docker containers..."
run_docker_compose up -d --build

echo "⏳ Waiting for containers to be ready..."
sleep 15

echo "🔑 Generating application key..."
run_docker_compose exec app php artisan key:generate

echo "📊 Running database migrations..."
run_docker_compose exec app php artisan migrate --force

echo "🌱 Seeding database..."
run_docker_compose exec app php artisan db:seed --force

echo "🔗 Creating symbolic link for storage..."
run_docker_compose exec app php artisan storage:link

echo "📦 Installing Node.js dependencies..."
run_docker_compose exec node npm install

echo "🎨 Building frontend assets..."
run_docker_compose exec node npm run build

echo "🧹 Clearing application cache..."
run_docker_compose exec app php artisan config:clear
run_docker_compose exec app php artisan cache:clear
run_docker_compose exec app php artisan view:clear

echo "✅ Setup complete!"
echo ""
echo "🌐 Your application is now running at: http://localhost:8000"
echo "🗄️  Database is available at: localhost:3306"
echo "📮 Redis is available at: localhost:6379"
echo ""
echo "📋 Useful commands:"
echo "  • View logs: docker-compose logs -f (or docker compose logs -f)"
echo "  • Stop containers: docker-compose down (or docker compose down)"
echo "  • Access app container: docker-compose exec app bash"
echo "  • Run artisan commands: docker-compose exec app php artisan <command>"
echo ""
echo "💡 If you encounter permission issues in the future, try:"
echo "  • Log out and back in to refresh group membership"
echo "  • Or use 'newgrp docker' to activate the docker group in current session" 