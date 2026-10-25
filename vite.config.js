import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/js/dashboard/responsive.js','resources/css/dashboard/style.css', 'resources/css/AI/AI-Style.css', 'resources/js/ScanNota/scan.js', 'resources/js/dashboard/grafik1.js', 'resources/js/ScanNota/scan.js', 'resources/css/dashboard/grafik.css','resources/css/MenuList/dashboard.css','resources/css/MenuList/kategoripemasukan.css','resources/css/MenuList/kategoripengeluaran.css','resources/css/LaporanPemasukan/index.css','resources/css/LaporanPengeluaran/index.css','resources/css/MenuList/pemasukan.css', 'resources/js/pemasukan/pemasukanImportExcel.js','resources/css/MenuList/pemasukan.css',
        'resources/css/MenuList/pemasukanTambah.css','resources/css/MenuList/pemasukan.css','resources/css/MenuList/pemasukanEdit.css','resources/css/MenuList/pengeluaran.css','resources/css/MenuList/pengeluaranEdit.css','resources/css/MenuList/pengeluaran.css','resources/js/pengeluaran/pengeluaranImportExcel.js','resources/css/MenuList/pengeluaran.css',
        'resources/css/MenuList/pengeluaranTambah.css','resources/css/MenuList/profile.css','resources/css/MenuList/tabungan.css','resources/css/MenuList/tabungan.css','resources/css/auth/login.css','resources/css/auth/register.css'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
