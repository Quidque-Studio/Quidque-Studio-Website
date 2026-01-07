document.addEventListener('DOMContentLoaded', function () {
  initGalleryUpload();
  initResourceManager();
});

function initGalleryUpload() {
  const container = document.getElementById('gallery-container');
  if (!container) return;

  const input = document.getElementById('gallery-upload');
  const preview = document.getElementById('gallery-preview');

  input.addEventListener('change', async function () {
    for (const file of this.files) {
      const formData = new FormData();
      formData.append('file', file);

      const res = await fetch('/admin/media/upload', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();
      if (data.success) {
        addGalleryItem(preview, data.media);
      }
    }
    input.value = '';
  });

  preview.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-media')) {
      e.target.closest('.gallery-item').remove();
    }
  });
}

function addGalleryItem(container, media) {
  const item = document.createElement('div');
  item.className = 'gallery-item';
  item.innerHTML = `
        <input type="hidden" name="gallery[]" value="${media.id}">
        <img src="${media.path}" alt="">
        <button type="button" class="remove-media">&times;</button>
    `;
  container.appendChild(item);
}

function initResourceManager() {
  const container = document.getElementById('resources-container');
  if (!container) return;

  const addBtn = document.getElementById('add-resource');
  const list = document.getElementById('resources-list');

  addBtn.addEventListener('click', function () {
    const type = document.getElementById('resource-type').value;
    addResourceForm(list, type);
  });

  list.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-resource')) {
      e.target.closest('.resource-item').remove();
    }
  });

  list.addEventListener('change', async function (e) {
    if (e.target.classList.contains('download-file-input')) {
      const file = e.target.files[0];
      if (!file) return;

      const formData = new FormData();
      formData.append('file', file);

      const res = await fetch('/admin/media/upload-download', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();
      if (data.success) {
        const item = e.target.closest('.resource-item');
        item.querySelector('.download-path-input').value = data.path;
        item.querySelector('.download-size-input').value = data.size;
        item.querySelector('.file-path-display').textContent = data.path;
      }
    }
  });
}

function addResourceForm(container, type) {
  const index = container.children.length;
  const item = document.createElement('div');
  item.className = 'resource-item';

  let fields = '';
  switch (type) {
    case 'link':
      fields = `
                <input type="text" name="resources[${index}][label]" placeholder="Label" required>
                <input type="url" name="resources[${index}][url]" placeholder="URL" required>
            `;
      break;
    case 'steam':
      fields = `<input type="text" name="resources[${index}][app_id]" placeholder="Steam App ID" required>`;
      break;
    case 'itch':
      fields = `<input type="url" name="resources[${index}][url]" placeholder="Itch.io URL" required>`;
      break;
    case 'youtube':
      fields = `<input type="text" name="resources[${index}][video_id]" placeholder="YouTube Video ID" required>`;
      break;
    case 'download':
      fields = `
                <input type="text" name="resources[${index}][label]" placeholder="Label (e.g. v1.0)" required>
                <div class="file-upload-row">
                    <input type="file" class="download-file-input">
                    <span class="file-path-display">No file selected</span>
                </div>
                <input type="hidden" name="resources[${index}][file_path]" class="download-path-input" required>
                <input type="hidden" name="resources[${index}][file_size]" class="download-size-input">
            `;
      break;
    case 'embed':
      fields = `
                <input type="text" name="resources[${index}][label]" placeholder="Label (optional)">
                <textarea name="resources[${index}][html]" placeholder="Embed HTML" required></textarea>
            `;
      break;
  }

  item.innerHTML = `
        <input type="hidden" name="resources[${index}][type]" value="${type}">
        <span class="resource-type-label">${type}</span>
        ${fields}
        <button type="button" class="remove-resource">&times;</button>
    `;

  container.appendChild(item);
}