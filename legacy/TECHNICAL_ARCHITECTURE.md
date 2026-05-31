# 🏛️ Technical Architecture Overview

## System Design

```
┌─────────────────────────────────────────────────────────────────┐
│                         CARE CONNECT                             │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────── PRESENTATION LAYER ────────────────────┐ │
│  │                                                              │ │
│  │  ┌─ SIDEBAR ─────────┐  ┌─ MAIN CONTENT ──────────────┐   │ │
│  │  │ • Navigation      │  │ • Dashboard                  │   │ │
│  │  │ • User Profile    │  │ • Mood Tracker               │   │ │
│  │  │ • Language Toggle │  │ • Journal                    │   │ │
│  │  └───────────────────┘  │ • Meditation                 │   │ │
│  │                         │ • Therapists                 │   │ │
│  │  ┌─ TOPBAR ──────────┐  │ • Groups                     │   │ │
│  │  │ • Page Title      │  │ • Crisis Help                │   │ │
│  │  │ • Actions         │  └──────────────────────────────┘   │ │
│  │  └───────────────────┘                                      │ │
│  │                                                              │ │
│  └──────────────────────────────────────────────────────────────┘ │
│                                                                   │
├─────────────────── LOGIC & TRANSLATION ─────────────────────────┤
│                                                                   │
│  ┌─ LANGUAGE SYSTEM ────────────────────────────────────────┐  │
│  │                                                            │  │
│  │  LANG_DATA                                               │  │
│  │  ├─ en: { nav, pages, dashboard, mood, journal, ... }   │  │
│  │  └─ ur: { nav, pages, dashboard, mood, journal, ... }   │  │
│  │                                                            │  │
│  │  currentLang = 'en' or 'ur'                             │  │
│  │  t(path) → retrieves translated string                  │  │
│  │                                                            │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                   │
│  ┌─ PAGE MANAGEMENT ──────────────────────────────────────────┐  │
│  │                                                              │  │
│  │  go(page, element)    → Show active page                  │  │
│  │  updatePageContent()  → Call page-specific update         │  │
│  │                                                              │  │
│  │  Page Update Functions:                                    │  │
│  │  ├─ updateDashboardPage()  → Dashboard translations       │  │
│  │  ├─ updateMoodPage()       → Mood page translations       │  │
│  │  ├─ updateJournalPage()    → Journal page translations    │  │
│  │  ├─ updateMeditationPage() → Meditation page translate    │  │
│  │  ├─ updateGroupsPage()     → Groups dynamic rendering     │  │
│  │  └─ updateCrisisPage()     → Crisis helplines rendering   │  │
│  │                                                              │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                   │
│  ┌─ FEATURE LOGIC ────────────────────────────────────────────┐  │
│  │                                                              │  │
│  │  Mood Tracking:                                            │  │
│  │  └─ saveMood() → { mood, energy, anxiety, tags, note }   │  │
│  │                                                              │  │
│  │  Journaling:                                               │  │
│  │  └─ saveJ() → { entry, wordCount, date }                 │  │
│  │                                                              │  │
│  │  Therapist Booking:                                        │  │
│  │  └─ confirmBk() → Modal capture + notification           │  │
│  │                                                              │  │
│  │  Group Management:                                         │  │
│  │  └─ joinG() → Track joined groups, update UI             │  │
│  │                                                              │  │
│  │  Meditation:                                               │  │
│  │  └─ toggleB() → 4-7-8 breathing animation                │  │
│  │                                                              │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                   │
├───────────────── DATA PERSISTENCE LAYER ──────────────────────┤
│                                                                   │
│  Browser localStorage                                           │
│  ├─ cc_user       → { name }                                  │
│  ├─ cc_moods      → [ { date, mood, energy, anxiety, ... } ] │
│  ├─ cc_journals   → [ { date, entry, wordCount } ]           │
│  ├─ cc_groups     → [ "group-id-1", "group-id-2", ... ]      │
│  ├─ cc_sessions   → 0 (meditation counter)                   │
│  └─ cc_lang       → "en" or "ur"                             │
│                                                                   │
│  DB Object (Utility)                                            │
│  ├─ DB.get(key)   → JSON.parse(localStorage)                 │
│  ├─ DB.set(key)   → JSON.stringify + localStorage            │
│  └─ DB.arr(key)   → get() || []                              │
│                                                                   │
└───────────────────────────────────────────────────────────────┘
```

