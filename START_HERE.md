# 🏆 START HERE - PredictCup World Cup Prediction Platform

**Welcome to PredictCup!** This guide will get you up and running in minutes.

---

## ⚡ QUICK START (5 Minutes)

### Step 1: Load World Cup Matches (1 min)
```
1. Open browser: http://localhost/worldcupprediction-big/sync-worldcup-data.php
2. Wait for script to complete
3. You should see: "✅ All World Cup matches are now loaded"
```

### Step 2: Create Your Account (1 min)
```
1. Go to: http://localhost/worldcupprediction-big/register
2. Enter:
   - Email: your@email.com
   - Password: Password123 (min 8 chars, must have uppercase, lowercase, number)
   - Country: India (or your country)
3. Click "Register"
4. You're logged in!
```

### Step 3: View Today's Matches (1 min)
```
1. Go to: http://localhost/worldcupprediction-big/daily-matches
2. You should see all matches with:
   - Match names (e.g., "Argentina vs France")
   - Times in IST (e.g., "20:30 IST")
   - "Make Prediction" buttons
```

### Step 4: Make Your First Prediction (2 min)
```
1. Click any match
2. You'll see a form with two sections:
   
   🎯 EXACT SCORE (10 points if correct)
   Argentina: [2] - [1] France
   
   👑 WHO WINS? (5 points if correct)
   ○ Argentina Wins  ○ Draw  ○ France Wins

3. Fill in your predictions:
   - Enter score
   - Select winner
4. Click "Submit Both Predictions"
5. Done! Your prediction is saved
```

### Step 5: Check Your Points
```
1. Go to: http://localhost/worldcupprediction-big/dashboard
2. See your points and rank
3. After matches complete, you'll see points awarded
```

---

## 📚 KEY FEATURES

### ✅ Indian Timezone (IST)
- All times shown in **IST (Asia/Kolkata, UTC+5:30)**
- Example: Match at UTC 15:00 displays as **20:30 IST**
- Times stored in database as UTC, displayed as IST

### ✅ 5-Minute Prediction Cutoff
- You can predict until **5 minutes before match starts**
- After that: **Predictions are locked**
- You'll see countdown timer showing time remaining

### ✅ Dual Predictions (Score + Winner)
- Submit **two predictions together**:
  - Exact score (e.g., 2-1) → 10 points
  - Winner prediction (e.g., Draw) → 5 points
- One prediction per match (cannot change after submit)

### ✅ Points System
```
Exact score correct:      10 points
Winner prediction correct: 5 points
Neither correct:          0 points
```

### ✅ Leaderboard & Rankings
- Global ranking by total points
- Updated after each match completes
- See top predictors

---

## 🎮 USER EXPERIENCE TIMELINE

### Before Match (More than 5 minutes)
```
✓ Form visible
✓ Countdown shows: "Predictions close in: 45 minutes"
✓ You can submit prediction
```

### 5 Minutes Before Match
```
✓ Countdown shows: "Predictions close in: 0 minutes"
✓ Form disappears
✓ Message shows: "🔒 Predictions Locked"
✓ You CANNOT submit now
```

### During Match
```
✓ Form hidden
✓ Match is "LIVE"
✓ Cannot make predictions
```

### After Match Completes
```
✓ Final score appears
✓ Your prediction displayed
✓ Points calculated and awarded
✓ Leaderboard updated
✓ Can see top 20 predictors for that match
```

---

## 📊 EXAMPLES

### Example 1: Exact Score Correct

**Match**: Argentina vs France  
**Actual Result**: Argentina 3, France 2  
**Your Prediction**: 3-2, Argentina Wins  

**Result**:
```
Exact score (3-2):    ✓ CORRECT  →  10 points
Winner (Argentina):   ✓ CORRECT  →  Already counted in exact score
Total:                                10 points
```

### Example 2: Winner Only Correct

**Match**: Brazil vs Germany  
**Actual Result**: Brazil 2, Germany 2 (Draw)  
**Your Prediction**: 1-1, Draw  

**Result**:
```
Exact score (1-1):    ✗ WRONG  →  0 points
Winner (Draw):        ✓ CORRECT  →  5 points
Total:                              5 points
```

