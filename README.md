# OLR Template

A modern, configurable website template for One Loft Race pigeon racing organisations. Built with Laravel, Tailwind CSS, and Alpine.js.

## Features

- Live race results synced from [oneloftrace.live](https://oneloftrace.live)
- Online entry form with package/offer support
- Pool betting system (Hot Spots & Races) with online entry and PDF forms
- Weather data for race days (historical and live)
- Bird performance analysis and search
- Team standings and leaderboards
- Prize structure management
- News/blog with livestream support
- Photo gallery with lightbox
- Multi-language support
- Fully configurable admin panel
- PDF entry form and pool sheet generation
- Mobile-responsive design

## Requirements

- PHP 8.4+
- Composer
- Node.js 18+
- SQLite (default) or MySQL/PostgreSQL

## Installation

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Configuration

All site settings (name, colours, logo, contact details, etc.) are configured via the `.env` file and the admin panel.

## Deployment

A `Dockerfile` is included for containerised deployment on platforms like Render, Railway, or Fly.io.

## Contact

For enquiries about using this template for your loft, contact [stewright88@gmail.com](mailto:stewright88@gmail.com).
