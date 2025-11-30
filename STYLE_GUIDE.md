# TODOLIST UI/UX Style Guide

Tujuan: Menyediakan sistem desain konsisten, modern, dan terukur untuk seluruh halaman aplikasi.

## Design Tokens

- Warna
  - `--color-bg`: warna latar aplikasi (light/dark)
  - `--color-surface`: warna permukaan komponen (card, modal)
  - `--color-text`: warna teks utama
  - `--color-text-muted`: teks sekunder/muted
  - `--color-border`: warna garis/batas
  - Aksen: `--color-primary`, `--color-success`, `--color-warning`, `--color-danger`

- Tipografi
  - Font: `Inter`, fallback `system-ui`
  - Skala: `--text-xs`, `--text-sm`, `--text-base`, `--text-lg`, `--text-xl`, `--text-2xl`, `--text-3xl`
  - Hierarki: `h1` tebal dan besar, `h2`/`h3` sedang, `p` line-height 1.6

- Spacing
  - Skala ruang: `--space-1` … `--space-10`
  - Gunakan konsisten untuk padding/margin (contoh: `var(--space-4)` untuk komponen utama)

- Radius & Shadow
  - Radius: `--radius-sm`, `--radius-md`, `--radius-lg`, `--radius-xl`
  - Elevasi: `--shadow-sm`, `--shadow-md`, `--shadow-lg`

- Transisi
  - Easing: `--ease`
  - Durasi: `--duration-fast`, `--duration-normal`, `--duration-slow`

Semua tokens didefinisikan pada `resources/css/app.css` di `@layer base :root`, dengan varian `dark` override.

## Komponen Dasar

- Button
  - Kelas: `.btn`, varian: `.btn-primary`, `.btn-secondary`, `.btn-success`, `.btn-warning`, `.btn-danger`
  - Interaksi: hover menaikkan elevasi, active menurunkan; disabled set `opacity: 0.6`
  - Gunakan untuk semua aksi utama/sampingan secara konsisten

- Input & Select
  - Kelas: `.form-input`, `.form-select`
  - Fokus: outline halus dengan warna `--color-primary`, jangan pakai outline default browser
  - Validasi: warna kesalahan melalui kelas utilitas Tailwind tambahan jika diperlukan

- Card
  - Kelas: `.card`
  - Padding default `--space-4`, border `--color-border`, elevasi `--shadow-sm`, hover `--shadow-md`

- Badge
  - Kelas: `.badge`
  - Gunakan untuk menandai status ringan/label kecil

- Notification
  - Kelas wrapper: `.notification-success`, `.notification-error`, `.notification-warning`, `.notification-info`
  - Aksesibilitas: gunakan `role="status"`/`role="alert"` dan `aria-live` sesuai konteks

## Pola Interaksi

- Navigasi
  - Link menampilkan underline animasi saat hover, gunakan elemen `<nav>` untuk grup link
  - Tambahkan `aria-current="page"` untuk link aktif jika tersedia

- Tema Gelap/Terang
  - Toggle menyetel class `.dark` pada `document.documentElement`, tokens otomatis menyesuaikan
  - Simpan preferensi pada `localStorage('theme')`

- Animasi Mikro
  - Hindari animasi berlebihan; gunakan durasi `--duration-fast`/`--duration-normal`
  - Pertimbangkan `prefers-reduced-motion: reduce` (sudah didukung di CSS)

- Drag & Drop
  - Kelas tambahan `.sortable-drag` untuk umpan balik visual selama drag (sinkron dengan Sortable.js)

## Maintainability Best Practices

- Konsistensi
  - Selalu gunakan kelas komponen yang tersedia (hindari style inline)
  - Hindari variasi manual untuk spacing/radius/shadow di luar tokens

- Skalabilitas
  - Tambah varian baru melalui tokens dan kelas komponen (hindari duplikasi)
  - Perluas komponen di `@layer components` pada `resources/css/app.css`

- Aksesibilitas
  - Pastikan kontras cukup antara teks dan latar (WCAG AA)
  - Gunakan `role`, `aria-live`, dan `:focus-visible` yang sesuai

- Struktur File
  - `resources/css/app.css`: tokens, komponen, utilitas
  - `resources/js/app.js`: interaksi (dark mode, sortable, notifikasi)

## Contoh Penggunaan

```html
<button class="btn-primary">Simpan</button>
<input class="form-input" placeholder="Judul task" />
<div class="card">
  <h3 class="mb-2">Ringkasan</h3>
  <p class="text-sm text-gray-600">Konten card…</p>
  <span class="badge">Baru</span>
  <div class="mt-4 flex gap-2">
    <button class="btn-secondary">Batal</button>
    <button class="btn-success">Konfirmasi</button>
  </div>
 </div>
```

Dengan panduan ini, UI/UX aplikasi tetap modern, konsisten, dan mudah dirawat.

