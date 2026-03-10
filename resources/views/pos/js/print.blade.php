
{{-- print invoice and load pirnt popup --}}
<script>
    // print button 

    $(document).on('click', '.print-invoice-btn', function(e) {
        // Define the URL for the print view
        var printUrl = $(this).data('url');
        loadPrintPopup(printUrl);
    });
    $(document).on('click', '.print-kot-btn', function(e) {
        // Define the URL for the print view
        var printUrl = $(this).data('url');
        loadPrintPopup(printUrl);
    });

    function loadPrintPopup(printUrl) {
        // Open the print view in a new window
        var printWindow = window.open(printUrl, '_blank', 'height=600,width=800');
        // Wait for the print view to load
        printWindow.onload = function() {
            // Trigger the print dialog
            printWindow.print();
            // Close the print window after the print dialog is opened
            printWindow.addEventListener('afterprint', function() {
                printWindow.close();
            });
            // Close the print window if the user cancels the print dialog
            printWindow.addEventListener('beforeunload', function() {
                printWindow.close();
            });
        };
    }
</script>