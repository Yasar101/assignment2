# Project Manager - Procedural PHP Version

## Overview
Simple procedural PHP + MySQL app to manage software projects. Public users can view and search projects. Registered users can register, login, add, edit their own projects, and logout.

## Security features implemented
1. Password hashing using password_hash().
2. Prepared statements (PDO) to prevent SQL injection.
3. CSRF tokens for all forms stored in session.
4. Server-side input validation & sanitization; basic client-side validation.
5. Authorization checks — users can only edit their own projects.
6. Session hardening: session_regenerate_id on login, httpOnly cookies recommended in php.ini.
7. Output escaped with htmlspecialchars to prevent XSS.

## Quick start (local server)
1. Create a MySQL database and import `aproject.sql`.
2. Copy `config.example.php` to `config.php` and update DB credentials.
3. Put the folder in your web server root (e.g., htdocs or www).
4. Ensure PHP sessions are working. Access `index.php`.

## Notes
- This is intentionally simple (no frameworks) for learning and assignments.
- For production, run behind HTTPS, harden PHP config, and use stronger session stores.
