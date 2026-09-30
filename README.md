# Miáo (Laravel + Blade + MySQL)

1. Buat folder kosong `Miao_UAS`, buka terminal di dalamnya, jalankan: `composer create-project laravel/laravel .`
2. Ekstrak zip ini ke folder yang sama, pilih **Replace** kalau ditanya.
3. Salin folder `img/` dari project lama ke `public/img/`.
4. Buat database MySQL kosong bernama `miao_uas` (XAMPP/Laragon > phpMyAdmin).
5. Edit `.env`:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=miao_uas
   DB_USERNAME=root
   DB_PASSWORD=
   SESSION_EXPIRE_ON_CLOSE=true
6. `php artisan migrate`
7. `php artisan serve` lalu buka http://127.0.0.1:8000
