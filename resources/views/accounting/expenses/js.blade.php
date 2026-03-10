<script>

    $(document).on('keyup', '.row-amount', function(e) {
        
        let row = $(this).closest('tr');  

        calculation(row);
    
    });


    $(document).on('select2:select', '.tax-dropdown, .tax-type-dropdown', function(){
        let row = $(this).closest('tr');  

        calculation(row);
    });

    function calculation(row)
    {
        let tax = row.find('.tax-dropdown option:selected').val(); 

        let tax_value = row.find('.tax-dropdown option:selected').data('tax-rate');
        let tax_percentage = tax_value/100;
        let tax_type = row.find('.tax-type-dropdown option:selected').val();

        let amount = parseFloat(row.find('.row-amount').val()) || 0;
        let tax_amount = 0;
        let total = 0;
        let total_after_tax = 0;

        if(tax_type == 'inclusive')
        {
            tax_amount = amount -  (amount / (1+ tax_percentage));
            total_after_tax = amount - tax_amount;
            total = amount - tax_amount;
        
        }else if(tax_type == 'exclusive'){
            // For exclusive, calculate tax amount from the base amount
            tax_amount = amount * tax_percentage;
            total_after_tax = amount + tax_amount;
            total = amount;
        }else{
            total = amount;
            total_after_tax = amount;
        } 


        row.find('.row-total-after-tax').val(total_after_tax.toFixed(2));
        row.find('.row-tax-amount').val(tax_amount.toFixed(2));
        row.find('.row-total').val(total.toFixed(2));
        
        



        summaryCalculation();
    } 




    function summaryCalculation()
    {
        let total_amount = 0;
        let total_tax_amount = 0;
        let grand_total = 0;


        //total before tax
        $('.row-total ').each(function(){
            total_amount +=  parseFloat($(this).val()) || 0;
        });
        $('#total_amount').val(total_amount.toFixed(2));

        // total tax
        $('.row-tax-amount').each(function(){
            total_tax_amount +=  parseFloat($(this).val()) || 0;
        });
        $('#total_tax_amount').val(total_tax_amount.toFixed(2));

        grand_total = total_amount+ total_tax_amount
        $('#grand_total').val(grand_total.toFixed(2));


    
    }





    $('#btn-add-more').on('click', function(e){
        e.preventDefault();

        appendNewRow();
    
    });

    $(document).on('click', '.remove-item', function(e) {
        e.preventDefault();
        
        // Show a confirmation dialog
        if (confirm("Are you sure you want to remove this item?")) {
            // If confirmed, remove the row
            $(this).closest('tr').remove();
            
            // Recalculate the summary
            summaryCalculation();
        }
    });



    function appendNewRow(){
        let tableBody = $('#table tbody');

        let row = `
    <tr>
            <td><a style="cursor:grab"><i style="font-size:25px" class="mdi mdi-drag handle text-dark"></i></a> </td>

            <td> 
                {{-- coa => chart of account --}}
                <select  name="COA_id[]" class="form-control select2 coa-dropdown-list" style="width: 100%">                                                
                    <option >Choose...</option>
                    @foreach ($accounts as $account)
                        <option value="{{$account->id}}">{{ $account->id.'-'. $account->name }}</option>
                    @endforeach
                </select>
            </td> 
            <td>
                <input type="text" name="description[]"  class="form-control">  
            </td>
            <td>
                <input type="number" name="amount[]" step="0.0001" class="form-control row-amount" >  
            </td>
            <td> 
                <select  name="tax_type[]" class="form-control tax-type-dropdown select2" style="width: 100%">                                                
                    <option value="">Choose...</option>
                    <option value="inclusive">Inclusive</option>
                    <option value="exclusive">Exclusive</option>
                </select>
            </td> 
            <td> 
                <select  name="tax_percentage[]" class="form-control select2 tax-dropdown" style="width: 100%">                                                
                    @foreach ($taxes as $tax)
                        <option value="{{$tax->rate}}"  data-tax-rate="{{ $tax->rate }}" >{{ $tax->rate }}</option>
                    @endforeach
                </select>  
            </td>  
            <td>
                <input type="number" name="tax_amount[]" step="0.0001" class="form-control row-tax-amount" readonly>  
            </td>
            
            <td>
                <input type="number" name="total[]" step="0.0001" class="form-control row-total" readonly>  
            </td>
            <td class="text-center">  
                <a href="#"><span style="font-size:18px" class="bx bx-trash text-danger remove-item"></span></a>
            </td>
        </tr>
        `;
    
        tableBody.append(row);
        $('.select2', 'table').select2();

    }



</script>


<script>
$(function() {
    // Enable full sorting for tbody rows, allowing drag and drop from bottom to top
    $("#sortable-table").sortable({
        handle: ".handle",  // Set the 'handle' option to the bx-menu icon
        placeholder: "ui-state-highlight",  // Placeholder while dragging
        axis: "y",  // Restrict dragging to vertical movement
        update: function(event, ui) {
            // This event is triggered when the row has been moved
            console.log('Row moved');
        }
    }).disableSelection();  // Disable text selection while dragging
});
</script>

