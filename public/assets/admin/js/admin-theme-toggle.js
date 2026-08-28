/**
 * Admin Panel Theme Switcher
 * Handles instant switching between Dark Mode and Light (White) Mode
 * with background transition and cookie persistence.
 */

(function () {
  'use strict';

  window.setAdminTheme = function (theme) {
    if (theme !== 'dark' && theme !== 'light') return;

    const body = document.body;
    const html = document.documentElement;

    if (theme === 'dark') {
      body.setAttribute('data-background-color', 'dark');
      html.setAttribute('data-background-color', 'dark');
    } else {
      body.removeAttribute('data-background-color');
      html.removeAttribute('data-background-color');
      body.setAttribute('data-background-color', 'light');
      html.setAttribute('data-background-color', 'light');
    }

    // Set cookie for 365 days
    const d = new Date();
    d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = "admin-theme=" + theme + ";" + expires + ";path=/";

    // Update active button indicators in UI
    const sunBtns = document.querySelectorAll('.js-theme-sun');
    const moonBtns = document.querySelectorAll('.js-theme-moon');

    if (theme === 'dark') {
      sunBtns.forEach(btn => btn.classList.remove('active'));
      moonBtns.forEach(btn => btn.classList.add('active'));
    } else {
      sunBtns.forEach(btn => btn.classList.add('active'));
      moonBtns.forEach(btn => btn.classList.remove('active'));
    }
  };

  // Keyboard shortcut listener (Ctrl + / for global search focus)
  document.addEventListener('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && e.key === '/') {
      e.preventDefault();
      const searchInput = document.querySelector('.topbar-search-box input');
      if (searchInput) searchInput.focus();
    }
  });

})();
