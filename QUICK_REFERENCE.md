# PredictCup - Quick Reference Card

## 🎯 Dual Prediction System at a Glance

**What:** Users make TWO predictions per match in ONE form
- **Score:** Predict exact final score (0-20 each team)
- **Winner:** Predict match outcome (Home/Draw/Away)

**Points:**
- Both correct: **10 pts**
- Winner only: **5 pts**
- Neither correct: **0 pts**

---

## 📊 Data Flow

```
Form Input
├─ home_score (number)
├─ away_score (number)
├─ predicted_winner (radio)
└─ match_id (hidden)
    ↓
MainController.predict()
    ↓
Prediction.addPrediction()
    ↓
Database INSERT
├─ Column: home_score
├─ Column: away_score
├─ Column: prediction_type = 'both'
├─ Column: predicted_winner
└─ Column: points = 0 (calculated later)
    ↓
Match Completes
    ↓
Admin Enters Score
    ↓
Match.calculatePoints()
    ↓
calculatePoints(prediction, match)
    ├─ If score exact: Return 10
    ├─ If winner matches: Return 5
    └─ If neither: Return 0
    ↓
User Points Updated
```

---

## 🗄️ Database

### Predictions Table Key Columns
```sql
id                 INT PRIMARY KEY
user_id            INT FOREIGN KEY → users(id)
match_id           INT FOREIGN KEY → matches(id)
home_score         INT              -- User's predicted home score
away_score         INT              -- User's predicted away score
predicted_winner   ENUM('home','draw','away')
prediction_type    ENUM('winner','score','both') DEFAULT 'both'
points             INT DEFAULT 0    -- Awarded after match
is_exact_score     TINYINT(1)       -- Flag: exact match
is_correct_winner  TINYINT(1)       -- Flag: winner correct
created_at         DATETIME
```

### UNIQUE Constraint
```sql
UNIQUE KEY unique_user_match (user_id, match_id)
-- User can only predict once per match
```

---

## 💻 Code Locations

### Form (User Interaction)
**File:** `app/views/matches/detail.php`
```html
<input type="number" name="home_score" min="0" max="20">
<input type="number" name="away_score" min="0" max="20">
<input type="radio" name="predicted_winner" value="home">
<input type="radio" name="predicted_winner" value="draw">
<input type="radio" name="predicted_winner" value="away">
```

### Controller (Request Handling)
**File:** `app/controllers/MainController.php`
```php
public function predict() {
    $data = [
        'user_id' => getCurrentUserId(),
        'match_id' => (int)$_POST['match_id'],
        'home_score' => (int)$_POST['home_score'],
        'away_score' => (int)$_POST['away_score'],
        'predicted_winner' => $_POST['predicted_winner'],
        'prediction_type' => 'both'
    ];
    $this->predictionModel->addPrediction($data);
}
```

### Model (Storage)
**File:** `app/models/Prediction.php`
```php
public function addPrediction($data) {
    // Check if prediction exists (UNIQUE constraint)
    // Check if match is locked
    // INSERT into predictions table
    // Return success/error
}
```

### Helper (Points Calculation)
**File:** `app/helpers/helpers.php`
```php
function calculatePoints($prediction, $actual) {
    if ($prediction['prediction_type'] === 'both') {
        // Exact score: 10 pts
        if ($pred['home_score'] === $actual['home_score'] &&
            $pred['away_score'] === $actual['away_score']) {
            return POINTS_EXACT_SCORE; // 10
        }
        
        // Winner only: 5 pts
        if ($pred['predicted_winner'] === getWinner($actual)) {
            return POINTS_CORRECT_WINNER; // 5
        }
        
        return 0;
    }
}
```

### Match Model (Award)
**File:** `app/models/Match.php`
```php
public function calculatePoints($matchId) {
    // Called when admin marks match completed
    // For each prediction:
    $points = calculatePoints($prediction, $match);
    // Update prediction.points
    // Add to user.points total
}
```

---

## ⚙️ Configuration

```php
// config/config.php
define('POINTS_EXACT_SCORE', 10);        // Both correct
define('POINTS_CORRECT_WINNER', 5);      // Winner only
define('MAX_POINTS_PER_MATCH', 10);      // Max cap
```

---

## ✅ Installation Checklist

