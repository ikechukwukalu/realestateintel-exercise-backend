## About This Exercise

Requirements:

- Composer.
- Php 7.4+
- NodeJS.
- MySQL.
- Local Email client [MailHog](https://github.com/mailhog/MailHog).

### Steps To Install

- `git clone https://github.com/ikechukwukalu/realestateintel-exercise-backend`
- For Linux - `cp .env.example .env`
- For Windows - `copy .env.example .env`
- `composer install`
- `npm install && npm run prod`
- Create database `realestate_intel` in MySQL
- `php artisan migrate --seed`
- To run tests `php artisan test`
