# Harvest Power – COS10026 Applied Web Project Part 2

Group 03. Part 1 static site converted to PHP + MySQL (runs on XAMPP, not on GitHub Pages).

## Setup (each member)
1. XAMPP Control Panel: start **Apache** and **MySQL**.
2. Put this folder at `C:\xampp\htdocs\project2` (folder name must be exactly `project2`).
3. Open http://localhost/phpmyadmin → **Import** → choose `harvest_power.sql` → **Go**.
   This creates the `harvest_power` database with the tables `jobs`, `eoi`, `users`, `about`.
4. Open http://localhost/project2/index.php

Manager login (for the marker): **admin / admin** (stored as a password hash).

## What is already done (skeleton)
- `header.inc`, `nav.inc`, `footer.inc` shared by every page; pages converted to `.php`.
- `settings.php` with the local database settings (user `root`, no password).
- `apply.php` posts to `process_eoi.php`; all HTML validation removed as the brief requires.
- `harvest_power.sql` with all four tables and starting data (2 jobs, admin user, team members).
- `logout.php` works; `manage.php` already redirects to `login.php` when not logged in.

## TODO – split between members
| Requirement (brief) | Files | Owner |
|---|---|---|
| 4. Validate and store EOIs, show EOInumber, block direct access | `process_eoi.php` | ___ |
| 5. Jobs from the database + search bar | `jobs.php` (`search.php` optional) | ___ |
| 6. Login with hashed passwords | `login.php` | ___ |
| 6. Manager queries (list, filter, delete, status, sort) | `manage.php` | ___ |
| 7. About page from the `about` table, add Part 2 contributions | `about.php`, `harvest_power.sql` | ___ |
| Export the final database to `harvest_power.sql` before submitting | phpMyAdmin → Export | ___ |

Each TODO is also written as a comment at the top of the file.

## Rules
- No JavaScript. Relative links only.
- Escape every value printed from the database with `htmlspecialchars()`; use prepared statements for every query with user input.
- Pull before you start, commit small, push when done.

## Acknowledgement of Generative AI use
Claude (Anthropic) was used to generate this starter skeleton (includes, page conversion, database schema and file stubs). The team reviews, completes and tests all code.
