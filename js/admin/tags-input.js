class TagsInput {
  constructor(container) {
    this.container = container;
    this.input = container.querySelector('.tags-text-input');
    this.jsonInput = container.querySelector('.tags-json-input');
    this.tagsDisplay = container.querySelector('.tags-display');

    this.tags = [];
    this.init();
  }

  init() {
    try {
      this.tags = JSON.parse(this.jsonInput.value) || [];
    } catch (e) {
      this.tags = [];
    }

    this.render();
    this.bindEvents();
  }

  bindEvents() {
    this.input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        this.addTag(this.input.value);
        this.input.value = '';
      }
    });

    this.input.addEventListener('blur', () => {
      if (this.input.value.trim()) {
        this.addTag(this.input.value);
        this.input.value = '';
      }
    });

    this.tagsDisplay.addEventListener('click', (e) => {
      if (e.target.classList.contains('tag-remove')) {
        const tag = e.target.closest('.tag-item');
        const index = parseInt(tag.dataset.index);
        this.tags.splice(index, 1);
        this.render();
      }
    });
  }

  addTag(value) {
    value = value.trim().replace(/,/g, '');
    if (value && !this.tags.includes(value)) {
      this.tags.push(value);
      this.render();
    }
  }

  render() {
    this.tagsDisplay.innerHTML = this.tags.map((tag, i) => `
            <span class="tag-item" data-index="${i}">
                ${this.escapeHtml(tag)}
                <button type="button" class="tag-remove">×</button>
            </span>
        `).join('');

    this.jsonInput.value = JSON.stringify(this.tags);
  }

  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.tags-input').forEach(container => {
    new TagsInput(container);
  })
});