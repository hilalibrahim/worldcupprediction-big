# 🔍 Verification & Troubleshooting Guide

**Purpose**: Verify everything is working correctly and troubleshoot common issues

---

## ✅ VERIFICATION CHECKLIST

### 1. Database Connection

**Test**: Can the application connect to database?

**How to Check**:
```
1. Go to: http://localhost/worldcupprediction-big/test.php
2. Should show: "✓ Database connection successful"
3. If error: Check config/config.php database settings
```

**Database Test Query**:
```sql
-- Check from command line
mysql -u root -p predictcup_db
SHOW TABLES;
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM teams;
SELECT COUNT(*) FROM matches;
SELECT COUNT(*) FROM predictions;
```

---

### 2. Timezone Configuration

**Test**: Is IST (Asia/Kolkata) correctly configured?

**How to Check**:
```php
// Add to any PHP file and view
<?php
date_default_timezone_set('Asia/Kolkata');
echo date('Y-m-d H:i:s e');  // Should show Asia/Kolkata
echo date_default_timezone_get();  // Should return Asia/Kolkata
?>
```

**Expected Output**:
```
2026-06-12 12:30:45 Asia/Kolkata
Asia/Kolkata
```

**If Wrong**:
```
1. Open: config/config.php
2. Line 32: define('DEFAULT_TIMEZONE', 'Asia/Kolkata');
3. Verify it's set correctly
4. Clear browser cache
5. Restart Apache
```

---

### 3. Prediction Form Display

**Test**: Does the prediction form show with correct timezone?

**Steps**:
```
1. Go to: http://localhost/worldcupprediction-big/daily-matches
2. Click any match
3. Should see:
   - Match time in IST (e.g., "Jun 15, 2026 - 20:30 IST")
   - Score input fields (home and away)
   - Winner prediction radio buttons (Home/Draw/Away)
   - "Submit Both Predictions" button
4. If 5 min before match: Should see "Predictions Locked" message instead
```

**Correct Display**:
```
Match Details:
Jun 15, 2026 - 20:30 IST
Argentina vs France @ San Diego Stadium

Make Your Prediction:
🎯 Exact Score (10 pts if correct)
  Argentina: [ 2 ] - [ 1 ] France

👑 Who Wins? (5 pts if correct)
  ○ Argentina Wins   ○ Draw   ○ France Wins
  
  [Submit Both Predictions]
```

---

### 4. Cutoff Timer

**Test**: Is the countdown timer working?

**Steps**:
```
1. Go to: http://localhost/worldcupprediction-big/daily-matches
2. Click any upcoming match (not < 5 min from now)
3. Should see: "⏰ Predictions close in: X minutes"
4. Refresh page - number should decrease
5. When < 5 minutes: Form should show "🔒 Predictions Locked"
```

**Expected Behavior**:
```
Time to Match: 30 minutes
Display: "⏰ Predictions close in: 25 minutes" (5 min cutoff)

Time to Match: 5 minutes
Display: "⏰ Predictions close in: 0 minutes"

Time to Match: 3 minutes
Display: "🔒 Predictions Locked" (form hidden)
```

---

### 5. Prediction Submission

**Test**: Can you submit a prediction?

**Steps**:
```
1. Login as a user
2. Go to upcoming match
3. Enter score (e.g., 2-1)
4. Select winner (e.g., Home)
5. Click "Submit Both Predictions"
6. Should see success: "✓ Your Prediction Submitted"
```

**Success Indicators**:
```
✓ Page shows: "Your Prediction Submitted"
✓ Score displays: "2 - 1"
✓ Winner shows: "Argentina Wins"
✓ Cannot modify prediction
✓ Cannot submit again
```

**If Failed**:
```
Error: "Prediction already exists"
→ Already predicted for this match

Error: "Predictions are locked"
→ Within 5 minutes of kickoff

Error: "Match not found"
→ Match ID issue (check URL)

Error: Database error
→ Check config/config.php database settings
```

---

### 6. Database Schema Verification

**Test**: Are all required columns present?

