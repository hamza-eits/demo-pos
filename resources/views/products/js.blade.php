<script>
    $('#single-product-radio').on('change', function() {
        if ($(this).is(':checked')) {
            emptyVariantTable();
            showOrHideElement('#variation-select', false, '');
            showOrHideElement('#add-custom-variant', false);
            addRowsVariationTable([''], true);

            showHideTableColumn(false);

        }
    });

    $('#variable-product-radio').on('change', function() {
        if ($(this).is(':checked')) {
            showOrHideElement('#variation-select', true, '');
            showOrHideElement('#add-custom-variant', true);
            showHideTableColumn(true);
            emptyVariantTable();
            addRowsVariationTable([''], true);

        }
    });


    function addRowsVariationTable(values, isSingleProduct = false, isEditable = false) {
        // Get the table body element
        let tableBody = $('#variant-table tbody');
        let productName = $('#product_name').val();

        // Loop through the values and append them to the table
        values.forEach(function(value, index) {
            let row = `

                <tr>
                    <td class="border toggle-visibility">
                        <input type="hidden" class="form-control variant-id" name="product_variation_id[]" value="">

                        <div class="add-product">
                            <input type="checkbox" class="form-check-input is-available" name="is_available[]" checked>
                        </div>
                    </td>
                    <td class="border toggle-visibility">
                        <div class="add-product">
                            <input 
                                type="text" 
                                class="form-control variant-size" 
                                name="size[]" 
                                value="${isSingleProduct ? ' ' : value}" 
                                placeholder="e.g S,M,L" 
                                ${isEditable ? '' : 'readonly'}
                            > 
                        </div>
                    </td>
                    <td class="border">
                        <div class="add-product">
                            <input 
                                type="text" 
                                class="form-control variant-name"  
                                name="name[]" 
                                value="${isSingleProduct ? productName : productName + '-' + value}" 
                                placeholder=""  
                                readonly
                                >
                        </div>
                    </td>
                     <td class="border">
                        <div class="add-product">
                            <input type="text" class="form-control variant-barocde"  name="barcode[]" min="5" max="5" placeholder="Enter 5 Digits barcode e.g code:19 will be like 00019">
                        </div>
                    </td>
                    <td class="border">
                        <div class="add-product">
                            <input type="number" class="form-control text-end purchase-price" step="0.01" name="purchase_price[]">
                        </div>
                    </td>    
                    <td class="border">
                        <div class="add-product">
                            <input type="number" class="form-control text-end selling-price" step="0.01" name="selling_price[]">
                        </div>
                    </td>    
                  
                    <td class="border  d-none">
                        <div class="add-product">
                            <input type="file" class="form-control"  name="image[]">
                        </div>
                    </td>
                    <td class="border toggle-visibility">
                        <button type="button" class="btn btn-danger delete-row">Delete</button>
                    </td>

                </tr>
                
            `;

            // Append the row to the table body
            tableBody.append(row);
        });

    }



    function showHideTableColumn(show = true) {
        if (show) {
            $('.toggle-visibility').show();
        } else {
            $('.toggle-visibility').hide();
        }


    }

    $(document).on('click', '#add-custom-variant', function() {
        console.log('Button clicked');

        addRowsVariationTable([''], false, true);

    });



    function emptyVariantTable() {
        $('#variant-table tbody').empty();
    }

    // Assuming you are appending the rows to a tbody with an id of "variant-table"
    $('#variant-table').on('click', '.delete-row', function() {
        let row = $(this).closest('tr');
        let rowCount = $('#variant-table tbody tr').length;

        // Check if there is only one row left
        if (rowCount <= 1) {
            alert("You cannot delete the last row.");
            return;  // Prevent deletion if there's only one row left
        }

        row.remove();  // Proceed with removing the row
    });



    //variant Dropdown
    $('#variation-select').change(function() {
        let values = $(this).find(':selected').data(
            'values'); // selected variation attribute, data-values store

        emptyVariantTable();

        addRowsVariationTable(values, false); // calling function to append the rows in #variant-table

    });



    function showOrHideElement(selector, show = false, value = false) {

        let element = $(selector); // wrap selector as jQuery object

        if (show) {
            element.removeClass('d-none'); // Show the select box
        } else {
            element.addClass('d-none'); // Hselectore the select box
        }

        if (value) {
            element.val(value);

        }
    }

    $(document).on('change', '#product_name, .variant-size', function() {
        let productName = $('#product_name').val();
        let isSingleProduct = $('#single-product-radio').is(':checked');

        // Loop through each variant row and generate barcode
        $('.variant-size').each(function() {
            let variantName = $(this).val();
            let variantBarcodeInput = $(this).closest('tr').find('.variant-name');
            let generatedBarcode = isSingleProduct ? productName : productName + '-' + variantName;
            variantBarcodeInput.val(generatedBarcode);
        });
    });
    /*
    $('#product_name').on('change', function() {
        let productName = $('#product_name').val();

        // Loop through each variant row and generate barcode
        $('.variant-size').each(function() {
            let variantName = $(this).val();
            let variantBarcodeInput = $(this).closest('tr').find('.variant-name');
            let isSingleProduct = $('#single-product-radio').is(':checked');
            let generatedBarcode = isSingleProduct ? productName : productName + '-' + variantName;
            variantBarcodeInput.val(generatedBarcode);
        });

    });
   */
    $('#common-selling-price').on('change', function() {
        let commonSellingPrice = $('#common-selling-price').val();
        $('.selling-price').val(commonSellingPrice);
    });

    $('#common-purchase-price').on('change', function() {
        let commonPurchasePrice = $('#common-purchase-price').val();
        $('.purchase-price').val(commonPurchasePrice);
    });

    $('.is-available').on('change', function() {
        let isAvailable = $(this).val();
        if (isAvailable) {
            alert('Available');
        } else {
            alert('Not Available');
        }
    });





</script>
