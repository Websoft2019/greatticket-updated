# PDF Images Missing - Troubleshooting Guide

## Problem: PDFs Generate But Images Are Missing

When PDFs are generated successfully but QR codes, logos, or other images don't appear, this is usually due to:

1. **File Permission Issues**
2. **Missing Storage Link**
3. **Incorrect File Paths**
4. **Missing Image Files**

## Quick Diagnosis Commands (Run on VPS)

### 1. Check Storage Link
```bash
cd /var/www/new.greatticket.my
ls -la public/storage
```
**Expected**: Should show a symbolic link pointing to `../storage/app/public`

### 2. Check QR Code Directory
```bash
ls -la storage/app/public/qr_codes/
```
**Expected**: Should show QR code PNG files with proper permissions

### 3. Check Permissions
```bash
ls -la storage/app/public/
```
**Expected**: Directories should be `775` and owned by `www-data:www-data`

## Fix Steps

### Step 1: Recreate Storage Link
```bash
cd /var/www/new.greatticket.my

# Remove broken link if exists
rm -f public/storage

# Create new storage link
php artisan storage:link

# Verify it was created
ls -la public/storage
```

### Step 2: Fix File Permissions
```bash
# Set ownership
sudo chown -R www-data:www-data storage/app/public/

# Set permissions
sudo find storage/app/public/ -type d -exec chmod 775 {} \;
sudo find storage/app/public/ -type f -exec chmod 664 {} \;

# Special attention to QR codes
sudo chmod -R 775 storage/app/public/qr_codes/
```

### Step 3: Clear Caches
```bash
php artisan view:clear
php artisan cache:clear
```

## Debug Mode (Temporary)

### Enable Debug Information
1. Edit `.env` file:
```bash
nano .env
```

2. Set:
```
APP_DEBUG=true
LOG_LEVEL=debug
```

3. Generate a test PDF and check logs:
```bash
tail -f storage/logs/laravel.log
```

### Add Debug Route (Temporary)
Add to `routes/web.php`:
```php
Route::get('/debug-pdf-images', function () {
    use App\Helpers\QRCodeHelper;
    use App\Models\TicketUser;
    
    $ticketUser = TicketUser::whereNotNull('qr_image')->first();
    
    if (!$ticketUser) {
        return 'No ticket users with QR images found';
    }

    $debug = QRCodeHelper::debugImagePath($ticketUser->qr_image);
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
});
```

Visit: `https://new.greatticket.my/debug-pdf-images`

## Common Issues & Solutions

### Issue 1: "QR CODE" Placeholder Shown
**Cause**: QR image file not found
**Solution**: 
```bash
# Check if QR files exist
ls -la storage/app/public/qr_codes/
# If empty, regenerate QR codes (contact developer)
```

### Issue 2: Logo Missing
**Cause**: Logo file not in expected location
**Solution**:
```bash
# Check logo locations
ls -la public/site/images/logo.png
ls -la public/images/logo.png
ls -la public/assets/images/logo.png
```

### Issue 3: Organizer Photos Missing
**Cause**: Permission or path issues
**Solution**:
```bash
# Check organizer photos
ls -la storage/app/public/organizer_photos/
sudo chmod -R 775 storage/app/public/organizer_photos/
```

### Issue 4: Event Posters Missing
**Cause**: Permission or path issues
**Solution**:
```bash
# Check event posters
ls -la storage/app/public/event_posters/
sudo chmod -R 775 storage/app/public/event_posters/
```

## Verification Tests

### Test 1: Manual File Access
```bash
# Try to read a QR code file manually
cat storage/app/public/qr_codes/[filename].png > /dev/null
echo $?  # Should return 0 if readable
```

### Test 2: Web Server Access
Visit: `https://new.greatticket.my/storage/qr_codes/[filename].png`
Should display the QR code image

### Test 3: Generate Test PDF
Try generating a PDF through the application and check if images appear.

## After Fixing

1. **Disable Debug Mode**:
```bash
# Edit .env
APP_DEBUG=false
LOG_LEVEL=error
```

2. **Remove Debug Routes**: Remove any temporary debug routes from `routes/web.php`

3. **Clear Caches**:
```bash
php artisan config:cache
php artisan view:clear
```

## Prevention

### Regular Maintenance
```bash
# Weekly permission check
sudo find /var/www/new.greatticket.my/storage -type f ! -perm 664 -exec chmod 664 {} \;
sudo find /var/www/new.greatticket.my/storage -type d ! -perm 775 -exec chmod 775 {} \;
```

### Backup Important Directories
```bash
# Backup QR codes and images
tar -czf ~/backup-images-$(date +%Y%m%d).tar.gz storage/app/public/
```

This should resolve the missing images in PDF issue. The problem is usually permissions or missing storage links rather than the PDF generation code itself.
