<p>Please review the transaction and payments.</p>
<div class="row">
    <form action="{{ route('cash-register.close') }}" method="POST">
        @csrf

        <div class="col-md-12">
            <table class="table table-hover">
                <tbody>
                    @foreach ($data as $value)
                        <tr>
                            <td>{!! $value['title'] !!}</td>
                            <td class="text-end">{{ $value['amount'] }}</td>
                            <input type="hidden" name="{{ $value['name'] }}" value="{{ $value['amount'] }}">
                        </tr>  
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-12 mb-2">
            <label class="form-label">Notes / Remarks</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <div class="modal-footer-btn text-end">
            
            <button type="submit" class="btn btn-submit btn-danger">
                Close Cash Register
            </button>
        </div>
    </form>
</div>

