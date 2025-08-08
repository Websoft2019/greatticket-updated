#!/bin/bash

# Great Ticket Simple Deployment Script
# For VPS that is already configured

echo "🚀 Deploying Great Ticket to configured VPS..."

# Set variables
PROJECT_DIR="/var/www/new.greatticket.my"
BRANCH="main"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Navigate to project directory
cd $PROJECT_DIR || {
    print_error "Project directory not found: $PROJECT_DIR"
    exit 1
}

# Pull latest changes
print_status "Pulling latest changes from GitHub..."
git pull origin $BRANCH

# Preserve existing .env file (don't overwrite with repository version)
if [ -f "$PROJECT_DIR/.env" ]; then
    print_status "Preserving existing .env configuration..."
    git checkout HEAD -- .env 2>/dev/null || true
fi

# Install PHP dependencies
print_status "Installing/updating PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Set up environment if not exists
if [ ! -f "$PROJECT_DIR/.env" ]; then
    print_status "Setting up environment file..."
    cp $PROJECT_DIR/.env.production $PROJECT_DIR/.env
    print_warning "Please update $PROJECT_DIR/.env with your settings"
fi

# Set proper permissions
print_status "Setting file permissions..."
sudo chown -R www-data:www-data $PROJECT_DIR
sudo chmod -R 755 $PROJECT_DIR
sudo chmod -R 775 $PROJECT_DIR/storage
sudo chmod -R 775 $PROJECT_DIR/bootstrap/cache

# Laravel optimization
print_status "Running Laravel optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if not exists
if [ ! -L "$PROJECT_DIR/public/storage" ]; then
    print_status "Creating storage link..."
    php artisan storage:link
fi

print_status "✅ Deployment completed successfully!"
print_status "Your application is ready at: https://new.greatticket.my"
