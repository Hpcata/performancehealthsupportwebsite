// Initialize Bootstrap tooltips for sidebar links
if (window.bootstrap) {
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
    new bootstrap.Tooltip(el);
  });
}

// Remove all tooltips on page load or sidebar state change
function removeAllTooltips() {
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
    if (el._tooltipInstance) {
      el._tooltipInstance.dispose();
      el._tooltipInstance = null;
    }
  });
}

// Enable tooltips only in mini sidebar mode
function enableSidebarTooltips() {
  document.querySelectorAll('.sidebar.sidebar-mini [data-bs-toggle="tooltip"]').forEach(function (el) {
    if (!el._tooltipInstance) {
      el._tooltipInstance = new bootstrap.Tooltip(el);
    }
  });
}

// Handle sidebar state
function handleSidebarTooltips() {
  removeAllTooltips();
  if (document.querySelector('.sidebar.sidebar-mini')) {
    enableSidebarTooltips();
  }
}

function updateSidebarTooltips() {
  var sidebar = document.querySelector('.sidebar');
  var links = document.querySelectorAll('.sidebar .m-link');

  // Always destroy all tooltips and remove attributes first
  links.forEach(function(link) {
    if (link._tooltipInstance) {
      link._tooltipInstance.dispose();
      link._tooltipInstance = null;
    }
    link.removeAttribute('data-bs-toggle');
    link.removeAttribute('data-bs-placement');
    link.removeAttribute('title');
  });
  // Remove any tooltip DOM elements left by Bootstrap
  var tooltips = document.querySelectorAll('.tooltip');
  tooltips.forEach(function(tip) { tip.parentNode.removeChild(tip); });

  // Only in mini mode, add attributes and initialize
  if (sidebar && sidebar.classList.contains('sidebar-mini')) {
    links.forEach(function(link) {
      var text = link.querySelector('.sidebar-mini-text');
      if (text) {
        link.setAttribute('title', text.textContent.trim());
        console.log('Setting tooltip for:', text.textContent.trim());
      }
      link.setAttribute('data-bs-toggle', 'tooltip');
      link.setAttribute('data-bs-placement', 'right');
      if (!link._tooltipInstance) {
        link._tooltipInstance = new bootstrap.Tooltip(link);
        console.log('Tooltip initialized for link:', link);
      }
    });
  }
}

// Run on page load
document.addEventListener('DOMContentLoaded', updateSidebarTooltips);

// Listen for sidebar toggle
var sidebarToggleBtns = document.querySelectorAll('.sidebar-mini-btn');
sidebarToggleBtns.forEach(function(btn) {
  btn.addEventListener('click', function() {
    setTimeout(updateSidebarTooltips, 300);
  });
}); 