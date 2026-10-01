# Product Manager - PHP MySQL (PDO)

Aplikasi manajemen produk berbasis web sederhana yang dibuat menggunakan PHP (PDO) dan MySQL. Project ini dirancang untuk mendemonstrasikan penanganan keamanan dasar, validasi input, pencegahan resubmit data (*Post/Redirect/Get*), serta tampilan kartu yang responsif.

---

## 🛠️ Fitur Utama

- **Create**: Menambah produk baru dengan data nama, kategori, harga, dan stok.
- **Read**: Menampilkan daftar produk menggunakan *layout card* yang responsif.
- **Update**: Memperbarui data produk berdasarkan ID.
- **Delete**: Menghapus produk dengan metode `POST` yang dilindungi Token CSRF.
- **Validasi Server-side**:
  - Nama produk minimal 3 karakter.
  - Nama produk harus unik (tidak boleh ganda).
  - Harga harus lebih besar dari `0`.
  - Stok tidak boleh negatif (`>= 0`).
- **Keamanan (Security)**:
  - **Koneksi PDO & Prepared Statements**: Mencegah SQL Injection.
  - **CSRF Token**: Mengamankan form dari serangan *Cross-Site Request Forgery*.
  - **XSS Protection**: Memproses output teks menggunakan `htmlspecialchars()`.
  - **Pencegahan Duplikasi**: Menerapkan pola *Post/Redirect/Get* (PRG) agar data tidak terinput ulang saat halaman di-*refresh*.

---

## 📁 Struktur Folder

```text
product-manager/
│
├── config/
│   └── db.php           # Koneksi PDO & Inisialisasi Session/CSRF
│
├── database/
│   └── store_db.sql     # Skrip pembuat database dan tabel
│
├── public/
│   ├── assets/
│   │   └── style.css    # Styling halaman & responsive card grid
│   ├── index.php        # Halaman utama (Menampilkan daftar produk)
│   ├── create.php       # Form & proses tambah produk
│   ├── edit.php         # Form & proses edit produk
│   └── delete.php       # Proses hapus produk
│
└── README.md            # Dokumentasi project