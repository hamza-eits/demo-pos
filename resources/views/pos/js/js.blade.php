<script>
    function startupModal() {
        let no_items = $('#order-items-tbody tr').length;

        if (no_items == 0) {
            let option = $('#serving_type').find('option:selected').val();
            if (option == 'Dine In') {
                $('#tableNo-modal').modal('show');
                $('#staff-modal').modal('show');
            }

        }



    }
</script>

 


{{-- ***************************** RIGHT SIDE POS SCREEN CALCULATION ***************************** --}}


{{-- fetch Customer List --}}
<script>
    function fetchCustomerList() {
        let dropdown = $('#customer_id'); // Reference to the clicked dropdown

        $.ajax({
            url: '{{ route('party.fetchCustomerList') }}',
            type: 'GET',
            beforeSend: function() {
                dropdown.empty().append('<option value="">Loading...</option>');
            },
            success: function(data) {
                dropdown.empty().append('<option selected value="">Choose...</option>');
                if (data.length === 0) {
                    dropdown.append('<option disabled>No records found</option>');
                } else {
                    $.each(data, function(index, item) {
                        dropdown.append(new Option(item.business_name, item.id));
                    });
                    dropdown.find('option').eq(1).prop('selected', true);


                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
                alert('Failed to load items. Please try again.');
            }
        });
    }
</script>


{{-- Serving Table and Staff --}}
<script>
    $('.serving-table-no').on('click', function(e) {
        e.preventDefault();
        let table_no = $(this).data('table-no');
        $('#table-no').val(table_no);
        $('#tableNo-modal').modal('hide');

    });

    $('.serving-staff').on('click', function(e) {
        e.preventDefault();
        let staff_id = $(this).data('staff-id');
        let staff_name = $(this).data('staff-name');
        $('#staff-id').val(staff_id);
        $('#staff-name').val(staff_name);
        $('#staff-modal').modal('hide');

    });
</script>


{{-- Search scan Product by name and code --}}
<script>
    $('#barcode-scan').on('keyup', function(e) {

        if (e.key === "Escape") {
            $('#product-dropdown').hide();
            $('#barcode-scan').blur();
            $('#barcode-scan').val('');
            return; // Exit the function if Escape key is pressed
        }

        let query = $(this).val();

        if (query.length > 0) {
            // Show the dropdown when typing

            // searchProductVariationInventory(query)
            
            $('#product-dropdown').show();

            $.ajax({
                url: '{{ route('pos.searchProductVariations') }}', // Define your route here
                method: 'GET',
                data: {
                    query: query
                },
                success: function(data) {
                    // Clear the dropdown before appending new results
                    $('#product-dropdown').empty();
                    console.log(data);

                  
                    // Loop through the products and display them in the dropdown
                    if (data.length > 0) {
                        $.each(data, function(index, data) {
                            let productItem = `
                            <a 
                            class="dropdown-item searched-product"
                            title="${data.name}" 
                            data-id="${data.id}"
                            data-name="${data.name}"
                            data-product-type="${data.product_type}"
                            data-stock-barcode="${data.barcode}"
                            data-variation-barcode="${data.barcode}"
                            data-selling-price="${data.selling_price}"
                            data-unit-cost="${data.purchase_price}"
                            data-product-description="${data.product.description}"
                            data-product-is-decimal-qty-allowed="${data.product.is_decimal_qty_allowed}"
                            data-product-is-price-editable="${data.product.is_price_editable}"
                            href="#">
                                ${data.barcode} -  ${data.name} 
                            </a>`;
                            $('#product-dropdown').append(productItem);
                        });
                        
                    } else {
                        $('#product-dropdown').append(
                            '<a class="dropdown-item" href="#">No products found</a>');
                    }
                },
                error: function() {
                    $('#product-dropdown').empty().append(
                        '<a class="dropdown-item" href="#">Error occurred</a>');
                }
            });
        } else {
            // Hide the dropdown if the search input is empty
            $('#product-dropdown').hide();
        }
    });
    $('#process-barcode-scan').on('keyup', function(e) {

        let query = $(this).val();
       
        if (e.key === 'Enter' ||e.keyCode === 13) {

            $.ajax({
                url: '{{ route('pos.processBarcodeScan',':barcode') }}'.replace(':barcode',query), 
                method: 'GET',
                data: {
                    query: query
                },
                success: function(response) {


                    if (response.success == true) {


                        $('#process-barcode-scan').val('');
                        addProductToOrderItemTable(
                            response.id, 
                            response.name, 
                            response.stockBarcode, 
                            response.variationBarcode,
                            response.unitPrice,
                            response.unitCost,
                            response.quantity,
                            response.subtotal,
                            response.type,
                            response.description,
                            response.isPriceEditable,
                            response.isDecimalQtyAllowed
                        );
                        summaryCalculation();
                    
                        $('#process-barcode-scan').val();

                        // notyf.success({
                        //     message: response.message,
                        //     duration: 3000
                        // });
                    } else {
                        notyf.error({
                            message: response.message,
                            duration: 5000
                        });
                    }
                    console.log(data);

                   
                },
                error: function(e) {
                    notyf.error({
                        message: e.responseJSON.message,
                        duration: 5000
                    });
                }
            });
        } else {
            // Hide the dropdown if the search input is empty
            $('#product-dropdown').hide();
        }


       
       
    });




   
</script>


{{-- On Product Variation Img Click and Searched Product Click --}}
<script>
    // When a product image or its td is clicked
    $(document).on('click', '.product-variation-img, .searched-product', function(e) {
        e.preventDefault();
        // Get product data

        // startupModal();



        let product_id = $(this).data('id');
        let name = $(this).data('name');
        // let stockbarcode = $(this).data('stock-barcode');
        let stockbarcode = product_id;
        let variationBarcode = $(this).data('variation-barcode');
        let sellingPrice = parseFloat($(this).data('selling-price')) || 0;
        let productType = $(this).data('product-type');
        let productDescription = $(this).data('product-description');
        let unitCost = $(this).data('unit-cost');
        let isPriceEditable = $(this).data('product-is-price-editable');
        let isDecimalQtyAllowed = $(this).data('product-is-decimal-qty-allowed');
        let quantity = 1;
        let subtotal = sellingPrice;

        let existingRow = $('#order-items-tbody').find('tr[data-id="' + stockbarcode + '"]');
        if (existingRow.length > 0) {
            increaseExistingProductQty(existingRow);
        } else {
            addProductToOrderItemTable(product_id, name, stockbarcode, variationBarcode,sellingPrice,unitCost,quantity,subtotal,productType, productDescription,isPriceEditable,isDecimalQtyAllowed);

        }


        summaryCalculation(); // Calling the summaryCalculation() function to update the order summary


        if ($(this).hasClass('product-variation-img')) { // if product variation was clicked
            $('#product-variation-modal').modal('hide');

        } else if ($(this).hasClass('searched-product')) { // if searched-product was clicked
            $('#barcode-scan').val('');
            $('#barcode-scan').trigger('keyup');
        }

    });

    function increaseExistingProductQty(existingRow) {
        // If product exists, just increment the quantity
        let currentQty = parseInt(existingRow.find('.row-quantity').val());
        let newQty = currentQty + 1;
        existingRow.find('.row-quantity').val(newQty);
        rowCalculation(existingRow);

    }


    function addProductToOrderItemTable(id, name, stockBarcode, variationBarcode ,unitPrice,unitCost,quantity,subtotal, type, description,isPriceEditable,isDecimalQtyAllowed) {
        
        
        let table_length = $('#order-items-tbody tr').length;

        let newRow = `
            <tr data-id="${stockBarcode}" data-row-no="${table_length}">
                <!-- Product -->
                <td class="text-start">
                    <input type="hidden" name="product_type[]" value="${type}">
                    <input type="hidden" name="parent_no[]" value="${table_length}">
                    <input type="hidden" name="product_variation_id[]" value="${id}">
                    <input type="hidden" name="stock_barcode[]" value="${stockBarcode}">
                    <input type="hidden" name="variation_barcode[]" value="${variationBarcode}">
                    <input type="hidden" name="description[]" value="${description}">
                    <strong>${name} </strong>  <br><small class="text-muted">${description}</small>
                    <br>
                    <span class="addon-item-name text-warning small"></span>
                </td>

                <!-- Price -->
                <td>
                     <input type="hidden" name="unit_cost[]" class="row-unit-cost" value="${unitCost}">
                     <input 
                        type="number" 
                        name="unit_price[]" 
                        class="row-unit-price js-event-auto-highlight row-calculation form-control form-control-sm text-center" step="0.01" min="0" 
                        value="${unitPrice}" 
                        ${isPriceEditable == 1 ? '' : 'readonly'}
                    >
                </td>

                <!-- Minus -->
                <td>
                    <a class="row-minus-btn text-danger" style="cursor: pointer;" title="Decrease quantity">
                        <i class="fa fa-minus-square fa-lg"></i>
                    </a>
                </td>

                <!-- Quantity -->
                <td>
                    <input 
                        type="number" 
                        name="quantity[]" 
                        class="row-quantity row-calculation form-control form-control-sm text-center" 
                        min="${isDecimalQtyAllowed == 1 ? '0.01' : '1'}"
                        step="${isDecimalQtyAllowed == 1 ? '0.01' : '1'}"
                        data-product-is-decimal-qty-allowed="${isDecimalQtyAllowed}"
                        value="${quantity}"

                    >
                </td>

                <!-- Plus -->
                <td>
                    <a class="row-plus-btn text-success" style="cursor: pointer;" title="Increase quantity">
                        <i class="fa fa-plus-square fa-lg"></i>
                    </a>
                </td>

                <!-- Subtotal -->
                <td>
                    <input type="hidden" name="cost_amount[]" class="row-cost-amount form-control form-control-sm text-center" value="${unitCost}">
                    <input type="number" name="subtotal[]" class="form-control form-control-sm text-center row-subtotal" value="${subtotal}" readonly>
                </td>

                <!-- Discount -->
                <td>
                    <div class="input-group">
                        <input type="number" name="discount_value[]" class="row-discount-value form-control form-control-sm text-center" value="0" readonly step="0.01">
                        <!-- <span class="input-group-text">%</span>--> 

                        <input type="hidden" name="discount_type[]"       class="row-discount-type" value="percentage">
                        <input type="hidden" name="discount_amount[]"     class="row-discount-amount" step="0.01" >
                        <input type="hidden" name="discount_unit_price[]" class="row-discount-unit-price" value="0" step="0.01">
                    </div>
                </td>
                
                <!-- After Discount -->
                 <td>
                    <input type="number" name="subtotal_after_discount[]" class="row-subtotal-after-discount form-control form-control-sm text-center " value="${subtotal}" readonly>
                </td>

                <!-- Actions -->
                <td>
                    <a class="row-remove-btn text-danger" style="cursor: pointer;" title="Remove item">
                        <i class="far fa-times-circle fa-lg"></i>
                    </a>
                </td>

              
                <!-- Add-ons -->
                <td class="d-none">
                    <table>
                        <tbody class="row-addon-tbody"></tbody>
                    </table>
                </td>
            </tr>
            `;

        $('#order-items-tbody').append(newRow);
        

        // Function to scroll to the bottom of the table when new content is added
        // Scroll to the bottom of the table after adding the new row
        $('#order-items-table-div').animate({
            scrollTop: $('#order-items-table-div')[0].scrollHeight
        }, 'slow');

    }

    $(document).on('keyup','.row-calculation', function() {
        let row = $(this).closest('tr');
        rowCalculation(row); // Call the rowCalculation function to update the row's subtotal and other calculations
    });
    
    
    $(document).on('keyup', '.row-quantity', function () {
        let input = $(this);
        let isDecimalAllowed = input.data('product-is-decimal-qty-allowed') == 1;
        let value = parseFloat(input.val());

        
        // Rule 1: Disallow negative numbers
        if (value < 0) {
        alert("Negative quantity is not allowed.");
            // input.val(isDecimalAllowed ? "0.01" : "1");
             input.val(1);
            return;
        }

        // Rule 2: If decimal is not allowed, disallow decimal input
        if (!isDecimalAllowed && !Number.isInteger(value)) {
            alert("This product does not allow decimal quantities.");
            input.val("1");
            return;
        }
    });

    // Check if the input is editable event is on : .row-unit-pirce
    $(document).on('focus', '.js-event-auto-highlight', function () {
        // Check if the input is editable (i.e., does not have the 'readonly' attribute)
        if (!$(this).prop('readonly')) {
        // Automatically select all text inside the input when it receives focus
        // This allows the user to easily overwrite the value without manually clearing it
            $(this).select(); 
        }
    });


    // When a remove btn is clicked
    $(document).on('click', '.row-remove-btn', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();

        summaryCalculation(); // Calling the summaryCalculation() function to update the order summary
    });


    function updateProductAddonQuantity(row, quantity) {
        let addon_table = row.find('table tbody');

        addon_table.find('tr').each(function() {

            $(this).find('.addon-qty').val(quantity);

            let addon_price = $(this).find('.addon-price').val();
            let addon_total = parseFloat(quantity * addon_price);
            $(this).find('.addon-total').val(addon_total);
        });
    }

    // Event handler for increasing quantity
    $(document).on('click', '.row-plus-btn', function(e) {
        e.preventDefault();
        let row = $(this).closest('tr');
        let currentQty = parseFloat(row.find('.row-quantity').val()) || 1;
        let newQty = currentQty + 1;
        row.find('.row-quantity').val(newQty);

        rowCalculation(row);
    });

    // Event handler for decreasing quantity
    $(document).on('click', '.row-minus-btn', function(e) {
        e.preventDefault();
        let row = $(this).closest('tr');
        let currentQty = parseFloat(row.find('.row-quantity').val()) || 1;
        let newQty = currentQty - 1;

        // Ensure quantity doesn't go below 1
        if (newQty >= 1) {
            row.find('.row-quantity').val(newQty);
            rowCalculation(row);
        }
    });

    
    // END:: #order-items-table update qty and subtotal 

    function rowCalculation(row)
    {
        let qty = parseFloat(row.find('.row-quantity').val());
        let price = parseFloat(row.find('.row-unit-price').val()) || 0;
        let unitCost = parseFloat(row.find('.row-unit-cost').val()) || 0;

        let subtotal = qty * price;
        let costAmount = qty * unitCost;

        row.find('.row-subtotal').val(subtotal.toFixed(2));
        row.find('.row-cost-amount').val(costAmount.toFixed(2));

        let discountUnitPrice = parseFloat(row.find('.row-discount-unit-price').val()) || 0;
        let discountAmount = discountUnitPrice * qty;
        let totalAfterDiscount = subtotal - discountAmount;

        row.find('.row-subtotal-after-discount').val(totalAfterDiscount.toFixed(2));

        summaryCalculation();

    }
