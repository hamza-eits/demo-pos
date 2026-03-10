<div class="row">
    <div class="row">
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Party Type</label>
                <select name="party_type" id="edit_party_type" class="form-control select2" style="width: 100%">
                    <option value="">Choose...</option>
                    <option value="supplier">Supplier</option>
                    <option value="customer">Customer</option>
                    {{-- <option value="both">Both (Supplier & Customer)</option> --}}
                </select>
            </div>
        </div>
        
        <div class="col-md-6 mt-4">
            <div class="d-flex mx-5">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="edit-type-individual" value="individual">
                    <label class="form-check-label">Individual</label>
                </div>    
                    
                <div class="form-check">
                    <input class="mx-3 form-check-input" type="radio" name="type" id="edit-type-business" value="business">
                    <label class="form-check-label">Business</label>
                </div>
             
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3 ">
                <label class="col-form-label">Status</label>
                <select name="is_active" id="edit_is_active" class="form-select form-control" style="width:100%">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                   
                </select>
            </div>
        </div>
    </div>  

   

    <div class="row business">
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Business Name</label>
                <input name="business_name" id="edit_business_name" type="text" class="form-control">
            </div>   
        </div>
    </div>

    <div class="row individual">
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Prefix</label>
                <input name="prefix" id="edit_prefix" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input name="first_name" id="edit_first_name" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Middle Name</label>
                <input name="middle_name" id="edit_middle_name" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input name="last_name" id="edit_last_name" type="text" class="form-control">
            </div>
        </div> 
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Primary Number</label>
                <input name="mobile" id="edit_mobile" type="number" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Secondary Number</label>
                <input name="alternate_number" id="edit_alternate_number" type="number" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Landline</label>
                <input name="landline" id="edit_landline" type="number" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" id="edit_email" type="text" class="form-control">
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-3 mb-3 mt-3">
            <button id="edit-btn-more-info" type="button" class="btn btn-success waves-effect waves-light">
                 More Information
                 <i class="bx bx bx-caret-down font-size-16 align-middle me-2 mx-2"></i>
            </button>
        </div>
    </div>
      
        
    <div class="row more-info d-none">

        <hr>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Tax Number</label>
                <input name="tax_number" id="edit_tax_number" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Opening Balance</label>
                <input name="opening_balance" id="edit_opening_balance" type="number" step="0.01" value="0" class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Pay Term Type</label>
                <select name="pay_term_type" id="edit_pay_term_type" class="form-control select2" style="width:100%">
                    <option value="days">Days</option>
                    <option value="months">Months</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Credit Limit</label>
                <input name="credit_limit" id="edit_credit_limit" type="number" step="0.01" value="0" class="form-control">
            </div>
        </div>

       <hr>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Address Line 1</label>
                <input name="address_line_1" id="edit_address_line_1" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Address Line 2</label>
                <input name="address_line_2" id="edit_address_line_2" type="text" class="form-control">
            </div>
        </div>
      
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">City</label>
                <input name="city" id="edit_city" type="text" class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">State</label>
                <input name="state" id="edit_state" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Country</label>
                <input name="country" id="edit_country" type="text" class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <div class="mb-3">
                <label class="form-label">Zip Code</label>
                <input name="zip_code" id="edit_zip_code" type="text" class="form-control">
            </div>
        </div>
        <hr>
        
        <div class="col-md-8">
            <div class="mb-3">
                <label class="form-label">Shipping Address</label>
                <input name="shipping_address" id="edit_shipping_address" type="text" class="form-control">
            </div>
        </div>
    </div>   

</div>