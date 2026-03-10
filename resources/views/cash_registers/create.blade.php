<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Open Cash Register</title>
  </head>
  <body>
    <div class="container p-5">
     
        <div class="col-md-6">
            <form action="{{ route('cash-register.store') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label >Enter Opening Cash</label>
                  <input type="number" step="0.01" name="opening_cash" class="form-control" placeholder="Enter Opening Cash" required>
                </div>
               
                <button type="submit" class="btn btn-primary d-flex">Submit</button>
            </form>
        </div>

        @if($lastClosing)
          <div class="col-md-6 mt-5">
              <table class="table table-bordered">
                <tr>
                  <td colspan="2" class="text-center font-weight-bold">Last Cash Register Closing</td>
                </tr>
                <tr>
                  <td>last User</td>
                  <td>{{ $lastClosing->user->name ?? '' }}</td>
                </tr>
                <tr>
                  <td>Closing Cash</td>
                  <td>{{ $lastClosing->closing_cash ?? '' }}</td>
                </tr>
                @if($lastClosing->status == 1)
                  <tr>
                    <td colspan="2" class="text-danger text-center font-weight-bold"> Cash Register is not Closed by {{ $lastClosing->user->name }}</td>
                  </tr>
                @endif
              </table>
          </div>
        @endif  
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>