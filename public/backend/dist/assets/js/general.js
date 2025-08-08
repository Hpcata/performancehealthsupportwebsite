$(document).ready(function() {
    // Initialize select2 for each element
    $('#top-selling-products, #bestseller-products, #selectionofmonth-products, #related-products-form').each(function () {
        var $this = $(this);
        var elementId = $this.attr('id');

        $this.select2({
            placeholder: 'Select products',
            allowClear: true,
            maximumSelectionLength: getMaximumSelectionLength(elementId),
            ajax: {
                url: window.product_suggestion_url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items.map(function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            }
        });
    });

});

//fetch products name from product ids and set it to the select2 dropdown
function getProductNameArray(productIds, dropdownID) {
    $.ajax({
        url: window.products_name_fetch_url,
        data: {
            product_ids: productIds
        },
        success: function(data) {
            if(data) {
                $.each(data, function(i, e) {                    
                    $('#' + dropdownID).append('<option selected value="' + i + '">' + e + '</option>');
                });
            }
        }
    });
}

function getMinimumSelectionLength(elementId) {
    switch (elementId) {
        case 'top-selling-products':
            return 5;
        case 'bestseller-products':
            return 1; // Example value, adjust as needed
        case 'selectionofmonth-products':
            return 5; // Example value, adjust as needed
        default:
            return 1; // Default value
    }
}

function getMaximumSelectionLength(elementId) {
    switch (elementId) {
        case 'top-selling-products':
            return 5;
        case 'bestseller-products':
            return 99999; // Example value, adjust as needed
        case 'selectionofmonth-products':
            return 5; // Example value, adjust as needed
        case 'related-products-form':
            return 99999; // Example value, adjust as needed
        default:
            return 1; // Default value
    }
}

function loadImgPreview(imgId, imgPreviewId) {
    $(document).on('change', imgId, function (e) {
        file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (event) {
                $(imgPreviewId).attr("src", event.target.result);
                $(imgPreviewId).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            $(imgPreviewId).addClass('d-none');
        }
    });
}

function tinymceInit(id)
{
    tinymce.init({
        selector: 'textarea#'+id,
        plugins: 'code table lists',
        menubar: true,
        toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist',
    });
}
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