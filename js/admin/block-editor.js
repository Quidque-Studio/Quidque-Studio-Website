class BlockEditor {
  constructor(container, options = {}) {
    this.container = container;
    this.blocksContainer = container.querySelector('#blocks-container');
    this.typeSelect = container.querySelector('#block-type');
    this.addBtn = container.querySelector('#add-block');

    const form = container.closest('form');
    this.jsonInput = form ? form.querySelector('#content-json') : document.querySelector('#content-json');

    this.blocks = [];
    this.uploadUrl = options.uploadUrl || '/admin/media/upload';

    if (!this.blocksContainer || !this.jsonInput) {
      console.error('BlockEditor: Missing required elements');
      return;
    }

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
      const type = this.typeSelect.value;
      const block = { type, value: '' };

      if (type === 'list') {
        block.items = [''];
        block.ordered = false;
      }

      this.blocks.push(block);
      this.render();
    });

    this.blocksContainer.addEventListener('input', (e) => {
      const item = e.target.closest('.block-item');
      if (!item) return;
      const index = parseInt(item.dataset.index);

      if (e.target.classList.contains('block-content')) {
        this.blocks[index].value = e.target.value;
        this.updateJson();
      } else if (e.target.classList.contains('block-caption')) {
        this.blocks[index].caption = e.target.value;
        this.updateJson();
      } else if (e.target.classList.contains('list-item-input')) {
        const itemIndex = parseInt(e.target.dataset.itemIndex);
        this.blocks[index].items[itemIndex] = e.target.value;
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
      } else if (e.target.classList.contains('add-list-item')) {
        this.blocks[index].items.push('');
        this.render();
      } else if (e.target.classList.contains('remove-list-item')) {
        const itemIndex = parseInt(e.target.dataset.itemIndex);
        this.blocks[index].items.splice(itemIndex, 1);
        this.render();
      } else if (e.target.classList.contains('toggle-list-type')) {
        this.blocks[index].ordered = !this.blocks[index].ordered;
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
                        <input type="text" class="block-caption" placeholder="Caption (optional)" value="${this.escapeHtml(block.caption || '')}">
                    </div>
                `;
        break;
      case 'code':
        content = `<textarea class="block-content block-code" rows="6" placeholder="Code...">${this.escapeHtml(block.value || '')}</textarea>`;
        break;
      case 'quote':
        content = `<textarea class="block-content" rows="3" placeholder="Quote...">${this.escapeHtml(block.value || '')}</textarea>`;
        break;
      case 'divider':
        content = `<div class="block-divider-preview"><hr></div>`;
        break;
      case 'list':
        const items = block.items || [''];
        content = `
                    <div class="block-list">
                        <div class="list-controls">
                            <button type="button" class="toggle-list-type btn-small">${block.ordered ? 'Numbered' : 'Bulleted'}</button>
                            <button type="button" class="add-list-item btn-small">+ Add Item</button>
                        </div>
                        <div class="list-items">
                            ${items.map((item, i) => `
                                <div class="list-item-row">
                                    <span class="list-marker">${block.ordered ? (i + 1) + '.' : '•'}</span>
                                    <input type="text" class="list-item-input" data-item-index="${i}" value="${this.escapeHtml(item)}">
                                    <button type="button" class="remove-list-item" data-item-index="${i}">×</button>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
        break;
      case 'callout':
        content = `<textarea class="block-content" rows="3" placeholder="Callout text...">${this.escapeHtml(block.value || '')}</textarea>`;
        break;
      case 'video':
        content = `<input type="text" class="block-content" placeholder="YouTube URL or video path..." value="${this.escapeHtml(block.value || '')}">`;
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
  document.querySelectorAll('#block-editor').forEach(container => {
    new BlockEditor(container);
  });
});