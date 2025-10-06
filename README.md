```markdown
# DailyDrive — Local Laravel 12 To-Do, Goals & Diary App

**One-line:** DailyDrive is a single-user, local Laravel 12 productivity web app with Daily/Weekly/Monthly tasks, time scheduling, Pomodoro focus mode, motivational quotes (dashboard + daily email), diary + calendar, gamification, chatbot, and PWA & auth support.

> This README summarizes the full 9-stage roadmap, how to install and run the project locally, what has been/will be implemented per stage, key files and commands, environment variables, and deployment notes.

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Key Features](#2-key-features-summary)
3. [Tech Stack & Packages](#3-tech-stack--recommended-packages)
4. [Project Structure](#4-project-structure-short)
5. [Installation](#5-installation-local-dev)
6. [Environment Variables](#6-example-env-essential-variables)
7. [Database, Migrations & Seeders](#7-database-migrations--seeders)
8. [Running the App](#8-running-scheduled-tasks--testing-mail)
9. [Stage-by-Stage Summary](#9-stage-by-stage-summary-whats-done--pending)
10. [How to Use Major Features](#10-how-to-use-major-features-quick-guide)
11. [Deployment Recommendations](#11-deployment-recommendations)
12. [Troubleshooting & Common Commands](#12-troubleshooting--common-commands)
13. [Future Improvements](#13-future-improvements--contributor-notes)

---

## 1. Project Overview

DailyDrive helps you plan and track daily life: time-scheduled tasks (e.g., 3–4 PM Play), recurring goals, short diary entries, a Pomodoro focus mode, motivational quotes daily (shown on dashboard and emailed each morning), gamification (points, streaks, badges), a chatbot for quick commands, and PWA capabilities for offline usage and installability.

Designed as a **single-user** app for local development with **MySQL** (can use SQLite for portability). Frontend uses **Blade + Bootstrap** and a bit of custom CSS/JS.

---

## 2. Key Features (Summary)

* Task CRUD (Daily / Weekly / Monthly)
* Time scheduling per daily task (`start_time`, `end_time`)
* Pomodoro timer (25 min focus / 5 min break, pause/reset)
* Goals model & progress % (goals → tasks)
* Motivational quotes (DB seeded): shown on dashboard + emailed daily
* Scheduler & mailable (`quotes:send`) — configurable by `.env`
* Diary (CRUD) with calendar view (FullCalendar)
* Gamification: points per task, streaks, badges, analytics (Chart.js + heatmap)
* Chatbot (rule-based; optional AI mode via API)
* Auth (login/register) and session management
* Progressive Web App (manifest + service worker + installable)

---

## 3. Tech Stack & Recommended Packages

* PHP 8.2+
* Laravel 12
* MySQL (local) — or SQLite for local fallback
* Blade templates, Bootstrap 5, custom CSS
* JavaScript: Vanilla or small libs (FullCalendar, Chart.js) via CDN or npm
* Suggested Laravel packages (optional):
  * `laravel/breeze` or `laravel/fortify` for auth scaffolding
  * `spatie/laravel-permission` (if multi-user/roles later)
  * `meilisearch/laravel-scout` or `laravel/scout` + Meili for diary search (optional)
  * `spatie/laravel-google-calendar` for future calendar sync (optional)
* Mail: Mailtrap (dev) or Gmail SMTP (user)

---

## 4. Project Structure (Short)

```
dailydrive/
├─ app/
│  ├─ Http/Controllers/ (TaskController, GoalController, DiaryController, PomodoroController, ChatController, ProgressController, CalendarController)
│  ├─ Models/ (Task, Goal, Quote, DiaryEntry, PomodoroSession, UserStat, ChatHistory)
│  ├─ Mail/ (MotivationalQuoteMail)
│  └─ Services/ (QuotePicker, ChatbotService)
├─ database/
│  ├─ migrations/
│  └─ seeders/
├─ resources/views/ (layouts, tasks/, goals/, diary/, pomodoro/, progress/, chat/)
├─ public/
│  ├─ css/custom.css
│  ├─ js/pomodoro.js, calendar.js, chat.js, progress.js, pwa.js
│  ├─ manifest.json
│  └─ service-worker.js
└─ routes/web.php
```

---

## 5. Installation (Local Dev)

> Example uses MySQL and Composer.

1. Clone repo:
```bash
git clone <your-repo-url> dailydrive
cd dailydrive
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install frontend dependencies (if using npm for build):
```bash
npm install
npm run dev   # or npm run build
```

4. Copy `.env` and generate app key:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure DB in `.env` (see next section).

6. Create DB & run migrations + seeders:
```bash
php artisan migrate --seed
# or: php artisan migrate
# then: php artisan db:seed --class=QuoteSeeder
```

7. Create a user (if auth enabled):
```bash
# Option A: register via UI at /register
# Option B: create from Tinker
php artisan tinker
>>> \App\Models\User::create(['name'=>'You','email'=>'you@example.com','password'=>bcrypt('secret')]);
```

8. Start dev server:
```bash
php artisan serve
# default: http://127.0.0.1:8000
```

---

## 6. Example `.env` (Essential Variables)

```env
APP_NAME="DailyDrive"
APP_ENV=local
APP_KEY=base64:...
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dailydrive
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@dailydrive.local
MAIL_FROM_NAME="DailyDrive"

USER_EMAIL=youremail@example.com   # recipient for daily quote
TIMEZONE=Asia/Kolkata

# Chatbot
CHATBOT_AI_ENABLED=false
OPENAI_API_KEY= # if using AI mode

# PWA
PWA_NAME="DailyDrive"
```

