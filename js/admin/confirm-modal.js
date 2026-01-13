(function () {
  const modal = document.createElement('div');
  modal.id = 'confirm-modal';
  modal.innerHTML = `
        <div class="confirm-modal-backdrop"></div>
        <div class="confirm-modal-box">
            <p class="confirm-modal-message"></p>
            <div class="confirm-modal-buttons">
                <button type="button" class="btn confirm-modal-cancel">Cancel</button>
                <button type="button" class="btn btn-accent confirm-modal-ok">Confirm</button>
            </div>
        </div>
    `;
  document.body.appendChild(modal);

  let pendingForm = null;
  let pendingButton = null;

  const okButton = modal.querySelector('.confirm-modal-ok');

  function getButtonLabel(message) {
    const lower = message.toLowerCase();
    if (lower.includes('delete')) return 'Delete';
    if (lower.includes('send')) return 'Send';
    if (lower.includes('remove')) return 'Remove';
    return 'Confirm';
  }

  const originalConfirm = window.confirm;
  window.confirm = function (message) {
    if (window._confirmBypassing) return true;
    modal.classList.add('active');
    modal.querySelector('.confirm-modal-message').textContent = message;
    okButton.textContent = getButtonLabel(message);
    return false;
  };

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('button[type="submit"][onclick*="confirm"]');
    if (!btn) return;
    const form = btn.closest('form');
    if (!form) return;
    pendingForm = form;
    pendingButton = btn;
  }, true);

  modal.querySelector('.confirm-modal-cancel').addEventListener('click', function () {
    modal.classList.remove('active');
    pendingForm = null;
    pendingButton = null;
  });

  modal.querySelector('.confirm-modal-ok').addEventListener('click', function () {
    modal.classList.remove('active');
    if (pendingForm) {
      window._confirmBypassing = true;
      pendingButton ? pendingButton.click() : pendingForm.submit();
      window._confirmBypassing = false;
      pendingForm = null;
      pendingButton = null;
    }
  });

  modal.querySelector('.confirm-modal-backdrop').addEventListener('click', function () {
    modal.querySelector('.confirm-modal-cancel').click();
  });

  document.addEventListener('keydown', function (e) {
    if (!modal.classList.contains('active')) return;
    if (e.key === 'Escape') modal.querySelector('.confirm-modal-cancel').click();
    if (e.key === 'Enter') modal.querySelector('.confirm-modal-ok').click();
  });
})();