</script>

{{-- Calcuate Discount --}}
<script>

    $('#invoice-apply-discount').on('click', function(e) {
        
        summaryCalculation(); // do summary calculation again
        $('#invoice-discount-modal').modal('hide');

    });

    $('#apply-shipping').on('click', function() {
        let shipping_fee = parseFloat($('#shipping-fee').val()) || 0;
        $('.show-shipping-fee').val(shipping_fee.toFixed(2));

        summaryCalculation(); // do summary calculation again

        $('#shipping-modal').modal('hide');
    });

    $('#reset-shipping').on('click', function() {
       
        if (confirm('Are you sure you want to reset the shipping information?')) {
        $('#shipping-form')[0].reset();       // Reset the shipping form
        $('.show-shipping-fee').val(0);            // summary table Shipping Fee
        $('#shipping-modal').modal('hide');   // Hide the modal
        summaryCalculation();                 // Recalculate summary
    }
    });
</script>


{{-- Summary Calcualtion --}}
<script>
    function summaryCalculation() {
        const itemCount = getTotalQuantity();

        const itemsSubtotal = getItemsSubtotal();

        const addonsSubtotal = getAddonsSubtotal();

        const totalCostAmount = getTotalCostAmount();
        
        const subtotal = itemsSubtotal + addonsSubtotal;
        $('#subtotal').val(subtotal.toFixed(2));

        const discountAmount = calculateInvoiceDiscount(subtotal);
        
        const subtotalAfterDiscount = subtotal - discountAmount;

        
        const taxValue = parseFloat($('#tax-value').val()) || 0;
        const taxType = $('#tax-type').val();
        const taxAmount = calculateTax(subtotalAfterDiscount, taxValue, taxType);

        const shippingFee = parseFloat($('#shipping-fee').val()) || 0;
        const grandTotal = calculateGrandTotal(subtotalAfterDiscount,shippingFee, taxAmount, taxType);

        updatePaymentFields(grandTotal);
    }

