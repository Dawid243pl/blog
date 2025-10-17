## Blog Platform

Laravel 12 + Inertia React blog with authentication, role-based authorisation, posts, and comments.

### Requirements
- PHP 8.2+
- Composer
- Node.js 18+ and npm
- MySQL 8.x (server running locally or via Docker/Sail)

### Local Installation
1. Install dependencies:
   ```bash
   composer install
   npm install
   ```
2. Create your environment file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Configure your database connection in `.env` so it points to MySQL. The project ships with values like:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=blog
   DB_USERNAME=root
   DB_PASSWORD=root
   ```
   Update the host, database, username, and password to match your MySQL setup.
4. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
   Seeded accounts:
   - Admin: `david.koleczko.cobc@gmail.com` / `password`
   - User: `testuser@gmail.com` / `password`
5. Build frontend assets or start the dev servers:
   ```bash
   npm run dev       # Vite dev server (use alongside php artisan serve)
   ```

### Running the App Locally
1. Ensure your MySQL service is running and accessible with the credentials configured in `.env`.
2. Start the Laravel backend:
   ```bash
   php artisan serve
   ```
3. Start Vite `npm run dev`.
4. Visit `http://localhost:8000` — you’ll be redirected to `/` where all of the Posts are listed.

### Features
- Laravel Breeze authentication (register, login, logout, password reset, email verification).
- Posts CRUD (create/update/delete restricted to users or admins).
- Commenting for authenticated users and guests, with moderation by post owners, comment owners, or admins.
- Role support via `spatie/laravel-permission` (seeded admin role).
- Sample seed data for users, posts, and comments.

### Docker (Laravel Sail)
If you prefer a containerized setup, a Sail-based `compose.yaml` is included.

1. Make sure Docker Desktop (or another Docker runtime) is running.
2. Complete the steps from **Local Installation** through copying `.env` so dependencies and Sail are available.
3. Adjust `.env` for Sail’s MySQL service, e.g.:
   ```
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```
4. Start the containers:
   ```bash
   ./vendor/bin/sail up -d
   ```
5. Install Node dependencies inside the Sail container (this ensures platform-specific binaries like Rollup use the Linux builds):
   ```bash
   ./vendor/bin/sail npm install
   ```
5. Run migrations and seeders inside the Sail environment:
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
7. Access the app at `http://localhost` (or the port defined by `APP_PORT`). Vite runs on `${VITE_PORT}` if you start it with:
   ```bash
   ./vendor/bin/sail npm run dev
   ```
   If Sail reports that a port is already in use, add overrides to your `.env` before running `sail up`, for example:
   ```
   APP_URL=http://localhost:8080
   APP_PORT=8080      # map the app to http://localhost:8080
   ```

If Sail/Docker fails for any reason, fall back to the **Running the App Locally** section above—just keep the same MySQL credentials (adjusting `DB_HOST` as needed for your environment).

### Running Tests
  - Local (or Sail) CLI Feature Tests:
  ```bash
  php artisan test --filter=BlogTest   
  ```
   - Local (or Sail) CLI Unit Tests:
  ```bash
  php artisan test --filter=ValidationTest
  ```
  - Local (or Sail) CLI All Tests:
  ```bash
  php artisan test
  ```

- Tests use an in-memory SQLite database, so they do not depend on your MySQL containers or local database configuration.
