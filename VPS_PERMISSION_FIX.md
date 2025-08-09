# VPS File Permission Fix for Laravel

## Issue Description
The VPS is experiencing permission errors when Laravel tries to write compiled view files:
```
file_put_contents(/var/www/new.greatticket.my/storage/framework/views/...): Failed to open stream: Permission denied
```

## Root Cause
The web server (Apache/Nginx) doesn't have proper write permissions to Laravel's storage directories on the VPS.

## Solution Commands
Run these commands on your VPS to fix the permissions:

### 1. Navigate to your project directory
```bash
cd /var/www/new.greatticket.my
```

### 2. Set proper ownership (replace 'www-data' with your web server user if different)
```bash
# Find out your web server user first
ps aux | grep -E '(apache|nginx|httpd)'

# Set ownership (use the user from above, typically www-data, apache, or nginx)
sudo chown -R www-data:www-data /var/www/new.greatticket.my
```

### 3. Set correct directory permissions
```bash
# Set directory permissions
sudo find /var/www/new.greatticket.my -type d -exec chmod 755 {} \;

# Set file permissions  
sudo find /var/www/new.greatticket.my -type f -exec chmod 644 {} \;
```

### 4. Set special permissions for Laravel storage and cache directories
```bash
# Make storage and bootstrap/cache writable
sudo chmod -R 775 /var/www/new.greatticket.my/storage
sudo chmod -R 775 /var/www/new.greatticket.my/bootstrap/cache

# Ensure web server can write to these directories
sudo chown -R www-data:www-data /var/www/new.greatticket.my/storage
sudo chown -R www-data:www-data /var/www/new.greatticket.my/bootstrap/cache
```

### 5. Alternative: If using different web server user
```bash
# For Apache on some systems
sudo chown -R apache:apache /var/www/new.greatticket.my

# For Nginx on some systems  
sudo chown -R nginx:nginx /var/www/new.greatticket.my

# Or check what user your web server runs as:
ps aux | grep -E '(apache|nginx|httpd)' | grep -v root
```

### 6. Clear Laravel caches after fixing permissions
```bash
cd /var/www/new.greatticket.my
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### 7. Verify permissions are correct
```bash
# Check storage directory permissions
ls -la /var/www/new.greatticket.my/storage/

# Check framework views directory specifically
ls -la /var/www/new.greatticket.my/storage/framework/views/
```

## Security Best Practices

### Recommended Permission Structure:
- **Directories**: 755 (owner: read/write/execute, group/others: read/execute)
- **Files**: 644 (owner: read/write, group/others: read only)
- **Storage directories**: 775 (owner/group: read/write/execute, others: read/execute)
- **Executable files** (like artisan): 755

### Never do this (security risk):
```bash
# DON'T USE 777 - it's a security risk
chmod -R 777 /var/www/new.greatticket.my  # ❌ NEVER DO THIS
```

## Expected Results
After running these commands:
1. Laravel should be able to compile and cache view files
2. The checkout process should work without permission errors
3. PDF generation should continue to work with our previous fixes
4. The application should run smoothly on the VPS

## Common Web Server Users by OS:
- **Ubuntu/Debian**: www-data
- **CentOS/RHEL/Amazon Linux**: apache
- **Some configurations**: nginx
- **cPanel/WHM**: nobody or the account username

## Verification Test
Try accessing your application after fixing permissions. The checkout process should complete successfully without the "Permission denied" error.
