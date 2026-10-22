//  document.addEventListener('DOMContentLoaded',function(){

//  const root = $wire.$el;

//     const tombolScan = root.querySelector('[data-scan-button]');
//     const modalElement = root.querySelector('[data-scan-modal]');
//     const video = root.querySelector('[data-scan-video]');
//     const canvas = root.querySelector('[data-scan-canvas]');
//     const status = root.querySelector('[data-scan-status]');
//     const tombolAmbil = root.querySelector('[data-scan-capture]');
//     const tombolUlang = root.querySelector('[data-scan-again]');

//     const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
//     const mediaHP = window.matchMedia('(max-width: 767.98px)');

//     let stream = null;
//     let sedangUpload = false;

//     function aturTombolScan() {
//         tombolScan.disabled = !mediaHP.matches;
//         tombolScan.title = mediaHP.matches
//             ? 'Buka kamera untuk scan nota'
//             : 'Scan Nota tersedia melalui HP';

//         if (!mediaHP.matches && modalElement.classList.contains('show')) {
//             modal.hide();
//         }
//     }

//     function hentikanKamera() {
//         if (stream) {
//             stream.getTracks().forEach(track => track.stop());
//             stream = null;
//         }

//         video.pause();
//         video.srcObject = null;
//     }

//     async function mulaiKamera() {
//         if (stream) return;

//         if (!navigator.mediaDevices?.getUserMedia) {
//             status.textContent =
//                 'Kamera membutuhkan browser yang mendukung akses kamera melalui HTTPS atau localhost.';
//             return;
//         }

//         status.textContent = 'Meminta izin kamera...';

//         try {
//             stream = await navigator.mediaDevices.getUserMedia({
//                 audio: false,
//                 video: {
//                     facingMode: { ideal: 'environment' },
//                     width: { ideal: 1280 },
//                     height: { ideal: 720 }
//                 }
//             });

//             video.srcObject = stream;
//             await video.play();

//             tombolAmbil.disabled = false;
//             status.textContent =
//                 'Kamera siap. Arahkan ke nota dan pastikan tulisannya jelas.';
//         } catch (error) {
//             hentikanKamera();

//             status.textContent = error.name === 'NotAllowedError'
//                 ? 'Akses kamera ditolak. Izinkan kamera pada browser.'
//                 : 'Kamera gagal dibuka. Periksa izin kamera dan HTTPS.';

//             console.error('Kesalahan kamera:', error);
//         }
//     }

//     tombolScan.addEventListener('click', function () {
//         if (mediaHP.matches) modal.show();
//     });

//     modalElement.addEventListener('shown.bs.modal', mulaiKamera);
//     modalElement.addEventListener('hidden.bs.modal', hentikanKamera);

//     mediaHP.addEventListener('change', aturTombolScan);
//     aturTombolScan();

//     tombolAmbil.addEventListener('click', async function () {
//         if (sedangUpload) return;

//         if (!stream || !video.videoWidth) {
//             status.textContent = 'Tunggu sampai kamera siap.';
//             return;
//         }

//         try {
//             canvas.width = video.videoWidth;
//             canvas.height = video.videoHeight;

//             canvas.getContext('2d').drawImage(
//                 video,
//                 0,
//                 0,
//                 canvas.width,
//                 canvas.height
//             );

//             // Ubah gambar kamera menjadi file JPEG.
//             const blob = await new Promise((resolve, reject) => {
//                 canvas.toBlob(
//                     result => result
//                         ? resolve(result)
//                         : reject(new Error('Gambar gagal dibuat.')),
//                     'image/jpeg',
//                     0.85
//                 );
//             });

//             const file = new File(
//                 [blob],
//                 'nota.jpg',
//                 { type: 'image/jpeg' }
//             );

//             hentikanKamera();

//             sedangUpload = true;
//             tombolAmbil.disabled = true;
//             tombolUlang.disabled = true;
//             tombolUlang.classList.remove('d-none');
//             status.textContent = 'Mengunggah gambar ke Livewire...';

//             // Upload sementara ke komponen Livewire, lalu jalankan Prism.
//             $wire.upload(
//                 'fotoNota',
//                 file,
//                 () => {
//                     status.textContent = 'Gambar diterima. Prism sedang memproses nota...';

//                     $wire.scanNota()
//                         .then(() => {
//                             status.textContent =
//                                 'Pemrosesan selesai. Periksa nama, harga, kategori, dan tanggal.';
//                         })
//                         .catch(error => {
//                             status.textContent =
//                                 'Pemrosesan gagal. Periksa pesan kesalahan pada formulir.';
//                             console.error(error);
//                         })
//                         .finally(() => {
//                             sedangUpload = false;
//                             tombolUlang.disabled = false;
//                         });
//                 },
//                 error => {
//                     sedangUpload = false;
//                     tombolAmbil.disabled = false;
//                     tombolUlang.disabled = false;

//                     status.textContent =
//                         'Upload gambar gagal. Periksa koneksi lalu coba kembali.';

//                     console.error(error);
//                 },
//                 event => {
//                     status.textContent =
//                         'Mengunggah gambar: ' +
//                         event.detail.progress + '%';
//                 }
//             );
//         } catch (error) {
//             sedangUpload = false;
//             tombolAmbil.disabled = false;

//             status.textContent =
//                 error.message || 'Gagal mengambil gambar nota.';

//             console.error(error);
//         }
//     });

//     tombolUlang.addEventListener('click', async function () {
//         if (sedangUpload) return;

//         hentikanKamera();

//         tombolAmbil.disabled = true;
//         tombolUlang.disabled = true;

//         try {
//             await $wire.resetScan();

//             await mulaiKamera();
//         } finally {
//             tombolUlang.disabled = false;
//         }
//     });
//  })