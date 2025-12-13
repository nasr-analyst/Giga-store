// Simple theme toggler — stores selection in localStorage and applies class to <html>
(function () {
  const KEY = 'gigastore:theme';
  const root = document.documentElement;

  function applyTheme(t) {
    root.classList.remove('theme-light', 'theme-dark');
    root.classList.add(t === 'dark' ? 'theme-dark' : 'theme-light');
  }

  function getTheme() {
    return localStorage.getItem(KEY) || 'light';
  }

  function setToggleIcon(btn, t) {
    if (!btn) return;
    // Font Awesome icon classes (solid)
    btn.innerHTML = t === 'dark'
      ? '<i class="fa-solid fa-moon" aria-hidden="true"></i>'
      : '<i class="fa-solid fa-sun" aria-hidden="true"></i>';
    btn.setAttribute('aria-pressed', t === 'dark' ? 'true' : 'false');
  }

  function toggleTheme() {
    const next = getTheme() === 'dark' ? 'light' : 'dark';
    localStorage.setItem(KEY, next);
    applyTheme(next);
    setToggleIcon(document.getElementById('theme-toggle-btn'), next);
  }

  // Init
  document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getTheme());
    const btn = document.getElementById('theme-toggle-btn');
    setToggleIcon(btn, getTheme());
    if (btn) {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        toggleTheme();
      });
    }
  });

  window.GigaTheme = { getTheme, applyTheme, toggleTheme };
})();