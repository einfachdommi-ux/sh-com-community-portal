document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('textarea').forEach(el => {
    if (el.dataset.autosize !== 'off') {
      el.addEventListener('input', () => {
        el.style.height = 'auto';
        el.style.height = (el.scrollHeight) + 'px';
      });
    }
  });
});
