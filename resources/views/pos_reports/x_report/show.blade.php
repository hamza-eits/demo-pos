<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>X Report</title>
    <style>
        * {
            font-size: 12px;
            font-family: 'Ubuntu', sans-serif;
            /* font-family: "Times New Roman", Times, serif; */
            /* font-family: "Lucida Console", "Courier New", monospace; */
            text-transform: capitalize;
        }

        @media print {
            .hidden-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div style="max-width: 400px">
        
       
        <table width="100%">
            <tr colspan="2">
                <th align="center">
                    <h4>X Report {{ date('d-m-Y', strtotime($startDate)) }}</h4>
                </th>

            </tr>
            @foreach ($data as $row)
                <tr>
                    <th align="left">{{ $row['name'] }}</th>
                    <th align="right">{{ $row['amount'] }}</th>
                </tr> 
            @endforeach
        </table>  
        
    </div>
</body>


</html> 