# Work Instruction Portal

## Local run

The local workspace is seeded with SQLite for immediate evaluation:

```powershell
& 'C:\xampp\php\php.exe' artisan serve
```

Open `http://127.0.0.1:8000`. The seeded administrator is `ADMIN001` with password `password`. Change this password before any shared use.

## MySQL deployment

Set the following values in `.env`, then run `php artisan migrate --seed` and `php artisan storage:link`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=work_instruction_portal
DB_USERNAME=your_mysql_user
DB_PASSWORD=your_mysql_password
```

For production, set `APP_ENV=production`, `APP_DEBUG=false`, use a unique `APP_KEY`, and serve Laravel's `public` directory through the web server.
