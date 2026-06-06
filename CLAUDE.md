# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Running the Application

This project runs on XAMPP (Apache + MySQL). There is no build step — PHP files are served directly.

- **Dependencies**: Run `composer install` (dev) or `composer install --no-dev --optimize-autoloader` (production) before starting Apache.
- **Start**: Launch Apache and MySQL from XAMPP, then open `http://localhost/Proyecto_AplicacionWeb_PHP/public/`
- **Database setup**: Import `database/schema.sql` then `database/seeder.sql` into MySQL
- **Config**: Copy `.env.example` to `.env` and set DB credentials. `APP_URL` must include `/public` (e.g., `APP_URL=http://localhost/Proyecto_AplicacionWeb_PHP/public`)
- **Logs**: Structured logs are written to `storage/logs/app.log` (rotated daily, 14 days). Ensure Apache's user has write permission on that directory.

**Tests:** `composer test` runs the PHPUnit unit test suite (no DB, no Apache required). Tests live in `tests/Unit/` mirroring the `app/` structure. Domain models and services are covered; repositories are excluded (require real DB). CI runs on GitHub Actions on push/PR to `master`.

## Architecture

Custom PHP framework managed via **Composer** (PSR-4 autoload), organized as a classic N-Layer architecture (Presentation / Application / Domain / Infrastructure). Entry point: `public/index.php`.

**Request lifecycle:**

1. `public/index.php` requires `bootstrap/app.php`, which loads `vendor/autoload.php` (Composer PSR-4), initializes phpdotenv, binds dependencies via `config/bindings.php`, instantiates `Router`, loads `routes/web.php`, and returns the router.
2. `Router` resolves the controller from the `Container` (with autowiring) and calls the action method.
3. Controllers call `Service` → `Repository` (typed domain models) → return `ViewModel` → `View::renderWith()`.
4. `View::renderWith()` builds a `LayoutViewModel` (nav, auth, CSRF, flash toasts) and includes `header.php`, the view file, and `footer.php`.

**Directory layout:**

```
app/
  Presentation/
    Controllers/       ← HTTP controllers
    Http/              ← Request, Response
    ViewModels/        ← Typed ViewModels (one per view) + base ViewModel
    Views/             ← PHP templates
  Application/
    Contracts/         ← Service interfaces (e.g. AuthServiceInterface)
    Services/          ← Application services (orchestration, no SQL)
  Domain/
    Models/            ← Domain models with validation via Model::create()
  Infrastructure/
    Contracts/         ← Repository interfaces
    Persistence/
      Repositories/    ← PDO repository implementations
      Database.php     ← PDO singleton
core/                  ← Framework: Router, Container, Auth, Csrf, Flash, View, etc.
config/
  bindings.php         ← DI container bindings
bootstrap/
  app.php              ← Bootstrap: autoloader, container, router setup
routes/
  web.php              ← Route definitions
public/
  index.php            ← Front controller
database/
  schema.sql
  seeder.sql
```

**Key core classes (`core/`):**

- `Container` — DI container with `bind()`, `singleton()`, and reflection-based autowiring. Exposes `Container::setInstance()` / `Container::getInstance()` for global static access (used by `Auth`)
- `Router` — static GET/POST routing; resolves paths with or without `/public` prefix; supports `?route=` fallback
- `View` — `View::renderWith(string $view, ViewModel $vm)` is the only rendering method; no `extract()`, data is passed as a typed `ViewModel`
- `Auth` — session-based auth; `Auth::requireAdmin()` redirects if not logged in or if `$_SESSION['is_admin']` is not `true` (backed by the `is_admin TINYINT` column in `usuarios`). `Auth::isAdmin(): bool` exposes the flag. `Auth::requireLogin()` also attempts transparent re-login via remember-me cookie before redirecting. `Auth::issueRememberCookie(int $userId)` issues the persistent cookie. Uses `Container::getInstance()` internally to resolve `AuthServiceInterface`.
- `Csrf` — `Csrf::token()` generates token, `Csrf::validateOrFail()` checks it in POST handlers
- `Flash` — one-time session messages (`success`/`error`); consumed by `View::buildLayoutViewModel()` and rendered as toasts
- `ErrorHandler` — `ErrorHandler::abort(int $code)` terminates with the appropriate HTTP error view