// Helper Functions

    function getTotalQuantity()
    {
        let total = 0;
        $('#order-items-table tbody .row-quantity').each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total-quantity').val(total.toFixed(2));

        return total;
    }

    function getItemsSubtotal() {
        let total = 0;
        $('#order-items-table tbody .row-subtotal-after-discount').each(function () {
            total += parseFloat($(this).val()) || 0;
        });


        return total;
    }

    function getAddonsSubtotal() {
        let total = 0;
        $('#order-items-table tbody .addon-total').each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        //assign that 
        $('#addon-total-amount').val(total.toFixed(2));

        return total;
    }

   
    function getSubtotalAfterDiscount(subtotal, discount)
    {
        return subtotal - discount;
    }

    function calculateTax(taxableAmount, taxValue, taxType) {
        let taxAmount = 0;

        if (taxValue > 0 && taxableAmount > 0) {
            if (taxType == 'inclusive') {
                taxAmount = taxableAmount * (taxValue / (100 + taxValue));
            } else { // exclusive
                taxAmount = taxableAmount * (taxValue / 100);
            }
        }

        $('#tax-amount').val(taxAmount.toFixed(2)); // Assign to field
        return taxAmount;
    }

    function getTotalCostAmount(){
        let total = 0;
        $('#order-items-table tbody .row-cost-amount').each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        //assign that 
        $('#total-cost-amount').val(total.toFixed(2));

        return total;
    }

    function calculateGrandTotal(subtotal, shipping, taxAmount , taxType) {
        
        let grandTotal = 0;
        
        if(taxType === 'inclusive')
        {
            grandTotal = subtotal  + shipping;
        }else{
            grandTotal = subtotal + taxAmount + shipping;
        }

        $('#grand-total').val(grandTotal.toFixed(2));
        return grandTotal;

    }

    function updatePaymentFields(total) {
        $('#multiple-payments-total-amount').val(total.toFixed(2));
        $('#multiple-payments-paid-amount').val(total.toFixed(2));
        $('#multiple-payments-drfault-payment-amount').val(total.toFixed(2));
        $('#multiple-payments-change-return-amount').val(0);
    }


    function calculateInvoiceDiscount(subtotal) {
        const discountType = $('input[name="invoice_discount_type"]:checked').val();
        const rawDiscountValue = $('input[name="invoice_discount_value"]').val();
        const discountValue = parseFloat(rawDiscountValue) || 0;

        let discountAmount = 0;

        if (discountValue > 0) {
            if (discountType === 'fixed') {
                discountAmount = discountValue;
            } else if (discountType === 'percentage') {
                discountAmount = (discountValue / 100) * subtotal;
            }
        }

        $('#invoice-discount-amount').val(discountAmount.toFixed(2));
        return discountAmount;
    }