- [ ] Place files in `c:\xampp\htdocs\worldcupprediction-big\`
- [ ] Start Apache & MySQL
- [ ] Visit `/install.php`
- [ ] Database created
- [ ] Tables with new columns created
- [ ] Admin account created
- [ ] Access main page

---

## 🧪 Quick Test

### 1. Create Prediction
```
POST /worldcupprediction-big/predict
{
  match_id: 1,
  home_score: 2,
  away_score: 1,
  predicted_winner: "home"
}
```
Expected: Redirect with success message

### 2. View Database
```sql
SELECT * FROM predictions WHERE match_id = 1 AND user_id = [user_id];
```
Expected: Row with prediction_type='both', predicted_winner='home'

### 3. Score Match
- Admin panel → Matches → Select match
- Enter: Home 2, Away 1
- Click: Mark Complete

### 4. Check Points
```sql
SELECT points FROM predictions WHERE id = [prediction_id];
```
Expected: 10 (since exact match)

---

## 🐛 Debugging

### Check Prediction Type
```sql
SELECT prediction_type, predicted_winner FROM predictions LIMIT 5;
```

### Check Points Calculation
```sql
SELECT p.*, m.home_score, m.away_score 
FROM predictions p
JOIN matches m ON p.match_id = m.id
WHERE p.match_id = [match_id];
```

### Check User Points
```sql
SELECT SUM(points) as total FROM predictions WHERE user_id = [user_id];
SELECT points FROM users WHERE id = [user_id];
```

### Database Columns
```sql
DESCRIBE predictions;
-- Should show: prediction_type, predicted_winner columns
```

---

## 🚀 Common Tasks

### Add New Match
```
Admin Panel → Matches → Add Match
Select from Football-Data.org API
```

### Enter Match Results
```
Admin Panel → Matches → Select Match
Enter Home Score and Away Score
Click: Mark Complete
→ Points automatically awarded
```

### Check User Accuracy
```sql
SELECT 
  u.username,
  COUNT(p.id) as total_predictions,
  SUM(CASE WHEN p.points > 0 THEN 1 ELSE 0 END) as correct,
  ROUND(100 * SUM(CASE WHEN p.points > 0 THEN 1 ELSE 0 END) / COUNT(p.id), 2) as accuracy_percent
FROM users u
LEFT JOIN predictions p ON u.id = p.user_id
GROUP BY u.id;
```

### Top Predictors
```sql
SELECT username, points, COUNT(*) as predictions
FROM users u
JOIN predictions p ON u.id = p.user_id
GROUP BY u.id
ORDER BY u.points DESC
LIMIT 10;
```

---

## 📈 Performance Tips

- Use indexes on user_id, match_id
- UNIQUE constraint prevents duplicates
- Batch calculate points (run once per match)
- Cache leaderboard for 1 hour
- Pagination for large result sets

---

## 🔐 Security Checklist

- [x] Password hash: `password_hash()`
- [x] Input sanitize: `htmlspecialchars()`, `strip_tags()`
- [x] SQL params: `$db->single()`, `$db->query()`
- [x] CSRF tokens: `verifyCsrfToken()`
- [x] Session HttpOnly: `'httponly' => true`
- [x] Email validation: `filter_var(FILTER_VALIDATE_EMAIL)`

---

## 📚 Key Files

| Path | Purpose |
|---|---|
| `config/config.php` | Constants & config |
| `app/helpers/helpers.php` | Utility functions |
| `app/models/Prediction.php` | Prediction storage |
| `app/models/Match.php` | Match management |
| `app/controllers/MainController.php` | Main logic |
| `app/views/matches/detail.php` | Prediction form |
| `predictcup.sql` | Database schema |

---

## 🆘 Troubleshooting

| Issue | Cause | Fix |
|---|---|---|
| "Prediction already exists" | Duplicate attempt | Check existing prediction |
| Points = 0 | Match not marked complete | Use admin panel |
| Form won't submit | Validation fail | Check console (F12) |
| Columns not found | Database not updated | Run install.php |
| Can't login | User not registered | Register first |

---

## 📞 Quick Commands

### Start XAMPP
```bash
# Windows
xampp_start.exe
# Or use XAMPP Control Panel
```

### Access System
```
http://localhost/worldcupprediction-big/
http://localhost/worldcupprediction-big/admin
```

### Database
```
PhpMyAdmin: http://localhost/phpmyadmin
Database: predictcup_db
```

---

## 🎯 Points Formula

```
IF score exact THEN 10 points
ELSE IF winner correct THEN 5 points
ELSE 0 points
```

**Example:**
```
Prediction: Brazil 2-1 (Brazil Wins)
Actual:     Brazil 3-1
→ Winner correct = 5 points
→ Score wrong = Not 10 points
→ Final = 5 points
```

---

## ✨ Feature Highlights

✅ Single form submission (both predictions)
✅ Automatic point calculation
✅ One prediction per match (UNIQUE constraint)
✅ Real-time leaderboard updates
✅ Room competitions
✅ Achievement badges
✅ Admin score entry
✅ API integration
✅ Responsive design
✅ Mobile friendly

---

**Last Updated:** June 11, 2026
**Status:** ✅ Production Ready