### Example 3: Neither Correct

**Match**: Spain vs Portugal  
**Actual Result**: Spain 1, Portugal 2  
**Your Prediction**: 2-2, Spain Wins  

**Result**:
```
Exact score (2-2):    ✗ WRONG  →  0 points
Winner (Spain):       ✗ WRONG  →  0 points
Total:                             0 points
```

---

## 🏠 MAIN PAGES

### For Users

**Home**: `http://localhost/worldcupprediction-big`
- Latest matches
- Top predictors
- Quick links

**Dashboard**: `http://localhost/worldcupprediction-big/dashboard`
- Your points and rank
- Your predictions
- Upcoming matches
- Achievements

**Daily Matches**: `http://localhost/worldcupprediction-big/daily-matches`
- All today's matches
- Quick predictions
- Current countdown timers

**Match Detail**: `http://localhost/worldcupprediction-big/match/[ID]`
- Full match info
- Make prediction
- See top 20 predictors (after match completes)

**Leaderboard**: `http://localhost/worldcupprediction-big/leaderboard`
- Global rankings
- See where you stand
- Compare with others

**Profile**: `http://localhost/worldcupprediction-big/profile`
- Your information
- Your stats
- Your achievements
- Change password

### Admin Pages

**Admin Login**: `http://localhost/worldcupprediction-big/admin`
- Email: admin@predictcup.com
- Password: password

**Admin Dashboard**: `http://localhost/worldcupprediction-big/admin/dashboard`
- System statistics
- Manage teams
- Manage matches
- Manage users
- Sync API data

---

## 🔐 ACCOUNT SECURITY

### Password Requirements
- Minimum 8 characters
- At least 1 uppercase letter (A-Z)
- At least 1 lowercase letter (a-z)
- At least 1 number (0-9)

✅ **Good Password**: `MyPassword123`  
❌ **Bad Password**: `password123` (no uppercase)

### What's Protected
- Your password is hashed (encrypted)
- Your account is session-protected
- All forms have CSRF protection
- Your email is private

### If You Forget Password
1. Go to login page
2. Click "Forgot Password?"
3. Enter your email
4. Check email for reset link
5. Create new password

---

## 📱 RESPONSIVE DESIGN

PredictCup works on:
- ✅ Desktop computers
- ✅ Tablets
- ✅ Mobile phones

All views automatically adjust to your screen size.

---

## ❓ FAQ

### Q: Can I change my prediction after submitting?
**A**: No, one prediction per match only. Think carefully before submitting!

### Q: What if I predict correctly after a match completes?
**A**: Points are automatically calculated and added to your account. You'll see them on your dashboard.

### Q: How often does the leaderboard update?
**A**: Leaderboard updates in real-time whenever a match completes.

### Q: Can I join a league/room?
**A**: Yes! Go to "Rooms" section to create or join a league with friends.

### Q: What are achievements/badges?
**A**: Special badges you earn for predictions. View them in your profile.

### Q: Why is my time showing wrong?
**A**: Make sure your computer time is set correctly. PredictCup uses Indian Standard Time (IST).

### Q: What if there's a match today I don't see?
**A**: Run the sync script: http://localhost/worldcupprediction-big/sync-worldcup-data.php

### Q: Can I delete my account?
**A**: Contact admin. Your predictions will remain in the system for leaderboard.

---

## 🛠️ TROUBLESHOOTING

### No matches showing
```
1. Go to: http://localhost/worldcupprediction-big/sync-worldcup-data.php
2. Wait for it to complete
3. Go back to daily matches
```

### Wrong time display
```
1. Check your computer time is correct
2. Timezone should be IST (India Standard Time)
3. Times show as "HH:MM IST" format
```

### Can't submit prediction
```
- Check if 5 minutes before match
- If locked: Wait until next match
- If already predicted: Cannot change
```

### Forgot password
```
1. Click "Forgot Password?" on login
2. Enter email
3. Check email for reset link
4. Create new password
```

### Other issues
See: `VERIFICATION_AND_TROUBLESHOOTING.md`

---

## 📖 DOCUMENTATION

