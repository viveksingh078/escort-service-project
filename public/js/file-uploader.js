document.querySelectorAll('.file-uploader').forEach(input => {
    let filesArray = [];

    // Create wrapper
    const wrapper = document.createElement('div');
    wrapper.className = 'file-upload-wrapper';
    input.parentNode.insertBefore(wrapper, input.nextSibling);
    wrapper.appendChild(input);

    // Select files button
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn btn-primary rounded-pill me-2';
    btn.innerHTML = '<i class="fa fa-upload"></i> Select Files';
    wrapper.appendChild(btn);

    // Show files button (hidden by default)
    const showBtn = document.createElement('button');
    showBtn.type = 'button';
    showBtn.className = 'btn btn-success rounded-pill d-none mx-2';
    showBtn.innerHTML = '<i class="fa fa-images"></i> Show Uploaded Files';
    wrapper.appendChild(showBtn);

    // Build modal dynamically
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.setAttribute('data-bs-backdrop', 'static');
    modal.setAttribute('tabindex', '-1');
    modal.id = 'uploadedFilesModal';
    modal.innerHTML = `
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
          <div class="modal-body p-5 file-upload-modal-body">
            <h5 class="modal-title mb-4">Uploaded Files</h5>
            <div class="row" id="uploadedFilesContainer"></div>
            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-sm btn-secondary mt-3 mx-1" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);

    // Open file dialog
    btn.addEventListener('click', () => input.click());

    // On file select
    input.addEventListener('change', () => {
        Array.from(input.files).forEach(file => filesArray.push(file));
        updateShowButton();
    });

    // Sync input with filesArray
    function updateInputFiles() {
        const dataTransfer = new DataTransfer();
        filesArray.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
        updateShowButton();
    }

    // Toggle "Show Uploaded Files" button
    function updateShowButton() {
        if (filesArray.length > 0) {
            showBtn.classList.remove('d-none');
        } else {
            showBtn.classList.add('d-none');
        }
    }

    // Show uploaded files in modal
    showBtn.addEventListener('click', () => {
        const container = document.getElementById('uploadedFilesContainer');
        container.innerHTML = '';

        filesArray.forEach((file, index) => {
            const col = document.createElement('div');
            col.className = 'col-sm-6 col-lg-3 mb-4';

            const card = document.createElement('div');
            card.className = 'image-card shadow-sm';

            // IMAGE FILE
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'card-img-top';
                card.appendChild(img);
            }

            // VIDEO FILE
            else if (file.type.startsWith('video/')) {
                const thumbWrapper = document.createElement('div');
                thumbWrapper.className = 'd-flex justify-content-center align-items-center bg-light';
                thumbWrapper.style.height = '168px';

                const icon = document.createElement('i');
                icon.className = 'fa fa-video fa-3x text-secondary';
                thumbWrapper.appendChild(icon);

                card.appendChild(thumbWrapper);
            }

            // Footer
            const body = document.createElement('div');
            body.className = 'image-card-body text-center';

            const title = document.createElement('p');
            title.className = 'image-card-text small text-truncate';
            title.innerText = file.name;

            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-sm btn-danger';
            removeBtn.innerHTML = '<i class="fa fa-trash"></i>';
            removeBtn.addEventListener('click', () => {
                filesArray.splice(index, 1);
                updateInputFiles();
                showBtn.click(); // re-render modal
            });

            body.appendChild(title);
            body.appendChild(removeBtn);
            card.appendChild(body);

            col.appendChild(card);
            container.appendChild(col);
        });

        if (filesArray.length === 0) {
            container.innerHTML = '<p class="text-muted">No files uploaded yet.</p>';
        }

        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
    });
});