---

## Core Objects & Data Structures

### **1. LANG_DATA Structure**
```javascript
const LANG_DATA = {
  en: {
    setup: { title, intro, placeholder, button },
    nav: { main, dashboard, moodTracker, journal, ... },
    pages: { dashboard, mood, journal, therapists, ... },
    dashboard: { welcome, intro, streak, avgMood, ... },
    mood: { title, feeling, verybad, sad, good, great, ... },
    journal: { newEntry, prompt1, prompt2, prompt3, prompt4, ... },
    therapists: { all, filterA, filterD, available, book, ... },
    meditation: { start, technique, begin, pause, resume, ... },
    groups: { anxiety, depression, grief, young, join, joined, ... },
    crisis: { title, intro, umang, rescue, rozan, icall, ... },
    booking: { title, name, email, date, time, cancel, confirm, ... }
  },
  ur: {
    // Same structure, all values in Roman Urdu
  }
};
```

### **2. Mood Entry Structure**
```javascript
{
  date: "2026-04-09",
  mood: 7,              // 1-10 scale
  energy: 6,            // 1-10 slider
  anxiety: 4,           // 1-10 slider
  tags: ["Calm", "Gratitude", "Hope"],
  note: "Great day today!",
  timestamp: 1712687400000  // Optional
}
```

### **3. Journal Entry Structure**
```javascript
{
  date: "2026-04-09",
  entry: "Today I am grateful for...",
  wordCount: 156,
  timestamp: 1712687400000
}
```

### **4. Therapist Object**
```javascript
{
  name: "Dr. Aisha Khan",
  spec: "Clinical Psychologist • 8 years",
  rating: "⭐ 4.9",
  avail: "Today Available",
  tags: ["Anxiety", "Depression", "CBT"],
  icon: "👩‍⚕️",
  bg: "var(--green-bg)"
}
```

---

## Function Stack by Category

### **Navigation Functions**
```
go(page, element)
├─ Hide all pages
├─ Show selected page
├─ Update topbar title/subtitle
│  └─ Call updateUILanguage()
│  └─ Call updatePageContent(page)
└─ Close sidebar

setLanguage(lang)
├─ currentLang = lang
├─ localStorage.setItem('cc_lang', lang)
├─ updateUILanguage()
└─ updatePageContent(getActivePage())

toggleLanguage(event)
└─ newLang = currentLang === 'en' ? 'ur' : 'en'
   └─ setLanguage(newLang)

getActivePage()
└─ Find active .page element, extract ID
```

### **Data Management Functions**
```
startApp()
├─ Validate name input
├─ DB.set('user', {name})
└─ launch(name)

launch(name)
├─ Hide setup screen
├─ Show app
├─ Init dashboard
├─ Load therapists
├─ Render mood history
└─ Render journal list

DB.get(key)
└─ JSON.parse(localStorage.getItem(`cc_${key}`)) || null

DB.set(key, value)
└─ localStorage.setItem(`cc_${key}`, JSON.stringify(value))

DB.arr(key)
└─ DB.get(key) || []
```

### **Mood Tracking Functions**
```
pickM(element, moodScore, emoji, label)
├─ Highlight selected mood
├─ Set selMood = moodScore
└─ Update UI

saveMood()
├─ Validate mood selected
├─ Get energy & anxiety values
├─ Get selected tags
├─ Get note text
├─ Create mood object
└─ DB.set('moods', [...])
   ├─ Recalculate dashboard stats
   └─ Show toast notification

updateDash(name)
├─ Calculate daily streak
├─ Calculate avg mood (7 days)
├─ Count journal entries
├─ Count meditation sessions
└─ Update dashboard display
```

### **Journal Functions**
```
addP(promptText)
└─ Insert promptText into textarea

saveJ()
├─ Get textarea content
├─ Count words
├─ Stamp with date
├─ DB.set('journals', [...])
└─ renderJL()  // Re-render journal list

renderJL()
├─ Get all journals
├─ For each journal:
│  └─ Create card with entry preview
└─ Display in #jList
```

