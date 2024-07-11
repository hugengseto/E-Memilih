# Petunjutk Instalasi aplikasi E-Memilih

## Persyaratan
- PHP 8.1 atau lebih
- Sudah terpasang Composer
- Sudah terpasang XAMPP
- Disarankan menggunakan VS Code

## Langkah-langah memasang E-Memilih
1. Lakukan clone repository ini / downlaod file zipnya
2. buka terminal di VS Code CTRL+` (pastikan berada di directori folder project)
3. jalankan perintah "composer install" (pastikan tidak ada yang error)
4. buat database dengan nama db_memilih
5. selanjutnya, jalankan perintah "php spark migrate". ini untuk membuat tabel-tabel pada database
6. jalankan perintah "php spark serve" untuk menjalankan aplikasinya.

## Akun untuk masuk E-Memilih
- username : Administrator
- password : @Admin123

## Tambahan 
untuk dapat menggunakan fitur whatsapp silahkan mendaftarkan/buat akun di https://fonnte.com/ , ini digunakan untuk fitur pengiriman kode otp serah hak suara oleh peserta
- perhatikan untuk konfigurasi fitur kirim wa ada pada controller HomeController pada method _sendOtpViaWa

## Perlu Ditanyakan atau Diskusi
<a href="mailto:hugengseto@gmail.com">
<img src="https://img.shields.io/badge/Gmail-D14836?style=for-the-badge&logo=gmail&logoColor=white" alt="Gmail Badge"/>
</a>

<a href="https://www.instagram.com/hugengseto/">
      <img src="https://img.shields.io/badge/Instagram-%23E4405F.svg?&style=for-the-badge&logo=instagram&logoColor=white" alt="Instagram Badge"/>
</a>
