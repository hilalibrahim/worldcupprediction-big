# Homepage Statistics Section - Visual Guide

## Design Overview

```
┌─────────────────────────────────────────────────────────────┐
│                  Platform Statistics                         │
│   Join thousands of football fans predicting the biggest     │
│          tournament in the world.                            │
└─────────────────────────────────────────────────────────────┘

┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│   ┌────────┐ │  │   ┌────────┐ │  │   ┌────────┐ │  │   ┌────────┐ │
│   │   👥   │ │  │   │   🏆   │ │  │   │   ⚽   │ │  │   │   📅   │ │
│   └────────┘ │  │   └────────┘ │  │   └────────┘ │  │   └────────┘ │
│     1,250    │  │      48      │  │    12,890    │  │      32      │
│ Active       │  │ Prediction   │  │ Predictions  │  │ Matches      │
│ Predictors   │  │ Rooms        │  │ Made         │  │ Completed    │
│ Players      │  │ Active       │  │ Total        │  │ Results      │
│ worldwide    │  │ competitions │  │ guesses      │  │ entered      │
└──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
```

## Component Breakdown

### Card Structure:
```
┌─ Stat Card ────────────────────┐
│                                 │
│  ┌──────────┐                  │
│  │          │                  │
│  │  ICON    │  ← Colored       │
│  │  (80x80) │     Circle       │
│  │          │                  │
│  └──────────┘                  │
│                                 │
│  ┌────────────────────────────┐ │
│  │  1,250                     │ │ ← Gold Text (2.8rem)
│  │  ACTIVE PREDICTORS         │ │ ← Label (uppercase)
│  │  Players worldwide         │ │ ← Description
│  └────────────────────────────┘ │
│                                 │
└─────────────────────────────────┘
```

## Color Palette

### Icon Backgrounds:
```
Users      🟦 Blue      #253b77 → #1e2d5f
Rooms      🟧 Orange    #f59e0b → #d97706
Predictions🟥 Red       #ef4444 → #dc2626
Matches    🟪 Purple    #8b5cf6 → #7c3aed
```

### Text Colors:
- **Numbers**: Gold (#ffd230)
- **Labels**: White (#ffffff) at 85% opacity
- **Descriptions**: White (#ffffff) at 50% opacity

### Shadows & Glows:
- **Hover Glow**: rgba(255,210,48,.15) with 40px spread
- **Icon Shadow**: rgba(37, 59, 119, 0.4) at 30px spread

## Animation Timeline

```
Page Load:
│
├─ 0ms    : Stat 1 starts counting (Users)
├─ 200ms  : Stat 2 starts counting (Rooms)
├─ 400ms  : Stat 3 starts counting (Predictions)
├─ 600ms  : Stat 4 starts counting (Matches)
│
└─ 2000ms : All numbers finished (plus 600ms = 2.6s total)

Loader Animation (while counting):
│
├─ 0ms   : Opacity 0.6, Scale 1
├─ 750ms : Opacity 1.0, Scale 1.1 (peak)
└─ 1500ms: Back to 0.6, Scale 1 (repeat)
```

## Hover Effects

```
Normal State:
┌──────────────────┐
│  ┌────────┐      │
│  │   👥   │      │
│  └────────┘      │
│    1,250         │
└──────────────────┘

Hover State (on card):
┌──────────────────┐  ↑ Lift 8px
│  ┌────────┐      │
│  │ 👥(↑)  │  ← Scale 1.1x
│  └────────┘      │
│    1,250         │  Gold border glow
└──────────────────┘  ✨ Enhanced shadow
```

## Responsive Grid

### Desktop (>1024px):
```
┌─────────┬─────────┬─────────┬─────────┐
│  Card1  │  Card2  │  Card3  │  Card4  │
└─────────┴─────────┴─────────┴─────────┘
```

### Tablet (768px-1024px):
```
┌─────────────────┬─────────────────┐
│     Card1       │     Card2       │
├─────────────────┼─────────────────┤
│     Card3       │     Card4       │
└─────────────────┴─────────────────┘
```

### Mobile (<768px):
```
┌──────────────────┐
│     Card1        │
├──────────────────┤
│     Card2        │
├──────────────────┤
│     Card3        │
├──────────────────┤
│     Card4        │
└──────────────────┘
```

## Loading States

### Initial Load:
```
┌──────────────────┐
│  ┌────────┐      │
│  │   👥   │      │
│  └────────┘      │
│      ⏳           │  ← Pulsing loader
│ ACTIVE           │     (0.6 - 1.0 opacity)
│ PREDICTORS       │
└──────────────────┘
```

### After Load (2.6 seconds):
```
┌──────────────────┐
│  ┌────────┐      │
│  │   👥   │      │
│  └────────┘      │
│    1,250         │  ← Final number
│ ACTIVE           │
│ PREDICTORS       │
└──────────────────┘
```

## Spacing Reference

- **Card Padding**: 2.5rem top/bottom, 2rem left/right
- **Gap Between Cards**: 2rem
- **Icon Size**: 80x80px (centered)
- **Icon-to-Content Gap**: 1.2rem
- **Content Gap**: 0.5rem (between items)
- **Min Card Width**: 280px

## Typography Scale

```
Stat Number:     2.8rem, weight 900, #ffd230
Stat Label:      0.9rem, weight 700, uppercase
Stat Description: 0.8rem, weight 400, subdued
```

## Shadow Effects

### Card Shadow:
```css
box-shadow: 
    0 0 40px rgba(255,210,48,.15),  /* Gold glow */
    0 20px 50px rgba(0,0,0,.4)      /* Dark shadow */
```

### Icon Shadow:
```css
box-shadow: 0 0 50px rgba(37, 59, 119, 0.6)  /* On hover */
```

## Browser Rendering

✅ All shadows, glows, and animations render smoothly at 60fps
✅ Smooth cubic-bezier easing for all transitions
✅ GPU-accelerated transforms (translate, scale)
✅ Blur effects supported (backdrop-filter)

---

**Last Updated**: 2026-06-12
**Design System**: PredictCup v2.0