</script>





{{-- ***************************** LEFT SIDE POS SCREEN CALCULATION ***************************** --}}


{{-- Product Search By Category, Brand and initial load --}}
<script>
    // START:: category brand show all filter

    var table = null;
    $(document).ready(function() {
        // Initialize DataTable only once
        table = $('#product-table').DataTable({
            "paging": true, // Enable pagination
            "lengthChange": false, // Disable page length change (if needed)
            "searching": true, // Enable search functionality
            "ordering": false, // Enable column sorting
            "info": false, // Show info about current page and total
            "autoWidth": false, // Disable auto-width
            "responsive": true, // Ensure responsiveness on smaller screens
            "pageLength": 2, // Number of rows per page
        });

        // Function to load products based on selected filters
        function loadProducts(categoryId, brandId) {
            $.ajax({
                url: '{{ route('pos.fetchProducts') }}', // Your route for fetching products dynamically
                type: 'GET',
                data: {
                    category_id: categoryId,
                    brand_id: brandId
                },
                success: function(data) {
                    $('#product-tbody').html(data);
                    table.clear(); // Clear the previous table data
                    table.rows.add($('#product-tbody tr'))
                        .draw(); // Add new rows and redraw the table
                }
            });

        }

        // Event listeners for filters
        $('.category-filter').click(function() {
            var categoryId = $(this).data('id');
            loadProducts(categoryId, '');
            $('#category-modal').modal('hide'); //close modal
        });

        $('.brand-filter').click(function() {
            var brandId = $(this).data('id');
            loadProducts('', brandId);
            $('#brand-modal').modal('hide'); //close modal

        });
        $('#show-all-filter').click(function() {

            loadProducts('', '');

        });

        // Initial load of products
        loadProducts('', ''); // Empty values to load all products initially
    });

    function searchProductVariationInventory(input) {
        
        $.ajax({
            url: '{{ route('pos.searchProductVariationInventory') }}',
            type: 'GET',
            data: {
                query: input
            },
            success: function(data) {
                $('#product-tbody').html(data);
                table.clear(); // Clear the previous table data
                table.rows.add($('#product-tbody tr'))
                    .draw(); // Add new rows and redraw the table
            }
        });

    }
    // END:: category brand show all filter
