# Fetch ALL World Cup Matches - Quick Guide

## 🎯 What This Does

This script fetches **ALL World Cup matches** from Football-Data.org API and automatically adds them to your database.

---

## ⚡ Quick Start (2 Steps)

### Step 1: Run the Script
Visit this URL in your browser:
```
http://localhost/worldcupprediction-big/fetch-all-worldcup-matches.php
```

### Step 2: View Your Matches
Go to:
```
http://localhost/worldcupprediction-big/daily-matches
```

**That's it!** All matches are now loaded. ⚽

---

## 📊 What Gets Loaded

✅ **All World Cup matches** for the entire tournament  
✅ **Match times** in IST (Indian Standard Time)  
✅ **Teams** automatically created  
✅ **Stages** (Group Stage, Knockouts, etc.)  
✅ **Stadiums** for each match  

---

## 📋 What You'll See

The script shows:
- 📅 Matches grouped by date
- ✅ Successfully added matches
- ⏭️ Skipped matches (already exist)
- ❌ Any errors encountered
- 📊 Final summary with totals

Example output:
```
📅 June 15, 2026 (2 matches)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  Brazil vs France at 20:30 IST ✅
  Germany vs Spain at 22:45 IST ✅

✅ Added: 64 matches
📊 Total Matches: 64
```

---

## 🔄 Run Multiple Times?

**It's safe to run multiple times!** ✅
- Won't add duplicate matches
- Automatically skips existing matches
- Perfect for updating the list

---

## 📱 After Loading Matches

### 1. View Today's Matches
```
http://localhost/worldcupprediction-big/daily-matches
```

### 2. Make a Prediction
- Click on a match
- Fill in your prediction
- Score prediction: 10 pts if exact
- Winner prediction: 5 pts if correct

### 3. View Your Stats
```
http://localhost/worldcupprediction-big/dashboard
```

### 4. Check Leaderboard
```
http://localhost/worldcupprediction-big/leaderboard
```

### 5. Create a Room
```
http://localhost/worldcupprediction-big/rooms
```

---

## ⚠️ Troubleshooting

### Issue: "API Key Not Configured"
**Fix:** 
1. Go to `config/config.php`
2. Get API key from: https://www.football-data.org
3. Add to config: `define('FOOTBALL_DATA_API_KEY', 'your-key-here');`

### Issue: "API returned HTTP 429"
**Meaning:** Too many requests  
**Fix:** Wait a few minutes before running again

### Issue: "No matches found"
**Possible causes:**
- API key is invalid
- World Cup season not active
- API temporarily down

**Solution:**
- Check API key
- Visit: https://www.football-data.org to verify API status

### Issue: Database error
**Check:**
- Is MySQL running?
- Is database configured correctly?
- Check `config/database.php`

---

## 📊 How Many Matches?

World Cup 2026:
- **64 total matches** in tournament
- **8 groups** of 4 teams = 24 group matches
- **8 Round of 16** matches = 8 matches
- **4 Quarterfinals** = 4 matches
- **2 Semifinals** = 2 matches
- **1 Final** = 1 match
- **3rd Place Match** = 1 match

Total: 64 matches ⚽

---

## 🕐 Time Display

All times shown in **IST** (Indian Standard Time):
- UTC 15:00 → IST 20:30 (UTC +5:30)
- UTC 17:30 → IST 23:00
- UTC 12:00 → IST 17:30

---

## 🔐 Prediction Cutoff

Users can predict until **5 minutes before match start**:
- Match at 20:30 IST
- Predictions close at 20:25 IST
- After 20:25: Form locked ❌

---

## 📝 Script Features

✅ Fetches from Football-Data.org  
✅ Converts times to IST  
✅ Creates teams automatically  
✅ Prevents duplicate matches  
✅ Groups matches by date  
✅ Shows progress  
✅ Reports statistics  
✅ Safe to run multiple times  

---

## 🎯 Next Steps

1. ✅ Run fetch-all-worldcup-matches.php
2. ✅ Go to daily-matches
3. ✅ Make your first prediction
4. ✅ Invite friends to play
5. ✅ Climb the leaderboard

---

## 📞 Support

**Script location:**
```
/worldcupprediction-big/fetch-all-worldcup-matches.php
```

**View matches:**
```
/worldcupprediction-big/daily-matches
```

**Admin panel:**
```
/admin (admin@predictcup.com / password)
```

---

**Happy predicting!** ⚽🎯
