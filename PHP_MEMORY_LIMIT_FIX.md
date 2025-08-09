# PHP Memory Limit Fix for Laravel

## Issue Description
PHP is running out of memory when processing views, particularly during PDF generation:
```
Allowed memory size of 134217728 bytes exhausted (tried to allocate 48250880 bytes)
```

Current memory limit: **128MB** (134217728 bytes)
Failed allocation: **46MB** (48250880 bytes)

## Root Cause
This typically happens when:
1. **PDF generation with large images** - QR codes, logos, event posters
2. **Large datasets** - Many tickets or attendees in one PDF
3. **Inefficient image processing** - Loading large images into memory
4. **Memory leaks** - Variables not being freed properly

## Quick Fix Solutions

### Option 1: Increase PHP Memory Limit (Recommended)

#### For VPS with php.ini access:
```bash
# Find your php.ini file
php --ini

# Edit the php.ini file (usually in /etc/php/8.x/apache2/ or /etc/php/8.x/fpm/)
sudo nano /etc/php/8.2/apache2/php.ini

# Find and change:
memory_limit = 256M
# or for heavy PDF processing:
memory_limit = 512M

# Restart web server
sudo systemctl restart apache2
# or for nginx:
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

#### For cPanel/Shared Hosting:
Add to `.htaccess` in your project root:
```apache
php_value memory_limit 256M
```

#### For Laravel .env (if allowed by hosting):
Add to `.env` file:
```
PHP_MEMORY_LIMIT=256M
```

### Option 2: Optimize PDF Generation Code

#### Reduce Image Size for PDFs:
```php
// In QRCodeHelper or PDF templates, resize images before processing
public static function getOptimizedImageForPdf($imagePath, $maxWidth = 200) {
    if (!file_exists($imagePath)) return null;
    
    $imageInfo = getimagesize($imagePath);
    if ($imageInfo[0] <= $maxWidth) {
        return $imagePath; // Already small enough
    }
    
    // Create optimized version (implement image resizing)
    return self::resizeImageForPdf($imagePath, $maxWidth);
}
```

### Option 3: Temporary Memory Increase in Code

Add to problematic controllers or PDF generation methods:
```php
// Temporarily increase memory limit for PDF generation
ini_set('memory_limit', '256M');

// Your PDF generation code here

// Optional: Reset after processing
ini_set('memory_limit', '128M');
```

## Implementation Steps for VPS

### Step 1: Check Current Settings
```bash
# Check current PHP memory limit
php -r "echo 'Memory Limit: ' . ini_get('memory_limit') . PHP_EOL;"

# Check PHP version and configuration file location
php --ini
```

### Step 2: Increase Memory Limit
```bash
# Find the correct php.ini file
sudo find /etc -name "php.ini" 2>/dev/null

# Edit the main php.ini file (adjust path based on your PHP version)
sudo nano /etc/php/8.2/apache2/php.ini

# Find the line:
memory_limit = 128M

# Change it to:
memory_limit = 256M
```

### Step 3: Restart Web Server
```bash
# For Apache
sudo systemctl restart apache2

# For Nginx + PHP-FPM
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm

# Verify the change
php -r "echo 'New Memory Limit: ' . ini_get('memory_limit') . PHP_EOL;"
```

### Step 4: Alternative - .htaccess Method
If you can't edit php.ini, add to `/var/www/new.greatticket.my/.htaccess`:
```apache
# Increase PHP memory limit
php_value memory_limit 256M
php_value max_execution_time 300
php_value max_input_vars 3000
```

## Code Optimizations

### Optimize PDF Image Handling
```php
// In PDF templates, use smaller images
@php
    $qrPath = QRCodeHelper::getSafeImagePath($user->qr_image);
    if ($qrPath && filesize($qrPath) > 1024 * 1024) { // If larger than 1MB
        // Skip large images or resize them
        $qrPath = null;
    }
@endphp
```

### Limit Records per PDF
```php
// In controllers, limit the number of tickets per PDF
$ticketsPerPdf = 50; // Adjust based on memory
$tickets = $query->take($ticketsPerPdf)->get();
```

## Prevention Measures

### 1. Monitor Memory Usage
Add to problematic methods:
```php
Log::info('Memory usage before PDF generation: ' . memory_get_usage(true));
// PDF generation code
Log::info('Memory usage after PDF generation: ' . memory_get_usage(true));
```

### 2. Clean Up Variables
```php
// After processing large datasets
unset($largeVariable);
gc_collect_cycles(); // Force garbage collection
```

### 3. Use Chunking for Large Datasets
```php
// Process tickets in chunks
TicketUser::chunk(20, function ($tickets) {
    // Process each chunk separately
});
```

## Verification

After implementing the fix:

### Test Memory Limit
```bash
# Create a test PHP file to check memory limit
echo "<?php echo 'Memory Limit: ' . ini_get('memory_limit');" > /tmp/memtest.php
php /tmp/memtest.php
```

### Test PDF Generation
1. Try generating a PDF with the problematic data
2. Check Laravel logs for memory errors
3. Monitor server resources during PDF generation

## Recommended Settings for Laravel PDF Generation

```ini
; In php.ini
memory_limit = 256M
max_execution_time = 300
max_input_vars = 3000
post_max_size = 50M
upload_max_filesize = 50M
```

## Emergency Quick Fix

If you need an immediate fix while working on optimization:

```bash
# Quick memory limit increase via .htaccess
echo "php_value memory_limit 512M" >> /var/www/new.greatticket.my/.htaccess

# Test the application
curl -I https://new.greatticket.my
```

This should resolve the memory exhaustion error during PDF generation.
