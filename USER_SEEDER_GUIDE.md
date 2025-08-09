# User Seeder Documentation

## Overview
The Great Ticket application includes comprehensive user seeders to create test users with different roles for development and testing purposes.

## User Roles
- **Admin (a)**: System administrator with full access
- **Organizer (o)**: Event organizer who can create and manage events
- **User (u)**: Regular customer who can book tickets

## Available Seeders

### 1. UserSeeder
Creates the basic test users and is included in the main DatabaseSeeder.

**Users Created:**
- Admin: `admin@greatticket.my` (password: `admin123`)
- Organizer: `organizer@greatticket.my` (password: `organizer123`)
- User: `user@greatticket.my` (password: `user123`)

### 2. TestUsersSeeder
Standalone seeder that can be run independently with cleanup options.

### 3. CreateTestUsers Command
Artisan command for easy user creation with various options.

## Usage

### Method 1: Run Full Database Seeder
```bash
# Run all seeders (includes UserSeeder)
php artisan db:seed

# Or refresh database and seed
php artisan migrate:fresh --seed
```

### Method 2: Run User Seeder Only
```bash
# Run only the UserSeeder
php artisan db:seed --class=UserSeeder

# Run standalone test users seeder
php artisan db:seed --class=TestUsersSeeder
```

### Method 3: Use Artisan Command (Recommended)
```bash
# Create all test users
php artisan users:create-test

# Create with fresh cleanup (removes existing test users first)
php artisan users:create-test --fresh

# Create only specific user types
php artisan users:create-test --admin-only
php artisan users:create-test --organizer-only
php artisan users:create-test --user-only
```

## Test Credentials

| Role | Email | Password | Role Code |
|------|-------|----------|-----------|
| Admin | admin@greatticket.my | admin123 | a |
| Organizer | organizer@greatticket.my | organizer123 | o |
| User | user@greatticket.my | user123 | u |

## Features

### Admin User
- Full system access
- Can manage all users, events, and system settings
- Access to admin dashboard

### Organizer User
- Can create and manage events
- Has organizer profile with address and about information
- Can create packages, manage bookings
- Access to organizer dashboard

### Regular User
- Can browse events
- Can book tickets
- Can view their bookings
- Standard customer access

## Database Structure

### Users Table Fields:
- `name`: User's full name
- `email`: Unique email address
- `password`: Hashed password
- `role`: Enum('a', 'o', 'u') - Admin, Organizer, User
- `gender`: Enum('male', 'female', 'others')
- `religion_id`: Foreign key to religions table
- `dob`: Date of birth
- `contact`: Phone number
- `email_verified_at`: Email verification timestamp

### Organizer Profile:
Organizer users automatically get an organizer profile with:
- `address`: Business address
- `about`: Description of the organizer

## Development Tips

### For Local Development:
```bash
# Quick setup for testing
php artisan users:create-test --fresh
```

### For Production:
```bash
# Create only admin user for production
php artisan users:create-test --admin-only
```

### For Testing Different Roles:
1. Login as admin to test admin features
2. Login as organizer to test event creation
3. Login as user to test ticket booking

## Verification

After running the seeder, you can verify the users were created:

```bash
# Check users in database
php artisan tinker
>>> App\Models\User::where('email', 'like', '%@greatticket.my')->get(['name', 'email', 'role']);
```

Or check via your application's login page using the credentials above.

## Troubleshooting

### Issue: "Religion ID doesn't exist"
**Solution**: Make sure to run the ReligionSeeder first:
```bash
php artisan db:seed --class=ReligionSeeder
```

### Issue: "Users already exist"
**Solution**: Use the `--fresh` flag to clean up existing users:
```bash
php artisan users:create-test --fresh
```

### Issue: "Organizer profile not created"
**Solution**: The organizer profile is automatically created. Check if the relationship is properly defined in the User model.

## Security Notes

- These are test credentials for development only
- Change passwords in production
- The seeder includes `email_verified_at` to bypass email verification for testing
- All users are created with verified emails for immediate testing
