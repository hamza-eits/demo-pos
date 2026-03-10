<div class="col-12">
    <div class="row">
        <div class="col-4 mb-4" >
            <select id="biller_id" class="form-control form-select" disabled>
                <option selected value="{{ $biller->id }}">{{ $biller->name }} </option> 
            </select>                                            
        </div>

        <div class="col-4 mb-4">
            <input type="date" name="date" id="date" class="form-control" value="{{ $order ? $order->date : date('Y-m-d') }}" readonly />
        </div>
        <div class="col-4 mb-4 d-flex align-items-center">
            <select name="customer_id" id="customer_id" class="form-control select2 me-2">
                <option value="">Choose...</option>
                @foreach ($customers as $customer)
                    <option 
                    @selected($order ? $customer->id == $order->party_id: false)
                    value="{{ $customer->id }}"
                    >{{ $customer->business_name }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary" type="button"
                data-bs-toggle="modal" data-bs-target="#add-customer-modal">
                <span class="fa fa-edit"></span>
            </button>
        </div>
        <div class="col-4 mb-4">
            <select name="serving_type" id="serving_type" class="form-control select2">
                <option value="">Choose...</option> 
                <option @selected($order ? $order->serving_type == "Dine In"  : false ) value="Dine In">Dine In </option> 
                <option @selected($order ? $order->serving_type == "Takeaway" : false )  value="Takeaway">Takeaway </option> 
                <option @selected($order ? $order->serving_type == "Delivery" : false ) value="Delivery">Delivery </option> 
            </select>
        </div>
      
        <div class="col-4 mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="table_no" id="table-no" value="{{ $order ? $order->table_no : '' }}" placeholder="Choose Table Number" readonly>
                <button class="btn btn-primary" type="button"
                    data-bs-toggle="modal" data-bs-target="#tableNo-modal">
                    <span class="fa fa-edit"></span>
                </button>
              </div>
        </div>
        <div class="col-4 mb-4">
            <div class="input-group">
                <input type="text" class="form-control " id="staff-name" value="{{ $order ? ($order->waiter->name ?? null ) : null }}"  placeholder="Choose Staff" readonly>
                <input type="hidden" class="form-control" name="waiter_id" id="staff-id" value="{{ $order ? ($order->waiter->id ?? null) : null }}" readonly>
                <button class="btn btn-primary" type="button" 
                    data-bs-toggle="modal" data-bs-target="#staff-modal">
                    <span class="fa fa-edit"></span>
                </button>
              </div>
        </div>
    </div>
</div>