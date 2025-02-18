# Simple Blog
### Fitur :

1. Login (Admin)
2. Forgot Password (Admin)
3. Dashboard (Admin)
4. CRUD Post (Admin)
5. View Post (Home for Public User)
6. Detail Post (Public User)
7. Create comment (Public User)

## Install and Run

1. Clone project dari github

```console
git clone https://github.com/ForceClose31/tc-blog-dafidea.git
```

2. Install composer, npm, buat copy dari .env.example, dan generate APP_KEY 

```console
composer install
npm install
cp .env.example .env
php artisan key:generate
```

3. Pada .env, setting Mailer menggunakan smtp untuk gmail.com 

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Ganti your-email@gmail.com menjadi email yang akan digunakan sebagai contoh (tes@gmail.com) dan pastikan **email valid dan aktif**, kemudian untuk password tambahkan pada fitur add app password bawaan google, 
untuk tutorial lebih lengkapnya bisa cek di link [youtube](https://youtu.be/PeK_tD4T3Og?si=J6iD_dHhXVSXVenD) ini pada menit 0:45 hingga 3:00

4. Pastikan untuk database pada env berisi seperti ini

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=
```

5. Migrasi database serta jalankan seeder

```console
php artisan migrate --seed
```

6. Link storage untuk menyimpan gambar

```console
php artisan storage:link
```

7. Jalankan npm serta laravel

```console
npm run dev
```
lalu new console/terminal
```console
php artisan serve
```

### Untuk login gunakan akun dari seeder

- email : admin3@gmail.com
- password : 123123

### Untuk forgot password/reset password

Pastikan untuk email pada database table user yang awalnya admin3@gmail.com, ganti dengan email bebas aktif anda seperti contoh forceclose11@gmail.com untuk mengetes apakah email reset link password berhasil dikirim atau tidak ke email anda

## Stack
1. Laravel untuk backend menggunakan route api.php dan frontend menggunakan route pada web.php
2. Vue.js untuk frontend
3. Spatie untuk role permission
4. Sanctum untuk auth
