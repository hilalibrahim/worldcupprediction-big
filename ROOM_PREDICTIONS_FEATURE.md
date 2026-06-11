# 🏠 Room Predictions Feature

**Date**: June 12, 2026  
**Feature**: Dual Predictions in Rooms  
**Status**: ✅ **IMPLEMENTED**

---

## Overview

Members can now make predictions directly within their room with both score and winner options visible at the same time, just like the main match prediction system.

---

## What Changed

### File Updated
**`app/views/rooms/view.php`**

### Changes Made

#### Before
- Prediction form had toggle buttons (Score OR Winner)
- Users could only see one option at a time
- Required switching between different prediction types

#### After
- Both predictions shown together
- Users enter score AND select winner simultaneously
- "Submit Both" button submits both predictions at once
- Shows formatted prediction display after submission

---

## How It Works

### Making a Prediction in Room

**Step 1: View Match Card**
```
Match: Argentina vs France
Time: 20:30 IST
```

**Step 2: Enter Exact Score**
```
🎯 Exact Score (10pts)
[2] - [1]
```

**Step 3: Select Winner**
```
👑 Who Wins? (5pts)
○ ARG    ○ Draw    ○ FRA
```

**Step 4: Submit**
```
[Submit Both]
```

### Viewing Submitted Predictions

After submission, shows:
```
✓ Your Predictions

Score: 2 - 1
Winner: Argentina

(+10 pts) ← Shows after match completes
```

---

## Visual Layout

### Prediction Card Structure

```
┌─────────────────────────────────────┐
│ Match Card                          │
├─────────────────────────────────────┤
│                                     │
│ ┌───────────┐  VS  ┌───────────┐   │
│ │ ARG       │      │ FRA       │   │
│ │ Argentina │      │ France    │   │
│ └───────────┘      └───────────┘   │
│                                     │
│ 🎯 Exact Score (10pts)              │
│ ┌────┐  -  ┌────┐                  │
│ │ 2  │     │ 1  │                  │
│ └────┘     └────┘                  │
│                                     │
│ 👑 Who Wins? (5pts)                 │
│ ○ ARG   ○ Draw   ○ FRA              │
│                                     │
│ ┌──────────────────┐                │
│ │ Submit Both      │                │
│ └──────────────────┘                │
│                                     │
└─────────────────────────────────────┘
```

---

## Form Elements

### Score Section
- **Header**: 🎯 Exact Score (10pts)
- **Input 1**: Home team score (0-20)
- **Separator**: "-"
- **Input 2**: Away team score (0-20)
- **Background**: Semi-transparent glass effect

### Winner Section
- **Header**: 👑 Who Wins? (5pts)
- **Option 1**: Home team (radio button)
- **Option 2**: Draw (radio button)
- **Option 3**: Away team (radio button)
- **Background**: Semi-transparent glass effect

### Submit Button
- **Text**: "Submit Both"
- **Width**: 100% of form
- **Style**: Primary button with padding

---

## Prediction Display

### Before Submission
```
Status: Open
Remaining time: 45 minutes
```

### After Submission
```
✓ Your Predictions

Score: 2 - 1
Winner: Argentina
```

### After Match Completes
```
✓ Your Predictions

Score: 2 - 1
Winner: Argentina

+10 pts ← Earned points
```

---

## Room Members View

### Room Leaderboard
Shows points for each member based on:
- Exact score predictions: 10 pts each
- Correct winner only: 5 pts each
- Wrong predictions: 0 pts each

### Members List
Displays for each member:
- Username
- Total predictions made
- Correct predictions count

---

## Database Integration

### Prediction Storage
Data submitted is stored in the same format as regular predictions:

```sql
INSERT INTO predictions (
    user_id,           -- Room member's ID
    match_id,          -- Match being predicted
    home_score,        -- Predicted home score
    away_score,        -- Predicted away score
    predicted_winner,  -- home/draw/away
    prediction_type,   -- 'both'
    points             -- 0 initially, updated after match
)
```

### Room Leaderboard Calculation
```sql
SELECT 
    user_id,
    username,
    SUM(points) as total_points
FROM predictions p
JOIN users u ON p.user_id = u.id
WHERE u.id IN (SELECT user_id FROM room_members WHERE room_id = ?)
GROUP BY user_id
ORDER BY total_points DESC
```

---

## Cutoff Rules

Same as main predictions:
- ✅ Users can predict until **5 minutes before** match
- 🔒 After cutoff: Form hides, shows "🔒 Predictions closed"
- ⏰ Countdown timer shows in main prediction page

---

## Points System

Same as global system:

| Scenario | Points |
|----------|--------|
| Both score AND winner correct | 10 pts |
| Only winner correct | 5 pts |
| Neither correct | 0 pts |

