@extends('template.tmp')
@section('title', 'Party')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                
                <form>
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h3 class="mb-sm-0 font-size-18">{{ $party->business_name }}</h3>
    
                                <div class="page-title-right d-flex">
    
                                  
                                </div>
    
    
    
                            </div>
                        </div>
                    </div>
                   <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <div class="row">
                                        <h4 class="card-title mb-4">Contact Information</h4>

                                    

                                       <div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Party Type</label>
            <p>{{ $party->party_type }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Type</label>
            <p>{{ $party->type }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Business Name</label>
            <p>{{ $party->business_name }}</p>
        </div>   
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Contact Person</label>
            <p>{{ $party->contact_person }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Prefix</label>
            <p>{{ $party->prefix }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <p>{{ $party->first_name }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Middle Name</label>
            <p>{{ $party->middle_name }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <p>{{ $party->last_name }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Mobile</label>
            <p>{{ $party->mobile }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Alternate Number</label>
            <p>{{ $party->alternate_number }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Landline</label>
            <p>{{ $party->landline }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <p>{{ $party->email }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Tax Number</label>
            <p>{{ $party->tax_number }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Balance</label>
            <p>{{ $party->balance }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Pay Term Type</label>
            <p>{{ $party->pay_term_type }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Credit Limit</label>
            <p>{{ $party->credit_limit }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Address Line 1</label>
            <p>{{ $party->address_line_1 }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Address Line 2</label>
            <p>{{ $party->address_line_2 }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">City</label>
            <p>{{ $party->city }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">State</label>
            <p>{{ $party->state }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Country</label>
            <p>{{ $party->country }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Zip Code</label>
            <p>{{ $party->zip_code }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Shipping Address</label>
            <p>{{ $party->shipping_address }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Is Active</label>
            <p>{{ $party->is_active ? 'Yes' : 'No' }}</p>
        </div>
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
   



    
    
   




@endsection
