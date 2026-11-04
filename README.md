# 🏢 HRIS & Absensi App (Laravel Geofencing GPS)

Aplikasi **Human Resource Information System (HRIS)** dan Sistem Absensi Karyawan berbasis web yang dikembangkan menggunakan **Laravel 9** dengan fitur unggulan **GPS-Based Geofencing** untuk memastikan absensi karyawan sesuai dengan radius lokasi kantor yang ditentukan.

---

## ✨ Fitur Utama

### 👤 Portal Karyawan (Employee)
* **Absen Masuk & Pulang Berbasis GPS (Geofencing)**: Memvalidasi posisi koordinat perangkat karyawan menggunakan rumus *Haversine* terhadap titik pusat kantor dan batas radius maksimal.
* **Riwayat Absensi**: Memantau catatan riwayat kehadiran secara *real-time*.
* **Pengajuan Cuti / Izin**: Form pengajuan cuti dan izin secara digital.
* **Profil Mandiri**: Mengelola data diri dan informasi karyawan.

### 🛠️ Panel Admin / HRD (Administrator)
* **Dashboard Statistik**: Memantau total karyawan, rekap kehadiran harian, dan persetujuan cuti.
* **Manajemen Pengaturan Koordinat Kantor (Dinamis)**: Admin dapat mengubah titik pusat kantor (`Latitude` & `Longitude`) serta jarak maksimal radius GPS langsung melalui panel web tanpa mengubah kode program.
* **Manajemen Karyawan & Shift Kerja**: Mengelola data pegawai dan pengaturan shift kerja.
* **Laporan Kehadiran**: Ekspor laporan absensi dalam format Excel (CSV) dan Cetak PDF.

---

## 💻 Tech Stack
* **Framework Backend:** Laravel 9 (PHP 8.0+)
* **Database:** MySQL / SQLite
* **Frontend UI:** Tailwind CSS, Blade Templates
* **Geolokasi:** HTML5 Geolocation API & Haversine Formula

---

<img width="920" height="595" alt="image" src="https://github.com/user-attachments/assets/845f93cb-e15f-49ca-ad46-49cfebd6b3fd" />

<img width="896" height="506" alt="image" src="https://github.com/user-attachments/assets/421d0ea1-6628-4770-affb-92057e7e2303" />

<img width="782" height="371" alt="image" src="https://github.com/user-attachments/assets/81538955-0506-4609-8fd6-8f4cdc200091" />

<img width="708" height="453" alt="image" src="https://github.com/user-attachments/assets/c35457d9-f050-4906-80a5-d4b2101c02c8" />

