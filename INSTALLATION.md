# PredictCup Installation Guide

## Requirements

- **XAMPP** (Apache + MySQL + PHP 8+)
- **Browser** with JavaScript enabled

## Installation Steps

### Step 1: Start XAMPP Services

1. Open XAMPP Control Panel
2. Click "Start" for **Apache**
3. Click "Start" for **MySQL**

### Step 2: Create Database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on "New" in the left sidebar
3. Database name: `predictcup_db`
4. Collation: `utf8mb4_general_ci`
5. Click "Create"

### Step 3: Import Database Schema

1. In phpMyAdmin, select the `predictcup_db` database
2. Click on "Import" tab
3. Click "Choose File" and select `predictcup.sql` from the project folder
4. Click "Go" to import

### Step 4: Verify Database Configuration

The database configuration is already set in `config/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'predictcup_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

For XAMPP, the default credentials are:
- **Host**: localhost
- **Username**: root
- **Password**: (empty)

### Step 5: Access the Application

Open your browser and navigate to:

```
http://localhost/worldcupprediction-big
```

Or if your folder name is different:
```
http://localhost/your-folder-name
```

### Step 6: Admin Login

After installation, log in as admin:

- **URL**: `http://localhost/worldcupprediction-big/admin/login`
- **Email**: `admin@predictcup.com`
- **Password**: `password`

### Step 7: Register First User

1. Click "Register Now" on the homepage
2. Fill in the registration form
3. Start predicting!

## Default Database Contents

The `predictcup.sql` file includes:

### Admin Account
- Email: `admin@predictcup.com`
- Password: `password`
- Username: `admin`

### Sample Teams (World Cup 2022)
- Argentina
- France
- Brazil
- Germany
- Spain
- Portugal
- England
- Belgium
- Netherlands
- Italy

### Achievement Badges
- First Prediction
- 10 Correct Predictions
- 25 Correct Predictions
- Prediction Master (500 points)
- Goal Guru (50 exact scores)
- Champion Predictor

## Troubleshooting

### Database Connection Error

If you see a database connection error:

1. Check if MySQL is running in XAMPP
2. Verify database name: `predictcup_db`
3. Check username: `root`
4. Check password: (should be empty for XAMPP default)

### 404 Error

If you see a 404 error:

1. Make sure Apache is running
2. Check if the folder is in `C:\xampp\htdocs\`
3. Verify `.htaccess` file exists in the project root

### Session Issues

If you have session issues:

1. Check if `session.save_path` is writable
2. Clear browser cookies
3. Restart Apache

### Permission Issues

If you get permission errors:

1. Make sure the project folder has read/write permissions
2. Check uploads directory: `public/uploads/`

## Post-Installation Setup

### Add More Teams

1. Go to Admin Panel
2. Navigate to Team Management
3. Click "Add Team"
4. Enter team details

### Add Matches

1. Go to Admin Panel
2. Navigate to Match Management
3. Click "Add Match"
4. Select teams and set match date
5. Enter results after match completion

### Create Prediction Rooms

1. Go to Dashboard
2. Click "Create Room"
3. Set room name and settings
4. Share invite code with friends

## Features Overview

### For Users
- ✅ Register/Login
- ✅ Make predictions
- ✅ Join/create rooms
- ✅ View leaderboards
- ✅ Earn achievements
- ✅ Update profile

### For Admins
- ✅ Manage teams
- ✅ Manage matches
- ✅ Enter results
- ✅ Manage users
- ✅ Manage rooms
- ✅ View statistics

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review the README.md
3. Contact the development team

---

**Enjoy PredictCup! ⚽**