</script>



{{-- ***************************** Buttons at the bottom ***************************** --}}


{{-- Reset Order page function --}}
<script>
    function resetOrderPage() {
        $('#order-items-table tbody').empty(''); //empty table

        if ($("#pos-screen-store")[0]) $("#pos-screen-store")[0].reset();
        if ($("#shipping-form")[0]) $("#shipping-form")[0].reset();
        if ($("#discount-form")[0]) $("#discount-form")[0].reset();
        if ($("#multiple-payments-form")[0]) $("#multiple-payments-form")[0].reset();
    }
</script>

{{-- Click Product Image, will load variations and show that in  modal --}}
<script>
    // When a product is clicked
    $(document).on('click', '.product-img', function(e) {
        var productId = $(this).data('id');
        var productName = $(this).data('name');
        var productVariationType = $(this).data('variation-type');


        if (productVariationType == 'single') {
            addProductToOrderList(productId);
        } else {
            // Set the modal title and price
            $('#productName').text(productName);
            loadProductVariationsInModel(productId);
        }



    });

    /**
     * Loads product variations and displays them in a modal.
     *
     * This function is used for products that have multiple variations (e.g., size, color).
     * It performs an AJAX request to fetch the variation HTML from the server,
     * injects it into the modal content area, and then shows the modal.
     *
     * @param {number} productId - The ID of the product to load variations for.
     */
    function loadProductVariationsInModel(productId) {
        $.ajax({
            url: '{{ route('pos.fetchProductVariations') }}',
            type: 'GET',
            data: {
                product_id: productId
            },

            success: function(data) {
                $('#product-variation-list').html(data);
                $('#product-variation-modal').modal('show');
            }
        });

    }
    /**
     * Adds a single-type product directly to the order list.
     *
     * If the product type is 'single', this function:
     * - Fetches its variations (usually one by default)
     * - Automatically triggers a click on the variation image
     * - Skips opening the modal
     *
     * This provides a faster workflow for simple products.
     */
    function addProductToOrderList(productId) {
        $.ajax({
            url: '{{ route('pos.fetchProductVariations') }}',
            type: 'GET',
            data: {
                product_id: productId
            },

            success: function(data) {
                $('#product-variation-list').html(data);
                // Automatically select the first available variation
                $('.product-variation-img').trigger('click');
            }
        });

    }