> **Notes:**
> * Use Mailtrap credentials for development/testing. Replace with Gmail or other SMTP for production.
> * `USER_EMAIL` is used as default recipient for daily motivational mail (single-user).

---

## 7. Database: Migrations & Seeders

Run migrations:
```bash
php artisan migrate
```

Seeders:
* `QuoteSeeder` seeds motivational quotes.
* `TaskSeeder` (optional) seeds example tasks.

Run seeders:
```bash
php artisan db:seed --class=QuoteSeeder
php artisan db:seed --class=TaskSeeder   # optional
```

Key tables: `tasks`, `goals`, `quotes`, `diary_entries`, `pomodoro_sessions`, `user_stats`, `chat_histories`, `users`.

---

## 8. Running Scheduled Tasks & Testing Mail

### Scheduler (Daily Quote)
* There is an Artisan command:
```bash
php artisan quotes:send
```

* To run scheduler locally for testing:
```bash
php artisan schedule:run
```

* For production, you must run the scheduler every minute via cron or background worker:
```
* * * * * cd /path/to/dailydrive && php artisan schedule:run >> /dev/null 2>&1
```
(Or use `php artisan schedule:work` / background worker in hosts like Render)

### Test Mail Locally
* Configure Mailtrap or SMTP in `.env`. Then:
```bash
php artisan quotes:send
```
* Check Mailtrap or inbox.

---

## 9. Stage-by-Stage Summary (What's Done / Pending)

| Stage | Name | Status | Key Features |
|-------|------|--------|--------------|
| 1 | Basic Task Management | ✅ Complete | Tasks CRUD, Blade, Bootstrap |
| 2 | Goals & Motivation | ✅ Complete | Sections, Goals, Quotes, Dashboard |
| 3 | Email Notifications | 🚧 In Progress | Mailable + Scheduler created; needs SMTP testing |
| 4 | Time Scheduling | ✅ Complete | Time fields, chronological ordering, visual status |
| 5 | Pomodoro Timer | ✅ Complete | 25/5 timer, session tracking, task linking |
| 6 | Diary & Calendar | ✅ Complete | Diary CRUD, FullCalendar integration |
| 7 | Gamification | ✅ Complete | Points, streaks, badges, analytics |
| 8 | Chatbot | ✅ Complete | Rule-based commands, optional AI mode |
| 9 | Auth & PWA | ✅ Complete | Login/register, PWA manifest & service worker |

> **Current Action Items:**
> * Configure an SMTP provider and test `quotes:send`.
> * If deploying: set up background worker/cron for scheduler on host.
> * Optionally enable AI chatbot by setting `CHATBOT_AI_ENABLED=true` and `OPENAI_API_KEY`.

---

## 10. How to Use Major Features (Quick Guide)

* **Tasks:** Go to `/tasks` — add title, description, optional time. Mark complete from UI, or toggle via AJAX.
* **Schedule view:** Dashboard → Daily tab shows time-ordered blocks. Current task is highlighted.
* **Pomodoro:** Navbar → Pomodoro. Link session to a task or run standalone. Start/Pause/Reset in-browser.
* **Diary:** Navbar → Diary. Create daily entries (no images). Use date picker.
* **Calendar:** Navbar → Calendar. FullCalendar loads tasks (with time) and diary (all-day).
* **Quotes:** Dashboard top shows random quote. Daily quote emailed at configured time via scheduler.
* **Progress:** Navbar → Progress. View points, streaks, badges, weekly/monthly charts and heatmap.
* **Chatbot:** Floating widget bottom-right. Try commands: `tasks today`, `quote`, `start pomodoro`, `show diary`.
* **Install PWA:** Visit site on supported browser → Add to home screen prompt / install option shows.

---

## 11. Deployment Recommendations

* **Why not Vercel/Netlify?** They are primarily for static/frontends and serverless functions. Laravel full backend requires a PHP host (with composer, cron, DB).
* **Free / low-cost hosts that work well with Laravel:**
  * **Render** — easy Git deployment; background workers for `schedule:work`.
  * **Railway** — simple deployment + managed DB add-ons (free credits may apply).
  * **Fly.io** — runs Dockerized Laravel app globally.

* **Deployment Checklist:**
  * Push repo to GitHub. Configure host to build composer install, migrate, seed.
  * Set environment variables on host (DB, MAIL, APP_KEY).
  * Configure persistent DB (MySQL/Postgres) — don't use ephemeral DB.
  * Configure web worker for scheduler (or cron).
  * Serve `public/` folder correctly and enable HTTPS.

---

## 12. Troubleshooting & Common Commands

* Migrate fresh:
```bash
php artisan migrate:fresh --seed
```

* Clear cache/config:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

* Seed quotes:
```bash
php artisan db:seed --class=QuoteSeeder
```

* Run scheduler manually:
```bash
php artisan schedule:run
```

* Test mail:
```bash
php artisan quotes:send
```

---

## 13. Future Improvements & Contributor Notes

* Add OAuth login (Google) and multi-device sync (Sanctum + API) for cross-device usage.
* Add push notifications (PWA push) for task reminders.
* Add advanced recurrence rules for tasks (iCal RRULE style).
* Optionally switch chatbot to AI mode (OpenAI/other) for natural conversation.
* Add export/import: JSON/CSV/ICS and automated backups.

---

## A Final Note

This README is designed to be copy-pasted into your repo as `README.md`. The project follows Laravel 12 best practices with a clean, maintainable codebase that's perfect for personal productivity tracking.

**Ready to start?** Run `php artisan serve` and visit `http://127.0.0.1:8000` to begin using DailyDrive!
```

This README.md file is ready to copy-paste into your repository. It includes all the essential information for users and developers, follows proper markdown formatting, and maintains the professional structure you requested.