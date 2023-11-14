        $(document).ready(function() {
            // Hide all tables initially
            $(".weekly-transactions, .monthly-transactions, .yearly-transactions").hide();

            // Show the appropriate table based on the selected option
            $("#transactions").change(function() {
                var selectedOption = $(this).val();

                // Hide all tables
                $(".daily-transactions, .weekly-transactions, .monthly-transactions, .yearly-transactions")
                    .hide();

                // Show the selected table
                $("." + selectedOption).show();
            });
        });