**ViewModel pattern:**
Every view has a dedicated typed ViewModel class (e.g., `TaxiIndexViewModel`, `TaxiCreateViewModel`) in `app/Presentation/ViewModels/`. All extend `App\Presentation\ViewModels\ViewModel` (abstract base). Views receive data exclusively through the ViewModel — never `extract()` or raw arrays.

**Domain models:**
Models in `app/Domain/Models/` are no longer anemic. Each exposes a `Model::create(...): self` static factory that validates its fields and throws `InvalidArgumentException` on failure. Services call this factory before delegating to the repository, keeping validation inside the domain. `fromRow(array $row): self` remains for hydration from DB results.

**Repositories and interfaces:**
Repositories (`app/Infrastructure/Persistence/Repositories/`) implement interfaces (`app/Infrastructure/Contracts/`). They are bound via the container in `config/bindings.php` and return typed domain model objects.

**Cross-domain joins:**
Repository methods must not JOIN across domain tables. `TaxiService::allWithOwner()` is the reference implementation: it calls `taxiRepository->all()` and `propietarioRepository->all()` separately, then combines in memory.

**Deletion (AJAX):**
`destroy()` methods detect `Request::isAjax()` and return `Response::json()` instead of redirecting. The frontend calls these via Fetch API: on success it stores the message in `sessionStorage` and reloads the page; `toast-config.js` reads it on load and fires the toast. This ensures the toast appears after the list has refreshed. Error toasts (non-AJAX path) are shown inline via `showToastError()`.

**Toast helpers (`public/js/toast-config.js`):**
Exposes `window.showToast(type, title)`, `window.showToastSuccess(title)`, `window.showToastError(title)`. Also reads `sessionStorage.pendingToast` on every page load to display post-reload toasts (used by AJAX delete modules).

## Adding a New Module

1. Create `app/Domain/Models/MyModel.php` with `create()` factory and `fromRow()` hydrator
2. Create `app/Infrastructure/Contracts/MyRepositoryInterface.php`
3. Create `app/Infrastructure/Persistence/Repositories/MyRepository.php` implementing the interface
4. Create `app/Application/Services/MyService.php` (inject repository via constructor)
5. Create `app/Presentation/ViewModels/My*ViewModel.php` classes (one per view)
6. Create `app/Presentation/Controllers/MyController.php` (inject service + `Request`)
7. Create views in `app/Presentation/Views/my-module/`
8. Register repository and service bindings in `config/bindings.php`
9. Add routes to `routes/web.php`
10. Add the navigation item in `core/View.php` → `buildLayoutViewModel()`

**Remember Me:**
Cookie persistente `remember_token` (30 días por defecto). El token en claro va en la cookie; su hash SHA-256 se guarda en `usuarios.remember_token`. Se rota en cada uso. Configuración via `.env`: `REMEMBER_ME_ENABLED`, `REMEMBER_ME_TTL_DAYS`, `REMEMBER_ME_COOKIE_NAME`. `SESSION_LIFETIME` (minutos) controla `session.gc_maxlifetime` y se aplica en `Session::start()`.

**URLs en vistas:**
Usar siempre `app_url('/ruta')` — tanto en vistas standalone (login) como en el layout (header/footer). No usar `$layout->baseUrl` para construir URLs de assets o rutas.

## Conventions

- All PHP files use `declare(strict_types=1)`
- Controllers validate CSRF on every POST: `Csrf::validateOrFail((string) $this->request->post('_token', ''))`
- Admin-only routes call `Auth::requireAdmin()` at the start of every action — this covers all modules (taxis, propietarios, conductores, usuarios). Admin access is determined by `is_admin = 1` in the `usuarios` table, not by username.
- Flash messages use only `'success'` or `'error'` keys; messages must be specific (e.g. `'Taxi creado exitosamente'`, not `'Registro Agregado'`)
- Repositories: `PDO::fetch()` returns `false` (not `null`) when no row is found — always check `$row !== false` before calling `fromRow()`
- `Response::redirect(app_url('/path'))` — use `app_url()` helper for all internal URLs
- Navigation items are hardcoded in `core/View::buildLayoutViewModel()`; add new modules there
- Domain models validate their own fields in `create()`; Services validate cross-domain rules (e.g. FK existence) before calling the factory
- `destroy()` methods: always add `return` after `Response::json()` — never rely on `exit` inside the callee to prevent fall-through
- `seeder.sql` inserts passwords already hashed with `password_hash(..., PASSWORD_DEFAULT)` — no plain-text passwords in seed data
