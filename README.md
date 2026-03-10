Installation
---
1. Clone Git repo
   `git clone https://github.com/kooler62/Reverb.git` or `git clone git@github.com:kooler62/Reverb.git`

2. Cd to project folder & install composer dependencies  
   `cd Reverb`  
   `composer install`

3. Create .env file  
   `cp .env.example .env`

4. Configure .env file
   check APP_KEY - if empty `php artisan key:generate`
   check Database credentials

5. Install NPM dependencies
   `npm install`

6. Run migrations
   `php artisan migrate`

7. Seed the database
   `php artisan db:seed`

8. Start Reverb WebSocket server
   `php artisan reverb:start`

9. Start queue worker
   `php artisan queue:listen`

10. Build frontend packages
    `npm run dev`

11. Serve application
    `php artisan serve`
---

В cідах створюються 2 користувача `test@example.com` та `test1@example.com` з паролем `password`

Для аторизації/реєстрації використано laravel breeze  
Повідомлення відображаються без розділень по чатам  
В повідомленнях не реалізовано секьюрна валідація та екранування спец символів  
Статус повідомлень можна змінювати кліком по галочці (тільки для отримувача)  
Додаткових перевірок на online - не реалізовано. Якщо користувач offline - повідомлення залишаются непрочитаними.
Після входу в ручному режимі користувач може змінити їх статус.  
Якщо користувач в мережі то нові повідомлення автоматично читаються.  
received_id и sender_id nullable a не cascadeOnDelete щоб зберігались повідомлення в разі видалення
