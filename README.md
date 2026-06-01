# ClinicDesk

ClinicDesk is a private PHP clinic management dashboard for three roles: admin, doctor, and patient. It uses a front controller, OOP models, mysqli prepared statements, session authentication, CSRF protection, role checks, controlled prescription downloads, pagination, filters, and CSV reports.

## Setup

1. Create the database:

```sql
SOURCE database/schema.sql;
```

2. Update database credentials in `config/database.php`.

3. Run with PHP's built-in server from this folder:

```bash
php -S localhost:8000
```

4. Open:

```text
http://localhost:8000
```

## Demo Accounts

All seeded accounts use:

```text
Admin@1234
```

- Admin: `admin@clinic.local`
- Doctor: `doctor@clinic.local`
- Patient: `patient@clinic.local`

## Project Notes

- Prescription PDFs are stored in `public/uploads/prescriptions/` and served only through `PrescriptionController::download()`.
- `config/database.php` is listed in `.gitignore`; `config/database.example.php` is the shareable template.
- Views load local assets from `public/assets/adminlte/` only, so no CDN is required for demonstration.

