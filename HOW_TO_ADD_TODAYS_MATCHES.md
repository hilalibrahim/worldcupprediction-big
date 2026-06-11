# How to Add Today's Matches to PredictCup

## Problem
You don't see today's matches on the home page or daily matches page.

## Solution

There are **2 ways** to add today's matches:

---

## Method 1: Automatic Script (Recommended) ⭐

### Step 1: Run the Fetch Script
Visit this URL in your browser:
```
http://localhost/worldcupprediction-big/fetch-todays-matches.php
```

**What happens:**
- ✅ Fetches today's World Cup matches from Football-Data.org API
- ✅ Automatically adds them to database
- ✅ Creates teams if they don't exist
- ✅ Shows you what was added

### Step 2: View Your Matches
After running the script, go to:
```
http://localhost/worldcupprediction-big/daily-matches
```

You should now see today's matches!

---

## Method 2: Manual Admin Panel

### Step 1: Login to Admin
1. Go to: `http://localhost/worldcupprediction-big/admin`
2. Login with:
   - **Email:** admin@predictcup.com
   - **Password:** password

### Step 2: Go to Matches
Click on "Matches" in the admin sidebar

### Step 3: Add Matches from API
1. Scroll down to "Available Matches from API"
2. Click "Refresh API Matches"
3. Select today's matches
4. Click "Add Match"

---

## Troubleshooting

### Issue: "API Not Configured"
**Solution:** Check if API key is in `config/config.php`

Run the fetch script - it will tell you if API key is missing.

### Issue: No matches appear even after fetching
**Possible causes:**
1. No World Cup matches scheduled for today
2. API might not have data for today yet
3. Database connection issue

**Check:**
- Visit: `http://localhost/worldcupprediction-big/fetch-todays-matches.php`
- Read the error message
- Check if there are real World Cup matches today

### Issue: Teams not created
**Solution:** The fetch script automatically creates teams. If it doesn't work, manually add teams in admin panel:
1. Admin → Teams → Add Team
2. Enter team name and country

---

## What Gets Fetched

The fetch script gets:
- ✅ Match ID from API
- ✅ Home team name
- ✅ Away team name
- ✅ Match date & time (in IST)
- ✅ Stadium
- ✅ Match stage
- ✅ Match status

---

## How Often to Fetch

**Recommended:**
- Once per day before first match
- Or whenever you want to update match list
- Script won't add duplicates (safe to run multiple times)

**Automatic:**
- Set up cron job to run: `fetch-todays-matches.php`
- Add to cron: `php /path/to/fetch-todays-matches.php`

---

## Quick Steps Summary

1. **Visit:** `http://localhost/worldcupprediction-big/fetch-todays-matches.php`
2. **Wait:** For script to complete
3. **Check:** Go to daily-matches page
4. **Enjoy:** Make your predictions!

---

## File Location
Script: `/worldcupprediction-big/fetch-todays-matches.php`

---

## Need Help?

Check these files:
- `config/config.php` - Verify API key is set
- `fetch-todays-matches.php` - Run this to load matches
- `app/views/matches/daily.php` - This shows the matches

---

**That's it! Your matches should now be visible.** ⚽
