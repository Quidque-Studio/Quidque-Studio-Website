document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('blocks-container');
  const jsonInput = document.getElementById('content-json');
  const addBtn = document.getElementById('add-block');
  const typeSelect = document.getElementById('block-type');

  if (!container) return;

  let blocks = [];
  try {
    blocks = JSON.parse(jsonInput.value) || [];
  } catch (e) {
    blocks = [];
  }

  function render() {
    container.innerHTML = '';
    blocks.forEach((block, index) => {
      const el = createBlockElement(block, index);
      container.appendChild(el);
    });
    updateJson();
  }

  function createBlockElement(block, index) {
    const div = document.createElement('div');
    div.className = 'block-item';
    div.dataset.index = index;

    let content = '';
    switch (block.type) {
      case 'text':
        content = `<textarea class="block-content" rows="4" placeholder="Text content...">${block.value || ''}</textarea>`;
        break;
      case 'heading':
        content = `<input type="text" class="block-content" placeholder="Heading..." value="${block.value || ''}">`;
        break;
      case 'image':
        content = `
                    <div class="block-image-upload">
                        ${block.value ? `<img src="${block.value}" class="block-image-preview">` : ''}
                        <input type="file" class="block-image-input" accept="image/*">
                        <input type="hidden" class="block-content" value="${block.value || ''}">
                    </div>
                `;
        break;
      case 'code':
        content = `<textarea class="block-content block-code" rows="6" placeholder="Code...">${block.value || ''}</textarea>`;
        break;
    }

    div.innerHTML = `
            <div class="block-header">
                <span class="block-type-label">${block.type}</span>
                <div class="block-controls">
                    <button type="button" class="block-move-up" ${index === 0 ? 'disabled' : ''}>↑</button>
                    <button type="button" class="block-move-down" ${index === blocks.length - 1 ? 'disabled' : ''}>↓</button>
                    <button type="button" class="block-delete">×</button>
                </div>
            </div>
            <div class="block-body">${content}</div>
        `;

    return div;
  }

  function updateJson() {
    jsonInput.value = JSON.stringify(blocks);
  }

  function getBlockValue(el) {
    const content = el.querySelector('.block-content');
    return content ? content.value : '';
  }

  addBtn.addEventListener('click', function () {
    blocks.push({ type: typeSelect.value, value: '' });
    render();
  });

  container.addEventListener('input', function (e) {
    if (e.target.classList.contains('block-content')) {
      const item = e.target.closest('.block-item');
      const index = parseInt(item.dataset.index);
      blocks[index].value = e.target.value;
      updateJson();
    }
  });

  container.addEventListener('click', function (e) {
    const item = e.target.closest('.block-item');
    if (!item) return;
    const index = parseInt(item.dataset.index);

    if (e.target.classList.contains('block-delete')) {
      blocks.splice(index, 1);
      render();
    } else if (e.target.classList.contains('block-move-up') && index > 0) {
      [blocks[index], blocks[index - 1]] = [blocks[index - 1], blocks[index]];
      render();
    } else if (e.target.classList.contains('block-move-down') && index < blocks.length - 1) {
      [blocks[index], blocks[index + 1]] = [blocks[index + 1], blocks[index]];
      render();
    }
  });

  container.addEventListener('change', async function (e) {
    if (e.target.classList.contains('block-image-input')) {
      const file = e.target.files[0];
      if (!file) return;

      const formData = new FormData();
      formData.append('file', file);

      const res = await fetch('/admin/media/upload', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();
      if (data.success) {
        const item = e.target.closest('.block-item');
        const index = parseInt(item.dataset.index);
        blocks[index].value = data.media.path;
        render();
      }
    }
  });

  render();
});