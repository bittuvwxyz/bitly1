# LinkForge

A secure, framework-free PHP 8.2 URL shortener with a responsive public site, account dashboard, and simple click analytics. LinkForge is an original product name and interface.

## Requirements

- PHP 8.2+ with PDO MySQL
- MySQL 8+
- Apache 2.4+ with `mod_rewrite`

## Installation

1. Create a database and import the schema: `mysql -u root -p < database/database.sql`.
2. Create a least-privilege MySQL account with access to the `linkforge` database.
3. Set `APP_URL`, `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, and optionally `APP_ENV` in Apache/PHP-FPM environment configuration. See `config/config.example.php` for expected values.
4. Point Apache's `DocumentRoot` at `public/` (recommended) and enable `mod_rewrite` with `AllowOverride FileInfo`. If the repository root is the document root, the supplied root `.htaccess` forwards requests to `public/`.
5. Ensure the web server cannot serve `config/`, `includes/`, or `database/` directly when using a nonstandard virtual host configuration.

## Local development

For a quick local PHP server, use `php -S localhost:8000 -t public` after exporting the database environment variables. This server does not apply Apache `.htaccess` rewrite rules; use Apache to test clean short URLs.

## Features and security

- Cryptographically random codes, database uniqueness constraints, aliases, expiration, enable/disable, and deletion.
- PDO native prepared statements; all user data is parameterized and HTML output escaped.
- `password_hash`, `password_verify`, session ID renewal at login, HttpOnly/SameSite cookies, CSRF tokens, headers, and a database-backed login rate limit.
- Each dashboard query and write scopes links to the signed-in user. Redirect tracking stores a salted IP hash and bounded user agent rather than a raw IP.

## Testing

Run `find . -name '*.php' -print0 | xargs -0 -n1 php -l` for syntax checks. Test registration, login/logout, creation, aliases (including duplicate), expiry, enable/disable, delete, ownership, analytics, and redirects in an Apache/MySQL environment. Use the included schema in a disposable database.

## Deployment

Set `APP_ENV=production`, configure HTTPS, set `APP_URL` to the public HTTPS URL, use a dedicated unprivileged database user, import the schema, and configure backups/log rotation. Keep credentials in the hosting environment—not Git. Run `git init`, `git add .`, and commit before pushing to GitHub.

## Troubleshooting

A 503 indicates a database connection issue; verify environment variables, MySQL availability, and account grants. Clean URLs returning 404 usually mean `mod_rewrite` or `AllowOverride FileInfo` is disabled. Check server logs for operational errors; production responses intentionally omit database details.
