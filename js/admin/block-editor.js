class BlockEditor {
  constructor(container, options = {}) {
    this.container = container;
    this.blocksContainer = container.querySelector('#blocks-container');
    this.typeSelect = container.querySelector('#block-type');
    this.addBtn = container.querySelector('#add-block');
    this.blockCount = container.querySelector('#block-count');

    const form = container.closest('form');
    this.jsonInput = form ? form.querySelector('#content-json') : document.querySelector('#content-json');

    this.blocks = [];
    this.expandedBlocks = new Set();
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
      this.expandedBlocks.add(this.blocks.length - 1);
      this.render();
    });

    this.blocksContainer.addEventListener('input', (e) => {
      const item = e.target.closest('.block-item');
      if (!item) return;
      const index = parseInt(item.dataset.index);

      if (e.target.classList.contains('block-caption')) {
        this.blocks[index].caption = e.target.value;
        this.updateJson();
      } else if (e.target.classList.contains('block-list-item-input')) {
        const itemIndex = parseInt(e.target.dataset.itemIndex);
        this.blocks[index].items[itemIndex] = e.target.value;
        this.updateJson();
      } else if (e.target.classList.contains('block-input') || e.target.classList.contains('block-textarea')) {
        this.blocks[index].value = e.target.value;
        this.updateJson();
      }
    });

    this.blocksContainer.addEventListener('click', (e) => {
      const item = e.target.closest('.block-item');
      if (!item) return;
      const index = parseInt(item.dataset.index);

      if (e.target.classList.contains('block-toggle') || e.target.closest('.block-toggle')) {
        if (this.expandedBlocks.has(index)) {
          this.expandedBlocks.delete(index);
        } else {
          this.expandedBlocks.add(index);
        }
        this.render();
      } else if (e.target.classList.contains('block-delete') || e.target.closest('.block-delete')) {
        this.blocks.splice(index, 1);
        this.render();
      } else if (e.target.classList.contains('block-move-up') || e.target.closest('.block-move-up')) {
        if (index > 0) {
          [this.blocks[index], this.blocks[index - 1]] = [this.blocks[index - 1], this.blocks[index]];
          this.render();
        }
      } else if (e.target.classList.contains('block-move-down') || e.target.closest('.block-move-down')) {
        if (index < this.blocks.length - 1) {
          [this.blocks[index], this.blocks[index + 1]] = [this.blocks[index + 1], this.blocks[index]];
          this.render();
        }
      } else if (e.target.classList.contains('block-list-add')) {
        this.blocks[index].items.push('');
        this.render();
      } else if (e.target.classList.contains('block-list-item-remove')) {
        const itemIndex = parseInt(e.target.dataset.itemIndex);
        this.blocks[index].items.splice(itemIndex, 1);
        this.render();
      } else if (e.target.classList.contains('block-list-toggle')) {
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

        const csrfToken = document.querySelector('input[name="_csrf"]');
        if (csrfToken) {
          formData.append('_csrf', csrfToken.value);
        }

        try {
          const res = await fetch(this.uploadUrl, {
            method: 'POST',
            body: formData
          });

          if (!res.ok) {
            throw new Error(`Server returned ${res.status}`);
          }

          const data = await res.json();
          if (data.success) {
            const item = e.target.closest('.block-item');
            const index = parseInt(item.dataset.index);
            this.blocks[index].value = data.media.path;
            this.render();
          }
        } catch (err) {
          console.error('Upload failed:', err);
        }
      }
    });
  }

  getBlockIcon(type) {
    const icons = {
      text: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 6.1H3"/><path d="M21 12.1H3"/><path d="M15.1 18H3"/></svg>',
      heading: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 12h12"/><path d="M6 20V4"/><path d="M18 20V4"/></svg>',
      image: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>',
      code: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
      quote: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V21z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3z"/></svg>',
      list: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>',
      callout: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
      video: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>',
      divider: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="12" y2="12"/></svg>'
    };
    return icons[type] || icons.text;
  }

  getBlockPreview(block) {
    switch (block.type) {
      case 'text':
      case 'quote':
      case 'callout':
        return block.value ? block.value.substring(0, 50) + (block.value.length > 50 ? '...' : '') : 'Empty';
      case 'heading':
        return block.value || 'Empty heading';
      case 'image':
        return block.value ? 'Image uploaded' : 'No image';
      case 'code':
        return block.value ? 'Code block' : 'Empty code';
      case 'list':
        return `${block.items?.length || 0} items`;
      case 'video':
        return block.value || 'No video';
      case 'divider':
        return 'Horizontal line';
      default:
        return block.type;
    }
  }

  render() {
    if (this.blocks.length === 0) {
      this.blocksContainer.innerHTML = `
        <div class="blocks-empty">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
          <p>No content blocks yet. Add your first block below.</p>
        </div>
      `;
    } else {
      this.blocksContainer.innerHTML = '';
      this.blocks.forEach((block, index) => {
        const el = this.createBlockElement(block, index);
        this.blocksContainer.appendChild(el);
      });
    }

    if (this.blockCount) {
      const count = this.blocks.length;
      this.blockCount.textContent = `${count} block${count !== 1 ? 's' : ''}`;
    }

    this.updateJson();
  }

  createBlockElement(block, index) {
    const div = document.createElement('div');
    const isExpanded = this.expandedBlocks.has(index);
    div.className = `block-item ${isExpanded ? 'block-expanded' : 'block-collapsed'}`;
    div.dataset.index = index;

    let content = '';
    switch (block.type) {
      case 'text':
        content = `<textarea class="block-textarea" rows="4" placeholder="Write your text here...">${this.escapeHtml(block.value || '')}</textarea>`;
        break;
      case 'heading':
        content = `<input type="text" class="block-input" placeholder="Heading text..." value="${this.escapeHtml(block.value || '')}">`;
        break;
      case 'image':
        content = `
          <div class="block-image-upload">
            ${block.value ? `<img src="${this.escapeHtml(block.value)}" class="block-image-preview">` : ''}
            <input type="file" class="block-image-input" accept="image/*">
            <input type="text" class="block-input block-caption" placeholder="Caption (optional)" value="${this.escapeHtml(block.caption || '')}">
          </div>
        `;
        break;
      case 'code':
        content = `<textarea class="block-textarea code" rows="8" placeholder="// Your code here...">${this.escapeHtml(block.value || '')}</textarea>`;
        break;
      case 'quote':
        content = `<textarea class="block-textarea" rows="3" placeholder="Quote text...">${this.escapeHtml(block.value || '')}</textarea>`;
        break;
      case 'divider':
        content = `<div class="block-divider-preview"><hr></div>`;
        break;
      case 'list':
        const items = block.items || [''];
        content = `
          <div class="block-list">
            <div class="block-list-controls">
              <button type="button" class="block-list-toggle">${block.ordered ? '1. Numbered' : '• Bulleted'}</button>
              <button type="button" class="block-list-add">+ Add Item</button>
            </div>
            <div class="block-list-items">
              ${items.map((item, i) => `
                <div class="block-list-item">
                  <span class="block-list-marker">${block.ordered ? (i + 1) + '.' : '•'}</span>
                  <input type="text" class="block-input block-list-item-input" data-item-index="${i}" value="${this.escapeHtml(item)}" placeholder="List item...">
                  <button type="button" class="block-list-item-remove" data-item-index="${i}">×</button>
                </div>
              `).join('')}
            </div>
          </div>
        `;
        break;
      case 'callout':
        content = `<textarea class="block-textarea" rows="3" placeholder="Important note or callout...">${this.escapeHtml(block.value || '')}</textarea>`;
        break;
      case 'video':
        content = `<input type="text" class="block-input" placeholder="YouTube URL or video ID..." value="${this.escapeHtml(block.value || '')}">`;
        break;
    }

    div.innerHTML = `
      <div class="block-header">
        <button type="button" class="block-toggle" title="${isExpanded ? 'Collapse' : 'Expand'}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="block-toggle-icon"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="block-type">
          <div class="block-type-icon">${this.getBlockIcon(block.type)}</div>
          <span class="block-type-label">${block.type}</span>
        </div>
        <span class="block-preview">${this.escapeHtml(this.getBlockPreview(block))}</span>
        <div class="block-controls">
          <button type="button" class="block-control block-move-up" ${index === 0 ? 'disabled' : ''} title="Move up">↑</button>
          <button type="button" class="block-control block-move-down" ${index === this.blocks.length - 1 ? 'disabled' : ''} title="Move down">↓</button>
          <button type="button" class="block-control delete block-delete" title="Delete">×</button>
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
  const editorContainer = document.querySelector('.block-editor');
  if (editorContainer) {
    new BlockEditor(editorContainer);
  }
});