# 🏠 Room Predictions - Complete Guide

**Date**: June 12, 2026  
**Feature**: Make Dual Predictions in Rooms  
**Status**: ✅ **FULLY IMPLEMENTED**

---

## Overview

Members of a room can now make predictions directly within their room. The system allows both **exact score** and **winner prediction** to be submitted together, just like the main prediction system.

---

## How to Access

### 1. Create or Join a Room
```
1. Go to: /worldcupprediction-big/rooms
2. Click "Create Room" OR join with invite code
3. You're now in a room
```

### 2. Go to Room View
```
1. Dashboard → Click room name
2. OR /worldcupprediction-big/rooms/view/[room-id]
```

### 3. See "Make Predictions" Section
```
Left side shows upcoming matches
- Room leaderboard
- Make predictions (center section)
- Room members (right side)
```

---

## Making a Prediction

### Step-by-Step

**Step 1: Find Upcoming Match**
```
Match Card displays:
- Time (in IST)
- Both teams
- Status (Open / Locked / ✓ Predicted)
```

**Step 2: Enter Exact Score**
```
🎯 Exact Score (10pts)
┌──────────┬─┬──────────┐
│ 2        │-│ 1        │
└──────────┴─┴──────────┘
Home Team    Away Team
```

**Step 3: Select Winner**
```
👑 Who Wins? (5pts)
○ ARG    ○ Draw    ○ FRA
(Home)   (Tie)     (Away)
```

**Step 4: Submit**
```
[Submit Both] ← Button
```

---

## What Happens After Submit

### Immediate
```
✓ Your Predictions

Score: 2 - 1
Winner: Argentina
```

The card now shows:
- Your exact score prediction
- Your winner prediction
- Green border around card
- ✓ Predicted badge

### After Match Completes
```
✓ Your Predictions

Score: 2 - 1
Winner: Argentina

+10 pts ← Points awarded
```

Points shown:
- **10 pts**: Both score and winner correct
- **5 pts**: Only winner correct
- **0 pts**: Neither correct

---

## Room Features

### Room Leaderboard
```
┌──────┬──────────┬────────┐
│ Rank │ User     │ Points │
├──────┼──────────┼────────┤
│ 1    │ John     │ 85 pts │
│ 2    │ Alice    │ 72 pts │
│ 3    │ Bob      │ 65 pts │
└──────┴──────────┴────────┘
```

**Updated**: Real-time as matches complete

### Room Members
```
👤 John
50 predictions | 25 correct

👤 Alice  
42 predictions | 20 correct

👤 Bob
35 predictions | 18 correct
```

**Shows**: Predictions made and accuracy

---

## Prediction Rules

### Timing
- ✅ Can predict until **5 minutes before match**
- 🔒 After cutoff: Form locked, shows "🔒 Predictions closed"
- ⏰ Countdown timer available on main match page

### One Per Match
- You can only make **one prediction per match per room**
- Cannot change after submission
- Predicted matches show status "✓ Predicted"

### Both Required
- Must enter **both score AND winner**
- Score: 0-20 for each team
- Winner: Home / Draw / Away

---

## Points System in Rooms

### Exact Score Correct
```
You predict: 2-1
Match result: 2-1
Winner prediction: Argentina (Correct)
→ Points: 10
```

### Only Winner Correct
```
You predict: 1-1 (Draw)
Match result: 2-0 (Winner: Home)
Your winner: Draw (Wrong)
→ Points: 0

BUT if you predict:
Score: 1-2
Winner: Away (Correct)
→ Points: 5
```

### Neither Correct
```
You predict: 1-1
Match result: 2-0
Winner prediction: Draw (Wrong)
→ Points: 0
```

---

## UI Layout

### Full Room View

