# World Cup Data Sync - Complete Guide

## 🎯 What This Does

This script syncs **ALL World Cup data** from Football-Data.org API:
- ✅ Teams (32 total)
- ✅ Matches (64 total with correct timings)
- ✅ Stores UTC times in database
- ✅ Displays IST times on frontend

---

## ⚡ Quick Start

### One-Click Setup
Visit this URL:
```
http://localhost/worldcupprediction-big/sync-worldcup-data.php
```

The script will:
1. Fetch all 32 teams
2. Fetch all 64 matches with timings
3. Store UTC times in database
4. Show beautiful progress output
5. Complete in 1-2 minutes

---

## 📊 What Gets Synced

### Teams (32)
- ✅ Brazil
- ✅ France
- ✅ Germany
- ✅ Spain
- ✅ ... and 27 more

### Matches (64)
- Group Stage: 24 matches
- Round of 16: 8 matches
- Quarterfinals: 4 matches
- Semifinals: 2 matches
- Final: 1 match
- 3rd Place: 1 match

---

## 🕐 Time Handling

### Database Storage
```
UTC Time (from API):
2026-06-15T15:00:00Z
↓
Stored in database as:
2026-06-15 15:00:00 (UTC)
```

### Frontend Display
```
Retrieved from database (UTC):
2026-06-15 15:00:00
↓
Converted to IST:
2026-06-15 20:30:00
↓
Displayed as:
Jun 15, 2026 - 20:30 IST
```

### Conversion Formula
```
UTC Time + 5 hours 30 minutes = IST
Example: 15:00 UTC + 5:30 = 20:30 IST
```

---

## 📋 Example Output

```
📡 Fetching teams from: https://api.football-data.org/v4/competitions/WC/teams
✅ Found 32 teams

✅ Brazil
✅ France
✅ Germany
... (and 29 more)

📡 Fetching matches from: https://api.football-data.org/v4/competitions/WC/matches
✅ Found 64 total matches

📅 June 15, 2026 (UTC: 2026-06-15) - 2 matches
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  Brazil vs Serbia (20:30 IST) ✅
  France vs Germany (23:00 IST) ✅

📊 Matches Summary:
   • Added: 64
   • Skipped: 0
   • Total: 64
   • Completed: 0
   • Upcoming: 64

✅ Next Steps:
   1. View matches: http://localhost/worldcupprediction-big/daily-matches
   2. Make predictions
```

---

## 🔄 Run Multiple Times?

**Yes, it's safe!** ✅

The script:
- Checks if data already exists
- Won't create duplicates
- Skips matches that are already in database
- Perfect for daily updates

---

## 📍 After Sync Complete

### 1. View Today's Matches
```
http://localhost/worldcupprediction-big/daily-matches
```
You'll see:
- All today's matches
- Times in IST
- Make Prediction buttons

### 2. Make a Prediction
- Click on a match
- See UTC time stored correctly
- Display shows IST time
- Make score & winner predictions

### 3. Check Your Stats
```
http://localhost/worldcupprediction-big/dashboard
```

### 4. View Leaderboard
```
http://localhost/worldcupprediction-big/leaderboard
```

---

## 🛠️ Technical Details

### API Endpoints Used

**Teams Endpoint:**
```
https://api.football-data.org/v4/competitions/WC/teams
```

**Matches Endpoint:**
```
https://api.football-data.org/v4/competitions/WC/matches
```

### Database Fields

**Matches Table:**
```sql
- api_match_id (from API)
- match_date (UTC time from API)
- home_team_id (foreign key)
- away_team_id (foreign key)
- stadium
- stage
- status
```

**Teams Table:**
```sql
- api_team_id (from API)
- name
- short_name
- country
- is_active
```

### Time Conversion

Frontend uses `formatMatchDate()` helper:
```php
// Database stores: 2026-06-15T15:00:00Z (UTC)
// Display shows: Jun 15, 2026 - 20:30 IST
// Conversion: UTC + 5:30 = IST
```

---

## ✨ Features

✅ Automatic team creation  
✅ All 64 matches with correct times  
✅ UTC storage, IST display  
✅ Duplicate prevention  
✅ Progress tracking  
✅ Error handling  
✅ Statistics summary  
✅ Safe to run multiple times  

---

## ⚠️ Troubleshooting

### "API Key Not Configured"
**Fix:** Add API key to `config/config.php`

### "HTTP 429"
**Meaning:** Too many requests  
**Fix:** Wait 5 minutes, then try again

### "No teams found"
**Check:**
- API key is valid
- Internet connection works
- API server is running

### "Database error"
**Check:**
- MySQL is running
- Database configured correctly
- All tables exist

---

## 🎯 What Happens Next

1. ✅ Teams synced to database
2. ✅ Matches synced with UTC times
3. ✅ Frontend displays IST times
4. ✅ Users can make predictions
5. ✅ Points awarded after match completion
6. ✅ Leaderboard updates automatically

---

## 📝 Time Examples

### Group Stage Match
```
API provides: 2026-06-15T15:00:00Z
Database stores: 2026-06-15 15:00:00
Frontend shows: Jun 15, 2026 - 20:30 IST
Predictions close: 20:25 IST (5 min before)
```

### Knockout Match
```
API provides: 2026-12-10T17:00:00Z
Database stores: 2026-12-10 17:00:00
Frontend shows: Dec 10, 2026 - 22:30 IST
Predictions close: 22:25 IST (5 min before)
```

### Final Match
```
API provides: 2026-12-19T15:00:00Z
Database stores: 2026-12-19 15:00:00
Frontend shows: Dec 19, 2026 - 20:30 IST
Predictions close: 20:25 IST (5 min before)
```

---

## 🚀 Ready?

Visit: `http://localhost/worldcupprediction-big/sync-worldcup-data.php`

And get all World Cup matches synced with perfect timing! ⚽

---

## 📞 Support

**Script location:**
```
/worldcupprediction-big/sync-worldcup-data.php
```

**View matches:**
```
/worldcupprediction-big/daily-matches
```

**Configuration:**
```
/config/config.php
```

**Documentation:**
```
/SYNC_DATA_GUIDE.md (this file)
```
