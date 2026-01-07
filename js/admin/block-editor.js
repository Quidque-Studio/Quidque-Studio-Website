class BlockEditor {
  constructor(container, options = {}) {
    this.container = container;
    this.blocksContainer = container.querySelector('.blocks-container');
    this.jsonInput = container.querySelector('.content-json');
    this.typeSelect = container.querySelector('.block-type-select');
    this.addBtn = container.querySelector('.add-block-btn');

    this.blocks = [];
    this.blockTypes = options.blockTypes || ['text', 'heading', 'image', 'code'];
    this.uploadUrl = options.uploadUrl || '/admin/media/upload';

    this.init();
  }

  init() {
    try {
      this.blocks = JSON.parse(this.jsonInput.value) || [];
    } catch (e) {
      this.blocks = [];
    }

    this.render();
    this.bindEvents();
  }

  bindEvents() {
    this.addBtn?.addEventListener('click', () => {
      this.blocks.push({ type: this.typeSelect.value, value: '' });
      this.render();
    });

    this.blocksContainer.addEventListener('input', (e) => {
      if (e.target.classList.contains('block-content')) {
        const item = e.target.closest('.block-item');
        const index = parseInt(item.dataset.index);
        this.blocks[index].value = e.target.value;
        this.updateJson();
      }
    });

    this.blocksContainer.addEventListener('click', (e) => {
      const item = e.target.closest('.block-item');
      if (!item) return;
      const index = parseInt(item.dataset.index);

      if (e.target.classList.contains('block-delete')) {
        this.blocks.splice(index, 1);
        this.render();
      } else if (e.target.classList.contains('block-move-up') && index > 0) {
        [this.blocks[index], this.blocks[index - 1]] = [this.blocks[index - 1], this.blocks[index]];
        this.render();
      } else if (e.target.classList.contains('block-move-down') && index < this.blocks.length - 1) {
        [this.blocks[index], this.blocks[index + 1]] = [this.blocks[index + 1], this.blocks[index]];
        this.render();
      }
    });

    this.blocksContainer.addEventListener('change', async (e) => {
      if (e.target.classList.contains('block-image-input')) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);

        const res = await fetch(this.uploadUrl, {
          method: 'POST',
          body: formData
        });

        const data = await res.json();
        if (data.success) {
          const item = e.target.closest('.block-item');
          const index = parseInt(item.dataset.index);
          this.blocks[index].value = data.media.path;
          this.render();
        }
      }
    });
  }

  render() {
    this.blocksContainer.innerHTML = '';
    this.blocks.forEach((block, index) => {
      const el = this.createBlockElement(block, index);
      this.blocksContainer.appendChild(el);
    });
    this.updateJson();
  }

  createBlockElement(block, index) {
    const div = document.createElement('div');
    div.className = 'block-item';
    div.dataset.index = index;

    let content = '';
    switch (block.type) {
      case 'text':
        content = `<textarea class="block-content" rows="4" placeholder="Text content...">${this.escapeHtml(block.value || '')}</textarea>`;
        break;
      case 'heading':
        content = `<input type="text" class="block-content" placeholder="Heading..." value="${this.escapeHtml(block.value || '')}">`;
        break;
      case 'image':
        content = `
                    <div class="block-image-upload">
                        ${block.value ? `<img src="${this.escapeHtml(block.value)}" class="block-image-preview">` : ''}
                        <input type="file" class="block-image-input" accept="image/*">
                        <input type="hidden" class="block-content" value="${this.escapeHtml(block.value || '')}">
                    </div>
                `;
        break;
      case 'code':
        content = `<textarea class="block-content block-code" rows="6" placeholder="Code...">${this.escapeHtml(block.value || '')}</textarea>`;
        break;
    }

    div.innerHTML = `
            <div class="block-header">
                <span class="block-type-label">${block.type}</span>
                <div class="block-controls">
                    <button type="button" class="block-move-up" ${index === 0 ? 'disabled' : ''}>↑</button>
                    <button type="button" class="block-move-down" ${index === this.blocks.length - 1 ? 'disabled' : ''}>↓</button>
                    <button type="button" class="block-delete">×</button>
                </div>
            </div>
            <div class="block-body">${content}</div>
        `;

    return div;
  }

  updateJson() {
    this.jsonInput.value = JSON.stringify(this.blocks);
  }

  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.block-editor').forEach(container => {
    new BlockEditor(container);
  });
});