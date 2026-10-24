
function initIncomeImportDropzones() {
    const dropzones = document.querySelectorAll(
        '[data-income-import-dropzone]'
    );

    dropzones.forEach((dropzone) => {
        if (dropzone.dataset.initialized === 'true') {
            return;
        }

        const input = dropzone.querySelector(
            '[data-income-import-input]'
        );

        const filename = dropzone.querySelector(
            '[data-income-import-filename]'
        );

        if (!input || !filename) {
            console.error(
                '[KiraAI] Elemen upload Excel tidak lengkap.'
            );

            return;
        }

        dropzone.dataset.initialized = 'true';

        function updateFilename() {
            const file = input.files?.[0];

            if (!file) {
                filename.textContent = 'Belum ada file yang dipilih';
                dropzone.classList.remove('is-file-selected');
                return;
            }

            filename.textContent = `File dipilih: ${file.name}`;
            dropzone.classList.add('is-file-selected');
        }

        input.addEventListener('change', updateFilename);

        dropzone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropzone.classList.add('is-dragging');
        });

        dropzone.addEventListener('dragleave', (event) => {
            if (!dropzone.contains(event.relatedTarget)) {
                dropzone.classList.remove('is-dragging');
            }
        });

        dropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropzone.classList.remove('is-dragging');

            const files = event.dataTransfer?.files;

            if (!files || files.length === 0) {
                return;
            }

            const file = files[0];
            const allowedExtensions = /\.(xlsx|xls|csv)$/i;

            if (!allowedExtensions.test(file.name)) {
                filename.textContent =
                    'Format file tidak didukung. Pilih XLSX, XLS, atau CSV.';

                input.value = '';
                dropzone.classList.remove('is-file-selected');
                return;
            }

            const transfer = new DataTransfer();
            transfer.items.add(file);

            input.files = transfer.files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener(
        'DOMContentLoaded',
        initIncomeImportDropzones,
        { once: true }
    );
} else {
    initIncomeImportDropzones();
}

// Mendukung halaman yang diakses melalui wire:navigate.
document.addEventListener(
    'livewire:navigated',
    initIncomeImportDropzones
);