</script>


{{-- BUTTONS AT BOTTOM --}}


{{-- create page button --}}
<script>
    $('#store-order-as-completed').on('click', function(e) {
        e.preventDefault();
        let clickedButton = $(this);
        let status = "completed";
        storeOrder(status, clickedButton, true);

    });

    $('#store-order-as-draft').on('click', function(e) {
        e.preventDefault();
        let clickedButton = $(this);
        let status = "draft";
        storeOrder(status, clickedButton);

    });


    


    $('#store-order-as-completed-in-multiple-payments').on('click', function(e) {
        e.preventDefault();
        let clickedButton = $(this);
        let status = "completed";

        let isValid = validateMultipayTable();
        if (!isValid) {
            return; // If validation fails, do not proceed
        }

        $('#multiple-payments-modal').modal('hide');
        /**
         * This checks the current status of the order.
         * #order-status this will have value if the order is being edited.
         * If it's 'completed', it updates the order.
         * If it's not, create the order.
         */
        let currentStatus = $('#order-status').val();

        if(currentStatus == 'completed') {
            updateOrder(status, clickedButton);
        }else{
            storeOrder(status, clickedButton,true);
        }

    });


    $('#cancel').on('click', function(e) {
        e.preventDefault();
        resetOrderPage();
    });


    


    





