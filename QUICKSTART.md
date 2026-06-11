# PredictCup - Quick Start Guide

## Prerequisites
- XAMPP installed with Apache and MySQL

## Installation Steps (3 Minutes)

### 1. Start XAMPP
Open XAMPP Control Panel and start:
- **Apache** 
- **MySQL**

### 2. Create Database
1. Open browser → `http://localhost/phpmyadmin`
2. Click "New" button
3. Database name: `predictcup_db`
4. Click "Create"

### 3. Import Database
1. Select `predictcup_db` database
2. Click "Import" tab
3. Click "Choose File" → Select `predictcup.sql` from project folder
4. Click "Go"

### 4. Access Application
Open browser and go to:
```
http://localhost/worldcupprediction-big
```

### 5. Admin Login
- URL: `http://localhost/worldcupprediction-big/admin/login`
- Email: `admin@predictcup.com`
- Password: `password`

### 6. Register Your Account
Click "Register Now" on homepage and create your user account!

## What You Can Do Now

### As User:
- ✅ Make match predictions
- ✅ Join prediction rooms
- ✅ Compete on leaderboards
- ✅ Earn achievement badges
- ✅ View your profile and stats

### As Admin:
- ✅ Manage teams
- ✅ Add/edit matches
- ✅ Enter match results
- ✅ Manage users
- ✅ View statistics

## Points System
- **Exact Score**: 5 points
- **Correct Winner**: 3 points
- **Correct Goal Difference**: 2 points
- **Maximum per match**: 10 points

## Files Location
```
Project: C:\xampp\htdocs\worldcupprediction-big\
SQL File: predictcup.sql
Config: config/config.php
```

## Troubleshooting

**Database connection error?**
- Check MySQL is running in XAMPP
- Database name must be `predictcup_db`

**404 error?**
- Make sure Apache is running
- Check `.htaccess` file exists

**Need help?**
- Run: `http://localhost/worldcupprediction-big/verify-install.php`

---

**Happy Predicting! ⚽**
