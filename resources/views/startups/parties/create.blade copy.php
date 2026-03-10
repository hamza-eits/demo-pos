@extends('template.tmp')
@section('title', 'pagetitle')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                
                <form>
                    <div class="row col-md-8">
                        <div class="card">
                            <div class="card-body">
                                
                                <div class="row">
                                    <h4 class="card-title mb-4">Contact Information</h4>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Contact Type</label>
                                                <select name="contact_type" id="contact_type" class="form-control select2">
                                                    <option value="supplier">Supplier</option>
                                                    <option value="customer">Customer</option>
                                                    <option value="both">Both (Supplier & Customer)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-4">
                                            <div class="d-flex mx-5">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="type" id="type-individual">
                                                    <label class="form-check-label">Individual</label>
                                                </div>    
                                                    
                                                <div class="form-check">
                                                    <input class="mx-3 form-check-input" type="radio" name="type" id="type-business">
                                                    <label class="form-check-label">Business</label>
                                                </div>
                                             
                                            </div>
                                        </div>
                                    </div>  

                                    <div class="row business">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Business Name</label>
                                                <input name="business_name" id="business_name" type="text" class="form-control">
                                            </div>   
                                        </div>
                                    </div>

                                    <div class="row individual">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Prefix</label>
                                                <input name="prefix" id="prefix" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">First Name</label>
                                                <input name="first_name" id="first_name" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Middle Name</label>
                                                <input name="middle_name" id="middle_name" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Last Name</label>
                                                <input name="last_name" id="last_name" type="text" class="form-control">
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile</label>
                                                <input name="mobile" id="mobile" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Alternate Number</label>
                                                <input name="alternate_number" id="alternate_number" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Landline</label>
                                                <input name="landline" id="landline" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input name="email" id="email" type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col-md-3 mb-3 mt-3">
                                            <button id="btn_more_info" type="button" class="btn btn-success waves-effect waves-light">
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
                                                <input name="tax_number" id="tax_number" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Balance</label>
                                                <input name="balance" id="balance" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Pay Term Type</label>
                                                <select name="pay_term_type" id="pay_term_type" class="form-control select2" style="width:100%">
                                                    <option value="days">Days</option>
                                                    <option value="months">Months</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Credit Limit</label>
                                                <input name="credit_limit" id="credit_limit" type="text" class="form-control">
                                            </div>
                                        </div>

                                       <hr>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 1</label>
                                                <input name="address_line_1" id="address_line_1" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line 2</label>
                                                <input name="address_line_2" id="address_line_2" type="text" class="form-control">
                                            </div>
                                        </div>
                                      
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">City</label>
                                                <input name="city" id="city" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">State</label>
                                                <input name="state" id="state" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Country</label>
                                                <input name="country" id="country" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Zip Code</label>
                                                <input name="zip_code" id="zip_code" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <hr>
                                        
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Shipping Address</label>
                                                <input name="shipping_address" id="shipping_address" type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>   

                                </div>
                            </div>
                            
                            
                        </div>
                      
                    </div>
                </form>
            </div>
         </div>
    </div>

<script>
    $(document).ready(function(){

        $('.individual').addClass('d-none');
        $('.business').addClass('d-none');

        $('#type-individual').on('click', function(){
            $('.business').addClass('d-none');
            $('.individual').removeClass('d-none');
        });

        $('#type-business').on('click', function(){
            $('.individual').addClass('d-none');
            $('.business').removeClass('d-none');
        });

        $('#btn_more_info').on('click', function(){
            
            if($('.more-info').hasClass('d-none')){
                $('.more-info').removeClass('d-none');
            }
            else{
                $('.more-info').addClass('d-none');
            }
         
        });

        btn_more_info
    });

    
</script>

@endsection