### **Page Update Functions**
```
updateUILanguage()
├─ Update page title & subtitle
├─ Update sidebar labels
├─ Update stats labels
└─ Call updateAllPageText()

updateMoodPage()
├─ Update emotion labels
├─ Update energy/anxiety labels
├─ Update tags text
└─ Update buttons & placeholders

updateJournalPage()
├─ Update title
├─ Update prompt button labels
├─ Update prompt text insertions
├─ Update save button text
└─ Update empty state message

updateMeditationPage()
├─ Update breathing title
├─ Update exercise names
└─ Update button text

updateGroupsPage()
├─ Build group data from LANG_DATA
├─ For each group:
│  ├─ Update name
│  ├─ Update description
│  └─ Update member count
└─ Render all groups

updateCrisisPage()
├─ Update title & description
├─ Build helplines from LANG_DATA
├─ For each helpline:
│  ├─ Inject name, number, hours, button
│  └─ Make data dynamic
└─ Update coping strategy labels
```

### **Meditation Functions**
```
toggleB()
├─ If not active:
│  ├─ Start animation
│  ├─ Begin breathing sequence
│  └─ Loop steps
├─ If active:
│  └─ Pause
└─ If paused:
   └─ Resume

resetB()
└─ Stop animation
   ├─ Reset visual state
   └─ Reset timer

doEx(name, emoji, duration)
└─ Show exercise modal/info
   ├─ Display name, duration, instructions
   └─ Trigger timer (optional)
```

### **Group Functions**
```
joinG(button)
├─ Get group ID
├─ If not joined:
│  ├─ Add to DB.get('groups')
│  ├─ Update button text to "✓ Joined"
│  └─ Show success toast
├─ If joined:
│  ├─ Remove from DB.get('groups')
│  ├─ Reset button text
│  └─ Show left toast
└─ DB.set('groups', updated)
```

### **Booking Functions**
```
openBk(therapistName)
├─ Mark therapist in modal
└─ Show modal overlay

confirmBk()
├─ Get form values (name, email, date, time)
├─ Validate all filled
├─ Show success message
└─ Close modal

closeMod()
├─ Hide modal overlay
└─ Clear form
```

### **Translation & Utility Functions**
```
t(path)
├─ Split path by '.'
├─ Navigate LANG_DATA[currentLang]
├─ For each key:
│  └─ Navigate deeper
└─ Return value || ''

toast(message)
├─ Inject message into toast element
├─ Show for 3 seconds
└─ Auto-hide

getGreeting()
├─ Get current hour
└─ Return time-based greeting (Morning/Afternoon/Evening)

getUrduGreeting()
├─ Get current hour
└─ Return Urdu greeting
```

---

## Event Flow Diagrams

### **Flow 1: User Logs a Mood**
```
User clicks mood emoji
  ↓
pickM(element, score, emoji, label)
  ├─ Highlight emoji
  └─ Set selMood = score
  ↓
User adjusts energy slider
  ├─ evR.oninput updates display
  └─ Energy value stored
  ↓
User adjusts anxiety slider
  ├─ axR.oninput updates display
  └─ Anxiety value stored
  ↓
User selects tags
  ├─ Click toggles .on class
  └─ Tag stored
  ↓
User enters note in textarea
  ├─ Realtime word counter
  └─ Text captured
  ↓
User clicks "Save Mood"
  └─ saveMood()
     ├─ JSON object created
     ├─ DB.set('moods', [...])
     ├─ updateDash() recalculates
     ├─ Dashboard refreshes
     └─ Toast: "✅ Mood saved!"
```

### **Flow 2: Language Switch (EN → UR)**
```
User clicks language toggle
  ↓
toggleLanguage(event)
  └─ currentLang = 'ur'
  ↓
setLanguage('ur')
  ├─ localStorage.setItem('cc_lang', 'ur')
  ├─ updateUILanguage()
  │  ├─ Update sidebar labels
  │  ├─ Update page title/subtitle
  │  └─ Update stats labels
  └─ updatePageContent(getActivePage())
     └─ Call appropriate update function
        (updateMoodPage, updateJournalPage, etc.)
        ├─ All elements query DOM
        ├─ Update textContent
        └─ All visible text now in Urdu
     ↓
Toast button updates: "EN" → "اردو"
  ↓
Navigate to different page → Page renders in Urdu
```

