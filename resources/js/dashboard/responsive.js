
function initNavigasi() {
    const btnCollapse = document.getElementById('btn-colapses');
    const navigasi = document.getElementById('navigasis');
    const containerDashboard = document.getElementById('container-dashboard');

    // Pastikan elemen dashboard tersedia
    if (!btnCollapse || !navigasi || !containerDashboard) {
        return;
    }

    // Hindari listener terduplikasi pada elemen yang sama
    if (btnCollapse.dataset.listenerReady === 'true') {
        return;
    }

    btnCollapse.dataset.listenerReady = 'true';

    const breakpoint = 760;

    btnCollapse.addEventListener('click', (event) => {
        event.stopPropagation();

        if (window.innerWidth <= breakpoint) {
            navigasi.classList.toggle('navigasihidden');
        } else {
            navigasi.classList.toggle('hiden');
        }
    });

    containerDashboard.addEventListener('click', (event) => {
        if (window.innerWidth > breakpoint) return;
        if (btnCollapse.contains(event.target)) return;
        if (navigasi.contains(event.target)) return;

        navigasi.classList.remove('navigasihidden');
    });
}

// Pertama kali halaman dimuat
document.addEventListener('DOMContentLoaded', initNavigasi);

// Setiap kali Livewire selesai melakukan navigasi
document.addEventListener('livewire:navigated', initNavigasi);
