<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Label</title>
    <style>
        @page {
            size: 30mm 20mm; /* Label dimensions */
            margin: 1px;        /* No margin for edge-to-edge printing */
            font-family: "Roboto", "Segoe UI", sans-serif;
            border: 1px solid #000; /* Optional border for visibility */
        }
        
        body {
            margin: 0;
            padding: 0;
            /* Optional border for visibility */
            /* border: 1px solid red;  */

        }
        
        .label {
            padding: 5px; /* Space between barcode and text */
        }
        .label .barcode {
            text-align: center;
            margin-bottom: 2px; /* Space between barcodes */
        }
        .label .text{
            text-align: center;
            font-size: 7px; /* Adjust font size as needed */
        }
        .label .price{
            text-align: center;
            font-size: 9px; /* Adjust font size as needed */
        }
        
        </style>
</head>
<body>
    <div class="label">
        @foreach ( $data as $row)
        <div class="barcode">
            <img src="data:image/png;base64,{{ base64_encode($row['barcode']) }}" alt="Barcode">
        </div>
        <div class="text"> {{ $row['label'] }}</div>
        <div class="price"> <b>{{ $row['price'] }}</b></div>
        @endforeach
    </div>
</body>
</html>