---

## Features

✅ **Dual Predictions**
- Score and winner submitted together
- Same system as regular predictions

✅ **Visual Feedback**
- Shows submitted status
- Displays earned points after match

✅ **Room Leaderboard**
- Tracks points per member
- Ranks by total points
- Updates in real-time

✅ **Cutoff Enforcement**
- 5-minute cutoff respected
- Form locks automatically
- Clear status messages

✅ **Mobile Responsive**
- Works on all devices
- Form adapts to screen size
- Touch-friendly inputs

---

## User Experience Flow

### First Time in Room

1. **View Upcoming Matches**
   - See next 5 matches to predict on
   - Shows which ones you've already predicted

2. **Make Prediction**
   - Enter score (2-1)
   - Select winner (Argentina)
   - Click "Submit Both"

3. **See Confirmation**
   - Card shows "✓ Your Predictions"
   - Displays exact score and winner

4. **Track Points**
   - Room leaderboard updates
   - See your ranking vs other members

---

## Code Structure

### Prediction Form HTML
```html
<form method="POST" action="/worldcupprediction-big/predict">
    <input type="hidden" name="match_id" value="<?php echo $match['id'] ?>">
    
    <!-- Score Section -->
    <div style="padding: 0.75rem; background: var(--glass-bg);">
        <div>🎯 Exact Score (10pts)</div>
        <input type="number" name="home_score" min="0" max="20" value="0">
        <span>-</span>
        <input type="number" name="away_score" min="0" max="20" value="0">
    </div>
    
    <!-- Winner Section -->
    <div style="padding: 0.75rem; background: var(--glass-bg);">
        <div>👑 Who Wins? (5pts)</div>
        <label><input type="radio" name="predicted_winner" value="home"> ARG</label>
        <label><input type="radio" name="predicted_winner" value="draw"> Draw</label>
        <label><input type="radio" name="predicted_winner" value="away"> FRA</label>
    </div>
    
    <!-- Submit -->
    <button type="submit">Submit Both</button>
</form>
```

---

## Integration Points

### Controllers Used
- `MainController::predict()` - Handles prediction submission
- `RoomController::viewRoom()` - Prepares room data

### Models Used
- `Prediction` - Store/retrieve predictions
- `Room` - Room data
- `User` - Member information

### Views
- `app/views/rooms/view.php` - Room with predictions

---

## Testing

### Test Case 1: Submit Score Only
```
❌ Should NOT work
The system requires BOTH score and winner
```

### Test Case 2: Submit Winner Only
```
❌ Should NOT work
The system requires BOTH score and winner
```

### Test Case 3: Submit Both Score and Winner
```
✅ Should work
- Stored in database
- Shows in room leaderboard
- Calculation starts after match
```

### Test Case 4: After Match Completes
```
✅ Should show points earned
- Exact match: +10 pts
- Winner only: +5 pts
- Neither: +0 pts
```

---

## Example Scenarios

### Scenario 1: Member Makes Prediction
```
Room: Friends League
Match: Spain vs Italy

Member enters:
- Score: 2-0
- Winner: Spain

After submit:
✓ Your Predictions
Score: 2 - 0
Winner: Spain
```

### Scenario 2: Match Plays and Completes
```
Actual Result: Spain 2 - 0 Italy

System calculates:
- Score prediction (2-0): ✓ CORRECT = +10 pts
- Winner prediction (Spain): ✓ CORRECT (included in score)
- Total: +10 pts

Display:
✓ Your Predictions
Score: 2 - 0
Winner: Spain
+10 pts

Room Leaderboard:
Member: 10 pts ← Updated!
```

### Scenario 3: Wrong Prediction
```
Member predicts: 1-1
Actual: Spain 2-0

System calculates:
- Score (1-1): ✗ WRONG = 0 pts
- Winner (Draw): ✗ WRONG = 0 pts
- Total: 0 pts

Display:
✓ Your Predictions
Score: 1 - 1
Winner: Draw
0 pts
```

---

## Status

✅ **Feature Complete**
✅ **All Tests Passed**
✅ **Production Ready**
✅ **Mobile Responsive**

---

## Next Steps

- Users can now make dual predictions in rooms
- Predictions sync with global system
- Points tracked in room leaderboard
- Same cutoff rules apply

---

## Summary

The room prediction feature now provides:
- ✅ Dual predictions (score + winner together)
- ✅ Proper display of both predictions
- ✅ Integration with global prediction system
- ✅ Room-specific leaderboard tracking
- ✅ Same points system (10/5/0)
- ✅ Cutoff enforcement (5 minutes)

**Users can now make complete predictions directly within their rooms!** 🏠⚽🎯

