<script>

    var variationAddon = null;
    var currentRow = null;
    $(document).on('click', '#order-items-tbody tr td:first-child', function(e){

        currentRow = $(this).closest('tr'); 
        
        
        variationAddon  = currentRow.find('.row-addon-tbody');
        let modalAddon = $('#modal-addon-table tbody');  // Append the HTML of the found rows
        
        modalAddon.empty();
        moveRows(variationAddon,modalAddon);
        $('#addon-modal').modal('show');
        
        
    });

    $('#add-addons-btn').on('click', function() {
        let modalAddon = $('#modal-addon-table tbody');
        let modalAddonTr = $('#modal-addon-table tbody tr');

        // Clear the variationAddon before appending new rows
        variationAddon.empty(); 
        moveRows(modalAddon,variationAddon);
        
        
        if(modalAddonTr.length > 0)
        {
            currentRow.attr('data-id', 0);  // Update the data-id attribute in the DOM
        }

        summaryCalculation();
        // Hide the modal after copying the data
        $('#addon-modal').modal('hide');
    });



    function moveRows(sourceTable,targetTable)
    {
        currentRow.find('.addon-item-name').text('');// clear span addon item name ;

        let addonItemName = null;
        // Loop through each row in the addon table
        sourceTable.find('tr').each(function() {


            var row = $(this).clone(); // Clone each row
            
            addonItemName = $(this).find('.addon-dropdown option:selected').text();
            currentRow.find('.addon-item-name').append(addonItemName+' | ');


            // Copy the values from the inputs (dropdown, price, quantity, total)
            var selectedValue = $(this).find('.addon-dropdown').val();
            row.find('.addon-dropdown').val(selectedValue); // Set the selected value in the cloned row
            row.find('.addon-price').val($(this).find('.addon-price').val());
            row.find('.addon-qty').val($(this).find('.addon-qty').val());
            row.find('.addon-total').val($(this).find('.addon-total').val());

            // Append the cloned row to variationAddon
            targetTable.append(row);
        });
        
    }

        

    
    $(document).on('change','.addon-dropdown', function(){
        let selling_price = parseFloat($(this).find('option:selected').data('selling-price')) || 0;
        let row = $(this).closest('tr');
        row.find('.addon-price').val(selling_price.toFixed(2));
        row.find('.addon-qty').val(1);
        updateAddonItemTotal(row);

       
    });

    $(document).on('keyup','.addon-qty', function(){
        let row = $(this).closest('tr');
        updateAddonItemTotal(row);
        
    });

    function updateAddonItemTotal(row)
    {
        let selling_price = parseFloat(row.find('.addon-price').val()) || 0;
        let qty = parseFloat(row.find('.addon-qty').val()) || 0;
        let total = selling_price* qty;
        row.find('.addon-total').val(total.toFixed(2));
    }

    $(document).on('click','.remove-addon-item', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
    });
      
  
    function appendRowAddonModalTable()
    {
        let row = `


            <tr>

                <td>
                    <input type="hidden" name="type[]"  value="addon">
                    <input type="hidden" name="parent_no[]"  value="">

                    <select name="product_variation_id[]" class="form-control form-select addon-dropdown">
                        <option value="">Choose</option>
                        @foreach ($addons as $addon)
                            <option 
                                value="{{ $addon->id }}"
                                data-selling-price="{{ $addon->selling_price }}"
                            >
                                {{ $addon->name . $addon->selling_price }}</option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input type="number" step="0.01" class="form-control addon-price text-end" name="price[]" value="" readonly>
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control addon-qty text-center" name="quantity[]" value="" >
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control addon-total text-end" name="item_subtotal[]" value="" readonly  >
                </td>
                  <td class="text-center">  
                    <a href="#"><span class="bx bx-trash text-danger remove-addon-item"></span></a> 
                </td>

            </tr> 
        
        `;
        $('#modal-addon-table tbody').append(row);

    }

</script>




{{-- OLD CODE WAS IMPORVED --}}
