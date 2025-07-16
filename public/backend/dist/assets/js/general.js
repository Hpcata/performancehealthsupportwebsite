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