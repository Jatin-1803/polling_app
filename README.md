# Laravel Poll Management (Real-time)

## What’s included
- Admin signup/login (admin-only dashboard)
- Create polls (1 question + multiple options)
- Public poll listing + shareable poll link
- Vote submission (guest or authenticated)
- Vote-once enforcement per poll (**DB-enforced** via `votes.poll_id + votes.voter_key` unique)
- Real-time vote count updates (Laravel broadcasting + WebSockets)
- Tests (`php artisan test`)

## Setup (Windows / WAMP)
1. Install PHP 8.1+ and Composer
2. In project folder:

```bash
composer install
```

3. Copy env and generate key:

```bash
copy .env.example .env
php artisan key:generate
```

4. Configure DB in `.env` (MySQL recommended), then run migrations:

```bash
php artisan migrate
```

## Admin
- Register an admin at `GET /admin/register`
- Login at `GET /admin/login`
- Dashboard at `GET /admin/polls`

## Public
- Public poll listing: `GET /polls`
- Public poll page (shareable): `GET /polls/{uuid}`

## Real-time / WebSockets (Laravel Reverb)
This project is wired **Laravel Reverb** (Pusher-compatible API).

### Local development (Reverb)
In `.env`:

```env
BROADCAST_DRIVER=reverb

REVERB_APP_ID=local
REVERB_APP_KEY=local
REVERB_APP_SECRET=local
REVERB_APP_CLUSTER=mt1
REVERB_HOST=127.0.0.1
REVERB_PORT=6001
REVERB_SCHEME=http
```

Then, either:

- Run a local Reverb server (if installed in this project), or
- Point these REVERB_* values at a remote Reverb cluster (for example, your Laravel Cloud WebSockets resource).

Finally, start the app:

```bash
php artisan serve
```
## Notes on scalability
- Vote-once enforcement is **database-backed**, so it stays correct under concurrency.
- Vote totals are kept in `poll_options.votes_count` and incremented atomically.
- For very high throughput, move broadcasting to queues + Redis and consider a dedicated WebSocket server like Soketi.

## Run tests
```bash
php artisan test
```