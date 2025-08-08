#!/bin/bash

# Great Ticket VPS Deployment Script
# Run this script on your VPS to deploy the application

echo "🚀 Starting Great Ticket deployment..."

# Set variables
PROJECT_DIR="/var/www/greatticket"
REPO_URL="git@github.com:Websoft2019/greatticket-updated.git"
BRANCH="main"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    print_warning "Running as root. Consider using a non-root user with sudo privileges."
fi

# Update system packages
print_status "Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install required packages
print_status "Installing required packages..."
sudo apt install -y nginx mysql-server php8.1-fpm php8.1-mysql php8.1-xml php8.1-gd php8.1-curl php8.1-mbstring php8.1-zip php8.1-bcmath php8.1-intl composer git unzip

# Clone or update repository
if [ -d "$PROJECT_DIR" ]; then
    print_status "Updating existing repository..."
    cd $PROJECT_DIR
    git pull origin $BRANCH
else
    print_status "Cloning repository..."
    sudo git clone $REPO_URL $PROJECT_DIR
    cd $PROJECT_DIR
fi

# Set proper permissions
print_status "Setting file permissions..."
sudo chown -R www-data:www-data $PROJECT_DIR
sudo chmod -R 755 $PROJECT_DIR
sudo chmod -R 775 $PROJECT_DIR/storage
sudo chmod -R 775 $PROJECT_DIR/bootstrap/cache

# Install PHP dependencies
print_status "Installing PHP dependencies..."
sudo -u www-data composer install --no-dev --optimize-autoloader

# Copy environment file
if [ ! -f "$PROJECT_DIR/.env" ]; then
    print_status "Setting up environment file..."
    sudo cp $PROJECT_DIR/.env.production $PROJECT_DIR/.env
    print_warning "Please edit $PROJECT_DIR/.env with your production settings"
fi

# Generate application key
print_status "Generating application key..."
sudo -u www-data php artisan key:generate

# Run database migrations
print_status "Running database migrations..."
sudo -u www-data php artisan migrate --force

# Clear and cache configuration
print_status "Optimizing application..."
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Create storage link
print_status "Creating storage link..."
sudo -u www-data php artisan storage:link

# Restart services
print_status "Restarting services..."
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm

print_status "✅ Deployment completed successfully!"
print_warning "Don't forget to:"
print_warning "1. Configure your database settings in .env"
print_warning "2. Set up your domain DNS"
print_warning "3. Configure SSL certificate"
print_warning "4. Set up proper backup procedures"
