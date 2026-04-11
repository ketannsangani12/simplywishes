document.addEventListener('DOMContentLoaded', () => {
  const grid = document.querySelector('[data-donation-default-grid]');
  const defaultInput = document.getElementById('donation-image-default');
  const uploadInput = document.getElementById('donation-image-upload');
  const uploadWrapper = document.getElementById('donation-upload-preview-wrapper');
  const uploadPreview = document.getElementById('donation-upload-preview-image');
  const uploadFrame = document.getElementById('donation-upload-preview-frame');
  const zoomRange = document.getElementById('donation-zoom-range');
  const zoomIn = document.getElementById('donation-zoom-in');
  const zoomOut = document.getElementById('donation-zoom-out');

  if (!grid || !defaultInput) {
    return;
  }

  const clearSelected = () => {
    grid.querySelectorAll('[data-donation-default-item]').forEach((item) => {
      item.classList.remove('ring-2', 'ring-primary', 'scale-[1.02]', 'shadow-lg');
      const overlay = item.querySelector('[data-selected-overlay]');
      const check = item.querySelector('[data-selected-check]');
      if (overlay) overlay.classList.add('opacity-0');
      if (check) check.classList.add('opacity-0');
    });
  };

  grid.addEventListener('click', (event) => {
    const target = event.target.closest('[data-donation-default-item]');
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

  const form = document.getElementById('donation-form');
  const fundingYes = document.getElementById('donation-funding-yes');
  const fundingNo = document.getElementById('donation-funding-no');
  const methodBlock = document.getElementById('donation-method-block');
  const financialBlock = document.getElementById('donation-financial-block');
  const methodLabel = document.getElementById('donation-method-label');

  const showError = (key, show) => {
    const el = document.querySelector(`[data-error-for="${key}"]`);
    if (!el) return;
    el.classList.toggle('hidden', !show);
  };

  const validateDonationForm = () => {
    let valid = true;

    const title = document.getElementById('donation-title');
    const method = document.querySelector('input[name="donation_method"]:checked');
    const funding = document.querySelector('input[name="donation_funding"]:checked');
    const payment = document.querySelector('input[name="donation_payment"]:checked');
    const cost = document.getElementById('donation-cost');
    const terms = document.getElementById('donation-terms');

    const titleOk = !!(title && title.value.trim());
    showError('donation-title', !titleOk);
    valid = valid && titleOk;

    const fundingOk = !!funding;
    showError('donation-funding', !fundingOk);
    valid = valid && fundingOk;

    if (funding && funding.value === 'no') {
      const methodOk = !!method;
      showError('donation-method', !methodOk);
      valid = valid && methodOk;
    } else {
      showError('donation-method', false);
    }

    if (funding && funding.value === 'yes') {
      const paymentOk = !!payment;
      showError('donation-payment', !paymentOk);
      valid = valid && paymentOk;

      const costOk = !!(cost && String(cost.value).trim());
      showError('donation-cost', !costOk);
      valid = valid && costOk;
    } else {
      showError('donation-payment', false);
      showError('donation-cost', false);
    }

    const termsOk = !!(terms && terms.checked);
    showError('donation-terms', !termsOk);
    valid = valid && termsOk;

    return valid;
  };

  const updateFundingVisibility = () => {
    if (methodBlock) {
      if (fundingNo && fundingNo.checked) {
        methodBlock.classList.remove('hidden');
      } else {
        methodBlock.classList.add('hidden');
      }
    }
    if (financialBlock) {
      if (fundingYes && fundingYes.checked) {
        financialBlock.classList.remove('hidden');
      } else {
        financialBlock.classList.add('hidden');
      }
    }
  };

  if (fundingYes) fundingYes.addEventListener('change', updateFundingVisibility);
  if (fundingNo) fundingNo.addEventListener('change', updateFundingVisibility);
  updateFundingVisibility();

  const updateMethodLabel = () => {
    if (!methodLabel) return;
    const selected = document.querySelector('input[name="donation_method"]:checked');
    let labelText = 'Add details for your selected method';
    if (selected && selected.value === 'online_order') {
      labelText = 'Link to webpage';
    } else if (selected && selected.value === 'drop_off_pickup') {
      labelText = 'Drop-off/Pick up Location';
    } else if (selected && selected.value === 'mail') {
      labelText = 'Send in the mail (FedEx, UPS, DHL, USPS, etc)';
    }
    methodLabel.innerHTML = `${labelText} <span class="text-red-500">*</span>`;
  };

  if (form) {
    form.addEventListener('submit', (event) => {
      const submitter = event.submitter;
      if (submitter && submitter.value === 'draft') {
        return;
      }
      if (!validateDonationForm()) {
        event.preventDefault();
      }
    });
  }

  const bindHideOnInput = () => {
    const title = document.getElementById('donation-title');
    const terms = document.getElementById('donation-terms');

    if (title) {
      title.addEventListener('input', () => {
        if (title.value.trim()) showError('donation-title', false);
      });
    }

    document.querySelectorAll('input[name="donation_method"]').forEach((input) => {
      input.addEventListener('change', () => {
        showError('donation-method', false);
        updateMethodLabel();
      });
    });

    document.querySelectorAll('input[name="donation_funding"]').forEach((input) => {
      input.addEventListener('change', () => {
        showError('donation-funding', false);
      });
    });

    document.querySelectorAll('input[name="donation_payment"]').forEach((input) => {
      input.addEventListener('change', () => {
        showError('donation-payment', false);
      });
    });

    const cost = document.getElementById('donation-cost');
    if (cost) {
      cost.addEventListener('input', () => {
        if (String(cost.value).trim()) showError('donation-cost', false);
      });
    }

    if (terms) {
      terms.addEventListener('change', () => {
        if (terms.checked) showError('donation-terms', false);
      });
    }
  };

  bindHideOnInput();
  updateMethodLabel();
});
