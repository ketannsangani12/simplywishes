document.addEventListener('DOMContentLoaded', () => {
  const grid = document.querySelector('[data-default-grid]');
  const defaultInput = document.getElementById('wish-image-default');
  const uploadInput = document.getElementById('wish-image-upload');
  const uploadWrapper = document.getElementById('upload-preview-wrapper');
  const uploadPreview = document.getElementById('upload-preview-image');
  const uploadFrame = document.getElementById('upload-preview-frame');
  const zoomRange = document.getElementById('zoom-range');
  const zoomIn = document.getElementById('zoom-in');
  const zoomOut = document.getElementById('zoom-out');

  if (!grid || !defaultInput) {
    return;
  }

  const clearSelected = () => {
    grid.querySelectorAll('[data-default-item]').forEach((item) => {
      item.classList.remove('ring-2', 'ring-primary', 'scale-[1.02]', 'shadow-lg');
      const overlay = item.querySelector('[data-selected-overlay]');
      const check = item.querySelector('[data-selected-check]');
      if (overlay) overlay.classList.add('opacity-0');
      if (check) check.classList.add('opacity-0');
    });
  };

  grid.addEventListener('click', (event) => {
    const target = event.target.closest('[data-default-item]');
    if (!target) {
      return;
    }
    clearSelected();
    target.classList.add('ring-2', 'ring-primary', 'scale-[1.02]', 'shadow-lg');
    const overlay = target.querySelector('[data-selected-overlay]');
    const check = target.querySelector('[data-selected-check]');
    if (overlay) overlay.classList.remove('opacity-0');
    if (check) check.classList.remove('opacity-0');
    defaultInput.value = target.getAttribute('data-default-image') || '';
    if (uploadInput) {
      uploadInput.value = '';
    }
  });

  if (uploadInput) {
    uploadInput.addEventListener('change', () => {
      if (uploadInput.files && uploadInput.files.length > 0) {
        clearSelected();
        defaultInput.value = '';
        const file = uploadInput.files[0];
        if (uploadPreview) {
          const url = URL.createObjectURL(file);
          uploadPreview.src = url;
          uploadPreview.onload = () => URL.revokeObjectURL(url);
        }
        if (uploadWrapper) {
          uploadWrapper.classList.remove('hidden');
        }
        state.scale = 1.2;
        state.x = 0;
        state.y = 0;
        if (zoomRange) {
          zoomRange.value = '1.2';
        }
        applyTransform();
      }
    });
  }

  const state = {
    scale: 1.2,
    x: 0,
    y: 0,
    dragging: false,
    startX: 0,
    startY: 0,
    baseX: 0,
    baseY: 0,
  };

  const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

  const getBounds = () => {
    if (!uploadFrame) return { maxX: 0, maxY: 0 };
    const rect = uploadFrame.getBoundingClientRect();
    const maxX = (state.scale - 1) * rect.width / 2;
    const maxY = (state.scale - 1) * rect.height / 2;
    return { maxX, maxY };
  };

  const applyTransform = () => {
    if (!uploadPreview) return;
    const { maxX, maxY } = getBounds();
    state.x = clamp(state.x, -maxX, maxX);
    state.y = clamp(state.y, -maxY, maxY);
    uploadPreview.style.transform = `translate(${state.x}px, ${state.y}px) scale(${state.scale})`;
  };

  const applyZoom = (value) => {
    if (!uploadPreview) return;
    state.scale = Math.min(3, Math.max(1, value));
    applyTransform();
    if (zoomRange) {
      zoomRange.value = String(state.scale);
    }
  };

  if (zoomRange) {
    zoomRange.addEventListener('input', () => applyZoom(parseFloat(zoomRange.value)));
  }
  if (zoomIn) {
    zoomIn.addEventListener('click', () => applyZoom((parseFloat(zoomRange.value) || 1) + 0.1));
  }
  if (zoomOut) {
    zoomOut.addEventListener('click', () => applyZoom((parseFloat(zoomRange.value) || 1) - 0.1));
  }

  if (uploadFrame) {
    uploadFrame.addEventListener('pointerdown', (event) => {
      if (!uploadPreview || !uploadPreview.src) return;
      state.dragging = true;
      state.startX = event.clientX;
      state.startY = event.clientY;
      state.baseX = state.x;
      state.baseY = state.y;
      uploadFrame.setPointerCapture(event.pointerId);
    });

    uploadFrame.addEventListener('pointermove', (event) => {
      if (!state.dragging) return;
      const dx = event.clientX - state.startX;
      const dy = event.clientY - state.startY;
      state.x = state.baseX + dx;
      state.y = state.baseY + dy;
      applyTransform();
    });

    const endDrag = (event) => {
      if (!state.dragging) return;
      state.dragging = false;
      try {
        uploadFrame.releasePointerCapture(event.pointerId);
      } catch (e) {
        // ignore if capture already released
      }
    };

    uploadFrame.addEventListener('pointerup', endDrag);
    uploadFrame.addEventListener('pointercancel', endDrag);
    uploadFrame.addEventListener('pointerleave', endDrag);
  }

  const fundingYes = document.getElementById('funding-yes');
  const fundingNo = document.getElementById('funding-no');
  const financialBlock = document.getElementById('financial-block');
  const nonFinancialBlock = document.getElementById('non-financial-block');
  const dropoffMessage = document.getElementById('dropoff-message');
  const nonFinancialLabel = document.getElementById('non-financial-label');
  const form = document.getElementById('wish-form');
  const nonFinancialNotes = document.getElementById('non-financial-notes');

  const updateFinancialVisibility = () => {
    if (!financialBlock) return;
    if (fundingYes && fundingYes.checked) {
      financialBlock.classList.remove('hidden');
    } else {
      financialBlock.classList.add('hidden');
    }
    if (nonFinancialBlock) {
      if (fundingNo && fundingNo.checked) {
        nonFinancialBlock.classList.remove('hidden');
      } else {
        nonFinancialBlock.classList.add('hidden');
      }
    }
    if (nonFinancialNotes) {
      if (fundingNo && fundingNo.checked) {
        nonFinancialNotes.setAttribute('required', 'required');
      } else {
        nonFinancialNotes.removeAttribute('required');
      }
    }
  };

  if (fundingYes) fundingYes.addEventListener('change', updateFinancialVisibility);
  if (fundingNo) fundingNo.addEventListener('change', updateFinancialVisibility);
  updateFinancialVisibility();

  const updateNonFinancialMessage = () => {
    if (!dropoffMessage) return;
    const selected = document.querySelector('input[name="non_financial_method"]:checked');
    if (selected && selected.value === 'drop_off_pickup') {
      dropoffMessage.classList.remove('hidden');
    } else {
      dropoffMessage.classList.add('hidden');
    }

    if (!nonFinancialLabel) return;
    let labelText = 'Details';
    if (selected && selected.value === 'online_order') {
      labelText = 'Link to webpage';
    } else if (selected && selected.value === 'drop_off_pickup') {
      labelText = 'Drop-off/Pick up Location';
    } else if (selected && selected.value === 'mail') {
      labelText = 'Send in the mail (FedEx, UPS, DHL, USPS, etc)';
    }
    nonFinancialLabel.innerHTML = `${labelText} <span class="text-red-500">*</span>`;
  };

  document.querySelectorAll('input[name="non_financial_method"]').forEach((input) => {
    input.addEventListener('change', updateNonFinancialMessage);
  });
  updateNonFinancialMessage();

  const dateInput = document.getElementById('wish-date');
  if (window.flatpickr && dateInput) {
    window.flatpickr(dateInput, {
      dateFormat: 'Y-m-d',
      allowInput: true,
    });
  }

  const showError = (key, show) => {
    const el = document.querySelector(`[data-error-for="${key}"]`);
    if (!el) return;
    el.classList.toggle('hidden', !show);
  };

  const validateWishForm = () => {
    let valid = true;

    const title = document.getElementById('wish-title');
    const date = document.getElementById('wish-date');
    const payment = document.querySelector('input[name="payment"]:checked');
    const contact = document.getElementById('contact');
    const funding = document.querySelector('input[name="funding"]:checked');
    const nonFinancial = document.querySelector('input[name="non_financial_method"]:checked');
    const nonFinancialNotesValue = nonFinancialNotes ? nonFinancialNotes.value.trim() : '';
    const termsCheck = document.getElementById('terms-check');

    const titleOk = !!(title && title.value.trim());
    showError('wish-title', !titleOk);
    valid = valid && titleOk;

    const dateOk = !!(date && date.value.trim());
    showError('wish-date', !dateOk);
    valid = valid && dateOk;

    const fundingOk = !!funding;
    showError('funding', !fundingOk);
    valid = valid && fundingOk;

    if (funding && funding.value === 'yes') {
      const paymentOk = !!payment;
      showError('payment', !paymentOk);
      valid = valid && paymentOk;

      const contactOk = !!(contact && contact.value.trim());
      showError('contact', !contactOk);
      valid = valid && contactOk;
    } else {
      showError('payment', false);
      showError('contact', false);
    }

    if (funding && funding.value === 'no') {
      const nonFinancialOk = !!nonFinancial;
      showError('non_financial_method', !nonFinancialOk);
      valid = valid && nonFinancialOk;
      const notesOk = !!nonFinancialNotesValue;
      showError('description_of_way', !notesOk);
      valid = valid && notesOk;
    } else {
      showError('non_financial_method', false);
      showError('description_of_way', false);
    }

    const termsOk = !!(termsCheck && termsCheck.checked);
    showError('terms', !termsOk);
    valid = valid && termsOk;

    return valid;
  };

  if (form) {
    form.addEventListener('submit', (event) => {
      const submitter = event.submitter;
      if (submitter && submitter.value === 'draft') {
        return;
      }
      if (!validateWishForm()) {
        event.preventDefault();
      }
    });
  }

  const bindHideOnInput = () => {
    const title = document.getElementById('wish-title');
    const date = document.getElementById('wish-date');
    const contact = document.getElementById('contact');
    const nonFinancialNotesInput = document.getElementById('non-financial-notes');
    const termsCheck = document.getElementById('terms-check');

    if (title) {
      title.addEventListener('input', () => {
        if (title.value.trim()) showError('wish-title', false);
      });
    }
    if (date) {
      date.addEventListener('input', () => {
        if (date.value.trim()) showError('wish-date', false);
      });
    }
    if (contact) {
      contact.addEventListener('input', () => {
        if (contact.value.trim()) showError('contact', false);
      });
    }
    if (nonFinancialNotesInput) {
      nonFinancialNotesInput.addEventListener('input', () => {
        if (nonFinancialNotesInput.value.trim()) showError('description_of_way', false);
      });
    }

    document.querySelectorAll('input[name="funding"]').forEach((input) => {
      input.addEventListener('change', () => {
        showError('funding', false);
      });
    });

    document.querySelectorAll('input[name="payment"]').forEach((input) => {
      input.addEventListener('change', () => {
        showError('payment', false);
      });
    });

    document.querySelectorAll('input[name="non_financial_method"]').forEach((input) => {
      input.addEventListener('change', () => {
        showError('non_financial_method', false);
      });
    });

    if (termsCheck) {
      termsCheck.addEventListener('change', () => {
        if (termsCheck.checked) showError('terms', false);
      });
    }
  };

  bindHideOnInput();
});
