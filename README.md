# CivicTrack

A Laravel 12 application for reporting infrastructure problems. Visitors submit reports and optional photos; an administrator reviews reports and updates their status, priority, and public official note.

## Local setup (SQLite)

Requires PHP 8.2+, Composer, Node.js 22+, and PHP SQLite, mbstring, and XML extensions.

```sh
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

Set a strong `ADMIN_PASSWORD` in `.env`. If your existing `.env` uses MySQL, change `DB_CONNECTION=sqlite` and remove its `DB_DATABASE` setting (or set an absolute SQLite file path). This does not transfer existing MySQL records.

```sh
php artisan migrate
php artisan storage:link
npm ci
npm run build
php artisan serve
```

Visit `/issues` and `/admin/login`. Admin login is disabled until `ADMIN_PASSWORD` is set. Official notes are visible publicly. Reporter email and phone are shown only to administrators.

## Deploy on Render with SQLite

Render requires a **paid web service with a persistent disk** to retain SQLite records and uploaded photos across restarts. The supplied `render.yaml` uses the Starter plan and a 1 GB disk. Review current costs in Render before creating the service. A free service loses local data on redeploy/restart.

1. Push this project, including `Dockerfile`, `docker/`, and `render.yaml`, to your Git repository. Never commit `.env`, database files, or uploaded photos.
2. In Render choose **New → Blueprint**, connect the repository, and select this directory as the Blueprint location if the repository contains multiple projects.
3. Supply the prompted environment variables:
   - `APP_KEY`: generate locally with `php artisan key:generate --show`. Keep this value unchanged across redeploys.
   - `ADMIN_PASSWORD`: a strong unique password.
   - `APP_URL`: the final HTTPS service URL, for example `https://civictrack.onrender.com`. Update it if Render assigns a different hostname or you add a custom domain.
4. Deploy. The container builds frontend assets, installs production PHP dependencies, creates the database only if missing, runs migrations, links public uploads, and starts Apache on port 80.
5. Verify `/up`, submit a report with a photo, sign in at `/admin/login`, update its status, and redeploy to confirm the report and photo remain available.

The disk mounts at `/var/www/html/storage`; SQLite lives at `/var/www/html/storage/database/database.sqlite` and photos at `storage/app/public`. Migrations run at container startup because Render disks are unavailable during builds and pre-deploy commands. Keep this service at one instance when using SQLite. Existing local data is not uploaded automatically; the deployment starts empty.

If configuring manually, use Docker runtime, health check `/up`, the same disk mount, and all environment values from `render.yaml`. Never generate a new application key automatically at startup. Back up records using SQLite's backup command/API and copy uploaded photos separately; do not copy a live database file without a consistent backup process.

Render documentation: https://render.com/docs/disks and https://render.com/docs/docker

## Verification

```sh
php artisan test
npm run build
```

Feature tests use an isolated in-memory SQLite database. They cover submission and photos, public visibility, administrator login/logout, rate limits, and resolution timestamps.