**Run in MySQL**:
```sql
-- Check predictions table structure
DESC predictions;

-- Should show columns:
id              INT
user_id         INT
match_id        INT
home_score      INT
away_score      INT
points          INT
is_correct_winner TINYINT
is_correct_diff TINYINT
is_exact_score  TINYINT
prediction_type ENUM('winner','score','both')
predicted_winner ENUM('home','draw','away')
created_at      DATETIME
updated_at      DATETIME

-- Check unique constraint
SHOW INDEX FROM predictions;
-- Should show unique_user_match (user_id, match_id)
```

**If Missing Columns**:
```sql
ALTER TABLE predictions ADD COLUMN prediction_type ENUM('winner','score','both') DEFAULT 'both';
ALTER TABLE predictions ADD COLUMN predicted_winner ENUM('home','draw','away') DEFAULT NULL;
```

---

### 7. API Integration Test

**Test**: Can the app connect to Football-Data.org API?

**Steps**:
```
1. Go to: http://localhost/worldcupprediction-big/test-api-direct.php
2. Should show:
   - "✓ Connected to Football-Data.org API"
   - First 5 matches listed with times
   - Times shown in ISO format (UTC)
```

**Expected Output**:
```
✓ Connected to Football-Data.org API

Match 1: Argentina vs France
UTC Date: 2026-06-15T15:00:00Z

Match 2: Brazil vs Germany
UTC Date: 2026-06-15T18:00:00Z
...
```

**If API Fails**:
```
Error: "API key not configured"
→ Check config/config.php line 48

Error: "HTTP 403" or "HTTP 401"
→ Invalid API key - get new one from football-data.org

Error: "Connection timeout"
→ Check internet connection
→ Check firewall rules
```

---

### 8. Data Sync Script

**Test**: Can you load all World Cup matches?

**Steps**:
```
1. Go to: http://localhost/worldcupprediction-big/sync-worldcup-data.php
2. Should show detailed progress:
   - Fetching teams...
   - Found 32 teams
   - Fetching matches...
   - Found 64 matches
   - Teams synced: X added
   - Matches synced: Y added
3. Result: "✅ All World Cup matches are now loaded"
```

**Verify in Database**:
```sql
SELECT COUNT(*) FROM teams;      -- Should be 32
SELECT COUNT(*) FROM matches;    -- Should be 64
SELECT * FROM matches LIMIT 1;   -- Check time is UTC
```

**If Issues**:
```
Error: "API key not configured"
→ Same as API test above

Error: Script doesn't run
→ Check browser console for errors
→ Check Apache error log

Error: Partial data loaded
→ Script is safe to run multiple times
→ Will skip duplicates
→ Run again to complete
```

---

### 9. Time Format Verification

**Test**: Are times stored as UTC and displayed as IST?

**Database Check**:
```sql
SELECT id, match_date FROM matches LIMIT 1;
-- Should show: 2026-06-15 15:00:00 (UTC format)
```

**Display Check**:
```
Go to: http://localhost/worldcupprediction-big/daily-matches
Should show: "Jun 15, 2026 - 20:30 IST"
(That's UTC 15:00 + 5:30 = IST 20:30)
```

**Time Calculation**:
```
UTC:  2026-06-15T15:00:00Z
IST:  2026-06-15T20:30:00  (UTC + 5:30)
Display: Jun 15, 2026 - 20:30 IST ✓
```

---

### 10. Leaderboard Update

**Test**: Does leaderboard update correctly after predictions?

**Steps**:
```
1. Go to: http://localhost/worldcupprediction-big/leaderboard
2. Note your current points
3. Go to a completed match (or wait for one to complete)
4. Check if your points increased
5. Check if rank changed
```

**Expected Result**:
```
Before prediction: 0 points, Rank: 100000
Submit prediction: Exact score correct
After match: 10 points, Rank: Much higher

Points shown correctly on:
- Dashboard
- Leaderboard
- User profile
```

---

## 🐛 TROUBLESHOOTING GUIDE

### Issue: "No matches showing in daily matches"

**Cause**: Database is empty  
**Solution**:
```
1. Go to: http://localhost/worldcupprediction-big/sync-worldcup-data.php
2. Wait for script to complete
3. Go back to daily matches
4. Matches should now appear
```

**Alternative Fix**:
```sql
-- Manual check
SELECT COUNT(*) FROM matches;
-- If 0, then sync script didn't work

-- Check if API key is set
SELECT * FROM config;  -- (if config stored in DB)
-- Or check config/config.php
```

---

