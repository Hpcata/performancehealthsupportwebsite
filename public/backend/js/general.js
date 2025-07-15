// Initialize Bootstrap tooltips for sidebar links
if (window.bootstrap) {
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
    new bootstrap.Tooltip(el);
  });
} 