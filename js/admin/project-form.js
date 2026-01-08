document.addEventListener('DOMContentLoaded', function () {
  initGalleryUpload();
  initResourceManager();
  initCheckboxHighlight();
});

function initCheckboxHighlight() {
  document.querySelectorAll('.editor-checkbox input').forEach(input => {
    input.addEventListener('change', function () {
      this.closest('.editor-checkbox').classList.toggle('checked', this.checked);
    });
  });
}

function initGalleryUpload() {
  const input = document.getElementById('gallery-upload');
  const preview = document.getElementById('gallery-preview');
  if (!input || !preview) return;

  input.addEventListener('change', async function () {
    for (const file of this.files) {
      const formData = new FormData();
      formData.append('file', file);

      try {
        const res = await fetch('/admin/media/upload', {
          method: 'POST',
          body: formData
        });

        const data = await res.json();
        if (data.success) {
          addGalleryItem(preview, data.media);
        }
      } catch (err) {
        console.error('Upload failed:', err);
      }
    }
    input.value = '';
  });

  preview.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-media') || e.target.closest('.gallery-item-remove')) {
      e.target.closest('.gallery-item').remove();
      updateThumbnailBadge();
    }
  });
}

function addGalleryItem(container, media) {
  const isFirst = container.querySelectorAll('.gallery-item').length === 0;
  const item = document.createElement('div');
  item.className = 'gallery-item';
  item.innerHTML = `
    <input type="hidden" name="gallery[]" value="${media.id}">
    <img src="${media.path}" alt="">
    ${isFirst ? '<span class="gallery-item-badge">Thumb</span>' : ''}
    <button type="button" class="gallery-item-remove">×</button>
  `;
  container.appendChild(item);
}

function updateThumbnailBadge() {
  const preview = document.getElementById('gallery-preview');
  if (!preview) return;

  preview.querySelectorAll('.gallery-item-badge').forEach(badge => badge.remove());
  const firstItem = preview.querySelector('.gallery-item');
  if (firstItem && !firstItem.querySelector('.gallery-item-badge')) {
    const badge = document.createElement('span');
    badge.className = 'gallery-item-badge';
    badge.textContent = 'Thumb';
    firstItem.appendChild(badge);
  }
}

function initResourceManager() {
  const addBtn = document.getElementById('add-resource');
  const list = document.getElementById('resources-list');
  if (!addBtn || !list) return;

  addBtn.addEventListener('click', function () {
    const type = document.getElementById('resource-type').value;
    const emptyMsg = list.querySelector('.resources-empty');
    if (emptyMsg) emptyMsg.remove();
    addResourceForm(list, type);
  });

  list.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-resource') || e.target.closest('.resource-item-remove')) {
      e.target.closest('.resource-item').remove();
      if (list.querySelectorAll('.resource-item').length === 0) {
        list.innerHTML = '<div class="resources-empty">No resources added yet</div>';
      }
    }
  });

  list.addEventListener('change', async function (e) {
    if (e.target.classList.contains('download-file-input')) {
      const file = e.target.files[0];
      if (!file) return;

      const formData = new FormData();
      formData.append('file', file);

      try {
        const res = await fetch('/admin/media/upload-download', {
          method: 'POST',
          body: formData
        });

        const data = await res.json();
        if (data.success) {
          const item = e.target.closest('.resource-item');
          item.querySelector('.download-path-input').value = data.path;
          item.querySelector('.download-size-input').value = data.size;
        }
      } catch (err) {
        console.error('Upload failed:', err);
      }
    }
  });
}

function addResourceForm(container, type) {
  const index = container.querySelectorAll('.resource-item').length;
  const item = document.createElement('div');
  item.className = 'resource-item';

  let fields = '';
  switch (type) {
    case 'link':
      fields = `
        <div class="resource-item-row">
          <div class="resource-item-field">
            <span class="resource-item-label">Label</span>
            <input type="text" name="resources[${index}][label]" class="resource-item-input" placeholder="Link text" required>
          </div>
          <div class="resource-item-field">
            <span class="resource-item-label">URL</span>
            <input type="url" name="resources[${index}][url]" class="resource-item-input" placeholder="https://..." required>
          </div>
        </div>
      `;
      break;
    case 'steam':
      fields = `
        <div class="resource-item-field">
          <span class="resource-item-label">Steam App ID</span>
          <input type="text" name="resources[${index}][app_id]" class="resource-item-input" placeholder="e.g. 730" required>
        </div>
      `;
      break;
    case 'itch':
      fields = `
        <div class="resource-item-field">
          <span class="resource-item-label">Itch.io URL</span>
          <input type="url" name="resources[${index}][url]" class="resource-item-input" placeholder="https://username.itch.io/game" required>
        </div>
      `;
      break;
    case 'youtube':
      fields = `
        <div class="resource-item-field">
          <span class="resource-item-label">YouTube Video ID</span>
          <input type="text" name="resources[${index}][video_id]" class="resource-item-input" placeholder="e.g. dQw4w9WgXcQ" required>
        </div>
      `;
      break;
    case 'download':
      fields = `
        <div class="resource-item-row">
          <div class="resource-item-field">
            <span class="resource-item-label">Label</span>
            <input type="text" name="resources[${index}][label]" class="resource-item-input" placeholder="e.g. v1.0" required>
          </div>
          <div class="resource-item-field">
            <span class="resource-item-label">File</span>
            <input type="file" class="download-file-input resource-item-input">
            <input type="hidden" name="resources[${index}][file_path]" class="download-path-input" required>
            <input type="hidden" name="resources[${index}][file_size]" class="download-size-input">
          </div>
        </div>
      `;
      break;
    case 'embed':
      fields = `
        <div class="resource-item-field">
          <span class="resource-item-label">Label (optional)</span>
          <input type="text" name="resources[${index}][label]" class="resource-item-input" placeholder="Section title">
        </div>
        <div class="resource-item-field">
          <span class="resource-item-label">Embed HTML</span>
          <textarea name="resources[${index}][html]" class="resource-item-textarea" placeholder="<iframe>...</iframe>" required></textarea>
        </div>
      `;
      break;
  }

  item.innerHTML = `
    <input type="hidden" name="resources[${index}][type]" value="${type}">
    <div class="resource-item-header">
      <span class="resource-type-badge">${type}</span>
      <button type="button" class="resource-item-remove">×</button>
    </div>
    <div class="resource-item-body">${fields}</div>
  `;

  container.appendChild(item);
}