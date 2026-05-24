# Welfare Theme — Laravel UI Package

Self-contained UI from the **Welfare NGO** ThemeForest theme, ready to copy into any Laravel project.

## Folder structure

```
welfare-laravel-ui/
├── README.md                          ← You are here
├── public/welfare/                    → Copy to YOUR_LARAVEL/public/welfare/
├── resources/views/welfare/           → Copy to YOUR_LARAVEL/resources/views/welfare/
├── app/Http/Controllers/Welfare/      → Copy to YOUR_LARAVEL/app/Http/Controllers/Welfare/
├── app/Support/Welfare/               → Copy to YOUR_LARAVEL/app/Support/Welfare/
├── config/welfare.php                 → Copy to YOUR_LARAVEL/config/welfare.php
└── routes/welfare.php                 → Include from YOUR_LARAVEL/routes/web.php
```

## WordPress UI match

This package mirrors the **Welfare** ThemeForest WordPress theme:

- Original theme **CSS, JS, Fontello icons, and generated color/font styles**
- Same **HTML class names** (`cmsms_row`, `cmsms_icon_box`, `cmsms_campaign`, etc.)
- Same **header layout** (`l_nav`: top bar, logo mid, nav bottom)
- Home page follows the **demo XML** section order and shortcode structure

The Revolution Slider is a **static hero image** (demo artwork). All other sections use theme-native markup.

## Quick install (3 steps)

### 1. Copy folders

From this package into your Laravel project root:

| Copy from | Paste to |
|-----------|----------|
| `public/welfare/` | `public/welfare/` |
| `resources/views/welfare/` | `resources/views/welfare/` |
| `app/Http/Controllers/Welfare/` | `app/Http/Controllers/Welfare/` |
| `app/Support/Welfare/` | `app/Support/Welfare/` |
| `config/welfare.php` | `config/welfare.php` |

### 2. Register routes

**Laravel 11** — add to `routes/web.php`:

```php
require __DIR__.'/welfare.php';
```

Then copy `routes/welfare.php` from this package to `routes/welfare.php` in your project.

**Or** paste the entire `Route::name('welfare.')...` block from `routes/welfare.php` directly into `routes/web.php`.

### 3. Autoload & run

```bash
composer dump-autoload
php artisan serve
```

Visit: `http://127.0.0.1:8000/`

---

## Optional: `.env` settings

Add to your `.env`:

```env
WELFARE_SITE_NAME="Welfare NGO"
WELFARE_TAGLINE="Nonprofit Organization & Charity"
WELFARE_EMAIL=info@example.org
WELFARE_PHONE="1-800-123-1234"
WELFARE_ADDRESS="Brooklyn, NY 10036, United States"
```

---

## Pages included

| URL | Route name | Description |
|-----|------------|-------------|
| `/` | `welfare.home` | Home with hero, stats, campaigns |
| `/about` | `welfare.about` | About + team |
| `/causes` | `welfare.campaigns.index` | All campaigns |
| `/causes/{slug}` | `welfare.campaigns.show` | Single campaign |
| `/blog` | `welfare.blog.index` | Blog listing |
| `/blog/{slug}` | `welfare.blog.show` | Single post |
| `/contact` | `welfare.contact` | Contact form (wire to your backend) |
| `/donate` | `welfare.donate` | Donation form (wire to Stripe/PayPal) |

---

## Customize

- **Navigation / social / colors:** edit `config/welfare.php`
- **Layouts:** `resources/views/welfare/layouts/app.blade.php`
- **Header / footer:** `resources/views/welfare/partials/`
- **Theme helper:** `app/Support/Welfare/Theme.php` (donation progress bars)
- **Controllers:** replace sample arrays with Eloquent models

---

## Use as a prefix (optional)

If your site already has routes on `/`, mount under a prefix:

```php
Route::prefix('charity')->name('welfare.')->group(function () {
  // ... routes from welfare.php
});
```

Then update `config/welfare.php` nav routes or use `url()` instead of `route()`.

---

## Requirements

- Laravel 10 or 11
- PHP 8.1+
- No extra Composer packages required for the UI

---

## Notes

- Sample data is hardcoded in controllers — connect to your database when ready.
- Contact and donate forms POST to `#` — implement `ContactController` and payment logic in Laravel.
- Images on demo pages use Unsplash URLs; replace with your own assets or `storage/`.
- Original WordPress theme assets (CSS/JS/fonts) are in `public/welfare/`.