### Issue: Times showing wrong timezone

**Cause**: formatMatchDate() not called or wrong timezone  
**Solution**:
```
1. Check config/config.php line 32
2. Should be: define('DEFAULT_TIMEZONE', 'Asia/Kolkata');
3. Check app/helpers/helpers.php formatMatchDate() function
4. Ensure it converts to Asia/Kolkata timezone
```

**Manual Fix** (if needed):
```php
// In config/config.php - ensure this is set
date_default_timezone_set('Asia/Kolkata');

// In helpers/helpers.php - ensure this function exists
function formatMatchDate($date) {
    if (empty($date)) return 'TBD';
    $dateObj = new DateTime($date, new DateTimeZone('Asia/Kolkata'));
    return $dateObj->format('M d, Y - H:i') . ' IST';
}
```

---

### Issue: Predictions locked immediately

**Cause**: Cutoff time calculation wrong or current time wrong  
**Solution**:
```
1. Check server time: echo date('Y-m-d H:i:s');
2. Check match time in database
3. If server time is ahead of match time, predictions will be locked
4. Set server time correctly
```

**Verify Cutoff**:
```php
// Add this to any page for debugging
$matchTime = "2026-06-15 20:30:00";  // IST time
echo isMatchLocked($matchTime) ? "LOCKED" : "OPEN";

// Or check time remaining
$remaining = getPredictionTimeRemaining($matchTime);
echo "Time remaining: " . $remaining . " minutes";
```

---

### Issue: Cannot submit predictions - "Already exists"

**Cause**: Already predicted for this match  
**Solution**:
```
This is expected behavior - one prediction per match

If user wants to change:
1. Currently cannot (one prediction enforced)
2. Could delete and re-submit (if allowed)
3. Contact admin to manually delete prediction
```

**Check Existing Prediction**:
```sql
SELECT * FROM predictions 
WHERE user_id = 1 AND match_id = 5;
-- If exists, user already predicted
```

---

### Issue: "Predictions are locked" - but match not for 2 hours

**Cause**: Timezone mismatch or wrong time in database  
**Solution**:
```
1. Check database match_date: 
   SELECT match_date FROM matches WHERE id = 5;
   
2. Check if time is UTC:
   Should be: 2026-06-15 15:00:00 (not IST)
   
3. If showing IST time in database, convert it
   UPDATE matches SET match_date = UTC_TIMESTAMP() + INTERVAL 5 HOURS 30 MINUTES;
```

---

### Issue: Admin panel not accessible

**Cause**: Not logged in as admin or wrong credentials  
**Solution**:
```
1. Check you're logged in: See username in top right
2. Verify you're admin:
   SELECT is_admin FROM users WHERE username = 'yourname';
   -- Should return 1
   
3. If not admin, make yourself admin:
   UPDATE users SET is_admin = 1 WHERE username = 'yourname';
   
4. Login again with admin@predictcup.com / password
```

---

### Issue: Points not calculated after match completes

**Cause**: Match status not updated or calculation failed  
**Solution**:
```
1. Check match status:
   SELECT status FROM matches WHERE id = 5;
   -- Should be 'finished' or 'completed'
   
2. If still 'scheduled', manually update:
   UPDATE matches SET status = 'finished', 
                      home_score = 2, 
                      away_score = 1 
   WHERE id = 5;
   
3. Manually calculate points:
   SELECT * FROM predictions WHERE match_id = 5;
   -- Check if points column is filled
   
4. If empty, run points calculation manually
```

---

### Issue: API connection fails with "HTTP 403"

**Cause**: Invalid or expired API key  
**Solution**:
```
1. Get new API key from: https://www.football-data.org
2. Update config/config.php:
   define('FOOTBALL_DATA_API_KEY', 'YOUR_NEW_KEY_HERE');
3. Clear browser cache
4. Try again
```

---

### Issue: Database connection error

**Cause**: Wrong database credentials or database doesn't exist  
**Solution**:
```
1. Check config/config.php:
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'predictcup_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   
2. Verify database exists:
   mysql -u root
   SHOW DATABASES;
   -- Should show predictcup_db
   
3. If not exists, create it:
   CREATE DATABASE predictcup_db;
   
4. Apply schema:
   mysql -u root predictcup_db < predictcup.sql
```

---

