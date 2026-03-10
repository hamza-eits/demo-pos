<!-- Category list Modal -->
<div class="modal fade" id="category-modal">
    <div class="modal-dialog custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Categories</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        @foreach ($categories as $category)
                            <button class="btn btn-primary category-filter mx-2 my-2"
                                data-id="{{ $category->id }}">{{ $category->name }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Brand list Modal -->
<div class="modal fade" id="brand-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Brands</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        @foreach ($brands as $brand)
                            <button class="btn btn-primary brand-filter mx-2 my-2"
                                data-id="{{ $brand->id }}">{{ $brand->name }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Discount Modal -->
<div class="modal fade" id="invoice-discount-modal">
    <div class="modal-dialog custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Discount</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">

                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">

                        <form id="discount-form">
                            <div class="form-group">
                                <label for="discount-type">Discount Type</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="invoice_discount_type"
                                        value="fixed" checked>
                                    <label class="form-check-label">Fixed</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="invoice_discount_type"
                                    value="percentage">
                                    <label class="form-check-label">Percentage</label>
                                </div>
                            </div>

                            <div class="my-3">
                                <label class="form-label">Value</label>
                                <input type="number" name="invoice_discount_value" value="0"
                                    step="0.01" class="form-control">
                            </div>

                            <div class="modal-footer-btn text-end">
                                <button type="button" class="btn btn-cancel me-2 btn-dark"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="invoice-apply-discount" class="btn btn-submit  btn-primary">Apply
                                    Discount</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Shipping Modal -->
<div class="modal fade" id="shipping-modal">
    <div class="modal-dialog custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Shipping</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form id="shipping-form">
                            <!-- Rider Name -->
                            <div class="mb-3">
                                <label for="rider-name" class="form-label">Rider Name</label>
                                <input type="text" class="form-control" id="rider-name" name="rider_name"
                                    placeholder="Enter Rider's Name">
                            </div>

                            <!-- Shipping Address -->
                            <div class="mb-3">
                                <label for="shipping-address" class="form-label">Shipping Address</label>
                                <textarea class="form-control" id="shipping-address" name="shipping_address" rows="3" placeholder="Enter Shipping Address"></textarea>
                            </div>

                            <!-- Customer Phone Number -->
                            <div class="mb-3">
                                <label for="customer-phone" class="form-label">Customer Phone Number</label>
                                <input type="tel" class="form-control" id="customer-phone" name="customer_phone"
                                    placeholder="Enter Phone Number">
                            </div>

                            <!-- Shipping Cost -->
                            <div class="mb-3">
                                <label for="shipping-fee" class="form-label">Shipping Cost</label>
                                <input type="number" class="form-control" id="shipping-fee" name='shipping_fee'
                                    placeholder="Enter Shipping Cost">
                            </div>

                            <!-- Submit Button -->
                            <div class="modal-footer-btn text-end">
                                <button type="button" id="reset-shipping" class="btn btn-danger me-2">Reset</button>
                                <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="apply-shipping" class="btn btn-submit  btn-primary">Apply Shipping</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Table No Modal -->
<div class="modal fade" id="tableNo-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Select Table No</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <button class="btn btn-warning serving-table-no mx-2 my-2 "
                            data-table-no="{{ null }}">No Table</button>

                        @foreach ($servingTables as $table)
                            <button class="btn btn-primary serving-table-no mx-2 my-2"
                                data-table-no="{{ $table->table_number }}">{{ $table->table_number }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staff Modal -->
<div class="modal fade" id="staff-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Select Staff</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <button class="btn btn-warning serving-staff mx-2 my-2 "
                            data-staff-id="{{ null }}">No Staff</button>

                        @foreach ($staff as $person)
                            <button class="btn btn-primary serving-staff mx-2 my-2"
                                data-staff-id="{{ $person->id }}" data-staff-name="{{ $person->name }}">
                                {{ $person->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Product Variation Modal -->

<div class="modal fade" id="product-variation-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Product Variations</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <!-- First product variation -->
                                    <div id="product-variation-list" class="row col-md-12">
                                        {{-- render.blade -> product_variation.blade.php --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Addon Modal -->

<div class="modal fade" id="addon-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Addon</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>

                    <div class="modal-body modal-body-custom">
                        
                       <div class="table-responsive col-md-12">
                            <table id="modal-addon-table" class="table">
                                <thead>
                                    <tr>
                                        <td>Addon</td>
                                        <td>Price</td>
                                        <td>Qty</td>
                                        <td>Total</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            
                            
                            </table>
                       </div>
                       <button class="btn btn-success" onclick="appendRowAddonModalTable()">
                        <span class="fa fa-plus"></span> Add
                       </button>

                       <div class="modal-footer-btn text-end">
                        <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="add-addons-btn" class="btn btn-submit  btn-primary">Add Addon</button>
                    </div>
                    </div>
                    
                </div>
              
            </div>
        </div>
    </div>
</div>



<!-- Item Discount Modal -->
<div class="modal fade" id="item-discount-modal">
    <div class="modal-dialog custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Item Discount</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form id="item-discount-form">

                            <div class="mb-3">
                                <div class="form-group">
                                    <label>Discount Type</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input modal-item-discount-type" type="radio" name="modal_item_discount_type"  value="fixed">
                                        <label class="form-check-label">Fixed</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input modal-item-discount-type" type="radio" name="modal_item_discount_type" value="percentage">
                                        <label class="form-check-label">Percentage</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Discount Value</label>
                                <input type="number" class="form-control" id="modal-item-discount-value" 
                                    placeholder="Enter Discount Value">
                            </div>
                            <div class="mb-3 d-none">
                                <label class="form-label">Discount Amount</label>
                                <input type="number" class="form-control" id="modal-item-discount-amount" 
                                    placeholder="Enter Discount Amount" readonly>
                            </div>

                          

                            <!-- Submit Button -->
                            <div class="modal-footer-btn text-end">
                                <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="apply-item-discount-btn" class="btn btn-submit  btn-primary">Apply Discount</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Modal  -->

<div class="modal fade" id="add-customer-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Create Customer</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form id="customer-store" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="pos">
                            <input type="hidden" name="party_type" value="customer">
                            <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input name="business_name" id="business_name" type="text" class="form-control">
                                        </div>   
                                    </div>
                            
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mobile</label>
                                            <input name="mobile" id="mobile" type="number" class="form-control">
                                        </div>
                                    </div>
                                    
                                        
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Shipping Address</label>
                                        <textarea name="shipping_address" id="customer-shipping-address" class="form-control"></textarea>
                                    </div>
                                </div>
                                </div>
                                
                                    
                                    
                                

                            <div class="modal-footer-btn text-end">
                                <button type="button" class="btn btn-cancel me-2 btn-dark"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="submit-customer-store" class="btn btn-submit btn-primary">
                                    Create
                                    </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Completed Order Modal  -->

<div class="modal fade" id="completed-orders-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Completed Orders</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="completed-order-list" class="modal-body custom-modal-body">
                       
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Draft Order Modal  -->

<div class="modal fade" id="draft-orders-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Draft Orders</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="draft-order-list" class="modal-body custom-modal-body">
                       
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Multi pay Modal  -->

<div class="modal fade" id="multiple-payments-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header d-flex justify-content-between align-items-center">
                        <div class="page-title">
                            <h4>Multiple Payments</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>
                    <div class="modal-body custom-modal-body">
                        <form id="multiple-payments-form" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="table-responsive">
                                        <table class="table" id="multiple-payments-table">
                                            <thead>
                                                <tr>
                                                    <th width="60%">Accounts</th>
                                                    <th width="30%">Amount</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select class="form-control form-select" name="payment_coa_id[]">
                                                            @foreach ($chart_of_accounts as $account)
                                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" id="multiple-payments-drfault-payment-amount" name="payment_amount[]" class="multiple-payments-row-amount form-control text-end ">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                                            <span class="fa fa-trash"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                    
                                                {{-- dynamic row will be added here --}}
                                    
                                    
                                            </tbody>
                                            
                                        </table>
                                        <textarea name="reference_no" id="reference_no" class="form-control mb-2" placeholder="Payment Details (if any)"></textarea>
                                        <button type="button" onclick="addRowtoMutipayTable()" class="btn btn-primary btn-sm">
                                            <span class="fa fa-plus"></span> Add Row
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <table class="table">
                                        <tr>
                                          <td>Total Payment</td>
                                          <td><input class="form-control" id="multiple-payments-total-amount" type="number"  value="0" readonly style="border:none"></td>
                                        </tr>
                                        <tr>
                                          <td>Paid Amount</td>
                                          <td><input class="form-control" id="multiple-payments-paid-amount" type="number"  value="0" readonly style="border:none"></td>
                                        </tr>
                                        <tr>
                                          <td>Change Return</td>
                                          <td><input class="form-control" id="multiple-payments-change-return-amount" type="number"  value="0" readonly style="border:none"></td>
        
                                        </tr>
                                    </table>
                                    
                            
                                </div>
                            </div>
                            <div class="modal-footer-btn text-end mt-3">
                                <button type="button" class="btn btn-cancel me-2 btn-dark" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="store-order-as-completed-in-multiple-payments" class="btn btn-primary">Create & Print</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- print invoice modal --}}

<div class="modal fade" id="print-invoice-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Print Invocie</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="print-inovice-view" class="modal-body custom-modal-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Cash Register Summary Modal  -->

<div class="modal fade" id="cash-register-summary-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Cash Register Summary</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="cash-register-summary" class="modal-body custom-modal-body">
                        {{-- render -> cash_register_summary.blade --}}
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Expense Modal  -->

<div class="modal fade" id="add-expense-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Create Expense</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form id="add-expense-form" type="POST" enctype="multipart/form-data">
                            @csrf
                             <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label text-danger">Supplier</label>
                                        <select name="expense_supplier_id" id="" class="select2 form-control" style="width: 100%">                                                
                                            <option value="">Choose...</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{$supplier->id}}">{{ $supplier->business_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>                                        
                                </div>
    
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label text-danger">Paid Through</label>
                                        <select name="expense_paid_by_COA" class="select2 form-control" style="width: 100%">                                                
                                            <option value="">Choose...</option>
                                            @foreach ($expensePaidTruAccounts as $account)
                                                <option value="{{$account->id}}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>                                        
                                </div>
                                
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Date</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><span class="bx bx-calendar" ></span> </div>
                                            <input type="date" name="expense_date" id="date" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                                        </div>
                                        
                                    </div> 
                                </div>

                                    <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Reference No</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><span class="bx bx-receipt"></span> </div>
                                            <input type="text" name="expense_reference_no"  class="form-control">
                                        </div> 
                                    </div> 
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Expense Type</label>
                                        <div class="input-group">
                                            <input type="text" name="expense_type"  class="form-control">
                                        </div>
                                    </div> 
                                </div>

                                    <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Spend By</label>
                                        <select name="expense_spend_by" class="select2 form-control" style="width: 100%">                                                
                                            <option value="">Choose...</option>
                                            @foreach ($users as $user)
                                                <option value="{{$user->id}}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>                                        
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label text-danger">Expense Account</label>
                                        <select name="expense_paid_for_COA" class="select2 form-control" style="width: 100%">                                                
                                            <option value="">Choose...</option>
                                            @foreach ($expenseCoas as $account)
                                                <option value="{{$account->id}}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>                                        
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label text-danger">Amount</label>
                                        <div class="input-group">
                                            <input type="text" name="expense_amount"  class="form-control">
                                        </div>
                                    </div> 
                                </div>
                                    <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Applicable Tax</label>
                                        <select name="expense_tax_value" class="form-control" style="width: 100%">                                                
                                            <option selected value="0">No Tax</option>
                                            <option value="5">5% VAT(incl)</option>
                                            
                                        </select>
                                    </div>                                        
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label text-danger">Narration</label>
                                        <div class="input-group">
                                            <textarea name="expense_narration" class="form-control"></textarea>
                                        </div>
                                    </div> 
                                </div>        
                                            
                            </div> 
                                
                                    
                                    
                                

                            <div class="modal-footer-btn text-end">
                                <button type="button" class="btn btn-cancel me-2 btn-dark"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="submit-expense-store-form-btn" class="btn btn-submit btn-primary">
                                    Create
                                    </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


