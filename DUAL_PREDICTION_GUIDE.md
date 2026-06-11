# Dual Prediction System - Quick Start Guide

## What is the Dual Prediction System?

Users now make **TWO predictions** for each match in a **single form submission**:

1. **Exact Score** (e.g., "Brazil 2 - France 1")
2. **Match Winner** (e.g., "Brazil Wins")

---

## How Users Make Predictions

### Step 1: Navigate to Match
- Go to Matches → Select an upcoming match
- Click on the match card to view details

### Step 2: Fill the Prediction Form
The form has two sections:

#### Score Section (🎯)
- Enter the expected home team score (0-20)
- Enter the expected away team score (0-20)
- Example: Home 2, Away 1

#### Winner Section (👑)
- Select who will win:
  - **[Team Name] Wins** (Home team victory)
  - **Draw** (Equal score)
  - **[Team Name] Wins** (Away team victory)

### Step 3: Submit
- Click **"Submit Both Predictions"**
- Page refreshes showing your prediction was saved
- You cannot change or resubmit for this match

---

## Points System

### How Points Are Awarded

| Your Prediction | Match Result | Points | Example |
|---|---|---|---|
| Both Correct | Exact score AND winner match | **10 pts** | You predict 2-1 Brazil Win, actual is 2-1 Brazil wins ✓ |
| Winner Only | Winner correct, score different | **5 pts** | You predict Brazil 2-1, actual is Brazil 3-1 ✓ |
| Both Wrong | Nothing matches | **0 pts** | You predict Brazil 2-1, actual is France 1-0 ✗ |

### Maximum Points
- **10 points per match** - Most predictions are worth up to 10 points
- Points accumulate towards your global rank and leaderboard position

### When Points Are Awarded
1. Admin enters match results in admin panel
2. System automatically calculates points
3. Your score updates within seconds
4. You earn achievement badges for streaks and milestones

---

## Where to See Your Predictions

### 1. Match Detail Page
After submitting, you see your prediction locked in:
```
Your Prediction Submitted
Exact Score: 2 - 1
Winner Prediction: Brazil Wins
```

### 2. Dashboard
- View all your upcoming predictions
- See recent prediction results
- Check your current rank

### 3. Leaderboard
- See global rankings by total points
- Compare accuracy with other players
- Filter by timeframe (Overall, Weekly, Monthly)

### 4. Predictions History
- Full log of all predictions
- Points earned per prediction
- Accuracy percentage

---

## Prediction Tips & Strategies

### 1. Research Before Predicting
- Check team form and recent results
- Review player injuries
- Consider home/away advantage
- Look at head-to-head history

### 2. Understand Scoring Context
- **Exact Score** is hard to predict (only 10% of predictions are exact)
- **Winner Prediction** is safer but worth fewer points (5 pts)
- Aim for realistic scores based on team stats

### 3. Use Room Competitions
- Join a room with friends
- Compete on the same matches
- Compare your accuracy on room leaderboard
- Share strategies and learn from others

### 4. Track Your Accuracy
- Monitor your accuracy percentage
- See which teams/stages you predict best
- Adjust strategy based on performance
- Aim for consistent improvement

---

## Common Questions

### Q: Can I change my prediction after submitting?
**A:** No, one prediction per match. You can't edit it once submitted. Make sure you're confident before clicking submit!

### Q: What if my score is correct but winner is wrong?
**A:** You get 5 points (correct winner prediction). Exact score is only worth points if both the score AND winner are correct.

### Q: When are points awarded?
**A:** After the admin enters the final match results in the admin panel. Usually within hours of match completion.

### Q: Can I predict after the match starts?
**A:** No, predictions are locked when the match starts. You must predict before kickoff.

### Q: How does accuracy percentage work?
**A:** Accuracy = (Correct Predictions / Total Predictions) × 100
- Only predictions worth points (5 or 10) count as "correct"
- 0-point predictions don't count

### Q: What's the difference between rooms and global?
**A:** 
- **Global:** Compete against all players worldwide
- **Rooms:** Create private competitions with friends on specific matches

---

## Special Features

### Rooms
Create a room to compete with friends on the same matches:
1. Go to Rooms
2. Click "Create Room"
3. Set room name (private or public)
4. Share invite code with friends
5. All room members predict on same matches
6. Compare results on room leaderboard

### Achievements
Earn badges for milestones:
- 🎯 First Prediction - Submit your first prediction
- 🏆 10 Correct - Get 10 predictions correct
- 💎 Prediction Master - Reach 500 total points
- 👑 Champion - Correctly predict World Cup winner

### Daily Matches
See all matches happening today:
- Today's match schedule
- Quick links to make predictions
- Live score updates

---

## Example Walkthrough

**Scenario:** Brazil vs France upcoming match

### Your Prediction Process:
1. Navigate to the match detail page
2. See the form with:
   - Score inputs (0-20 range)
   - Winner options (Brazil Wins / Draw / France Wins)
3. You enter:
   - Score: Brazil 2, France 1
   - Winner: Brazil Wins
4. Click "Submit Both Predictions"
5. Form locked, shows your prediction

### When Match Finishes:
- **If actual result is Brazil 2-1:** You get **10 pts** ✓ Both correct!
- **If actual result is Brazil 3-1:** You get **5 pts** ✓ Winner correct
- **If actual result is France 1-0:** You get **0 pts** ✗ Both wrong

### Points Added:
- Your profile: +10 pts (or +5 or +0)
- Global leaderboard: Updated with new rank
- Room leaderboard (if in room): Updated with room rank
- Accuracy: Recalculated automatically

---

## Best Practices

✅ **DO:**
- Research teams before predicting
- Submit predictions before match starts
- Check form before submitting (can't edit)
- Compete in rooms for fun
- Review your accuracy trends
- Learn from prediction history

❌ **DON'T:**
- Submit random guesses (they rarely score)
- Wait until last minute (matches lock)
- Try to change predictions after submit
- Skip the research phase
- Get discouraged by low accuracy early
- Predict after the match has started

---

## Technical Notes

### Database
- One database row per user per match
- Stores both score and winner prediction
- One-time only via UNIQUE constraint
- Points calculated and stored after match

### Points Calculation
- Exact match = 10 points
- Winner only = 5 points
- No match = 0 points
- Instant award upon admin result entry

### API Integration
- Matches pulled from Football-Data.org
- Live score updates
- Automatic results import
- Points calculated server-side

---

## Support

### If You Have Issues:

1. **"Prediction already exists" error**
   - You already predicted this match
   - Navigate to match to see your prediction

2. **Points not showing after match**
   - Admin may not have entered results yet
   - Check daily - updates happen within hours
   - Contact admin if match was yesterday

3. **Form won't submit**
   - Make sure both sections are filled
   - Browser may need refresh
   - Try a different browser
   - Check console for errors (F12 → Console)

4. **Can't join a room**
   - Room may be full (max 50 members)
   - Invite code may be expired
   - Check if room is private
   - Ask room owner to invite you

---

## Have Fun!

The Dual Prediction System combines strategy, research, and luck. 

Good predictions! 🎯👑
