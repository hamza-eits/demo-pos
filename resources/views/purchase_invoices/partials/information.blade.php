<h4 class="card-title mb-4">Purchase Invoice</h4>

<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="select2 form-control">
                <option value="">Choose...</option>
                @foreach ($suppliers as $supplier)
                    <option 
                    @selected($purchaseInvoice ? $purchaseInvoice->party_id == $supplier->id : false) 
                    value="{{ $supplier->id }}"
                    data-credit-limit = "{{ $supplier->credit_limit }}"    
                    >
                        {{ $supplier->id . '-' . $supplier->business_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Invoice No (Ref.)</label>
            <div class="input-group">
                <div class="input-group-text"><span class="bx bxs-receipt"></span> </div>
                <input type="text" name="reference_no" id="reference_no" class="form-control"
                    autocomplete="off" value="{{ $purchaseInvoice ? $purchaseInvoice->reference_no : '' }}">
            </div>
        </div>
    </div>


    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Date</label>
            <div class="input-group">
                <div class="input-group-text"><span class="bx bx-calendar"></span> </div>
                <input type="date" name="date" id="date" class="form-control"
                    value="{{ $purchaseInvoice ? $purchaseInvoice->date : date('Y-m-d') }}">
            </div>

        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <div class="input-group">
                <div class="input-group-text"><span class="bx bx-calendar"></span> </div>
                <input type="date" name="due_date" id="due_date" class="form-control"
                    value="{{ $purchaseInvoice ? $purchaseInvoice->due_date : date('Y-m-d') }}">

            </div>
            <span id="creditLimitNotes"></span>

        </div>
    </div>
     <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Status</label>
            <div class="input-group">
               <input name="status" class="form-control" value="{{ $purchaseInvoice ? $purchaseInvoice->status : '' }}">
            </div>

        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">Payment Mode</label>
            <div class="input-group">
               <input name="payment_mode" class="form-control" value="{{ $purchaseInvoice ? $purchaseInvoice->payment_mode : '' }}">
            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <div class="input-group">
               <input name="subject" class="form-control" value="{{ $purchaseInvoice ? $purchaseInvoice->subject : '' }}">
            </div>

        </div>
    </div>
   
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Description</label>
            <div class="input-group">
               <textarea name="description" class="form-control">{{ $purchaseInvoice ? $purchaseInvoice->description : '' }}</textarea>
            </div>

        </div>
    </div>
