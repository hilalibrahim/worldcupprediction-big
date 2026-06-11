-- PredictCup - World Cup Prediction Platform Database Schema
-- Database: predictcup_db

CREATE DATABASE IF NOT EXISTS predictcup_db;
USE predictcup_db;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    country VARCHAR(50) DEFAULT 'Global',
    profile_picture VARCHAR(255) DEFAULT NULL,
    points INT DEFAULT 0,
    join_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    is_admin TINYINT(1) DEFAULT 0,
    verification_token VARCHAR(255) DEFAULT NULL,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_points (points)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admins table
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Teams table
CREATE TABLE teams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    api_team_id INT UNIQUE,
    name VARCHAR(100) NOT NULL,
    short_name VARCHAR(10) NOT NULL,
    country VARCHAR(50) NOT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    group_letter CHAR(1) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_country (country),
    INDEX idx_api_team_id (api_team_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Matches table
CREATE TABLE matches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    api_match_id INT UNIQUE,
    home_team_id INT NOT NULL,
    away_team_id INT NOT NULL,
    match_date DATETIME NOT NULL,
    stadium VARCHAR(100) DEFAULT NULL,
    stage VARCHAR(50) DEFAULT 'Group Stage',
    status VARCHAR(20) DEFAULT 'scheduled',
    home_score INT DEFAULT NULL,
    away_score INT DEFAULT NULL,
    is_locked TINYINT(1) DEFAULT 0,
    last_api_sync DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (home_team_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (away_team_id) REFERENCES teams(id) ON DELETE CASCADE,
    INDEX idx_match_date (match_date),
    INDEX idx_status (status),
    INDEX idx_home_away (home_team_id, away_team_id),
    INDEX idx_api_match_id (api_match_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Predictions table
CREATE TABLE predictions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    match_id INT NOT NULL,
    home_score INT NOT NULL,
    away_score INT NOT NULL,
    points INT DEFAULT 0,
    is_correct_winner TINYINT(1) DEFAULT 0,
    is_correct_diff TINYINT(1) DEFAULT 0,
    is_exact_score TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_match (user_id, match_id),
    INDEX idx_user (user_id),
    INDEX idx_match (match_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Rooms table
CREATE TABLE rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    is_public TINYINT(1) DEFAULT 1,
    password_hash VARCHAR(255) DEFAULT NULL,
    invite_code VARCHAR(10) NOT NULL UNIQUE,
    owner_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    max_members INT DEFAULT 50,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_invite_code (invite_code),
    INDEX idx_is_public (is_public)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Room members table
CREATE TABLE room_members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT NOT NULL,
    user_id INT NOT NULL,
    joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(20) DEFAULT 'member',
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_room_user (room_id, user_id),
    INDEX idx_room (room_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Achievements table
CREATE TABLE achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    badge_type VARCHAR(50) NOT NULL,
    points_required INT DEFAULT 0,
    image VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_badge_type (badge_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User achievements table
CREATE TABLE user_achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    achievement_id INT NOT NULL,
    earned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_achievement (user_id, achievement_id),
    INDEX idx_user (user_id),
    INDEX idx_achievement (achievement_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Notifications table
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    related_id INT DEFAULT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password resets table
CREATE TABLE password_resets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    is_used TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Remember tokens table
CREATE TABLE remember_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiry DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_expiry (expiry)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Room invitations table
CREATE TABLE room_invitations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT NOT NULL,
    inviter_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    email VARCHAR(100) NOT NULL,
    invite_code VARCHAR(10) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    accepted_at DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (inviter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_invite_code (invite_code),
    INDEX idx_status (status),
    INDEX idx_room (room_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User activities table
CREATE TABLE user_activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin
INSERT INTO users (username, email, password, country, is_admin, is_active, join_date) 
VALUES ('admin', 'admin@predictcup.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Global', 1, 1, NOW());

-- Insert sample teams (World Cup 2026)
INSERT INTO teams (name, short_name, country, group_letter) VALUES 
('Argentina', 'ARG', 'Argentina', 'A'),
('France', 'FRA', 'France', 'D'),
('Brazil', 'BRA', 'Brazil', 'G'),
('Germany', 'GER', 'Germany', 'E'),
('Spain', 'ESP', 'Spain', 'E'),
('Portugal', 'POR', 'Portugal', 'H'),
('England', 'ENG', 'England', 'B'),
('Belgium', 'BEL', 'Belgium', 'G'),
('Netherlands', 'NED', 'Netherlands', 'A'),
('Italy', 'ITA', 'Italy', 'I');

-- Insert sample achievements
INSERT INTO achievements (name, description, badge_type, points_required) VALUES 
('First Prediction', 'Make your first prediction', 'first_pred', 0),
('10 Correct Predictions', 'Get 10 predictions correct', 'ten_correct', 0),
('25 Correct Predictions', 'Get 25 predictions correct', 'twenty_five_correct', 0),
('Prediction Master', 'Reach 500 total points', 'master', 500),
('Goal Guru', 'Correctly predict 50 exact scores', 'guru', 0),
('Champion Predictor', 'Predict the World Cup winner correctly', 'champion', 0);
-- Add prediction_type column to predictions table
ALTER TABLE predictions ADD COLUMN prediction_type ENUM('winner', 'score') DEFAULT 'score' AFTER away_score;
ALTER TABLE predictions ADD COLUMN predicted_winner ENUM('home', 'draw', 'away') DEFAULT NULL AFTER prediction_type;