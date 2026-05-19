# Poultry Management System

A simple web-based poultry management system built with PHP + SQLite.

## Features
- Admin login and user management
- Track chicken batches (breed, quantity, status)
- Record daily egg collection
- Manage feed inventory
- Record sales and expenses
- Dashboard with key KPIs and trend visualization

## Getting Started
1. Make sure you have [XAMPP](https://www.apachefriends.org/) or a PHP webserver installed.
2. Place this folder in your web root (e.g. `C:/xampp/htdocs/Techfeathers`).
3. Start Apache (and MySQL if needed, though this app uses SQLite).
4. Visit: `http://localhost/Techfeathers/`

### Default Login
- **Email:** `admin@poultry.local`
- **Password:** `admin123`

## Notes
- The system stores data in `data/poultry.db` (SQLite).
- To reset the database, delete `data/poultry.db` and reload the site.

## Extending the System
- Add detailed health tracking (mortality, vaccinations)
- Add reporting exports (CSV/PDF)
- Add user permissions and roles
- Integrate photo uploads for batches