### **Flow 3: Save Journal Entry**
```
User clicks prompt button (e.g., Gratitude)
  ↓
addP("Today I am grateful for:")
  └─ Insert text into textarea
  ↓
User types additional content
  ├─ textarea.oninput updates wordcount
  └─ Show real-time word count
  ↓
User clicks "Save Entry"
  └─ saveJ()
     ├─ Get textarea value
     ├─ Count words
     ├─ Stamp date
     ├─ Create entry object
     ├─ DB.set('journals', [...])
     ├─ renderJL() re-render
     └─ Toast: "📓 Journal entry saved!"
     ↓
Entry appears in "Past Entries"
  └─ Shows truncated preview + timestamp
```

---

## State Management

### **Global State Variables**
```javascript
currentLang          // 'en' or 'ur' - tracks active language
selMood              // null or mood score (1-10)
bActive              // boolean - breathing animation active
bTimer               // setTimeout ID for breathing
bStep                // 0, 1, 2 - which breathing step
```

### **Dynamic Data Sources**
```
DB.get('user')       // { name: "Ahmed" }
DB.arr('moods')      // All mood entries
DB.arr('journals')   // All journal entries
DB.arr('groups')     // Joined groups list
localStorage         // Persists all data
```

---

## Component Interaction Matrix

```
         ↓ Affects
From → | Dashboard | Mood | Journal | Meditation | Groups | Crisis
─────────────────────────────────────────────────────────────────
Dashboard |    —      |  —   |   —    |     —      |   —    |   —
Mood Logger|  ✓ updates stats, streak, chart
Journal   |    ✓ updates entry count
Meditation|    ✓ updates session count
Language  |    ✓ ALL PAGES update translations
Groups    |    —      |  —   |   —    |     —      |   —    |   —
Crisis    |    —      |  —   |   —    |     ✓      |   —    |   —
```

---

## Performance Characteristics

| Operation | Complexity | Time |
|-----------|-----------|------|
| Add mood | O(1) | <10ms |
| Save journal | O(1) | <10ms |
| Calculate streak | O(n) | <50ms (n=days) |
| Render dashboard | O(n) | <100ms (n=entries) |
| Switch language | O(1) | <200ms (DOM updates) |
| Filter therapists | O(n) | <20ms (n=therapists) |
| Join group | O(1) | <10ms |

---

## Mobile Responsiveness

```
Desktop (>1024px)
├─ Sidebar: Fixed left, 240px
├─ Main: Flex fill
└─ Layout: Multi-column grids

Tablet (768px - 1024px)
├─ Sidebar: Collapsible hamburger
├─ Main: Full width
└─ Layout: 2-column grids

Mobile (<768px)
├─ Sidebar: Hidden, overlay on toggle
├─ Main: Full width
├─ Stats: Stacked single column
└─ Cards: Single column layout
```

---

## Security & Privacy

✅ **No Backend Server** → No data transmission  
✅ **localStorage Only** → Data never leaves device  
✅ **No Authentication** → Single-user, privacy by design  
✅ **No External APIs** → All processing local  
✅ **Data Encryption** → Not needed (no transmission)  

---

## Extensibility Points

```
To Add New Language:
├─ Add entry to LANG_DATA (e.g., ur, es, fr)
└─ Language automatically available

To Add New Feature:
├─ Create page HTML
├─ Add data to LANG_DATA
├─ Create update function
├─ Add to navigation

To Connect Backend:
├─ Replace DB.set/get with API calls
├─ Add authentication
└─ Enable multi-device sync
```

---

## Conclusion

Care Connect demonstrates:
- ✅ Clean, modular architecture
- ✅ Separation of concerns (UI, Logic, Data)
- ✅ Scalable language system
- ✅ Efficient data management
- ✅ Responsive, accessible design
- ✅ Privacy-first approach

Perfect as a foundation for enterprise wellness platforms.

---

*Technical Architecture v1.0 - April 2026*
