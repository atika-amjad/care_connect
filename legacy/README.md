# 🧠 Care Connect - Mental Wellness Platform

## Video Demo

[![Care Connect Demo](https://img.youtube.com/vi/4VTAKrCt2AM/maxresdefault.jpg)](https://www.youtube.com/watch?v=4VTAKrCt2AM)

## 📋 Overview

**Care Connect** is a comprehensive, bilingual (English/Urdu) mental wellness platform designed to help users track their emotional well-being, journal their thoughts, connect with therapists, practice mindfulness, and access crisis support—all in one intuitive web application.

**Key Philosophy**: A safe, inclusive space where users manage their mental health journey with professional support and peer community.

---

## ✨ Core Features

### 1. **🏠 Dashboard**
- **Purpose**: Central hub showing wellness overview and quick access to all features
- **Key Components**:
  - **Personalized Welcome**: Uses time-based greeting (Morning/Afternoon/Evening)
  - **Wellness Stats**: Displays 4 metrics
    - Daily Streak (consecutive days using the app)
    - Avg Mood (7-day average from logged moods)
    - Journal Entries (total entries written)
    - Meditation Sessions (sessions completed)
  - **Mood Chart**: Visual weekly mood trend
  - **Quick Actions**: 4 fast-access cards (Mood Log, Journal, Meditation, Therapist)
  - **Recent Activity**: Shows last mood entries stored

**Data Flow**: Local storage → Dashboard updates on every page refresh

---

### 2. **😊 Mood Tracker**
- **Purpose**: Log daily emotional state with detailed context
- **How It Works**:
  1. User selects mood (Very Bad/Sad/Okay/Good/Great) by clicking emoji
  2. Adjusts Energy Level (1-10 slider)
  3. Adjusts Anxiety Level (1-10 slider)
  4. Tags current feeling (10 tags: Calm, Anxiety, Gratitude, etc.)
  5. Adds optional note
  6. Clicks "Save Mood"

**Data Stored**: 
```javascript
{
  date: "2026-04-09",
  mood: 7,        // 1-10 scale
  energy: 6,      // 1-10
  anxiety: 4,     // 1-10
  tags: ["Calm", "Gratitude"],
  note: "Great day today!"
}
```

**Logic**: Mood history displays in mood tracker page; feeds into dashboard stats calculation

---

### 3. **📓 Journal**
- **Purpose**: Guided reflective writing with structured prompts
- **Features**:
  - **4 Guided Prompts** (with language-aware insertions):
    1. 💚 **Gratitude**: "Today I am grateful for:"
    2. 💭 **Challenge**: "Today was challenging because:"
    3. 🌅 **Tomorrow**: "Tomorrow I hope to:"
    4. 😊 **Happiness**: "I felt happy when:"
  - Real-time word counter
  - Date-stamped entries
  - Entry history with timestamp

**Data Stored**:
```javascript
{
  date: "2026-04-09",
  entry: "Today I am grateful for...",
  wordCount: 156
}
```

**Logic**: Clicking prompt inserts starter text; user completes thought; Save Entry stores in array

---

### 4. **👩‍⚕️ Therapists**
- **Purpose**: Connect users with mental health professionals
- **Features**:
  - **6 Filter Categories**: All, Anxiety, Depression, Trauma, Relationships, Online
  - **Therapist Cards** display: Name, Specialization, Years of Experience, Rating, Availability, Specialties
  - **"Book Now" Button**: Opens appointment booking modal

**Sample Therapist Data**:
```javascript
{
  name: "Dr. Aisha Khan",
  spec: "Clinical Psychologist • 8 years",
  rating: "⭐ 4.9",
  avail: "Today Available",
  tags: ["Anxiety", "Depression", "CBT"]
}
```

**Logic**: Filter onclick function rebuilds therapist grid based on selected category

---

### 5. **🧘 Meditation**
- **Purpose**: Guided breathing exercises and meditation sessions
- **Two Modes**:

**A. Breathing Exercise (4-7-8 Technique)**:
- Visual breathing ring that animates
- Step-by-step instructions:
  - "Breathe In..." (4 seconds)
  - "Hold..." (7 seconds)  
  - "Breathe Out..." (8 seconds)
- Start/Pause/Resume/Reset controls

**B. Preset Exercises** (6 options):
1. 🌅 Morning Calm (10 min, Beginner)
2. 💆 Anxiety Relief (15 min, Guided)
3. 🌙 Deep Sleep (20 min, Evening)
4. 🫀 Body Scan (12 min, Healing)
5. 🎯 Focus Boost (8 min, Work)
6. 💛 Loving Kindness (18 min, Advanced)

**Logic**: Clicking exercise opens demo/info; timer can track completion

---

### 6. **🤝 Support Groups**
- **Purpose**: Peer support community for shared experiences
- **4 Active Groups**:
  1. 🌿 **Anxiety Warriors** (243 members)
  2. 💜 **Depression Support** (189 members)
  3. 🧡 **Grief & Loss** (97 members)
  4. 💙 **Young Adults 18-25** (312 members)

**Group Features**:
- Join/Leave button with status tracking
- Member counts
- Group descriptions and purpose
- Toast notifications on join/leave

**Data Stored**:
```javascript
joinedGroups: ["anxiety-warriors", "young-adults"]
```

---

### 7. **🆘 Crisis Help**
- **Purpose**: Emergency support resources
- **4 Emergency Helplines with details**:
  - Umang Pakistan: 0317-4288665 (Mon–Sat, 3-9 PM)
  - Rescue Emergency: 1122 (24/7)
  - Rozan Counselling: 051-2890505 (Weekdays 9 AM–5 PM)
  - iCall Online Chat: icallhelpline.org (Mon–Sat 8 AM–10 PM)

**Coping Strategies** (Quick access):
- 🌬️ Breathing - Links to meditation
- 📓 Journal - Quick journal access

**Logic**: All helpline data stored in LANG_DATA; dynamically rendered on page load

---

### 8. **🌍 Language Toggle**
- **Purpose**: Seamless bilingual support (English & Roman Urdu)
- **Two Languages Fully Supported**:
  - English (EN)
  - Urdu/Roman (UR)

**Features**:
- Toggle button in top-right corner
- Preference saved to localStorage
- ALL page elements translate instantly
- Covers: UI labels, buttons, prompts, descriptions, helpline info

**Translation System**:
```javascript
LANG_DATA = {
  en: { nav: { dashboard: "Dashboard" } },
  ur: { nav: { dashboard: "Dashboard" } }
}
// Access via: t("nav.dashboard") → returns translated string
```

---

## 🏗️ Architecture & Components

### **1. Data Structure (localStorage)**
```
cc_user       → { name: "Ahmed", ... }
cc_moods      → [ { date, mood, energy, anxiety, tags, note }, ... ]
cc_journals   → [ { date, entry, wordCount }, ... ]
cc_sessions   → 0 (meditation counter)
cc_groups     → [ "anxiety-warriors", ... ] (joined groups)
cc_lang       → "en" or "ur" (language preference)
```

### **2. Core Objects/Functions**

#### **DB (Data Management)**
```javascript
DB.get(key)      // Retrieves from localStorage
DB.set(key, val) // Saves to localStorage
DB.arr(key)      // Gets array or empty array
```

#### **Language System (LANG_DATA)**
- Centralized translation dictionary
- 1000+ translation phrases
- Function `t(path)` retrieves translations using dot notation

#### **Main Navigation Functions**
```javascript
go(page, element)        // Navigate between pages
setLanguage(lang)        // Change language globally
toggleLanguage(event)    // Switch EN ↔ UR
updatePageContent(page)  // Update page-specific text on language change
```

### **3. Page Architecture**

```
┌─ LAYOUT ─────────────────────┐
│ ┌─ SIDEBAR ──────────────┐   │
│ │ • Brand Logo           │   │ ┌─ MAIN ─────────────────────────────┐
│ │ • User Profile         │   │ │ ┌─ TOPBAR ──────────────────────┐ │
│ │ • Navigation (7 items) │   │ │ │ • Page Title & Subtitle       │ │
│ │ • Language Toggle      │   │ │ │ • Notification & Settings     │ │
│ └────────────────────────┘   │ │ └───────────────────────────────┘ │
│                               │ │ ┌─ PAGE CONTENT ────────────────┐ │
│                               │ │ │ (Displays active page)        │ │
│                               │ │ │ Dashboard / Mood / Journal... │ │
│                               │ │ └───────────────────────────────┘ │
│                               │ └─────────────────────────────────────┘
└───────────────────────────────┘
```

### **4. Page Update Functions**

When user navigates OR changes language, these functions update:

| Page | Function | Responsible For |
|------|----------|-----------------|
| Dashboard | `updateDashboardPage()` | Stats, titles, quick actions |
| Mood | `updateMoodPage()` | Emotion labels, energy/anxiety, tags |
| Journal | `updateJournalPage()` | Entry title, prompts, buttons |
| Meditation | `updateMeditationPage()` | Exercise names, button text |
| Groups | `updateGroupsPage()` | Group names, descriptions dynamically |
| Crisis | `updateCrisisPage()` | Helpline info, coping options |
| UI General | `updateUILanguage()` | Sidebar labels, page titles |

---

## 🔄 Key Logic Flows

### **Flow 1: Logging a Mood**
```
User clicks mood emoji
  ↓
pickM(element, moodScore, emoji, label)
  ↓
getEnergyLevel() from slider
getAnxietyLevel() from slider
  ↓
User selects tags
  ↓
User adds note
  ↓
saveMood()
  ↓
Stores to DB.set('moods', [...])
  ↓
Mood appears in: Dashboard stats, Mood history, Mood chart
```

### **Flow 2: Language Switch**
```
User clicks Language Toggle
  ↓
toggleLanguage(event)
  ↓
currentLang = currentLang === 'en' ? 'ur' : 'en'
  ↓
JSON.stringify(currentLang) → localStorage
  ↓
updateUILanguage()      // Update sidebar, titles
  ↓
updatePageContent(activePage)  // Update current page
  ↓
ALL DOM elements update instantly
```

### **Flow 3: Saving Journal Entry**
```
User clicks prompt button
  ↓
addP(promptText) // Inserts starter text
  ↓
User types in textarea
  ↓
saveJ()
  ↓
Extract text from textarea
Count words
Stamp with date
  ↓
DB.set('journals', [...])
  ↓
Entry shows in "Past Entries" section
```

---

## 🎯 Major Components Summary

| Component | Type | Responsibility |
|-----------|------|-----------------|
| **Page Management** | Logic | Show/hide pages, handle navigation |
| **Data Persistence** | Storage | Save all user data to localStorage |
| **Language System** | Logic | Translate 1000+ phrases on demand |
| **Dashboard Stats** | Calculation | Compute streak, averages, counts |
| **Modal Booking** | UI | Appointment scheduling interface |
| **Responsive Design** | UI | Adapt to mobile/tablet/desktop |

---

## 📱 How to Use (User Perspective)

### **Getting Started**
1. Open Care Connect in browser
2. Enter your name in setup screen
3. Click "Get Started"

### **Daily Routine**
1. **Check Dashboard**: See wellness overview
2. **Log Mood**: Click Mood Tracker, select mood + details
3. **Journal**: Write entry using prompts
4. **Meditate**: Pick breathing or exercise
5. **Connect**: Browse therapists or support groups

### **Crisis Mode**
- Click Crisis Help
- See emergency helplines with numbers to call
- Access breathing & journaling coping tools

### **Language**
- Click toggle (EN/اردو) in top-right
- Everything translates instantly

---

## 💾 Data Persistence

**All data stored locally** in browser's localStorage:
- No server required
- Data persists across sessions
- User privacy protected (data never leaves device)

---

## 🛠️ Technology Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Storage** | Browser localStorage API |
| **Fonts** | Google Fonts (Nunito, Lora) |
| **Languages** | English, Roman Urdu |
| **Browser Support** | All modern browsers |

---

## 🎨 Design Principles

- **Color-Coded Categories**: Each feature has distinct color
  - 🟢 Green (Calm, Meditation)
  - 🟣 Purple (Mental Health)
  - 🟠 Orange (Challenges)
  - 🔵 Blue (Balance)
  - 🔴 Red (Crisis)

- **Accessibility**: Large buttons, clear labels, high contrast
- **Bilingual**: Native language support for diverse users
- **Responsive**: Works on mobile, tablet, desktop
- **Minimalist**: Clean UI, no distraction

---

## 📊 Data Flow Diagram

```
┌─ User Input ─┐
│  • Mood      │
│  • Journal   │
│  • Therapist │
└──────┬───────┘
       ↓
┌─ Processing ─┐
│  • Validate  │
│  • Calculate │
│  • Store     │
└──────┬───────┘
       ↓
┌─ localStorage ─┐
│  • cc_moods    │
│  • cc_journals │
│  • cc_groups   │
└──────┬─────────┘
       ↓
┌─ Display ─────┐
│  • Dashboard  │
│  • Charts     │
│  • History    │
└───────────────┘
```

---

## 🔑 Key Functions Reference

### **Navigation & Language**
- `go(page, element)` - Navigate to page
- `setLanguage(lang)` - Set EN/UR
- `t(path)` - Translate string

### **Mood & Tracking**
- `saveMood()` - Store mood entry
- `pickM(el, score, emoji, label)` - Select mood
- `updateDash(name)` - Calculate dashboard stats

### **Journal**
- `saveJ()` - Store journal entry
- `addP(text)` - Insert prompt text

### **Groups**
- `joinG(button)` - Join support group
- `updateGroupsPage()` - Render groups

### **Modal/Booking**
- `openBk(therapist)` - Open booking modal
- `confirmBk()` - Book appointment
- `closeMod()` - Close modal

---

## 📈 Future Features (Potential)

- Mood trend analysis & insights
- AI mood prediction
- Video therapy sessions
- Peer messaging
- Push notifications
- Data export (PDF reports)
- Offline mode
- Dark theme

---

## 📝 Notes for Demo/Presentation

### **What to Show**:
1. **Setup Flow** - Enter name → Welcome
2. **Dashboard** - Overview of wellness
3. **Mood Logging** - Complete a mood entry
4. **Journal** - Write using prompts
5. **Language Toggle** - Switch to Urdu, show everything translates
6. **Meditation** - Start breathing exercise
7. **Crisis Page** - Show helplines & resources
8. **Data Persistence** - Close & reload to show data saved

### **Key Points to Emphasize**:
- ✅ Fully bilingual system
- ✅ Zero backend required (localStorage only)
- ✅ Instant language switching
- ✅ Responsive & mobile-friendly
- ✅ Privacy-first (local storage only)
- ✅ Complete wellness ecosystem in one app

---

## 📞 Support Resources

**Built-in Helplines**:
- Umang Pakistan: 0317-4288665
- Rescue Emergency: 1122
- Rozan Counselling: 051-2890505
- iCall Online: icallhelpline.org

---

## ✅ Conclusion

Care Connect is a **comprehensive, production-ready mental wellness platform** that combines mood tracking, journaling, meditation, therapy access, and crisis support—all with seamless bilingual support. The architecture is clean, scalable, and fully functional for immediate deployment.

**Perfect for**: Mental health awareness, university counseling services, wellness programs, or personal mental health tracking.

---

*Last Updated: April 9, 2026*  
*Version: 1.0 - Complete*
