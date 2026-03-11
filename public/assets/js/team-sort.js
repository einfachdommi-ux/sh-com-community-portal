document.addEventListener('DOMContentLoaded', () => {
  const tbody = document.getElementById('sortable-team-list');
  const saveBtn = document.getElementById('save-sort-order');
  if (!tbody || !saveBtn) return;

  let dragRow = null;

  tbody.querySelectorAll('tr').forEach((row) => {
    row.addEventListener('dragstart', () => {
      dragRow = row;
      row.classList.add('opacity-50');
    });

    row.addEventListener('dragend', () => {
      row.classList.remove('opacity-50');
    });

    row.addEventListener('dragover', (e) => {
      e.preventDefault();
      const currentRow = e.currentTarget;
      if (!dragRow || dragRow === currentRow) return;
      const rect = currentRow.getBoundingClientRect();
      const offset = e.clientY - rect.top;
      if (offset > rect.height / 2) {
        currentRow.after(dragRow);
      } else {
        currentRow.before(dragRow);
      }
    });
  });

  saveBtn.addEventListener('click', async () => {
    const ids = Array.from(tbody.querySelectorAll('tr')).map((row) => row.dataset.id);
    const formData = new FormData();
    ids.forEach((id) => formData.append('ids[]', id));
    formData.append('_csrf', document.querySelector('input[name="_csrf"]')?.value || '');

    try {
      const response = await fetch('/admin/team/sort', { method: 'POST', body: formData });
      if (!response.ok) throw new Error('request_failed');
      window.location.reload();
    } catch (e) {
      alert('Sortierung konnte nicht gespeichert werden.');
    }
  });
});