</script>

{{-- Edit Page Buttons --}}
<script>
    $('#update-order-as-completed').on('click', function(e) {
        e.preventDefault();
        let clickedButton = $(this);
        let status = "completed";
        updateOrder(status, clickedButton);
    });

    $('#update-order-only').on('click', function(e) {
        e.preventDefault();
        let clickedButton = $(this);
        let status = $('#order-status').val();
        updateOrder(status, clickedButton);

    });
   

    $('#discard-order-changes').on('click', function(e) {
        e.preventDefault();
        window.location.href = '{{ route('point-of-sale.create') }}';

    });
</script>
{{-- common buttons --}}
<script>
    $('#fetch-completed-orders').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('pos.fetchTodayCompletedOrders') }}',
            type: 'GET',
            success: function(data) {
                $('#completed-order-list').html(data);
                $('#completed-orders-modal').modal('show');
            }
        });
    });
    $('#fetch-draft-orders').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('pos.fetchTodayDraftOrders') }}',
            type: 'GET',
            success: function(data) {
                $('#draft-order-list').html(data);
                $('#draft-orders-modal').modal('show');
            }
        });
    });
    $('#multiple-payments').on('click', function(e) {
        e.preventDefault();
        
        //remove all rows except the first one
        $('#multiple-payments-table tbody tr:not(:first)').remove();

        $('#multiple-payments-modal').modal('show');
    });


    $('#cash-register-btn').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('pos.fetchCashRegisterSummary') }}',
            type: 'GET',
            success: function(data) {
                $('#cash-register-summary').html(data);
                $('#cash-register-summary-modal').modal('show');
            }
        });
    });
</script>