```
╔════════════════════════════════════════════════════════╗
║ Room Name                          [Edit] [Members]    ║
║ Room description here                                  ║
║ Owner: John | 5 members | Code: ABC123                ║
╚════════════════════════════════════════════════════════╝

┌──────────────────┐  ┌──────────────────┐  ┌────────┐
│ Room Leaderboard │  │ Make Predictions │  │ Members│
│                  │  │                  │  │        │
│ 1. John - 85pts  │  │ Match Card 1     │  │ John   │
│ 2. Alice - 72pts │  │ [Prediction Form]│  │ Alice  │
│ 3. Bob - 65pts   │  │                  │  │ Bob    │
│                  │  │ Match Card 2     │  │        │
│                  │  │ ✓ Predicted      │  │        │
│                  │  │                  │  │        │
└──────────────────┘  └──────────────────┘  └────────┘
```

---

## Match Card States

### Open (Can Predict)
```
┌─────────────────────────────────┐
│ Match: Argentina vs France      │
│ Time: 20:30 IST          [Open] │
├─────────────────────────────────┤
│                                 │
│  [Score Inputs]  [Winner Radio] │
│  [Submit Both]                  │
│                                 │
└─────────────────────────────────┘
```

### Locked (Cutoff Reached)
```
┌─────────────────────────────────┐
│ Match: Argentina vs France      │
│ Time: 20:30 IST        [Locked] │
├─────────────────────────────────┤
│                                 │
│ 🔒 Predictions closed           │
│                                 │
└─────────────────────────────────┘
```

### Predicted (Already Submitted)
```
┌─────────────────────────────────┐
│ Match: Argentina vs France      │
│ Time: 20:30 IST    [✓ Predicted]│
├─────────────────────────────────┤
│ ✓ Your Predictions              │
│                                 │
│ Score: 2 - 1                    │
│ Winner: Argentina               │
│                                 │
└─────────────────────────────────┘
```

### After Match Completes
```
┌─────────────────────────────────┐
│ Match: Argentina vs France      │
│ Result: 2 - 1                   │
├─────────────────────────────────┤
│ ✓ Your Predictions              │
│                                 │
│ Score: 2 - 1      ✓ Correct    │
│ Winner: Argentina  ✓ Correct    │
│                                 │
│ +10 pts                         │
│                                 │
└─────────────────────────────────┘
```

---

## Prediction Form Details

### Score Section
- **Header**: 🎯 Exact Score (10pts)
- **Label 1**: Home team name (shortened)
- **Input 1**: Number field (0-20)
- **Separator**: "-"
- **Input 2**: Number field (0-20)
- **Label 2**: Away team name (shortened)

### Winner Section
- **Header**: 👑 Who Wins? (5pts)
- **Option 1**: Radio button + Home team name
- **Option 2**: Radio button + "Draw"
- **Option 3**: Radio button + Away team name

### Submit Button
- **Text**: "Submit Both"
- **Width**: Full width of form
- **Action**: POST to /predict with match_id, home_score, away_score, predicted_winner

---

## Backend Integration

### Data Flow
```
User submits form
    ↓
MainController::predict()
    ↓
Prediction::addPrediction()
    ↓
Database: predictions table
    ↓
RoomController::view()
    ↓
Room leaderboard updated
```

### Database Fields Stored
```sql
INSERT INTO predictions (
    user_id           -- Room member
    match_id          -- Match being predicted
    home_score        -- User's home prediction
    away_score        -- User's away prediction
    predicted_winner  -- home / draw / away
    prediction_type   -- 'both'
    points            -- 0 (calculated after match)
)
```

### Room Leaderboard Query
```sql
SELECT 
    u.id,
    u.username,
    SUM(p.points) as total_points
FROM users u
LEFT JOIN predictions p ON u.id = p.user_id
WHERE u.id IN (
    SELECT user_id 
    FROM room_members 
    WHERE room_id = ?
)
GROUP BY u.id
ORDER BY total_points DESC
```

---

## Example Workflow

### John's Prediction in "Friends League" Room

