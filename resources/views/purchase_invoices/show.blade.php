@extends('template.tmp')
@section('title', 'Purchase Invoice')

@section('content')
<style>
    /* Chrome, Safari, Edge, Opera : remove spin input type number*/
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      

    }

    .table>:not(caption)>*>* {
    padding: 0.15rem .15rem !important;
    }

    table tbody tr input.form-control{
    
        border-radius: 0rem !important;
        font-size: 11px;
    
    }

    #summary-table input.form-control{
        /* border: 0; */
        border-radius: 0.25rem !important;
    }

    .form-control:disabled, .form-control[readonly] {
    background-color: #eff2f780 !important;
    opacity: 1;
}

</style>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

              
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="card-title mb-4">Purchase Invoice</h4> --}}
                    <h4 class="card-title mb-4">Purchase Invoice</h4>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Supplier</label>
                                    <p>{{ $purchaseInvoice->party->business_name }}</p>   
                            </div>                                        
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Reference No</label>
                                <p>{{ $purchaseInvoice->reference_no }}</p>
                            </div> 
                        </div>
                        
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <p>{{ date('d-m-Y', strtotime($purchaseInvoice->date)) }}</p>
                                
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Due Date</label>
                                <p>{{ date('d-m-Y', strtotime($purchaseInvoice->due_date)) }}</p>

                                
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <p>{{ $purchaseInvoice->status }}</p>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Payment Mode</label>
                                <p>{{ $purchaseInvoice->payment_mode }}</p>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <p>{{ $purchaseInvoice->subject }}</p>
                            </div> 
                        </div>
                         <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <p>{{ $purchaseInvoice->description }}</p>
                            </div> 
                        </div>
                            
                    </div>
                </div> 
            </div>


            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Purchase Detail</h4>

                    <table id="table" class="table table-border" style="border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th width="20%" class="text-start">Item</th>
                                <th width="5%" class="text-start">Unit</th>
                                <th width="10%" class="text-end">{{ __('file.Quantity') }}</th>
                                <th width="10%" class="text-end">Cost Price</th>
                                <th width="10%" class="text-end">Purchase Category</th>
                                <th width="10%" class="text-end">Subtotal</th>
                                <th width="13%" class="text-end">{{ __('file.Tax') }} %</th>
                                <th width="13%" class="text-end">{{ __('file.Tax') }} Amount</th>
                                <th width="13%" class="text-end">Total</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseInvoice->details as $detail)
                            <tr>
                                <td>
                                    {{ $detail->productVariation->name ?? 'N/A' }}
                                </td>
                                <td >
                                    {{ $detail->unit->base_unit ?? 'N/A' }}
                                </td>
                                <td class="text-end">
                                    {{ $detail->quantity }}
                                </td>
                                <td class="text-end">
                                    {{ $detail->unit_price }}
                                </td>
                                
                                <td class="text-end">
                                    {{ $detail->purchase_category }}
                                    
                                </td>
                                <td class="text-end">
                                    {{ $detail->subtotal }}
                                </td>
                                <td class="text-end">
                                    {{ $detail->tax_value }}
                                </td>
                                <td class="text-end">
                                    {{ $detail->tax_amount }}
                                </td>
                                <td class="text-end">
                                    {{ $detail->subtotal_after_tax }}
                                </td>
                                <td> 
                                    <a target="_blank" href="{{ route('purchase-invoice.barcode-pdf', [$detail->invoice_master_id,1,$detail->product_variation_id ]) }}" class="dropdown-item">
                                        <i class="bx bx-download font-size-16 text-danger me-1"></i>
                                    </a>
                                </td>
                                <td> 
                                    <a target="_blank" href="{{ route('purchase-invoice.barcode-pdf', [$detail->invoice_master_id,0,$detail->product_variation_id ]) }}" class="dropdown-item">
                                        <i class="bx bxs-file-pdf font-size-16 text-danger me-1"></i>  
                                    </a>
                                </td>
                            
                            </tr> 
                            @endforeach
                        </tbody>
                        <tfoot>
                            {{-- <tr>
                                <td class="text-start" class="fw-bold">Total</td>
                                <td class="text-end">
                                    {{ $purchaseInvoice->total_quantity }}
                                </td>
                                <td class="text-end"></td>
                                <td class="text-end"></td>
                                <td class="text-end">
                                    {{ $purchaseInvoice->total_gross_amount }}
                                </td>
                                
                                <td class="text-end"></td>
                                <td class="text-end">
                                    {{ $purchaseInvoice->total_tax_amount }}
                                </td>
                                <td class="text-end">
                                    {{ $purchaseInvoice->grand_total }}
                                </td>
                            </tr> --}}
                        </tfoot>
                        
                    </table>
                    
                </div>
            </div>
                
        </div>    
    </div>    
</div>    




@endsection



