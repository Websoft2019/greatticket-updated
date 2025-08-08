# Great Ticket - VPS Deployment Guide

## 🚀 Quick Deployment

### Prerequisites on VPS
- Ubuntu 20.04+ or Debian 10+
- Root or sudo access
- Domain name pointing to your VPS

### 1. Initial VPS Setup

```bash
# Clone the repository
git clone git@github.com:Websoft2019/greatticket-updated.git /var/www/greatticket
cd /var/www/greatticket

# Make deployment script executable
chmod +x deploy.sh

# Run deployment script
./deploy.sh
```

### 2. Configure Environment

```bash
# Edit production environment
sudo nano /var/www/greatticket/.env
```

Update these critical settings:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_DATABASE=greatticket_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
```

### 3. Database Setup

```bash
# Create MySQL database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE greatticket_production;
CREATE USER 'greatticket_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON greatticket_production.* TO 'greatticket_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Nginx Configuration

```bash
# Copy nginx config
sudo cp /var/www/greatticket/nginx-config.conf /etc/nginx/sites-available/greatticket

# Edit with your domain
sudo nano /etc/nginx/sites-available/greatticket

# Enable site
sudo ln -s /etc/nginx/sites-available/greatticket /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Test and restart nginx
sudo nginx -t
sudo systemctl restart nginx
```

### 5. SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install snapd
sudo snap install core; sudo snap refresh core
sudo snap install --classic certbot

# Create symlink
sudo ln -s /snap/bin/certbot /usr/bin/certbot

# Get certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### 6. GitHub Actions Setup

Add these secrets to your GitHub repository:
- `VPS_HOST`: Your VPS IP address
- `VPS_USERNAME`: Your VPS username
- `VPS_SSH_KEY`: Your private SSH key content

## 🔧 Manual Deployment Commands

```bash
# Pull latest changes
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
```

## 📊 Monitoring & Maintenance

### Log Files
- Application: `/var/www/greatticket/storage/logs/laravel.log`
- Nginx Access: `/var/log/nginx/greatticket_access.log`
- Nginx Error: `/var/log/nginx/greatticket_error.log`

### Regular Maintenance
```bash
# Clear old logs (weekly)
sudo find /var/www/greatticket/storage/logs -name "*.log" -mtime +30 -delete

# Update system packages (monthly)
sudo apt update && sudo apt upgrade -y

# Backup database (daily via cron)
mysqldump -u greatticket_user -p greatticket_production > backup_$(date +%Y%m%d).sql
```

## 🔒 Security Checklist

- [ ] SSL certificate installed and configured
- [ ] Firewall configured (UFW recommended)
- [ ] Database user has minimal required privileges
- [ ] `.env` file permissions set to 600
- [ ] Regular security updates applied
- [ ] Backup strategy implemented
- [ ] Monitoring tools configured

## 🆘 Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data /var/www/greatticket
   sudo chmod -R 755 /var/www/greatticket
   sudo chmod -R 775 /var/www/greatticket/storage
   ```

2. **Database Connection Issues**
   - Check MySQL service: `sudo systemctl status mysql`
   - Verify credentials in `.env`
   - Test connection: `php artisan tinker` then `DB::connection()->getPdo();`

3. **Nginx 502 Error**
   - Check PHP-FPM: `sudo systemctl status php8.1-fpm`
   - Check Nginx error logs: `sudo tail -f /var/log/nginx/error.log`

4. **SSL Issues**
   - Renew certificate: `sudo certbot renew`
   - Check certificate status: `sudo certbot certificates`

## 📞 Support

For deployment issues, contact: info@websoftnepal.com.np
