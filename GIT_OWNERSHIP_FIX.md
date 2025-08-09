# Git Ownership Issue Fix for VPS

## Current Error
```
fatal: detected dubious ownership in repository at '/var/www/new.greatticket.my'
To add an exception for this directory, call:
git config --global --add safe.directory /var/www/new.greatticket.my
```

## Quick Fix (Run these commands on your VPS as ishwor user)

### Option 1: Add Safe Directory (Recommended)
```bash
# Add the directory as safe for git operations
git config --global --add safe.directory /var/www/new.greatticket.my

# Now you can pull updates
git pull origin main
```

### Option 2: Fix Ownership Structure
```bash
# Make sure you own the .git directory for git operations
sudo chown -R ishwor:ishwor /var/www/new.greatticket.my/.git

# Keep application files owned by web server
sudo chown -R www-data:www-data /var/www/new.greatticket.my
# But exclude .git directory
sudo chown -R ishwor:ishwor /var/www/new.greatticket.my/.git

# Now you can pull updates
git pull origin main
```

## After Pulling Updates

### 1. Fix Application Permissions
```bash
# Set proper ownership for Laravel
sudo chown -R www-data:www-data /var/www/new.greatticket.my

# Keep .git owned by your user
sudo chown -R ishwor:ishwor /var/www/new.greatticket.my/.git

# Set directory permissions
sudo find /var/www/new.greatticket.my -type d -exec chmod 755 {} \;
sudo find /var/www/new.greatticket.my -type f -exec chmod 644 {} \;

# Set Laravel storage permissions
sudo chmod -R 775 /var/www/new.greatticket.my/storage
sudo chmod -R 775 /var/www/new.greatticket.my/bootstrap/cache
```

### 2. Install/Update Dependencies
```bash
cd /var/www/new.greatticket.my
composer install --no-dev --optimize-autoloader
```

### 3. Create Storage Link (if missing)
```bash
php artisan storage:link
```

### 4. Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Complete Deployment Sequence

Here's the complete sequence to deploy your latest fixes:

```bash
# 1. Fix git ownership issue
git config --global --add safe.directory /var/www/new.greatticket.my

# 2. Pull latest code
git pull origin main

# 3. Install dependencies
composer install --no-dev --optimize-autoloader

# 4. Fix permissions
sudo chown -R www-data:www-data /var/www/new.greatticket.my
sudo chown -R ishwor:ishwor /var/www/new.greatticket.my/.git
sudo find /var/www/new.greatticket.my -type d -exec chmod 755 {} \;
sudo find /var/www/new.greatticket.my -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/new.greatticket.my/storage
sudo chmod -R 775 /var/www/new.greatticket.my/bootstrap/cache

# 5. Create storage link
php artisan storage:link

# 6. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 7. Test the application
curl -I https://new.greatticket.my
```

## Why This Happens

This error occurs because:
1. The repository was created/cloned as root or www-data user
2. You're trying to run git commands as ishwor user
3. Git security prevents operations on repositories owned by different users

## Prevention for Future

To prevent this in the future:
1. Always clone repositories as your user (ishwor)
2. Use deployment scripts that handle ownership correctly
3. Keep .git directory owned by your user, application files owned by web server

## Verification

After running the commands, verify:
```bash
# Git operations should work
git status
git log --oneline -5

# Application should be accessible
curl -I https://new.greatticket.my

# Permissions should be correct
ls -la /var/www/new.greatticket.my/.git/
ls -la /var/www/new.greatticket.my/storage/
```
