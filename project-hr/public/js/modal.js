document.addEventListener('DOMContentLoaded', () => {
  // Dark mode toggle
  const toggle = document.getElementById('themeToggle');
  if (toggle) {
    toggle.addEventListener('click', () => {
      document.body.classList.toggle('dark');
      toggle.textContent = document.body.classList.contains('dark') ? '☀️' : '🌙';
    });
  }

  // Modal control
  const modal = document.getElementById('modal');
  const openBtn = document.getElementById('openModal');
  const closeBtn = document.getElementById('closeModal');

  if (openBtn && modal && closeBtn) {
    openBtn.addEventListener('click', () => modal.classList.add('active'));
    closeBtn.addEventListener('click', () => modal.classList.remove('active'));
    window.addEventListener('click', (e) => {
      if (e.target === modal) modal.classList.remove('active');
    });
  }
});