**1. Join Room**
```
Room: Friends League
Invite Code: ABC123
Members: 4 (John, Alice, Bob, Carol)
```

**2. View Upcoming Matches**
```
Match 1: Spain vs Italy (20:30 IST) - Open
Match 2: Germany vs France (23:00 IST) - Open
Match 3: England vs Portugal (02:00 IST+1) - Locked
```

**3. Make First Prediction**
```
Spain vs Italy

John enters:
Score: 2 - 1 (Spain wins 2-1)
Winner: Spain

Submits...

Result:
✓ Your Predictions
Score: 2 - 1
Winner: Spain
```

**4. Check Leaderboard**
```
Room Leaderboard
1. John - 0 pts (no matches completed yet)
2. Alice - 0 pts
3. Bob - 0 pts
4. Carol - 0 pts
```

**5. Match Completes**
```
Actual Result: Spain 2 - 1 Italy

System calculates:
✓ Score (2-1): Correct → +10 pts
✓ Winner (Spain): Correct (included)
Total: +10 pts
```

**6. Leaderboard Updates**
```
Room Leaderboard
1. John - 10 pts ⭐ (updated!)
2. Alice - 5 pts
3. Bob - 0 pts
4. Carol - 3 pts
```

---

## Testing the Feature

### Test 1: Make a Prediction
```
1. Go to room
2. Enter score (2-1)
3. Select winner (Home)
4. Click "Submit Both"
→ Should see: ✓ Your Predictions displayed
```

### Test 2: Check Display
```
1. Submitted prediction shows both score and winner
2. Card has green border (predicted status)
3. ✓ Predicted badge shows
→ Should see: All three elements
```

### Test 3: View in Leaderboard
```
1. Go to room leaderboard
2. Your points should be 0 (match not played yet)
3. After match, should update
→ Should see: Points updated after match completes
```

### Test 4: Cutoff Logic
```
1. Wait until 5 minutes before match
2. Try to submit prediction
→ Should see: "🔒 Predictions closed"
```

---

## FAQ

**Q: Can I change my prediction after submitting?**  
A: No. One prediction per match. Think carefully before submitting!

**Q: What if I don't predict on a match?**  
A: That's fine. You won't earn points for that match, and other members can still get points.

**Q: Do room predictions affect my global ranking?**  
A: Yes! All predictions (global and room) are stored in the same database and affect your global leaderboard position.

**Q: Can I be in multiple rooms?**  
A: Yes! You can be member of multiple rooms and make predictions in each.

**Q: What happens if I leave a room?**  
A: Your predictions stay in the database. Points are still calculated. You just can't see the room anymore.

---

## Features Summary

✅ **Dual Predictions**: Score + Winner submitted together  
✅ **Real-time Display**: Shows both predictions after submit  
✅ **Room Leaderboard**: Ranked by total points  
✅ **Points Integration**: Same 10/5/0 system  
✅ **Cutoff Enforcement**: 5-minute cutoff applies  
✅ **Global Integration**: Predictions affect global rank  
✅ **Mobile Responsive**: Works on all devices  
✅ **Visual Feedback**: Clear status indicators  

---

## Technical Details

**Files Involved**:
- `app/views/rooms/view.php` - Room prediction form
- `app/controllers/RoomController.php` - Handles room data
- `app/controllers/MainController.php` - Handles prediction submit
- `app/models/Prediction.php` - Stores predictions
- `app/models/Room.php` - Room leaderboard

**Database Tables**:
- `predictions` - Stores all predictions
- `room_members` - Room membership
- `matches` - Match data
- `users` - User data

---

## Summary

The room predictions feature allows:
- ✅ Members to predict directly in their room
- ✅ Both score and winner submitted together
- ✅ Proper display of both predictions
- ✅ Room-specific leaderboard
- ✅ Integration with global system
- ✅ Same points system (10/5/0)
- ✅ Cutoff enforcement (5 minutes)

**Members can now make complete predictions within their rooms!** 🏠⚽

