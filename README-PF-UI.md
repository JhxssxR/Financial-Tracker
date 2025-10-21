PF Trackers UI (Starter)

What’s included
- Routes and controllers for Dashboard, Transactions, Budgets, Savings, Reports
- Dark theme Blade layout at resources/views/layouts/app.blade.php
- Static UI matching the provided mockups with Chart.js placeholders

How to run
1) Start the Laravel app and Vite dev server
   - PHP: php artisan serve (or use your Apache vhost)
   - Assets: npm run dev
2) Open the app and navigate using the top navbar.

Notes
- Tailwind v4 is enabled via @import in resources/css/app.css and the Laravel Vite plugin.
- Chart.js is loaded via CDN in the base layout; replace with local build if preferred.
- Next steps: wire real data, create models/migrations (transactions, budgets, savings), and CRUD actions.
