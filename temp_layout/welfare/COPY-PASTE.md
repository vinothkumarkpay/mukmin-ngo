# Copy-paste checklist (WordPress-matched UI)

Copy these **6 items** into your Laravel project:

```
✅ public/welfare/
✅ resources/views/welfare/
✅ app/Http/Controllers/Welfare/
✅ app/Support/Welfare/          ← Theme helper (campaign bars, etc.)
✅ config/welfare.php
✅ routes/welfare.php
```

Add to `routes/web.php`:

```php
require __DIR__.'/welfare.php';
```

Run:

```bash
composer dump-autoload
php artisan serve
```

Open `http://127.0.0.1:8000/`

## WordPress parity

This package uses the **same CSS, JS, fonts, HTML classes, and demo page structure** as the Welfare WordPress theme:

- `cmsms_row` / `cmsms_column` layout (Content Composer)
- `l_nav` header with top bar + mid + bottom navigation
- Campaign templates (`horizontal` / `vertical` featured)
- Demo home sections: slider, icon boxes, featured cause, cause carousel, quote, news, donators

**Note:** Revolution Slider plugin output is replaced with a static hero image (same demo artwork URL). Install RevSlider in Laravel only if you need animated slides.