**For Users**:
- This file (START_HERE.md) - Quick start
- `QUICK_REFERENCE.md` - All features explained
- `VERIFICATION_AND_TROUBLESHOOTING.md` - Troubleshooting

**For Developers/Admins**:
- `SYSTEM_STATUS_REPORT.md` - Complete system overview
- `IMPLEMENTATION_COMPLETE.md` - What was built
- `SYNC_DATA_GUIDE.md` - How to sync data
- `API_TEST_GUIDE.md` - Testing API connection
- `TIMEZONE_AND_CUTOFF_UPDATE.md` - Technical details

**Technical**:
- `config/config.php` - Configuration file
- `app/helpers/helpers.php` - Helper functions
- `app/models/Prediction.php` - Prediction logic
- `predictcup.sql` - Database schema

---

## 🚀 NEXT STEPS

### As a User
1. ✅ Load matches (sync script)
2. ✅ Create account
3. ✅ View matches
4. ✅ Make predictions
5. ✅ Check leaderboard
6. ✅ Earn achievements
7. ✅ Join leagues/rooms

### As an Admin
1. ✅ Login to admin panel
2. ✅ Check system status
3. ✅ Manage teams
4. ✅ Manage matches
5. ✅ Sync API data (if needed)
6. ✅ View statistics
7. ✅ Manage users

---

## 💡 TIPS FOR SUCCESS

### Prediction Tips
1. **Research teams** - Know current form and player injuries
2. **Check weather** - Weather affects match style
3. **Consider matchups** - Head-to-head history matters
4. **Think early** - Don't wait until last minute
5. **Balance predictions** - Mix defensive and attacking bets

### Leaderboard Tips
1. **Be consistent** - Regular predictions beat sporadic ones
2. **Study patterns** - Top teams usually win
3. **Expect surprises** - Upsets happen in cups
4. **Learn from mistakes** - Review your predictions
5. **Stay focused** - Don't chase losses with risky predictions

### Timezone Tips
1. **All times shown in IST** - Indian Standard Time, UTC+5:30
2. **Countdown shows remaining time** - Not time to match
3. **Cutoff is 5 minutes before** - Plan accordingly
4. **Set phone alarm** - For matches you want to predict

---

## 🎉 YOU'RE READY!

**Everything is set up and working!**

Go make your first prediction:
```
http://localhost/worldcupprediction-big/daily-matches
```

---

## 📞 SUPPORT

**Having issues?**
1. Check: `VERIFICATION_AND_TROUBLESHOOTING.md`
2. Check: `QUICK_REFERENCE.md`
3. Check: `SYSTEM_STATUS_REPORT.md`
4. Check browser console (F12) for errors

**Admin Questions?**
- Look at admin panel documentation
- Check database queries in troubleshooting guide
- Review sync script output

---

## ✨ FEATURES AT A GLANCE

| Feature | Status | Details |
|---------|--------|---------|
| User Registration | ✅ Active | Email, password, country |
| User Login | ✅ Active | Email/password auth |
| Match Viewing | ✅ Active | Times in IST |
| Predictions | ✅ Active | Score + Winner |
| Cutoff Timer | ✅ Active | 5 minutes before match |
| Points System | ✅ Active | 10/5/0 scoring |
| Leaderboard | ✅ Active | Global rankings |
| Dashboard | ✅ Active | User stats |
| Admin Panel | ✅ Active | Team/match management |
| API Sync | ✅ Active | Football-Data.org |
| Achievements | ✅ Active | Earned badges |
| Rooms/Leagues | ✅ Active | Group play |
| Responsive Design | ✅ Active | Mobile-friendly |

---

## 🏆 COMPETITION

**How to compete:**
1. Make predictions
2. Earn points
3. Climb leaderboard
4. Compare with friends
5. Join leagues
6. Earn achievements

**What you'll win:**
- Bragging rights 🎖️
- High leaderboard rank 📈
- Badges and achievements 🏅
- Glory! 🏆

---

**Let's go predict some matches! ⚽🎯**

---

## Last Updated
Date: June 12, 2026  
Status: ✅ Production Ready  
All systems operational

For detailed information, see the other documentation files in the project root.