### Issue: Form shows but can't click buttons

**Cause**: JavaScript not loaded or CSS issue  
**Solution**:
```
1. Check browser console (F12 > Console)
2. Look for JavaScript errors
3. Clear browser cache (Ctrl+Shift+Delete)
4. Hard refresh page (Ctrl+F5)
5. Check public/js/main.js exists
6. Check public/css/style.css exists
```

---

### Issue: "CSRF token verification failed"

**Cause**: Session expired or token not generated  
**Solution**:
```
1. Logout and login again
2. Check that cookies are enabled
3. Check session configuration in config/config.php
4. Verify session directory is writable:
   ls -la /tmp  (or your session dir)
```

---

## 📊 DIAGNOSTIC QUERIES

### Check All Matches Loaded
```sql
SELECT COUNT(*) as total_matches,
       SUM(CASE WHEN status = 'scheduled' THEN 1 ELSE 0 END) as scheduled,
       SUM(CASE WHEN status = 'live' THEN 1 ELSE 0 END) as live,
       SUM(CASE WHEN status = 'finished' THEN 1 ELSE 0 END) as finished
FROM matches;

-- Expected:
-- total_matches: 64
-- scheduled: Most (not started yet)
-- live: 0 or a few
-- finished: Some (already played)
```

### Check Today's Matches
```sql
SELECT COUNT(*) as todays_matches
FROM matches
WHERE DATE(match_date) = CURDATE();

-- Should return 2-4 (usually 2-4 per day during group stage)
```

### Check User Predictions
```sql
SELECT u.username, COUNT(p.id) as predictions, SUM(p.points) as total_points
FROM users u
LEFT JOIN predictions p ON u.id = p.user_id
GROUP BY u.id
ORDER BY total_points DESC
LIMIT 10;

-- Shows leaderboard
```

### Check Predictions Per Match
```sql
SELECT m.id, 
       CONCAT(h.name, ' vs ', a.name) as match,
       COUNT(p.id) as prediction_count,
       AVG(p.points) as avg_points
FROM matches m
LEFT JOIN predictions p ON m.id = p.match_id
LEFT JOIN teams h ON m.home_team_id = h.id
LEFT JOIN teams a ON m.away_team_id = a.id
GROUP BY m.id
ORDER BY prediction_count DESC
LIMIT 10;

-- Shows which matches have most predictions
```

---

## 🔧 QUICK FIXES CHECKLIST

- [ ] Check timezone: `config/config.php` line 32
- [ ] Check API key: `config/config.php` line 48
- [ ] Check database credentials: `config/config.php` lines 9-13
- [ ] Run sync script: `sync-worldcup-data.php`
- [ ] Verify database tables exist: `SHOW TABLES;`
- [ ] Clear browser cache: Ctrl+Shift+Delete
- [ ] Check server time is correct
- [ ] Verify .htaccess is working (URLs routing)
- [ ] Check error logs in Apache
- [ ] Restart Apache service

---

## ✨ EVERYTHING IS WORKING IF...

✅ **You see all of this**:
1. Home page loads with matches
2. Timezone shows as IST (e.g., "20:30 IST")
3. Can register and login
4. Can make predictions (when not locked)
5. Can see countdown timer
6. Matches lock 5 minutes before kickoff
7. Leaderboard shows users and points
8. Admin can sync matches from API
9. No JavaScript errors in console
10. Database has 32 teams and 64 matches

**Congratulations! System is working! 🎉**

---

## 📞 SUPPORT RESOURCES

**Documentation**:
- SYSTEM_STATUS_REPORT.md - Complete overview
- IMPLEMENTATION_COMPLETE.md - What was built
- SYNC_DATA_GUIDE.md - How to sync matches
- API_TEST_GUIDE.md - Testing API connection
- QUICK_REFERENCE.md - Developer reference

**Test Scripts**:
- `test.php` - Database connection test
- `test-api-direct.php` - API connection test
- `sync-worldcup-data.php` - Load all matches
- `phpinfo.php` - PHP information

**Database**:
- `predictcup.sql` - Schema (apply if needed)
- `config/database.php` - Connection class

**Admin Panel**:
- URL: http://localhost/worldcupprediction-big/admin
- Username: admin@predictcup.com
- Password: password

---

**System is ready for production! 🚀**

