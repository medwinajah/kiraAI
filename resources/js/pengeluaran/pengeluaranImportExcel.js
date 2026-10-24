
document.addEventListener('DOMContentLoaded', () => {
    const dropzone = document.querySelector('[data-expense-dropzone]');
    const input = document.querySelector('[data-expense-file-input]');
    const filename = document.querySelector('[data-expense-filename]');

    if (!dropzone || !input || !filename) {
        return;
    }

    const updateFilename = () => {
        const file = input.files?.[0];

        filename.textContent = file
            ? `File dipilih: ${file.name}`
            : 'Belum ada file yang dipilih';
    };

    input.addEventListener('change', updateFilename);

    ['dragenter', 'dragover'].forEach((eventName) => {
        dropzone.addEventListener(eventName, (event) => {
            event.preventDefault();
            dropzone.classList.add('is-dragging');
        });
    });

    ['dragleave', 'dragend'].forEach((eventName) => {
        dropzone.addEventListener(eventName, (event) => {
            event.preventDefault();
            dropzone.classList.remove('is-dragging');
        });
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
            input.value = '';
            filename.textContent = 'Format file tidak didukung.';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            input.value = '';
            filename.textContent = 'Ukuran file melebihi 5 MB.';
            return;
        }

        const transfer = new DataTransfer();
        transfer.items.add(file);

        input.files = transfer.files;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });
});
