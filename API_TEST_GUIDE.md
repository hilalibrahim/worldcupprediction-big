# API Test Guide - Football-Data.org

## Quick Test

### Test the API Connection
Visit this URL in your browser:
```
http://localhost/worldcupprediction-big/test-api-direct.php
```

This will:
- ✅ Test API connection
- ✅ Verify API key works
- ✅ Show first 5 matches
- ✅ Show statistics
- ✅ Confirm everything is working

---

## What You'll See

```
🏆 COMPETITION INFO
Name:     FIFA World Cup
Code:     WC
Area:     World

⚽ MATCHES
Found 64 matches

📋 First 5 Matches:
1. Brazil vs Serbia
   Date (IST): 2026-06-15 20:30 IST
   Status: SCHEDULED
   Stage: GROUP_STAGE
   Venue: Stadium Name

📊 MATCH STATISTICS
By Status:
  SCHEDULED: 32
  FINISHED: 32

By Stage:
  GROUP_STAGE: 24
  KNOCKOUT_ROUND: 40
```

---

## If Test Fails

### ❌ "API Key Not Configured"
**Fix:**
1. Open: `config/config.php`
2. Find: `FOOTBALL_DATA_API_KEY`
3. Add your key from: https://www.football-data.org

### ❌ "HTTP 429"
**Meaning:** Too many requests  
**Fix:** Wait 5 minutes, then try again

### ❌ "HTTP 400"
**Meaning:** Bad request  
**Check:**
- API key is correct
- Endpoint URL is correct
- Internet connection works

### ❌ "HTTP 403"
**Meaning:** Unauthorized  
**Check:**
- API key is valid
- API key has access to competitions/WC

### ❌ "Connection Timeout"
**Check:**
- Internet connection
- API server is running
- Firewall not blocking requests

---

## API Endpoint Details

**Endpoint:**
```
https://api.football-data.org/v4/competitions/WC/matches
```

**Method:** GET

**Headers:**
```
X-Auth-Token: YOUR_API_KEY
Content-Type: application/json
```

**Response:**
```json
{
  "competition": {...},
  "season": {...},
  "matches": [
    {
      "id": 12345,
      "homeTeam": {
        "id": 1,
        "name": "Brazil"
      },
      "awayTeam": {
        "id": 2,
        "name": "Serbia"
      },
      "utcDate": "2026-06-15T15:00:00Z",
      "status": "SCHEDULED",
      "stage": "GROUP_STAGE",
      "venue": "Stadium Name"
    }
  ]
}
```

---

## Get Your API Key

1. Go to: https://www.football-data.org/client/register
2. Sign up (free tier available)
3. Get your API key
4. Add to `config/config.php`

---

## After API Works

Once API test shows ✅ success:

1. **Fetch all matches:**
   ```
   http://localhost/worldcupprediction-big/fetch-all-worldcup-matches.php
   ```

2. **View matches:**
   ```
   http://localhost/worldcupprediction-big/daily-matches
   ```

3. **Start predicting!** ⚽

---

## Direct cURL Test

If you want to test from command line:

```bash
curl -X GET "https://api.football-data.org/v4/competitions/WC/matches" \
  -H "X-Auth-Token: YOUR_API_KEY" \
  -H "Content-Type: application/json"
```

---

## Files

- `test-api-direct.php` - Direct API test
- `fetch-all-worldcup-matches.php` - Fetch all 64 matches
- `fetch-todays-matches.php` - Fetch only today's matches

---

**Ready to test?** Visit: `http://localhost/worldcupprediction-big/test-api-direct.php`
