# Sistem Deteksi Kemiripan Proposal Skripsi

Aplikasi web berbasis Laravel untuk mendeteksi kemiripan dokumen proposal skripsi antar mahasiswa.

Sistem ini melakukan proses perbandingan dokumen untuk mengetahui **persentase kemiripan** antar proposal menggunakan **algoritma Winnowing** — yaitu teknik fingerprinting dokumen berbasis teks untuk mendeteksi kemiripan secara efisien dan akurat.

---

## 🔐 Informasi Login Demo

Website ini memiliki 2 role pengguna:

| Role       | Email                   | Password     |
|------------|--------------------------|--------------|
| Admin      | admin@example.com        | password123  |
| Mahasiswa  | ardiputra@gmail.com      | 123456       |

> 💡 Untuk login sebagai **mahasiswa**, kamu juga bisa **register terlebih dahulu** melalui halaman registrasi.

---

## 📦 Teknologi yang Digunakan

- Laravel
- PHP
- MySQL / SQLite (untuk demo)
- Blade Template
- Laravel Auth
- Winnowing Algorithm (Fingerprinting)

---

## 🚀 Cara Menjalankan Secara Lokal

```bash
git clone https://github.com/Ardiputra92/sistem_deteksi_kemiripan_proposal.git
cd sistem_deteksi_kemiripan_proposal
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

## 📬 Kontak

Dibuat oleh **Ardiansyah Putra Hidayatullah**  
Email: [ardiansyahputra0902@gmail.com](mailto:ardiansyahputra0902@gmail.com)

