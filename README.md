# PredictCup - World Cup Prediction Platform

A complete football prediction platform built with PHP, MySQL, and Bootstrap 5.

## Features

### User Features
- ✅ User registration and login
- ✅ Match predictions with points system
- ✅ Private and public prediction rooms
- ✅ Global and room-specific leaderboards
- ✅ Achievement badges system
- ✅ Profile management
- ✅ Real-time notifications

### Admin Features
- ✅ Admin panel with dashboard
- ✅ Team management
- ✅ Match management and results entry
- ✅ User management
- ✅ Room management
- ✅ Statistics and analytics

## Technology Stack

- **Backend**: PHP 8+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Bootstrap 5, Custom CSS
- **Architecture**: MVC Pattern
- **Security**: PDO Prepared Statements, CSRF Protection, Password Hashing

## Installation

### Requirements
- XAMPP (Apache + MySQL + PHP 8+)
- Browser with JavaScript enabled

### Steps

1. **Download and Extract**
   - Copy all files to `C:\xampp\htdocs\predictcup`

2. **Start XAMPP Services**
   - Start Apache
   - Start MySQL

3. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create database: `predictcup_db`
   - Import `predictcup.sql` file

4. **Configure Database**
   - Database Host: `localhost`
   - Database Name: `predictcup_db`
   - Username: `root`
   - Password: (empty by default)

5. **Access Application**
   - Main Site: `http://localhost/predictcup`
   - Admin Panel: `http://localhost/predictcup/admin/login`

6. **Admin Login**
   - Email: `admin@predictcup.com`
   - Password: `password`

## Project Structure

```
predictcup/
├── public/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── uploads/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── MainController.php
│   │   ├── RoomController.php
│   │   ├── AdminController.php
│   │   ├── ApiController.php
│   │   ├── NotificationController.php
│   │   └── Router.php
│   ├── models/
│   │   ├── User.php
│   │   ├── Team.php
│   │   ├── Match.php
│   │   ├── Prediction.php
│   │   ├── Room.php
│   │   ├── Achievement.php
│   │   └── Notification.php
│   ├── helpers/
│   │   └── helpers.php
│   └── views/
│       ├── home.php
│       ├── dashboard.php
│       ├── auth/
│       ├── matches/
│       ├── rooms/
│       └── admin/
├── config/
│   ├── config.php
│   ├── database.php
│   └── routes.php
├── index.php
├── api.php
├── predictcup.sql
└── README.md
```

## Points System

- **Exact Score**: 5 points
- **Correct Winner**: 3 points
- **Correct Goal Difference**: 2 points
- **Bonus Streak**: 2 points
- **Maximum**: 10 points per match

## Security Features

- ✅ Password hashing using `password_hash()`
- ✅ CSRF protection tokens
- ✅ SQL injection prevention with PDO prepared statements
- ✅ XSS protection with input sanitization
- ✅ Session-based authentication
- ✅ Input validation

## Database Schema

The database includes tables for:
- `users` - User accounts and profiles
- `teams` - Football teams
- `matches` - Match fixtures and results
- `predictions` - User predictions with points
- `rooms` - Prediction rooms
- `room_members` - Room memberships
- `achievements` - Achievement badges
- `notifications` - User notifications
- `password_resets` - Password reset tokens
- `remember_tokens` - Remember me tokens

## Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a pull request

## License

This project is open source and available under the MIT License.

## Support

For issues and questions, please contact the development team.

## Acknowledgments

- Built with passion for football and web development
- Uses Bootstrap 5 for responsive design
- Powered by MySQL and PHP

---

**Enjoy PredictCup! ⚽**
