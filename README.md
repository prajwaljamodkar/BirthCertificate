# Birth Certificate Approval Workflow System

A multi-step, role-based birth certificate issuance system built with **PHP** and **PostgreSQL**.

---

## Workflow

```
User registers & logs in
         │
         ▼
   Fills application form
         │
         ▼  (status: pending)
   Authority 1 reviews
         │
   ┌─────┴─────┐
Approve      Reject
   │              │
   ▼              ▼
(status:       (status:
approved       rejected_
_auth1)        auth1)
   │
   ▼
Authority 2 reviews
   │
   ┌─────┴─────┐
Approve      Reject
   │              │
   ▼              ▼
(status:       (status:
verified)      rejected_
               auth2)
   │
   ▼
User downloads verified
Birth Certificate PDF
```

---

## Prerequisites

| Requirement | Version |
|-------------|---------|
| PHP         | 7.4+    |
| PostgreSQL  | 12+     |
| PDO + pdo_pgsql extension | enabled |
| Web server  | Apache / Nginx / `php -S` |

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/prajwaljamodkar/BirthCertificate.git
cd BirthCertificate
```

### 2. Create the PostgreSQL database

```bash
psql -U postgres
```

```sql
CREATE DATABASE birthcertificate;
\q
```

### 3. Import the schema

```bash
psql -U postgres -d birthcertificate -f database.sql
```

This creates the `users` and `applications` tables and seeds the two default authority accounts.

### 4. Configure the database connection

Edit `config/db.php` and update the constants to match your environment:

```php
define('DB_HOST',   'localhost');
define('DB_PORT',   '5432');
define('DB_NAME',   'birthcertificate');
define('DB_USER',   'postgres');
define('DB_PASS',   'yourpassword');   // <-- change this
```

### 5. Start the built-in PHP web server (for development)

```bash
php -S localhost:8080
```

Open your browser at `http://localhost:8080/`.

---

## Default Credentials

| Role        | Username    | Password   |
|-------------|-------------|------------|
| Authority 1 | `authority1` | `auth1pass` |
| Authority 2 | `authority2` | `auth2pass` |

> Authorities are pre-seeded. Regular users must register through the UI.

---

## File Structure

```
BirthCertificate/
├── index.php                  Entry point — redirects based on login/role
├── database.sql               PostgreSQL schema + seed data
├── fpdf.php                   FPDF library (v1.86, unchanged)
├── config/
│   └── db.php                 PDO database connection helper
├── auth/
│   ├── login.php              Login page (all roles)
│   ├── register.php           Registration page (user role only)
│   └── logout.php             Session destroy + redirect
├── includes/
│   ├── session.php            Session start + role-check helpers
│   ├── header.php             Shared HTML header + navbar
│   └── footer.php             Shared HTML footer
├── user/
│   ├── dashboard.php          List of user's own applications
│   ├── apply.php              New application form
│   └── download.php           PDF certificate download (verified only)
├── authority1/
│   ├── dashboard.php          List of pending applications
│   ├── view_application.php   Full detail view + approve/reject form
│   └── approve.php            POST handler — updates status
├── authority2/
│   ├── dashboard.php          List of auth1-approved applications
│   ├── view_application.php   Full detail view + final approve/reject form
│   └── approve.php            POST handler — sets status to 'verified'
├── m1.php                     (legacy) Original HTML form
├── pdf.php                    (legacy) Direct PDF generation
└── pdf2.php                   (legacy) Sample FPDF test
```

---

## Security Features

- **Password hashing**: `password_hash()` / `password_verify()` (bcrypt)
- **Prepared statements**: All DB queries use PDO prepared statements (SQL injection prevention)
- **Output escaping**: `htmlspecialchars()` on all user-supplied output (XSS prevention)
- **Session-based auth**: Role checked on every protected page
- **Input validation**: Server-side validation of all form fields, including whitelist checks for radio values

---

## Usage Guide

### As a Regular User

1. Navigate to `http://localhost:8080/`
2. Click **Register**, create an account
3. Log in with your credentials
4. Click **Apply** to fill in your birth certificate details
5. Track status on your **Dashboard**
6. Once the status shows **Verified ✓**, click **Download Certificate** to get your PDF

### As Authority 1

1. Log in with `authority1` / `auth1pass`
2. The dashboard shows all **pending** applications
3. Click **View & Verify** to review an application
4. Enter optional remarks and click **Approve** or **Reject**

### As Authority 2

1. Log in with `authority2` / `auth2pass`
2. The dashboard shows applications **approved by Authority 1**
3. Click **View & Verify** to review the full application and Authority 1's remarks
4. Enter optional remarks and click **Approve & Issue Certificate** or **Reject**
5. On approval, the user's status changes to **Verified** and they can download their certificate

---

## Application Status Reference

| Status            | Meaning                                      |
|-------------------|----------------------------------------------|
| `pending`         | Submitted by user, waiting for Auth 1 review |
| `approved_auth1`  | Approved by Authority 1                      |
| `rejected_auth1`  | Rejected by Authority 1                      |
| `verified`        | Fully approved — certificate ready to download |
| `rejected_auth2`  | Rejected by Authority 2                      |
