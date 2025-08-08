# Great Ticket - VPS Deployment Guide

## 🚀 Simple Deployment (VPS Already Configured)

### Prerequisites ✅
- VPS is already configured with web server, PHP, MySQL
- Database is already set up and populated
- Domain `new.greatticket.my` is configured
- SSL certificate is installed

### 1. Deploy Laravel Project

```bash
# Clone/update the repository on VPS
git clone git@github.com:Websoft2019/greatticket-updated.git /var/www/new.greatticket.my
cd /var/www/new.greatticket.my

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set up environment
cp .env.production .env
nano .env  # Update with your VPS-specific settings

# Note: .env file is not tracked in Git and won't be overwritten by deployments

# Set permissions
sudo chown -R www-data:www-data /var/www/new.greatticket.my
sudo chmod -R 755 /var/www/new.greatticket.my
sudo chmod -R 775 /var/www/new.greatticket.my/storage
sudo chmod -R 775 /var/www/new.greatticket.my/bootstrap/cache

# Laravel setup
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# Note: We don't run 'php artisan key:generate' to preserve existing encrypted data
```

### Environment Settings
Create and configure your VPS-specific `.env` file:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://new.greatticket.my
DB_DATABASE=greatticket
DB_USERNAME=greatticket
DB_PASSWORD=Nepal@977Greatticket
```

**Important:** 
- The `.env` file is not tracked in Git and will be preserved during deployments.
- Database is already set up on VPS - no migrations will be run during deployment.
- **APP_KEY must not be changed** - it would break existing encrypted passwords and data.

### 2. Manual Deployment Workflow (Recommended)

When you want to deploy your code changes:

```bash
# 1. Push your changes to GitHub (from your local machine)
git add .
git commit -m "Your changes description"
git push origin main

# 2. SSH into your VPS and update the code
ssh your-username@your-vps-ip
cd /var/www/new.greatticket.my
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. GitHub Actions Setup (Optional - Skip This For Manual Deployment)

If you want automatic deployment later, you can set up GitHub Actions by adding these secrets to your repository:
- `VPS_HOST`: Your VPS IP address  
- `VPS_USERNAME`: Your VPS username
- `VPS_SSH_KEY`: Your private SSH key content

*Note: Skip this section for now since you're doing manual deployment.*

## 🔄 Daily Development Workflow

```bash
# On your local machine - make changes and push to GitHub
git add .
git commit -m "Describe your changes"
git push origin main

# On your VPS - pull and update the live site
ssh your-username@your-vps-ip
cd /var/www/new.greatticket.my
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache  
php artisan view:cache
```

## 📊 Monitoring & Maintenance

### Log Files
- Application: `/var/www/new.greatticket.my/storage/logs/laravel.log`
- Web Server Access: Check your web server's access logs
- Web Server Error: Check your web server's error logs

### Regular Maintenance
```bash
# Clear old logs (weekly)
sudo find /var/www/new.greatticket.my/storage/logs -name "*.log" -mtime +30 -delete

# Update system packages (monthly)
sudo apt update && sudo apt upgrade -y

# Backup database (daily via cron)
mysqldump -u greatticket -p greatticket > backup_$(date +%Y%m%d).sql
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
   sudo chown -R www-data:www-data /var/www/new.greatticket.my
   sudo chmod -R 755 /var/www/new.greatticket.my
   sudo chmod -R 775 /var/www/new.greatticket.my/storage
   ```

2. **Database Connection Issues**
   - Check MySQL service: `sudo systemctl status mysql`
   - Verify credentials in `.env`
   - Test connection: `php artisan tinker` then `DB::connection()->getPdo();`

3. **Web Server 502/500 Error**
   - Check your web server status
   - Check PHP-FPM: `sudo systemctl status php-fpm` or `sudo systemctl status php8.1-fpm`
   - Check web server error logs

4. **SSL Issues**
   - Renew certificate: `sudo certbot renew`
   - Check certificate status: `sudo certbot certificates`

## 📞 Support

For deployment issues, contact: info@websoftnepal.com.np
