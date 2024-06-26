{{-- Line Chart --}}
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function calculateMovingAverage(data, currentIndex, windowSize) {
        // Calculate the moving average based on a window of data points
        var total = 0;
        for (var i = currentIndex; i > currentIndex - windowSize; i--) {
            if (i >= 0) {
                total += data.getValue(i, 1); // Assuming the current earnings are in the second column
            }
        }
        return total / windowSize;
    }

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', 'Current Earnings');
        data.addColumn('number', 'Forecast/Future Earnings');

        // Dummy data for testing
        data.addRow(['September', 100, 120]);
        data.addRow(['October', 150, 130]);
        data.addRow(['November', 200, 110]);
        data.addRow(['December', null, 90]);

        var options = {
            title: 'Earnings Forecasting',
            titleTextStyle: {
                color: '#414141',
                fontSize: 28,
                bold: true,
                fontFamily: 'Arial, Helvetica, sans-serif',
            },
            curveType: 'function',
            legend: {
                position: 'bottom'
            },
            series: {
                0: {
                    pointShape: 'circle',
                    pointSize: 5,
                    lineWidth: 2
                },
                1: {
                    pointShape: 'circle',
                    pointSize: 5,
                    lineWidth: 2
                },
            },
        };

        try {
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        } catch (error) {
            console.error('Error drawing the chart:', error);
        }
    }
</script>



// {{-- Line Chart --}}
{{-- // <script type="text/javascript">
    //     google.charts.load('current', {
    //         'packages': ['corechart']
    //     });
    //     google.charts.setOnLoadCallback(drawChart);

    //     function calculateMovingAverage(data, currentIndex, windowSize) {
    //         // Calculate the moving average based on a window of data points
    //         var total = 0;
    //         for (var i = currentIndex; i > currentIndex - windowSize; i--) {
    //             if (i >= 0) {
    //                 total += data.getValue(i, 1); // Assuming the current earnings are in the second column
    //             }
    //         }
    //         return total / windowSize;
    //     }

    //     function drawChart() {
    //     var data = new google.visualization.DataTable();
    //     data.addColumn('string', 'Month');
    //     data.addColumn('number', 'Current Earnings');
    //     data.addColumn('number', 'Forecast/Future Earnings');

    //     var monthsWithData = [];
    //     var latestMonthWithData = null;

    //     @for ($i = 1; $i <= 12; $i++)
    //         var month = new Date('{{ date('Y-m', mktime(0, 0, 0, $i, 1)) }}');
    //         var currentMonthEarnings = <?php echo App\Models\Transaction::whereMonth('created_at', $i);
    //             ->whereYear('created_at', today()->year)
    //             ->sum('profit') ?? 0;
    ?>;
    //         var forecastMonthEarnings = <?php echo App\Models\Transaction::whereMonth('created_at', $i);
    //             ->whereYear('created_at', today()->year + 1)
    //             ->sum('profit') ?? 0;
    ?>;

    //         if (currentMonthEarnings > 0 || forecastMonthEarnings > 0) {
    //             data.addRow([month.toLocaleString('default', { month: 'long' }), currentMonthEarnings, forecastMonthEarnings]);
    //             monthsWithData.push(month.toLocaleString('default', { month: 'long' }));
    //             latestMonthWithData = month;
    //         }
    //     @endfor

    //     // Include the next month after the latest month with data
    //     if (latestMonthWithData !== null) {
    //         var nextMonth = new Date(latestMonthWithData);
    //         nextMonth.setMonth(nextMonth.getMonth() + 1);
    //         var nextMonthString = nextMonth.toLocaleString('default', { month: 'long' });

    //         // Check if the next month is not already in the array before adding it
    //         if (!monthsWithData.includes(nextMonthString)) {
    //             monthsWithData.push(nextMonthString);

    //             // Add the data for the next month
    //         data.addRow([nextMonthString, null, null]); // <- Add this line
    //         }
    //     } else {
    //         // If there is no latest month with data, just add the next month
    //         var currentMonth = new Date();
    //         var nextMonth = new Date(currentMonth);
    //         nextMonth.setMonth(currentMonth.getMonth() + 1);
    //         var nextMonthString = nextMonth.toLocaleString('default', { month: 'long' });

    //         // Check if the next month is not already in the array before adding it
    //         if (!monthsWithData.includes(nextMonthString)) {
    //             monthsWithData.push(nextMonthString);
    //         }
    //     }

    //     console.log(monthsWithData); // Log monthsWithData to the console
    //             // Add the moving average for the Future Earnings line
    //             for (var i = 0; i < data.getNumberOfRows(); i++) {
    //                 var movingAverage = calculateMovingAverage(data, i, 2); // You can adjust the window size
    //                 data.setValue(i, 2, movingAverage);
    //             }

    //             var options = {
    //                 title: 'Earnings Forecasting',
    //                 titleTextStyle: {
    //                     color: '#414141',
    //                     fontSize: 28,
    //                     bold: true,
    //                     fontFamily: 'Arial, Helvetica, sans-serif',
    //                 },
    //                 curveType: 'function',
    //                 legend: {
    //                     position: 'bottom'
    //                 },
    //                 series: {
    //                     0: {
    //                         pointShape: 'circle',
    //                         pointSize: 5,
    //                         lineWidth: 2
    //                     },
    //                     1: {
    //                         pointShape: 'circle',
    //                         pointSize: 5,
    //                         lineWidth: 2
    //                     },
    //                 },
    //             };

    //             try {
    //                 var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    //                 chart.draw(data, options);
    //             } catch (error) {
    //                 console.error('Error drawing the chart:', error);
    //             }

    //             var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    //             chart.draw(data, options);
    //     }
    // </script> --}}


{{-- Line Chart --}}
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function calculateMovingAverage(data, currentIndex, windowSize) {
        // Calculate the moving average based on a window of data points
        var total = 0;
        for (var i = currentIndex; i > currentIndex - windowSize; i--) {
            if (i >= 0) {
                total += data.getValue(i, 1); // Assuming the current earnings are in the second column
            }
        }
        return total / windowSize;
    }

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', 'Current Earnings');
        data.addColumn('number', 'Forecast/Future Earnings');

        var monthsWithData = [];
        var latestMonthWithData = null;

        @for ($i = 1; $i <= 12; $i++)
            var month = new Date('{{ date('Y-m', mktime(0, 0, 0, $i, 1)) }}');
            var currentMonthEarnings = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                ->whereYear('created_at', today()->year)
                ->sum('profit') ?? 0; ?>;
            var forecastMonthEarnings = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                ->whereYear('created_at', today()->year + 1)
                ->sum('profit') ?? 0; ?>;

            if (currentMonthEarnings > 0 || forecastMonthEarnings > 0) {
                data.addRow([month.toLocaleString('default', {
                    month: 'long'
                }), currentMonthEarnings, forecastMonthEarnings]);
                monthsWithData.push(month.toLocaleString('default', {
                    month: 'long'
                }));
                latestMonthWithData = month;
            }
        @endfor

        // Include the next month after the latest month with data
        if (latestMonthWithData !== null) {
            var nextMonth = new Date(latestMonthWithData);
            nextMonth.setMonth(nextMonth.getMonth() + 1);
            var nextMonthString = nextMonth.toLocaleString('default', {
                month: 'long'
            });

            // Check if the next month is not already in the array before adding it
            if (!monthsWithData.includes(nextMonthString)) {
                monthsWithData.push(nextMonthString);

                // Make AJAX request to fetch forecast for the next month
                $.ajax({
                    url: '/admin/get-forecast-earnings', // Update with your actual endpoint
                    method: 'GET',
                    data: {
                        month: nextMonthString,
                    },
                    success: function(response) {
                        console.log('AJAX Response:', response);

                        var nextMonthForecast = response.data;
                        // Add the data for the next month
                        data.addRow([nextMonthString, null, nextMonthForecast]);
                    },
                    error: function(error) {
                        console.error('Error fetching forecast for the next month:', error);
                    }
                });
            }
        } else {
            // If there is no latest month with data, just add the next month
            var currentMonth = new Date();
            var nextMonth = new Date(currentMonth);
            nextMonth.setMonth(currentMonth.getMonth() + 1);
            var nextMonthString = nextMonth.toLocaleString('default', {
                month: 'long'
            });

            // Make AJAX request to fetch forecast for the next month
            $.ajax({
                url: '/admin/get-forecast-earnings', // Update with your actual endpoint
                method: 'GET',
                data: {
                    month: nextMonthString,
                },
                success: function(response) {
                    // Assuming the response structure is { data: { forecast: forecastValue } }
                    var nextMonthForecast = response.data.forecast;

                    // Add the data for the next month
                    data.addRow([nextMonthString, null, nextMonthForecast]);

                    // Redraw the chart
                    chart.draw(data, options);
                },
                error: function(error) {
                    console.error('Error fetching forecast for the next month:', error);
                }
            });
        }
        var options = {
            title: 'Earnings Forecasting',
            titleTextStyle: {
                color: '#414141',
                fontSize: 28,
                bold: true,
                fontFamily: 'Arial, Helvetica, sans-serif',
            },
            curveType: 'function',
            legend: {
                position: 'bottom'
            },
            series: {
                0: {
                    pointShape: 'circle',
                    pointSize: 5,
                    lineWidth: 2
                },
                1: {
                    pointShape: 'circle',
                    pointSize: 5,
                    lineWidth: 2
                },
            },
        };

        try {
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        } catch (error) {
            console.error('Error drawing the chart:', error);
        }

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
    }
</script>


// Customer Add
// {{-- Add Modal --}}
<div id="newModal" class="modal">
    <div class="modal-content">
        <span class="close ">&times;</span>

        <form class="modal-form" action="{{ route('admin.customerStore') }}" method="POST">
            @csrf

            <center>
                <h2 style="margin: 0%; color:#333;">Add Customer</h2>
            </center>

            <label class="modal-top" for="">Company Name:</label>
            <input required autofocus type="text" name="name" id="autofocus" />

            <label for="">Contact Name:</label>
            <input required type="text" name="contact_name" id="" />

            <label for="">Contact Number:</label>
            <input required type="text" pattern="{5,15}" title="Enter a valid contact number" name="contact_num"
                id="" value="">
            <label for="">Address:</label>
            <input required type="text" name="address" id="" />

            <input class="add" type="submit" value="Add" />
        </form>

    </div>
</div>

// Controller
$password = $request->input('cjvc_password');
$password_confirm = $request->input('cjvc_password_confirm');

if ($password == $password_confirm){
$users->cjvc_password = Crypt::encryptString($request->input('cjvc_password_confirm'));
// $users->cjvc_password = Crypt::encryptString($request->cjvc_password);
$users->cjvc_accountType = $request->input('cjvc_accountType');
$users->save();

return redirect()->route('Login.index')->withSuccess('Account created successfully!');
}else{
return back()->with('message' , 'Password does not match.');
}








{{-- // Line Chart --}}
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function calculateWeightedAverage(data, currentIndex, alpha) {
        var weightedTotal = 0;
        var weightSum = 0;

        for (var i = currentIndex; i >= 0; i--) {
            var weight = Math.pow(alpha, currentIndex - i);
            weightedTotal += weight * data.getValue(i, 1);
            weightSum += weight;
        }

        return weightedTotal / weightSum;
    }

    function calculateWeightedAverageDynamic(data, currentIndex, alpha) {
        var weightedTotal = 0;
        var weightSum = 0;

        for (var i = currentIndex; i >= 0; i--) {
            var weight = Math.pow(alpha, currentIndex - i);
            weightedTotal += weight * data.getValue(i, 1);
            weightSum += weight;
        }

        return weightedTotal / weightSum;
    }

    function calculateDynamicAlpha(data, currentIndex, baseAlpha, sensitivity) {
        // If there's not enough data points to calculate the trend, use the base alpha
        if (currentIndex < 2) {
            return baseAlpha;
        }

        var recentTrend = data.getValue(currentIndex, 1) - data.getValue(currentIndex - 1, 1);
        var alpha = baseAlpha * (1 + sensitivity * recentTrend);
        return Math.max(0, Math.min(1, alpha)); // Ensure alpha is between 0 and 1
    }

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Month');
        data.addColumn('number', 'Current Sales');
        data.addColumn('number', 'Forecast/Future Sales');

        var monthsWithData = [];
        var latestMonthWithData = null;

        @for ($i = 1; $i <= 12; $i++)
            var month = new Date('{{ date('Y-m', mktime(0, 0, 0, $i, 1)) }}');
            var currentMonthSales = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                ->whereYear('created_at', today()->year)
                ->sum(DB::raw('qty * purchase_price')) ?? 0; ?>;

            if (currentMonthSales > 0) {
                data.addRow([month.toLocaleString('default', {
                    month: 'long'
                }), currentMonthSales, null]);
                monthsWithData.push(month.toLocaleString('default', {
                    month: 'long'
                }));
                latestMonthWithData = month;
            }
        @endfor

        // Include the next month after the latest month with data
        if (latestMonthWithData !== null) {
            var nextMonth = new Date(latestMonthWithData);
            nextMonth.setMonth(nextMonth.getMonth() + 1);
            var nextMonthString = nextMonth.toLocaleString('default', {
                month: 'long'
            });

            // Check if the next month is not already in the array before adding it
            if (!monthsWithData.includes(nextMonthString)) {
                monthsWithData.push(nextMonthString);

                // Set the base alpha parameter for weighted average
                var baseAlpha = 0.2; // You can adjust this value

                // Calculate dynamic alpha based on recent trend
                var dynamicAlpha = calculateDynamicAlpha(data, data.getNumberOfRows() - 1, baseAlpha, 0.1);

                // Calculate forecasted sales using weighted average with dynamic alpha
                var forecastedSales = calculateWeightedAverageDynamic(data, data.getNumberOfRows() - 1, dynamicAlpha);

                // Add the data for the next month
                data.addRow([nextMonthString, null, forecastedSales]);
            }
        }

        // Add the weighted average for the Future Sales line
        for (var i = 0; i < data.getNumberOfRows(); i++) {
            var weightedAverage = calculateWeightedAverage(data, i, baseAlpha);
            data.setValue(i, 2, weightedAverage);
        }

        var options = {
            title: 'Sales Forecasting',
            titleTextStyle: {
                color: '#414141',
                fontSize: 28,
                bold: true,
                fontFamily: 'Arial, Helvetica, sans-serif',
            },
            curveType: 'function',
            legend: {
                position: 'bottom'
            },
            series: {
                0: {
                    pointShape: 'circle',
                    pointSize: 5,
                    lineWidth: 2
                },
                1: {
                    pointShape: 'circle',
                    pointSize: 5,
                    lineWidth: 2
                },
            },
        };

        try {
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        } catch (error) {
            console.error('Error drawing the chart:', error);
        }
    }
</script>

// Function to calculate forecasted sales based on past transactions
// private function calculateForecastedSales($transactions)
// {
// // Implement your sales forecasting logic here
// // You can use the existing JavaScript logic or a similar calculation in PHP
// // Return the forecasted sales value

// // For demonstration purposes, I'm assuming a simple average calculation
// $totalSales = $transactions->sum('profit');
// $averageSales = $transactions->count() > 0 ? $totalSales / $transactions->count() : 0;

// return $averageSales;
// }


// private function calculateForecastedSales($transactions)
// {
// // Check if there are enough transactions to calculate forecast
// if ($transactions->count() < 2) { // return 0; // Not enough data for accurate forecasting // } // $alpha=0.2; // Set
    the base alpha parameter for weighted average // $sensitivity=0.1; // Set the sensitivity for dynamic alpha //
    $weightedTotal=0; // $weightSum=0; // // Iterate through transactions to calculate weighted average with dynamic
    alpha // foreach ($transactions as $index=> $transaction) {
    // // Assuming profit is qty * purchase_price, replace it with your actual field
    // $totalSales = $transaction->qty * $transaction->selling_price;

    // $weight = pow($alpha, $transactions->count() - $index);
    // $weightedTotal += $weight * $totalSales;
    // $weightSum += $weight;
    // }

    // // Calculate dynamic alpha based on recent trend
    // $recentTrend = ($transactions->last()->qty * $transactions->last()->selling_price) -
    // ($transactions->reverse()->get(1)->qty * $transactions->reverse()->get(1)->selling_price);
    // $alpha = $alpha * (1 + $sensitivity * $recentTrend);
    // $alpha = max(0, min(1, $alpha)); // Ensure alpha is between 0 and 1

    // // Calculate forecasted sales using weighted average with dynamic alpha
    // $forecastedSales = $weightedTotal / $weightSum;

    // return $forecastedSales;
    // }

    private function calculateForecastedSales($transactions)
    {
    // Check if there are enough transactions to calculate forecast
    if ($transactions->count() < 2) { return 0; // Not enough data for accurate forecasting } $alpha=0.2; // Set the
        base alpha parameter for weighted average $sensitivity=0.1; // Set the sensitivity for dynamic alpha
        $weightedTotal=0; $weightSum=0; $recentTrends=0; // Accumulate recent trends // Iterate through transactions to
        calculate weighted average with dynamic alpha foreach ($transactions as $index=> $transaction) {
        // Assuming profit is qty * purchase_price, replace it with your actual field
        $totalSales = $transaction->qty * $transaction->selling_price;

        $weight = pow($alpha, $transactions->count() - $index);
        $weightedTotal += $weight * $totalSales;
        $weightSum += $weight;

        // Accumulate recent trends
        if ($index > 0) {
        $recentTrends += ($transaction->qty * $transaction->selling_price) -
        ($transactions[$index - 1]->qty * $transactions[$index - 1]->selling_price);
        }
        }

        // Calculate dynamic alpha based on accumulated recent trends
        $alpha = $alpha * (1 + $sensitivity * $recentTrends);
        $alpha = max(0, min(1, $alpha)); // Ensure alpha is between 0 and 1

        // Calculate forecasted sales using weighted average with dynamic alpha
        $forecastedSales = $weightedTotal / $weightSum;

        return $forecastedSales;
        }

        // Ito yung Cumulative Total Sales lang
        private function calculateForecastedSales($transactions)
        {
        // Check if there are enough transactions to calculate forecast
        if ($transactions->count() < 2) { return 0; // Not enough data for accurate forecasting } $totalSales=0; //
            Calculate total sales for the day foreach ($transactions as $transaction) { // Assuming profit is qty *
            purchase_price, replace it with your actual field $totalSales +=$transaction->qty *
            $transaction->selling_price;
            }

            // Calculate the forecasted sales as the cumulative total sales
            return $totalSales;
            }

            //latest
            private function calculateForecastedSales($transactions)
            {
            // Check if there are enough transactions to calculate forecast
            if ($transactions->count() < 2) { return 0; // Not enough data for accurate forecasting } // Calculate total
                sales for the day $totalSales=$transactions->sum(function ($transaction) {
                return $transaction->qty * $transaction->selling_price;
                });

                // Calculate the average sales per transaction
                $averageSales = $totalSales / $transactions->count();

                // Calculate the number of transactions made in a day
                $numberOfTransactions = $transactions->count();

                // Calculate forecasted sales by adding a percentage of the average sales
                // and considering the number of transactions
                // $forecastedSales = $totalSales + ($averageSales * 0.2 * $numberOfTransactions);
                $forecastedSales = ($totalSales * 0.1) + $totalSales; // Adjust the factor (0.2) based on your
                preference

                return $forecastedSales;
                }





                // public function transactionStore(Request $request)
                // {
                // // Retrieve data from the request
                // $productName = $request->input('product_name');
                // $selling_price = $request->input('selling_price');
                // $qty = $request->input('qty');
                // $customerName = $request->input('customer_name');

                // // Retrieve the product's information from the Products table (assuming you have a 'Product' model)
                // $product = Product::where('name', $productName)->where('selling_price', $selling_price)->first();

                // if ($product) {
                // // Check if there's enough quantity to subtract
                // if ($product->quantity >= $qty) {
                // // Calculate total price
                // $totalPrice = $selling_price * $qty;

                // // Calculate total earned
                // $purchase_price = $product->purchase_price;
                // $profit = ($selling_price - $purchase_price) * $qty;

                // // Create a new Transactions record and save it to the database
                // $transaction = new Transaction;
                // $transaction->customer_name = $customerName;
                // $transaction->product_name = $productName;
                // $transaction->qty = $qty;
                // $transaction->selling_price = $selling_price;
                // $transaction->total_price = $totalPrice;
                // $transaction->profit = $profit;
                // $transaction->save();

                // // Update the product quantity by subtracting the sold quantity
                // $product->quantity -= $qty;
                // $product->save();

                // return back();
                // } else {
                // // Handle the case where the quantity is insufficient
                // return redirect()->back()
                // ->withInput()
                // ->withErrors(['error_stock' => 'Insufficient quantity in stock. Remaining quantity: ' .
                $product->quantity]);
                // }
                // } else {
                // // Keep the form data and repopulate the fields
                // return redirect()->back()
                // ->withInput()
                // ->withErrors(['error' => 'Selected product and unit price did not match.']);
                // }
                // }

                // Notification

                // Notification arrays
                $lowQuantityNotifications = [];
                $bestSellerNotifications = [];
                $salesForecastNotifications = [];

                // Get forecasts outside the loop to avoid duplication
                $forecasts = $this->forecastSalesForAllCustomers();

                // Find the best-selling product
                $bestSeller = Product::select('products.id', 'products.name')
                ->join('transactions', 'products.name', '=', 'transactions.product_name')
                ->selectRaw('SUM(transactions.qty) as total_qty')
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_qty')
                ->first();

                $productsss = Product::all();
                $forecastMessages = null;

                foreach ($productsss as $product) {
                // Check if there are forecasts for the customer
                $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

                $forecastMessages = null; // Initialize as null

                // Generate forecast messages for the current product
                foreach ($forecasts as $forecast) {
                // Customize the following condition based on your criteria
                if (strpos($forecast, $customerId) !== false) {
                $forecastMessages[] = $forecast;
                }
                }

                // Create a single message by joining the forecast messages
                $forecastMessage = $forecastMessages ? implode('<br>', $forecastMessages) : null; // Check if not null

                // If the product quantity is zero, add a specific message
                if ($product->quantity == 0) {
                $outOfStockNotification = [
                'message' => '<span class="bold-text">OUT OF STOCK!<br> Update: ' . $product->name . '</span> is out of
                stock. Urgently needs restocking!',
                'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $outOfStockNotification;
                } elseif ($product->quantity <= $product->low_quantity_threshold) {
                    // If the quantity is low, add it to low quantity notifications
                    $notification = [
                    'message' => '<span class="bold-text">LOW STOCK!</span><br> We wish to inform you that your
                    inventory <span class="bold-text">' . $product->name . "</span> is running critically low. Its time
                    for a restock!",
                    'forecastMessage' => $forecastMessage,
                    'productId' => $product->id,
                    ];

                    $lowQuantityNotifications[] = $notification;
                    }

                    // Display the best seller quantity sold in the notification
                    if ($bestSeller && $bestSeller->id == $product->id && $bestSeller->total_qty > 0) {
                    $bestSellerNotification = [
                    'message' => '<span class="bold-text">' . e($bestSeller->name) . '</span> is your best seller. It
                    might be wise to increase stock levels to meet the high demand and capitalize on its popularity.',
                    'productId' => $bestSeller->id,
                    ];

                    $bestSellerNotifications[] = $bestSellerNotification;
                    }
                    }

                    $totalLowQuantityNotifications = count($lowQuantityNotifications);
                    $totalBestSellerNotifications = count($bestSellerNotifications);
                    $totalForecastMessages = $forecastMessages ? count($forecastMessages) : 0;

                    // Calculate the total number of notifications
                    $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications +
                    $totalForecastMessages;


                    public function transactionStore(Request $request)
                    {
                    // Retrieve data from the request
                    $productName = $request->input('product_name');
                    $selling_price = $request->input('selling_price');
                    $qty = $request->input('qty');
                    $customerName = $request->input('customer_name');

                    // Retrieve the product's information from the Products table (assuming you have a 'Product' model)
                    $product = Product::where('name', $productName)->where('selling_price', $selling_price)->first();

                    if ($product) {
                    // Check if there's enough quantity to subtract
                    if ($product->quantity >= $qty) {
                    // Calculate total price
                    $totalPrice = $selling_price * $qty;

                    // Calculate total earned
                    $purchase_price = $product->purchase_price;
                    $profit = ($selling_price - $purchase_price) * $qty;

                    // Create a new Transactions record and save it to the database
                    $transaction = new Transaction;
                    $transaction->customer_name = $customerName;
                    $transaction->product_name = $productName;
                    $transaction->qty = $qty;
                    $transaction->selling_price = $selling_price;
                    $transaction->total_price = $totalPrice;
                    $transaction->profit = $profit;
                    $transaction->save();

                    // Update the product quantity by subtracting the sold quantity
                    $product->quantity -= $qty;
                    $product->save();

                    // Fetch past transactions for the current day
                    $currentDayTransactions = Transaction::whereDate('created_at', now()->toDateString())->get();

                    // Perform sales forecasting logic based on the past transactions
                    $forecastedSales = $this->calculateForecastedSales($currentDayTransactions);

                    // Increment the transaction count in the session
                    $transactionCount = session('transactionCount', 0) + 1;
                    session(['transactionCount' => $transactionCount]);

                    // // Display alert with forecasted sales after every two transactions
                    // if ($forecastedSales !== null && $transactionCount % 2 === 0) {
                    // // You can customize the alert message based on your requirements
                    // // Here, I'm using the basic alert() function for demonstration purposes
                    // echo "
                    <script>
                        alert('Forecasted Sales for the day: ₱$forecastedSales');
                    </script>";
                    // }


                    // Display alert with forecasted sales after every two transactions
                    if ($forecastedSales !== null && $transactionCount % 2 === 0) {
                    // You can customize the alert message based on your requirements
                    // Here, I'm using the basic alert() function for demonstration purposes
                    $message = "Forecasted Sales for the day: ₱$forecastedSales";
                    session()->flash('forecastedSalesAlert', $message);
                    }

                    return back();
                    } else {
                    // Handle the case where the quantity is insufficient
                    return redirect()->back()
                    ->withInput()
                    ->withErrors(['error_stock' => 'Insufficient quantity in stock. Remaining quantity: ' .
                    $product->quantity]);
                    }
                    } else {
                    // Keep the form data and repopulate the fields
                    return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Selected product and unit price did not match.']);
                    }
                    }

                    <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserAccount;

use App\Notifications\SMSNotification;

use Carbon\Carbon;

class StaffController extends Controller
{


    private function forecastSalesForAllCustomers()
    {
        // Get all unique customer names
        $uniqueCustomerNames = Transaction::distinct()->pluck('customer_name');

        $forecasts = [];

        foreach ($uniqueCustomerNames as $customerId) {
            // Fetch transactions for the given customer, ordered by transaction date in descending order
            $customerTransactions = Transaction::where('customer_name', $customerId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Check if there are more than one transaction for the customer
            if ($customerTransactions->count() > 1) {
                // Get the timestamps of the first and last transactions
                $firstTransactionDate = Carbon::parse($customerTransactions->last()->created_at);
                $lastTransactionDate = Carbon::parse($customerTransactions->first()->created_at);

                // Calculate the average time between transactions
                $averageTimeBetweenTransactions = $firstTransactionDate->diffInDays($lastTransactionDate) / ($customerTransactions->count() - 1);

                // Determine the most suitable timeframe based on the average time between transactions
                $timeFrame = ($averageTimeBetweenTransactions <= 7) ? 'week' : 'month';

                // Calculate the end date based on the selected time frame
                $endDate = ($timeFrame == 'week') ? $lastTransactionDate->copy()->addWeek() : $lastTransactionDate->copy()->addMonth();

                // Check if the current date is within the forecast period
                if (Carbon::now()->lte($endDate)) {
                    $forecasts[] = '<span class="bold-text">ATTENTION!</span> </br>Data indicates <span class="bold-text">' . $customerId . '</span> will transact again next <span class="bold-text">' . $timeFrame . '</span>.';
                }
            }
        }

        return $forecasts;
    }
    
    public function dashboard()
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        // Count the total quantity sold for the day
        $totalSalesQty = Transaction::selectRaw('SUM(qty) as total_qty')
            ->whereDate('created_at', today()) // Change this to match your date format
            ->value('total_qty') ?? 0;

        $productCount = Product::count();
        $transactionCount = Transaction::count();
        // $totalEarnings = Transaction::sum('profit');
        $totalEarnings = Transaction::sum(DB::raw('qty * purchase_price'));

        // Bar Chart/Graph
        $currentYear = date('Y');

$sales = Transaction::selectRaw('MONTH(created_at) as month, SUM(qty * purchase_price) as total_sales')
    ->whereYear('created_at', $currentYear)
    ->groupBy('month')
    ->orderBy('month')
    ->get();

$labels = [];
$data = [];
$colors = ['#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c'];

for ($i = 1; $i <= 12; $i++) {
    $month = date('F', mktime(0, 0, 0, $i, 1));
    $salesPerMonth = 0;

    foreach ($sales as $sale) {
        if ($sale->month == $i) {
            $salesPerMonth = $sale->total_sales;
            break;
        }
    }

    array_push($labels, $month);
    array_push($data, $salesPerMonth);
}


        $datasets = [
            [
                'label' => 'Monthly sales (' . $currentYear . ')',
                'data' => $data,
                'backgroundColor' => $colors,
            ]
        ];

        // Pie Chart Logic
        $highDemandProducts = DB::table('transactions')
            ->select('product_name', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $pieChartData = "";
        foreach ($highDemandProducts as $product) {
            $pieChartData .= "['" . $product->product_name . "', " . $product->total_qty . "],";
        }

        $arr['pieChartData'] = rtrim($pieChartData, ",");

        // Pass both arrays to the view
        return view('staff-navbar.dashboard', $arr, [
            'username' => $nm,
            'productCount' => $productCount,
            'transactionCount' => $transactionCount,
            'totalSalesQty' => $totalSalesQty,
            'totalEarnings' => $totalEarnings,
            'lowQuantityNotifications' => $lowQuantityNotifications,
            'bestSellerNotifications' => $bestSellerNotifications,
            'datasets' => $datasets, // Adding the datasets for the bar chart
            'labels' => $labels, // Adding the labels for the bar chart
            // 'productLabels' => $productLabels,
            'salesForecastNotifications' => $salesForecastNotifications,
            // 'productDatasets' => $productDatasets,
            'bestSeller' => $bestSeller, // Pass the best seller information to the view
            'totalNotifications' => $totalNotifications, // Pass the best seller information to the view
        ]);
    }

    //Product Controller
    public function product(Request $request)
    {
        $nm = Session::get('name');
        $query = Product::query();

        // Notification arrays
        $lowQuantityNotifications = [];
        $bestSellerNotifications = [];
        $salesForecastNotifications = [];

        // Get forecasts outside the loop to avoid duplication
        $forecasts = $this->forecastSalesForAllCustomers();

        // Find the best-selling product
        $bestSeller = Product::select('products.id', 'products.name')
            ->join('transactions', 'products.name', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->first();

        $productsss = Product::all();

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

            $forecastMessages = [];

            // Generate forecast messages for the current product
            foreach ($forecasts as $forecast) {
                // Customize the following condition based on your criteria
                if (strpos($forecast, $customerId) !== false) {
                    $forecastMessages[] = $forecast;
                }
            }

            // Create a single message by joining the forecast messages
            $forecastMessage = implode('<br>', $forecastMessages);

            // If the product quantity is zero, add a specific message
            if ($product->quantity == 0) {
                $outOfStockNotification = [
                    'message' => '<span class="bold-text">OUT OF STOCK!<br> Update: ' . $product->name . '</span> is out of stock. Urgently needs restocking!',
                    'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $outOfStockNotification;
            } elseif ($product->quantity <= $product->low_quantity_threshold) {
                // If the quantity is low, add it to low quantity notifications
                $notification = [
                    'message' => '<span class="bold-text">LOW STOCK!</span><br> We wish to inform you that your inventory <span class="bold-text">' . $product->name . "</span> is running critically low. Its time for a restock!",
                    'forecastMessage' => $forecastMessage,
                    'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $notification;
            }

            // Display the best seller quantity sold in the notification
            if ($bestSeller && $bestSeller->id == $product->id && $bestSeller->total_qty > 0) {
                $bestSellerNotification = [
                    'message' => '<span class="bold-text">' . e($bestSeller->name) . '</span> is your best seller. It might be wise to increase stock levels to meet the high demand and capitalize on its popularity.',
                    'productId' => $bestSeller->id,
                ];

                $bestSellerNotifications[] = $bestSellerNotification;
            }
        }

        $totalLowQuantityNotifications = count($lowQuantityNotifications);
        $totalBestSellerNotifications = count($bestSellerNotifications);
        $totalForecastMessages = count($forecastMessages);

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        // Sort
        $sortOption = $request->input('sort');

        if ($sortOption === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sortOption === 'category_asc') {
            $query->orderBy('category', 'asc');
        } elseif ($sortOption === 'quantity_asc') {
            $query->orderBy('quantity', 'asc');
        } elseif ($sortOption === 'purchase_price_asc') {
            $query->orderBy('purchase_price', 'asc');
        } elseif ($sortOption === 'selling_price_asc') {
            $query->orderBy('selling_price', 'asc');
        }

        $suppliers = Supplier::all();
        $products = $query->paginate(5);
        $searchQuery = $request->input('search');

        return view('staff-navbar.product', [
            'username' => $nm,
            'lowQuantityNotifications' => $lowQuantityNotifications,
            'salesForecastNotifications' => $salesForecastNotifications,
            'searchQuery' => $searchQuery,
            'products' => $products,
            'bestSellerNotifications' => $bestSellerNotifications,
            'bestSeller' => $bestSeller,
            'suppliers' => $suppliers,
            'totalNotifications' => $totalNotifications,

        ]);
    }


    public function productStore(Request $request)
    {
        // Your validation logic here
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required|unique:products,name,NULL,id',
            'brand_name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric|min:1',
            'low_quantity_threshold' => 'required|numeric|min:1',
            // 'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
        ], [
            'name.unique' => 'You already have :input in your table.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('staff.product')->withErrors($validator)->withInput();
        }

        $products = new Product;
        $products->code = $request->input('code');
        $products->name = $request->input('name');
        $products->brand_name = $request->input('brand_name');
        $products->description = $request->input('description');
        $products->category = $request->input('category');
        $products->quantity = $request->input('quantity');
        $products->low_quantity_threshold = $request->input('low_quantity_threshold');
        // $products->purchase_price = $request->input('purchase_price');
        $products->selling_price = $request->input('selling_price');

        if ($request->hasFile('photo')) {
            $fileName = time() . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('images', $fileName, 'public');
            $products->photo = '/storage/' . $path;
        }

        $products->save();
        return redirect()->route('staff.product')->with('success', 'Product created successfully.');
    }

    public function productUpdate(Request $request, string $id)
    {
        $updateValidator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required|unique:products,name,' . $id . ',id',
            'brand_name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric|min:1',
            'low_quantity_threshold' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
        ], [
            'name.unique' => 'You already have :input in your table.',
        ]);

        if ($updateValidator->fails()) {
            return redirect()->route('staff.transaction')->withErrors($updateValidator)->withInput();
        }

        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $product->code = $request->code;
        $product->name = $request->name;
        $product->brand_name = $request->brand_name;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->quantity = $request->quantity;
        $product->low_quantity_threshold = $request->low_quantity_threshold;
        $product->purchase_price = $request->purchase_price;
        $product->selling_price = $request->selling_price;

        if ($request->hasFile('photo')) {
            $fileName = time() . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('images', $fileName, 'public');
            $product->photo = '/storage/' . $path;
        }

        $product->save();
        return redirect()->route('staff.product');
    }

    public function productDestroy(string $id)
    {
        $products = Product::findOrFail($id);
        $products->delete();
        return redirect()->route('staff.product');
        // return back()->withSuccess('Account deleted successfully!');
    }

    public function productSearch(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        // Query the products table for matching records
        $results = Product::where('code', 'like', "%$searchTerm%")
            ->orWhere('name', 'like', "%$searchTerm%")
            ->orWhere('description', 'like', "%$searchTerm%")
            ->orWhere('category', 'like', "%$searchTerm%")
            ->get();

        // Return the results as JSON data
        return response()->json($results);
    }





    // Customer Controllers
    public function customer()
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        // Notification arrays
        $lowQuantityNotifications = [];
        $bestSellerNotifications = [];
        $salesForecastNotifications = [];

        // Get forecasts outside the loop to avoid duplication
        $forecasts = $this->forecastSalesForAllCustomers();

        // Find the best-selling product
        $bestSeller = Product::select('products.id', 'products.name')
            ->join('transactions', 'products.name', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->first();

        $productsss = Product::all();

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

            $forecastMessages = [];

            // Generate forecast messages for the current product
            foreach ($forecasts as $forecast) {
                // Customize the following condition based on your criteria
                if (strpos($forecast, $customerId) !== false) {
                    $forecastMessages[] = $forecast;
                }
            }

            // Create a single message by joining the forecast messages
            $forecastMessage = implode('<br>', $forecastMessages);

            // If the product quantity is zero, add a specific message
            if ($product->quantity == 0) {
                $outOfStockNotification = [
                    'message' => '<span class="bold-text">OUT OF STOCK!<br> Update: ' . $product->name . '</span> is out of stock. Urgently needs restocking!',
                    'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $outOfStockNotification;
            } elseif ($product->quantity <= $product->low_quantity_threshold) {
                // If the quantity is low, add it to low quantity notifications
                $notification = [
                    'message' => '<span class="bold-text">LOW STOCK!</span><br> We wish to inform you that your inventory <span class="bold-text">' . $product->name . "</span> is running critically low. Its time for a restock!",
                    'forecastMessage' => $forecastMessage,
                    'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $notification;
            }

            // Display the best seller quantity sold in the notification
            if ($bestSeller && $bestSeller->id == $product->id && $bestSeller->total_qty > 0) {
                $bestSellerNotification = [
                    'message' => '<span class="bold-text">' . e($bestSeller->name) . '</span> is your best seller. It might be wise to increase stock levels to meet the high demand and capitalize on its popularity.',
                    'productId' => $bestSeller->id,
                ];

                $bestSellerNotifications[] = $bestSellerNotification;
            }
        }

        $totalLowQuantityNotifications = count($lowQuantityNotifications);
        $totalBestSellerNotifications = count($bestSellerNotifications);
        $totalForecastMessages = count($forecastMessages);

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        $customers = Customer::paginate(7);

        return view('staff-navbar.customer', [
            'customers' => $customers, 'bestSellerNotifications' => $bestSellerNotifications, 'salesForecastNotifications' => $salesForecastNotifications,
            'username' => $nm, 'totalNotifications' => $totalNotifications,
            'lowQuantityNotifications' => $lowQuantityNotifications,
        ]);
    }


    public function customerStore(Request $request)
    {

        // $request -> validate([
        //     "fname"=>"required",
        //     "mname"=>"required|min:2|max:20",
        //     "lname"=>"required|min:2|max:30",
        //     "address"=>"required",
        //     "contact_num"=>"required|numeric|digits_between:5,11",
        // ]);

        $customers = new Customer;
        $customers->name = $request->input('name');
        $customers->contact_name = $request->input('contact_name');
        $customers->address = $request->input('address');
        $customers->contact_num = $request->input('contact_num');
        // $customers->item_sold = $request->input('item_sold');
        $customers->save();
        return redirect()->route('staff.customer')->with("message", "Customer added successfully!");
    }

    public function customerUpdate(Request $request, string $id)
    {
        $customers = Customer::find($id);
        $customers->name = $request->name;
        $customers->contact_name = $request->contact_name;
        $customers->address = $request->address;
        $customers->contact_num = $request->contact_num;
        // $customers->item_sold = $request->item_sold;
        $customers->save();
        return redirect()->route('staff.customer')->with("message", "Customer updated successfully!");
    }

    public function customerDestroy(string $id)
    {
        $customers = Customer::findOrFail($id);
        $customers->delete();
        return redirect()->route('staff.customer')->withSuccess('Account deleted successfully!');
    }




    // Transaction Controllers
    public function transaction(Request $request)
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        $sortOption = $request->input('sort');

        $query = Transaction::query();

        if ($sortOption === 'customer_name_asc') {
            $query->orderBy('customer_name', 'asc');
        } elseif ($sortOption === 'product_name_asc') {
            $query->orderBy('product_name', 'asc');
        } elseif ($sortOption === 'qty_asc') {
            $query->orderBy('qty', 'asc');
        } elseif ($sortOption === 'selling_price_asc') {
            $query->orderBy('selling_price', 'asc');
        } elseif ($sortOption === 'total_price_asc') {
            $query->orderBy('total_price', 'asc');
        } elseif ($sortOption === 'profit_asc') {
            $query->orderByDesc('profit');
        }

        $selling_price = $request->input('selling_price');
        $qty = $request->input('qty');
        $totalPrice = $selling_price * $qty;


        // Notification arrays
        $lowQuantityNotifications = [];
        $bestSellerNotifications = [];
        $salesForecastNotifications = [];

        // Get forecasts outside the loop to avoid duplication
        $forecasts = $this->forecastSalesForAllCustomers();

        // Find the best-selling product
        $bestSeller = Product::select('products.id', 'products.name')
            ->join('transactions', 'products.name', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->first();

        $productsss = Product::all();

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

            $forecastMessages = [];

            // Generate forecast messages for the current product
            foreach ($forecasts as $forecast) {
                // Customize the following condition based on your criteria
                if (strpos($forecast, $customerId) !== false) {
                    $forecastMessages[] = $forecast;
                }
            }

            // Create a single message by joining the forecast messages
            $forecastMessage = implode('<br>', $forecastMessages);

            // If the product quantity is zero, add a specific message
            if ($product->quantity == 0) {
                $outOfStockNotification = [
                    'message' => '<span class="bold-text">OUT OF STOCK!<br> Update: ' . $product->name . '</span> is out of stock. Urgently needs restocking!',
                    'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $outOfStockNotification;
            } elseif ($product->quantity <= $product->low_quantity_threshold) {
                // If the quantity is low, add it to low quantity notifications
                $notification = [
                    'message' => '<span class="bold-text">LOW STOCK!</span><br> We wish to inform you that your inventory <span class="bold-text">' . $product->name . "</span> is running critically low. Its time for a restock!",
                    'forecastMessage' => $forecastMessage,
                    'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $notification;
            }

            // Display the best seller quantity sold in the notification
            if ($bestSeller && $bestSeller->id == $product->id && $bestSeller->total_qty > 0) {
                $bestSellerNotification = [
                    'message' => '<span class="bold-text">' . e($bestSeller->name) . '</span> is your best seller. It might be wise to increase stock levels to meet the high demand and capitalize on its popularity.',
                    'productId' => $bestSeller->id,
                ];

                $bestSellerNotifications[] = $bestSellerNotification;
            }
        }

        $totalLowQuantityNotifications = count($lowQuantityNotifications);
        $totalBestSellerNotifications = count($bestSellerNotifications);
        $totalForecastMessages = count($forecastMessages);

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        $products = Product::all();
        $customers = Customer::all();
        $searchQuery = $request->input('search');
        $transactions = $query->paginate(8);

        return view('staff-navbar.transaction', [
            'bestSellerNotifications' => $bestSellerNotifications,
            'transactions' => $transactions, 'username' => $nm, 'products' => $products,
            'salesForecastNotifications' => $salesForecastNotifications,
            'totalNotifications' => $totalNotifications,
            'totalPrice' => $totalPrice,
        ])->with('lowQuantityNotifications', $lowQuantityNotifications)->with('searchQuery', $searchQuery)->with('customers', $customers);
    }

    public function transactionStore(Request $request)
    {
        // Retrieve data from the request
        $productName = $request->input('product_name');
        $selling_price = $request->input('selling_price');
        $qty = $request->input('qty');
        $customerName = $request->input('customer_name');

        // Retrieve the product's information from the Products table (assuming you have a 'Product' model)
        $product = Product::where('name', $productName)->where('selling_price', $selling_price)->first();

        if ($product) {
            // Check if there's enough quantity to subtract
            if ($product->quantity >= $qty) {
                // Calculate total price
                $totalPrice = $selling_price * $qty;

                // Calculate total earned
                $purchase_price = $product->purchase_price;
                $profit = ($selling_price - $purchase_price) * $qty;

                // Create a new Transactions record and save it to the database
                $transaction = new Transaction;
                $transaction->customer_name = $customerName;
                $transaction->product_name = $productName;
                $transaction->qty = $qty;
                $transaction->selling_price = $selling_price;
                $transaction->total_price = $totalPrice;
                $transaction->profit = $profit;
                $transaction->save();

                // Update the product quantity by subtracting the sold quantity
                $product->quantity -= $qty;
                $product->save();

                return back();
            } else {
                // Handle the case where the quantity is insufficient
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error_stock' => 'Insufficient quantity in stock. Remaining quantity: ' . $product->quantity]);
            }
        } else {
            // Keep the form data and repopulate the fields
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Selected product and unit price did not match.']);
        }
    }

    public function transactionUpdate(Request $request, string $id)
    {
        // Retrieve the existing transaction record by its ID
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return redirect()->route('staff.transaction')->with("error", "Transaction not found!");
        }

        // Retrieve data from the request
        $productName = $request->input('product_name');
        $selling_price = $request->input('selling_price');
        $qty = $request->input('qty');
        $customerName = $request->input('customer_name');

        // Retrieve the old 'qty' for the transaction
        $oldQty = $transaction->qty;

        // Retrieve the product's information from the Products table (assuming you have a 'Product' model)
        $product = Product::where('name', $productName)->where('selling_price', $selling_price)->first();

        if ($product) {
            // Check if there's enough quantity to subtract
            if ($product->quantity + $oldQty >= $qty) {
                // Calculate total price
                $totalPrice = $selling_price * $qty;

                // Calculate total earned
                $purchase_price = $product->purchase_price;
                $profit = ($selling_price - $purchase_price) * $qty;

                // Update the existing transaction record with the new data
                $transaction->product_name = $productName;
                $transaction->selling_price = $selling_price;
                $transaction->qty = $qty;
                $transaction->total_price = $totalPrice;
                $transaction->profit = $profit;
                $transaction->save();

                // Update the product quantity by adding the old 'qty' and subtracting the new 'qty'
                $product->quantity += $oldQty;
                $product->quantity -= $qty;
                $product->save();

                return redirect()->route('staff.transaction')->with("message", "Transaction updated successfully!");
            } else {
                // Calculate the remaining quantity as the sum of the past 'qty' and the current 'quantity'
                $remainingQuantity = $product->quantity + $oldQty;
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error_stock' => 'Insufficient quantity in stock. Remaining quantity: ' . $remainingQuantity]);
            }
        } else {
            // Keep the form data and repopulate the fields
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Selected product and unit price did not match.']);
        }
    }

    public function transactionDestroy(string $id)
    {   
        $transactions = Transaction::findOrFail($id);
        // return redirect()->route('staff.transaction')->withSuccess('Account deleted successfully!');
        $transactions->delete();
        return back()->withSuccess('Account deleted successfully!');
    }

    public function generateReport(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Increment the toDate by one day to include records on the toDate itself
        $toDate = date('Y-m-d', strtotime($toDate . ' +1 day'));

        $transactions = Transaction::whereBetween('created_at', [$fromDate, $toDate])->get();
        return view('staff-navbar.report', [
            'transactions' => $transactions,
            // 'fromDate' => $fromDate, // Pass both from and to dates to the view
            // 'toDate' => $toDate,
            'fromDate' => Carbon::parse($fromDate),
            'toDate' => Carbon::parse($toDate),

        ]);
    }
}


  {{-- // Line Chart --}}
    {{-- <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function calculateWeightedAverage(data, currentIndex, alpha) {
            var weightedTotal = 0;
            var weightSum = 0;

            for (var i = currentIndex; i >= 0; i--) {
                var weight = Math.pow(alpha, currentIndex - i);
                weightedTotal += weight * data.getValue(i, 1);
                weightSum += weight;
            }

            return weightedTotal / weightSum;
        }

        function calculateWeightedAverageDynamic(data, currentIndex, alpha) {
            var weightedTotal = 0;
            var weightSum = 0;

            for (var i = currentIndex; i >= 0; i--) {
                var weight = Math.pow(alpha, currentIndex - i);
                weightedTotal += weight * data.getValue(i, 1);
                weightSum += weight;
            }

            return weightedTotal / weightSum;
        }

        function calculateDynamicAlpha(data, currentIndex, baseAlpha, sensitivity) {
            // If there's not enough data points to calculate the trend, use the base alpha
            if (currentIndex < 2) {
                return baseAlpha;
            }

            var recentTrend = data.getValue(currentIndex, 1) - data.getValue(currentIndex - 1, 1);
            var alpha = baseAlpha * (1 + sensitivity * recentTrend);
            return Math.max(0, Math.min(1, alpha)); // Ensure alpha is between 0 and 1
        }

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Month');
            data.addColumn('number', 'Current Sales');
            data.addColumn('number', 'Forecasted Sales');

            var monthsWithData = [];
            var latestMonthWithData = null;

            @for ($i = 1; $i <= 12; $i++)
                var month = new Date('{{ date('Y-m', mktime(0, 0, 0, $i, 1)) }}');
                var currentMonthSales = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                    ->whereYear('created_at', today()->year)
                    ->sum(DB::raw('total_price')) ?? 0; ?>;

                    var currentMonthSalesLabel = '₱' + currentMonthSales;


                    if (currentMonthSales > 0) {
                    data.addRow([month.toLocaleString('default', {
                    month: 'long'
                    }), currentMonthSales, null]);
                    monthsWithData.push(month.toLocaleString('default', {
                    month: 'long'
                    }));
                    latestMonthWithData = month;
                    }
                    @endfor

                    // Include the next month after the latest month with data
                    if (latestMonthWithData !== null) {
                    var nextMonth = new Date(latestMonthWithData);
                    nextMonth.setMonth(nextMonth.getMonth() + 1);
                    var nextMonthString = nextMonth.toLocaleString('default', {
                    month: 'long'
                    });

                    // Check if the next month is not already in the array before adding it
                    if (!monthsWithData.includes(nextMonthString)) {
                    monthsWithData.push(nextMonthString);

                    // Set the base alpha parameter for weighted average
                    var baseAlpha = 0.2; // You can adjust this value

                    // Calculate dynamic alpha based on recent trend
                    var dynamicAlpha = calculateDynamicAlpha(data, data.getNumberOfRows() - 1, baseAlpha, 0.1);

                    // Calculate forecasted sales using weighted average with dynamic alpha
                    var forecastedSales = calculateWeightedAverageDynamic(data, data.getNumberOfRows() - 1,
                    dynamicAlpha);
                    var forecastedSalesLabel = '₱' + forecastedSales;

                    // Add the data for the next month
                    data.addRow([nextMonthString, null, forecastedSales]);
                    }
                    }

                    // Add the weighted average for the Future Sales line
                    for (var i = 0; i < data.getNumberOfRows(); i++) { var
                        weightedAverage=calculateWeightedAverage(data, i, baseAlpha); data.setValue(i, 2,
                        weightedAverage); } // // Add the weighted average for the Future Sales line // for (var i=0; i
                        < data.getNumberOfRows(); i++) { // // Calculate dynamic alpha based on recent trend // var
                        dynamicAlpha=calculateDynamicAlpha(data, i, baseAlpha, 0.1); // // Calculate forecasted sales
                        using weighted average with dynamic alpha // var
                        forecastedSales=calculateWeightedAverageDynamic(data, i, dynamicAlpha); // data.setValue(i, 2,
                        forecastedSales); // } var options={ title: 'Sales Forecasting' , titleTextStyle: {
                        color: '#414141' , fontSize: 28, bold: true, fontFamily: 'Arial, Helvetica, sans-serif' , },
                        curveType: 'function' , legend: { position: 'bottom' }, series: { 0: { pointShape: 'circle' ,
                        pointSize: 5, lineWidth: 2 }, 1: { pointShape: 'circle' , pointSize: 5, lineWidth: 2 }, },
                        vAxis: { format: '₱ ' , // Format vertical axis labels as currency } }; try { var chart=new
                        google.visualization.LineChart(document.getElementById('curve_chart')); chart.draw(data,
                        options); } catch (error) { console.error('Error drawing the chart:', error); } } </script> --}}

                        {{-- Line Chart --}}
                        <script type="text/javascript">
                            google.charts.load('current', {
                                'packages': ['corechart']
                            });
                            google.charts.setOnLoadCallback(drawChart);

                            function calculateWeightedAverage(data, currentIndex, alpha) {
                                var weightedTotal = 0;
                                var weightSum = 0;

                                for (var i = currentIndex; i >= 0; i--) {
                                    var weight = Math.pow(alpha, currentIndex - i);
                                    weightedTotal += weight * data.getValue(i, 1);
                                    weightSum += weight;
                                }

                                return weightedTotal / weightSum;
                            }

                            function calculateDynamicAlpha(data, currentIndex, baseAlpha, sensitivity) {
                                // If there's not enough data points to calculate the trend, use the base alpha
                                if (currentIndex < 2) {
                                    return baseAlpha;
                                }

                                var recentTrend = data.getValue(currentIndex, 1) - data.getValue(currentIndex - 1, 1);
                                var alpha = baseAlpha * (1 + sensitivity * recentTrend);
                                return Math.max(0, Math.min(1, alpha)); // Ensure alpha is between 0 and 1
                            }

                            function drawChart() {
                                var data = new google.visualization.DataTable();
                                data.addColumn('string', 'Month');
                                data.addColumn('number', 'Current Sales');
                                data.addColumn('number', 'Forecasted Sales');

                                var monthsWithData = [];
                                var latestMonthWithData = null;

                                @for ($i = 1; $i <= 12; $i++)
                                    var month = new Date('{{ date('Y-m', mktime(0, 0, 0, $i, 1)) }}');
                                    var currentMonthSales = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                                        ->whereYear('created_at', today()->year)
                                        ->sum(DB::raw('total_price')) ?? 0; ?>;

                                    var currentMonthSalesLabel = '₱' + currentMonthSales;

                                    if (currentMonthSales > 0) {
                                        data.addRow([month.toLocaleString('default', {
                                            month: 'long'
                                        }), currentMonthSales, null]);
                                        monthsWithData.push(month.toLocaleString('default', {
                                            month: 'long'
                                        }));
                                        latestMonthWithData = month;
                                    }
                                @endfor

                                // Include the next month based on the current date
                                var currentDate = new Date();
                                var nextMonth = new Date(currentDate);
                                nextMonth.setMonth(nextMonth.getMonth() + 1);
                                var nextMonthString = nextMonth.toLocaleString('default', {
                                    month: 'long'
                                });

                                // Check if the next month is not already in the array before adding it
                                if (!monthsWithData.includes(nextMonthString)) {
                                    monthsWithData.push(nextMonthString);

                                    // Set the base alpha parameter for weighted average
                                    var baseAlpha = 0.2; // You can adjust this value

                                    // Calculate dynamic alpha based on recent trend
                                    var dynamicAlpha = calculateDynamicAlpha(data, data.getNumberOfRows() - 1, baseAlpha, 0.1);

                                    // Calculate forecasted sales using weighted average with dynamic alpha
                                    var growthRate = 0.1; // You can adjust this value
                                    var forecastedSales = currentMonthSales * (1 + growthRate);
                                    var forecastedSalesLabel = '₱' + forecastedSales;

                                    // Add the data for the next month
                                    data.addRow([nextMonthString, null, forecastedSales]);
                                }


                                // Add the weighted average for the Future Sales line
                                for (var i = 0; i < data.getNumberOfRows(); i++) {
                                    var weightedAverage = calculateWeightedAverage(data, i, baseAlpha);
                                    data.setValue(i, 2, weightedAverage);
                                }

                                var options = {
                                    title: 'Sales Forecasting',
                                    titleTextStyle: {
                                        color: '#414141',
                                        fontSize: 28,
                                        bold: true,
                                        fontFamily: 'Arial, Helvetica, sans-serif',
                                    },
                                    curveType: 'function',
                                    legend: {
                                        position: 'bottom'
                                    },
                                    series: {
                                        0: {
                                            pointShape: 'circle',
                                            pointSize: 5,
                                            lineWidth: 2
                                        },
                                        1: {
                                            pointShape: 'circle',
                                            pointSize: 5,
                                            lineWidth: 2
                                        },
                                    },
                                    vAxis: {
                                        format: '₱ ', // Format vertical axis labels as currency
                                    }
                                };

                                try {
                                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                                    chart.draw(data, options);
                                } catch (error) {
                                    console.error('Error drawing the chart:', error);
                                }
                            }
                        </script>















                        // Login CSS

                        @import
                        url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@200;300;400;500;600;700&display=swap");

                        * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                        font-family: "Open Sans", sans-serif;
                        }

                        body {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        min-height: 100vh;
                        width: 100%;
                        padding: 0 10px;
                        }

                        body::before {
                        content: "";
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        /* background:
                        url("https://www.codingnepalweb.com/demos/create-glassmorphism-login-form-html-css/hero-bg.jpg"),
                        #000; */
                        background: url("../images/bg.jpg"), white;
                        background-position: center;
                        background-size: cover;
                        }

                        header {
                        display: flex;
                        /* align-items: center; */
                        justify-content: center;
                        padding: 3vh 5vw; /* Adjusted padding values */
                        height: 100vh;
                        width: 100vw;
                        min-height: 60px;
                        font-style: italic;
                        position: absolute;
                        top: 0;
                        }

                        .logo {
                        /* width: 9%; */
                        width: 120px;
                        height: auto;
                        position: absolute;
                        left: 3%;
                        top: 0;
                        }

                        .top-left {
                        position: absolute;
                        top: 20%;
                        left: 4%;
                        }

                        .taas-tleft {
                        font-size: 1.1rem;
                        font-weight: bold;
                        font-style: italic;
                        }

                        .baba-tleft {
                        font-size: .8rem;
                        font-weight: 500;
                        margin-left: 5rem;
                        }

                        .horizontal-line {
                        height: 1vh;
                        width: 30vw;
                        margin: 5px 0;
                        margin-left: -99px;
                        background-color: #D9D9D9;
                        }

                        .wrapper {
                        width: 400px;
                        border-radius: 8px;
                        padding: 30px;
                        text-align: center;
                        /* border: 1px solid rgba(255, 255, 255, 0.5); */
                        border: 1px solid black;
                        backdrop-filter: blur(9px);
                        -webkit-backdrop-filter: blur(9px);
                        }

                        form {
                        display: flex;
                        flex-direction: column;
                        }

                        h2 {
                        font-size: 2rem;
                        margin-bottom: 20px;
                        color: black;
                        }

                        .input-field {
                        position: relative;
                        border-bottom: 2px solid #ccc;
                        margin: 15px 0;
                        }

                        .input-field label {
                        position: absolute;
                        top: 50%;
                        left: 0;
                        transform: translateY(-50%);
                        color: black;
                        font-size: 16px;
                        pointer-events: none;
                        transition: 0.15s ease;
                        }

                        .input-field input {
                        width: 100%;
                        height: 40px;
                        background: transparent;
                        border: none;
                        outline: none;
                        font-size: 16px;
                        color: black;
                        }

                        .input-field input:focus ~ label,
                        .input-field input:valid ~ label {
                        font-size: 0.8rem;
                        top: 10px;
                        transform: translateY(-120%);
                        }

                        .forget {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin: 25px 0 35px 0;
                        color: black;
                        }

                        #remember {
                        accent-color: black;
                        }

                        .forget label {
                        display: flex;
                        align-items: center;
                        }

                        .forget label p {
                        margin-left: 8px;
                        }

                        .wrapper a {
                        color: black;
                        text-decoration: none;
                        }

                        .wrapper a:hover {
                        text-decoration: underline;
                        }

                        button {
                        background: black;
                        color: white;
                        font-weight: 600;
                        border: none;
                        padding: 12px 20px;
                        cursor: pointer;
                        border-radius: 3px;
                        font-size: 16px;
                        border: 2px solid transparent;
                        transition: 0.3s ease;
                        }

                        button:hover {
                        color: black;
                        border-color: black;
                        background: rgba(255, 255, 255, 0.15);
                        }

                        .register {
                        text-align: center;
                        margin-top: 30px;
                        color: black;
                        }

                        footer {
                        position: absolute;
                        bottom: 0;
                        }

                        .baba {
                        display: flex;
                        gap: 5px;
                        }

                        .input-field input.has-value ~ label,
                        .input-field input:focus ~ label,
                        .input-field input:valid ~ label {
                        font-size: 0.8rem;
                        top: 10px;
                        transform: translateY(-120%);
                        }

                        .text-danger {
                        color: red;
                        font-size: 14px;
                        position: relative;
                        bottom: 8px;
                        }



                        // Mga inalis after defense sa LiveSearchController

                        public function productSearch(Request $request)
                        {
                        $rowNumber = 1;
                        $output = "";

                        $products = Product::where('code', 'Like', '%' . $request->search . '%')
                        ->orWhere('name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('category', 'LIKE', '%' . $request->search . '%')
                        ->get();

                        foreach ($products as $product) {
                        $output .=
                        '<tr>
                            <td>' . $rowNumber++ . '</td>
                            <td> ' . $product->code . ' </td>
                            <td> ' . $product->brand_name . ' </td>
                            <td> ' . $product->description . ' </td>
                            <td> ' . $product->category . ' </td>
                            <td>
                                <img src="' . asset($product->photo) . '" alt="' . $product->name . '" width="auto"
                                    height="50px" style="background-color: transparent">
                            </td>
                            <td> ' . $product->quantity . ' </td>
                            <td class="nowrap"> ₱ ' . number_format($product->purchase_price) . ' </td>
                            <td class="nowrap"> ₱ ' . number_format($product->selling_price) . ' </td>
                            <td class="actions">
                                <div class="actions-container">
                                    <form action="' . route('admin.productEdit', $product->id) . '" method="POST">
                                        ' . csrf_field() . '
                                        ' . method_field('GET') . '
                                        <button type="submit" class="edit editButton" id="edit">
                                            <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                        </button>
                                    </form>
                                    <form action="' . route('admin.productDestroy', $product->id) . '" method="POST">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <button onclick="return confirm(\'Are you sure you want to delete this?\')"
                                            type="submit" class="delete" id="delete">
                                            <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>';
                        }

                        return response($output ?: '');
                        }

                        public function transactionSearch(Request $request)
                        {
                        $rowNumber = 1;
                        $output="";

                        $transactions = Transaction::where('customer_name', 'Like', '%' . $request->search . '%')
                        ->orWhere('product_name', 'LIKE', '%' . $request->search . '%')
                        ->get();

                        foreach ($transactions as $transaction)
                        {
                        $output.=

            '<tr>

            <td>' . $rowNumber++ . '</td>
                        <td> '.$transaction->customer_name.' </td>
                        <td> '.$transaction->product_name.' </td>
                        <td> '.$transaction->qty.' </td>
                        <td class="nowrap"> ₱ '.number_format($transaction->selling_price).' </td>
                        <td class="nowrap"> ₱ '.number_format($transaction->total_price).' </td>
                        <td class="nowrap"> ₱ '.number_format($transaction->profit).' </td>
                        <td> '.$transaction->created_at->format('M. d, Y').' </td>

                        <td class="actions">
                            <div class="actions-container">
                                <form action="'. route('admin.transactionEdit', $transaction->id) .'" method="POST">
                                    ' . csrf_field() . '
                                    ' . method_field('GET') . '
                                    <button type="submit" class="edit editButton" id="edit"
                                        data-id="'.$transaction->id.'">
                                        <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                    </button>
                                </form>
                                <form action="'. route('admin.transactionDestroy', $transaction->id) .'" method="POST">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <button onclick="return confirm(\'Are you sure you want to delete this?\')"
                                        type="submit" class="delete" id="delete">
                                        <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                        <tr>';

                            }

                            return response($output ?: '');
                            }



                            // Ito yung tama

                            @section('modals')
                                <div class="overlay"></div>

                                {{-- Add Modal --}}
                                <div id="newModal" class="modal">
                                    <div class="modal-content">
                                        <span class="close closeModal">&times;</span>

                                        <form class="modal-form" action="{{ route('admin.deliveryStore') }}" method="POST">
                                            @csrf
                                            <center>
                                                <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-plus"></i>Add
                                                    Delivery</h2>
                                            </center>
                                            <label class="modal-tops" for="">Delivery ID:</label>
                                            <input required autofocus type="text" name="delivery_id" id="autofocus"
                                                pattern="{5,15}" value="{{ old('delivery_id') }}" />
                                            @if ($errors->has('delivery_id'))
                                                <div class="text-danger">{{ $errors->first('delivery_id') }}</div>
                                            @endif

                                            <label class="modal-tops" for="">Name:</label>
                                            <input required type="text" name="name" id=""
                                                value="{{ old('name') }}" />
                                            @if ($errors->has('name'))
                                                <div class="text-danger">{{ $errors->first('name') }}</div>
                                            @endif

                                            {{-- <label class="modal-tops" for="">Product/s:</label>
                <input required  type="text" name="product" id="" value="{{ old('product') }}" />
                @if ($errors->has('product'))
                <div class="text-danger">{{ $errors->first('product') }}</div>
                @endif --}}
                                            {{-- <select required class="select_product" name="product" id="product_name">
                    <option value="" disabled selected>-- Select a Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->product_name }}"
                            {{ old('product') === $product->product_name ? 'selected' : '' }}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select> --}}

                                            {{-- <label class="modal-tops" for="">Quantity:</label>
                <input required type="number" name="quantity" id="" value="{{ old('quantity') }}" />
                @if ($errors->has('quantity'))
                    <div class="text-danger">{{ $errors->first('quantity') }}</div>
                @endif --}}

                                            <label class="modal-tops" for="">Address:</label>
                                            <input required type="text" name="address" id=""
                                                value="{{ old('address') }}" />
                                            @if ($errors->has('address'))
                                                <div class="text-danger">{{ $errors->first('address') }}</div>
                                            @endif

                                            <label for="">Pending Status:</label>
                                            <select required name="status" id="" class="">
                                                <option disabled selected value="">-- Select Status --</option>
                                                <option value="Delivered"
                                                    {{ old('status') === 'Delivered' ? 'selected' : '' }}>Delivered
                                                </option>
                                                <option value="Not Delivered"
                                                    {{ old('status') === 'Not Delivered' ? 'selected' : '' }}>Not Delivered
                                                </option>
                                            </select>
                                            @if ($errors->has('status'))
                                                <div class="text-danger">{{ $errors->first('status') }}</div>
                                            @endif

                                            <input class="add nextButton" type="button" onclick="showProductModal()"
                                                value="Next" />
                                            {{-- <button type="button" class="nextButton" onclick="showProductModal()">Next</button> --}}

                                        </form>
                                    </div>
                                </div>

                                {{-- Product Selection Modal --}}
                                <div id="productModal" class="modal" style="display:none;">
                                    <div class="modal-content">
                                        <span class="close closeModal" onclick="closeProductModal()">&times;</span>

                                        <form class="modal-form" id="productSelectionForm"
                                            action="{{ route('admin.deliveryStore') }}" method="POST">
                                            @csrf
                                            <center>
                                                <h2 style="margin: 0%; color:#333;">Select Products</h2>
                                            </center>

                                            {{-- Display products with checkboxes --}}
                                            @foreach ($products as $product)
                                                <label>
                                                    {{-- <input type="checkbox" name="selectedProducts[]" value="{{ $product->id }}" /> --}}
                                                    <input type="checkbox" name="product" value="{{ $product->id }}" />
                                                    {{ $product->name }}
                                                </label>
                                                {{-- <input type="number" name="productQuantities[]" placeholder="Quantity" /> --}}
                                                <input type="number" name="quantity" placeholder="Quantity" />
                                            @endforeach

                                            {{-- <button type="button" class="nextButton" onclick="submitProductSelection()">Submit</button> --}}
                                            {{-- <button type="button" class="backButton" onclick="goBack()">Back</button> --}}
                                            <input class="add backButton" type="button" onclick="goBack()"
                                                value="Back" />
                                            <input class="add" type="submit" value="Add" />

                                        </form>
                                    </div>
                                </div>
                            @endsection





                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const deliveryIdInput = document.getElementById('autofocus');
                                    const nameInput = document.getElementsByName('name')[0];
                                    const addressInput = document.getElementsByName('address')[0];
                                    const statusSelect = document.getElementsByName('status')[0];
                                    const nextButton = document.getElementById('nextButton');

                                    // Function to check if all required inputs are filled
                                    function checkFormCompleteness() {
                                        const isComplete = deliveryIdInput.value.trim() !== '' &&
                                            nameInput.value.trim() !== '' &&
                                            addressInput.value.trim() !== '' &&
                                            statusSelect.value !== '';

                                        console.log('isComplete:', isComplete); // Log isComplete value
                                        nextButton.disabled = !isComplete;

                                        return isComplete; // Return the boolean value
                                    }

                                    // Event listeners for input changes
                                    deliveryIdInput.addEventListener('input', checkFormCompleteness);
                                    nameInput.addEventListener('input', checkFormCompleteness);
                                    addressInput.addEventListener('input', checkFormCompleteness);
                                    statusSelect.addEventListener('change', checkFormCompleteness);

                                    // Call checkFormCompleteness initially to set the initial state
                                    checkFormCompleteness();

                                    // Handle Next button click
                                    nextButton.addEventListener('click', function() {
                                        console.log('Next button clicked');
                                        if (!checkFormCompleteness()) {
                                            console.log('Fields not complete. Showing alert.');
                                            alert('Please fill in all fields before proceeding.');
                                        } else {
                                            console.log('Fields complete. Opening Product Selection Modal.');
                                            // Open the Product Selection Modal
                                            document.getElementById('newModal').style.display = 'none';
                                            document.getElementById('productModal').style.display = 'block';
                                        }
                                    });
                                });
                            </script>










                            <form class="modal-form" id="addDeliveryForm"
                                action="{{ route('admin.deliveryStore') }}" method="POST">
                                <div id="newModal" class="modal"
                                    style="@if ($errors->any()) display:block; @endif">
                                    <div class="modal-content-delivery">
                                        <span class="close closeModal" onclick="window.closeModal()">&times;</span>
                                        @csrf
                                        <center>
                                            <h2 style="margin: 0%; color:#333; "><i class="fa-regular fa-plus"></i>Add
                                                Delivery</h2>
                                        </center>
                                        <label class="modal-tops" for="">Delivery ID:</label>
                                        <input required autofocus type="text" name="delivery_id" id="autofocus"
                                            pattern="^.{5,15}$" value="{{ old('delivery_id') }}" />
                                        @if ($errors->has('delivery_id'))
                                            <div class="text-danger">{{ $errors->first('delivery_id') }}</div>
                                        @endif

                                        <label class="modal-tops" for="">Name:</label>
                                        <input required type="text" name="name" id=""
                                            value="{{ old('name') }}" />
                                        @if ($errors->has('name'))
                                            <div class="text-danger">{{ $errors->first('name') }}</div>
                                        @endif

                                        <label class="modal-tops" for="">Address:</label>
                                        <input required type="text" name="address" id=""
                                            value="{{ old('address') }}" />
                                        @if ($errors->has('address'))
                                            <div class="text-danger">{{ $errors->first('address') }}</div>
                                        @endif

                                        <label for="">Pending Status:</label>
                                        <select required name="status" id="" class="">
                                            <option disabled selected value="">-- Select Status --</option>
                                            <option value="Delivered"
                                                {{ old('status') === 'Delivered' ? 'selected' : '' }}>Delivered
                                            </option>
                                            <option value="Not Delivered"
                                                {{ old('status') === 'Not Delivered' ? 'selected' : '' }}>Not Delivered
                                            </option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="text-danger">{{ $errors->first('status') }}</div>
                                        @endif

                                        <input class="add nextButton" type="button" id="nextButton"
                                            value="Next" />
                                        {{-- <input class="add nextButton" type="submit" id="nextButton" value="Next" /> --}}
                                    </div>
                                </div>


                                {{-- Product Selection Modal --}}
                                <div id="productModal" class="" style="display:none;">
                                    <div class="modal-content-delivery-next">
                                        <span class="close closeModal" onclick="window.closeModal()">&times;</span>


                                        <center>
                                            <h2 style="margin: 0%; color:#333; font-size: 1.4rem">Select Products to
                                                Deliver</h2>
                                        </center>

                                        {{-- Display products with checkboxes --}}
                                        @foreach ($products as $index => $product)
                                            <label>
                                                <input type="checkbox" name="product[]"
                                                    value="{{ $product->name }}" />
                                                {{ $product->name }}
                                            </label>
                                            @if ($loop->first)
                                                <!-- Display the first quantity input without any condition -->
                                                <input type="number" name="quantity[{{ $index }}]"
                                                    placeholder="Quantity" />
                                            @else
                                                <!-- Display the quantity input only if the checkbox is checked -->
                                                @if (old('product') && in_array($product->name, old('product')))
                                                    <input type="number" name="quantity[{{ $index }}]"
                                                        placeholder="Quantity" required />
                                                @else
                                                    <input type="number" name="quantity[{{ $index }}]"
                                                        placeholder="Quantity" />
                                                @endif
                                            @endif
                                        @endforeach


                                        <div class="buttons">
                                            <input class="add backButton" type="button" id="backButton"
                                                value="Back" />
                                            <input class="add" type="submit" value="Add" />
                                        </div>
                                    </div>
                                </div>
                            </form>














                            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

                            {{-- Line Chart --}}
                            <script type="text/javascript">
                                google.charts.load('current', {
                                    'packages': ['corechart']
                                });
                                google.charts.setOnLoadCallback(drawChart);

                                function calculateWeightedAverage(data, currentIndex, alpha) {
                                    var weightedTotal = 0;
                                    var weightSum = 0;

                                    for (var i = currentIndex; i >= 0; i--) {
                                        var weight = Math.pow(alpha, currentIndex - i);
                                        weightedTotal += weight * data.getValue(i, 1);
                                        weightSum += weight;
                                    }

                                    return weightedTotal / weightSum;
                                }

                                function calculateDynamicAlpha(data, currentIndex, baseAlpha, sensitivity) {
                                    // If there's not enough data points to calculate the trend, use the base alpha
                                    if (currentIndex < 2) {
                                        return baseAlpha;
                                    }

                                    var recentTrend = data.getValue(currentIndex, 1) - data.getValue(currentIndex - 1, 1);
                                    var alpha = baseAlpha * (1 + sensitivity * recentTrend);
                                    return Math.max(0, Math.min(1, alpha)); // Ensure alpha is between 0 and 1
                                }

                                function drawChart() {
                                    var data = new google.visualization.DataTable();
                                    data.addColumn('string', 'Month');
                                    data.addColumn('number', 'Current Sales');
                                    data.addColumn('number', 'Forecasted Sales');

                                    var monthsWithData = [];
                                    var latestMonthWithData = null;

                                    @for ($i = 1; $i <= 12; $i++)
                                        var month = new Date('{{ date('Y-m', mktime(0, 0, 0, $i, 1)) }}');
                                        var currentMonthSales = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                                            ->whereYear('created_at', today()->year)
                                            ->sum(DB::raw('total_price')) ?? 0; ?>;

                                        var currentMonthSalesLabel = '₱' + currentMonthSales;

                                        if (currentMonthSales > 0) {
                                            data.addRow([month.toLocaleString('default', {
                                                month: 'long'
                                            }), currentMonthSales, null]);
                                            monthsWithData.push(month.toLocaleString('default', {
                                                month: 'long'
                                            }));
                                            latestMonthWithData = month;
                                        }
                                    @endfor

                                    // Include the next month based on the current date
                                    var currentDate = new Date();
                                    var nextMonth = new Date(currentDate);
                                    nextMonth.setMonth(nextMonth.getMonth() + 1);
                                    var nextMonthString = nextMonth.toLocaleString('default', {
                                        month: 'long'
                                    });

                                    // Check if the next month is not already in the array before adding it
                                    if (!monthsWithData.includes(nextMonthString)) {
                                        monthsWithData.push(nextMonthString);

                                        // Set the base alpha parameter for weighted average
                                        // var baseAlpha = .2; // You can adjust this value
                                        var baseAlpha = .39; // You can adjust this value

                                        // Calculate dynamic alpha based on recent trend
                                        var dynamicAlpha = calculateDynamicAlpha(data, data.getNumberOfRows() - 1, baseAlpha, 0.1);

                                        // Calculate forecasted sales using weighted average with dynamic alpha
                                        // var growthRate = 0.1; // You can adjust this value
                                        var growthRate = 2; // You can adjust this value
                                        var forecastedSales = currentMonthSales * (1 + growthRate);
                                        var forecastedSalesLabel = '₱' + forecastedSales;

                                        // Add the data for the next month
                                        data.addRow([nextMonthString, null, forecastedSales]);
                                    }


                                    // Add the weighted average for the Future Sales line
                                    for (var i = 0; i < data.getNumberOfRows(); i++) {
                                        var weightedAverage = calculateWeightedAverage(data, i, baseAlpha);
                                        data.setValue(i, 2, weightedAverage);
                                    }

                                    var options = {
                                        title: 'Sales Forecasting',
                                        titleTextStyle: {
                                            color: '#414141',
                                            fontSize: 28,
                                            bold: true,
                                            fontFamily: 'Arial, Helvetica, sans-serif',
                                        },
                                        curveType: 'function',
                                        legend: {
                                            position: 'bottom'
                                        },
                                        series: {
                                            0: {
                                                pointShape: 'circle',
                                                pointSize: 5,
                                                lineWidth: 2
                                            },
                                            1: {
                                                pointShape: 'circle',
                                                pointSize: 5,
                                                lineWidth: 2
                                            },
                                        },
                                        vAxis: {
                                            format: '₱ ', // Format vertical axis labels as currency
                                        }
                                    };

                                    try {
                                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                                        chart.draw(data, options);
                                    } catch (error) {
                                        console.error('Error drawing the chart:', error);
                                    }
                                }
                            </script>





















                            // var options = {
                            // title: 'Sales Forecasting',
                            // curveType: 'function',
                            // legend: {
                            // position: 'bottom'
                            // },
                            // series: {
                            // 0: {
                            // pointShape: 'circle',
                            // pointSize: 5,
                            // lineWidth: 2
                            // },
                            // 1: {
                            // pointShape: 'circle',
                            // pointSize: 5,
                            // lineWidth: 2
                            // }
                            // },
                            // vAxis: {
                            // format: 'currency'
                            // }
                            // };






                            // Line Chart na may next month
                            <script type="text/javascript">
                                google.charts.load('current', {
                                    'packages': ['corechart']
                                });
                                google.charts.setOnLoadCallback(drawChart);

                                function calculateWeightedAverage(data, currentIndex, alpha) {
                                    var weightedTotal = 0;
                                    var weightSum = 0;

                                    for (var i = currentIndex; i >= 0; i--) {
                                        var weight = Math.pow(alpha, currentIndex - i);
                                        weightedTotal += weight * data.getValue(i, 1);
                                        weightSum += weight;
                                    }

                                    return weightedTotal / weightSum;
                                }

                                function calculateDynamicAlpha(data, currentIndex, baseAlpha, sensitivity) {
                                    // If there's not enough data points to calculate the trend, use the base alpha
                                    if (currentIndex < 2) {
                                        return baseAlpha;
                                    }

                                    var recentTrend = data.getValue(currentIndex, 1) - data.getValue(currentIndex - 1, 1);
                                    var alpha = baseAlpha * (1 + sensitivity * recentTrend);
                                    return Math.max(0, Math.min(1, alpha)); // Ensure alpha is between 0 and 1
                                }

                                function drawChart() {
                                    var data = new google.visualization.DataTable();
                                    data.addColumn('string', 'Month');
                                    data.addColumn('number', 'Current Sales');
                                    data.addColumn('number', 'Forecasted Sales');

                                    var monthsWithData = [];
                                    var latestMonthWithData = null;
                                    var baseAlpha = 0.39; // Move the declaration outside the loop

                                    @for ($i = 1; $i <= 12; $i++)
                                        var month = new Date('{{ date('Y-m', mktime(0, 0, 0, $i, 1)) }}');
                                        var currentYearSales = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                                            ->whereYear('created_at', today()->year)
                                            ->sum(DB::raw('total_price')) ?? 0; ?>;

                                        var previousYearSales = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                                            ->whereYear('created_at', today()->year - 1)
                                            ->sum(DB::raw('total_price')) ?? 0; ?>;

                                        var currentMonthSalesLabel = '₱' + currentYearSales;

                                        if (currentYearSales > 0) {
                                            var forecastedSales = null; // Initialize forecastedSales to null

                                            if (previousYearSales > 0) {
                                                // Compare with the same month of the previous year
                                                var growthRate = (currentYearSales - previousYearSales) / previousYearSales;
                                                forecastedSales = currentYearSales * (1 + growthRate);
                                            } else {
                                                // Handle the case when there are no transactions in the same month last year
                                                // You can set a default growth rate or exclude the month from the forecast
                                                var growthRate = 0; // Set a default growth rate to zero
                                                var forecastedSales = currentYearSales;
                                            }

                                            data.addRow([month.toLocaleString('default', {
                                                month: 'long'
                                            }), currentYearSales, forecastedSales]);
                                            monthsWithData.push(month.toLocaleString('default', {
                                                month: 'long'
                                            }));
                                            latestMonthWithData = month;
                                        }
                                    @endfor

                                    // Include the next month based on the current date
                                    @php
                                        $nextMonth = date('Y-m', strtotime('+1 month'));
                                        $nextMonthString = date('F', strtotime($nextMonth));
                                    @endphp

                                    // Check if the next month is not already in the array before adding it
                                    if (!monthsWithData.includes('{{ $nextMonthString }}')) {
                                        monthsWithData.push('{{ $nextMonthString }}');

                                        var nextMonthSales = <?php echo App\Models\Transaction::whereMonth('created_at', date('m', strtotime($nextMonth)))
                                            ->whereYear('created_at', date('Y', strtotime($nextMonth)))
                                            ->sum(DB::raw('total_price')) ?? 0; ?>;

                                        var previousYearNextMonthSales = <?php echo App\Models\Transaction::whereMonth('created_at', date('m', strtotime($nextMonth)))
                                            ->whereYear('created_at', date('Y', strtotime($nextMonth)) - 1)
                                            ->sum(DB::raw('total_price')) ?? 0; ?>;

                                        var forecastedSalesNextMonth = null;

                                        if (nextMonthSales > 0) {
                                            if (previousYearNextMonthSales > 0) {
                                                // Compare with the same month of the previous year
                                                var growthRateNextMonth = (nextMonthSales - previousYearNextMonthSales) /
                                                    previousYearNextMonthSales;
                                                forecastedSalesNextMonth = nextMonthSales * (1 + growthRateNextMonth);
                                            } else {
                                                // Handle the case when there are no transactions in the same month last year
                                                // You can set a default growth rate or exclude the month from the forecast
                                                var growthRateNextMonth = 0; // Set a default growth rate to zero
                                                forecastedSalesNextMonth = nextMonthSales;
                                            }
                                        }

                                        // Add the data for the next month
                                        data.addRow(['{{ $nextMonthString }}', null, forecastedSalesNextMonth]);
                                    }


                                    // Add the weighted average for the Future Sales line
                                    for (var i = 0; i < data.getNumberOfRows(); i++) {
                                        var weightedAverage = calculateWeightedAverage(data, i, baseAlpha);
                                        data.setValue(i, 2, weightedAverage);
                                    }

                                    var options = {
                                        title: 'Sales Forecasting',
                                        titleTextStyle: {
                                            color: '#414141',
                                            fontSize: 28,
                                            bold: true,
                                            fontFamily: 'Arial, Helvetica, sans-serif',
                                        },
                                        curveType: 'function',
                                        legend: {
                                            position: 'bottom'
                                        },
                                        series: {
                                            0: {
                                                pointShape: 'circle',
                                                pointSize: 5,
                                                lineWidth: 2
                                            },
                                            1: {
                                                pointShape: 'circle',
                                                pointSize: 5,
                                                lineWidth: 2
                                            },
                                        },
                                        vAxis: {
                                            format: '₱ ', // Format vertical axis labels as currency
                                        }
                                    };

                                    try {
                                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                                        chart.draw(data, options);
                                    } catch (error) {
                                        console.error('Error drawing the chart:', error);
                                    }
                                }
                                console.log(month.toLocaleString('default', {
                                    month: 'long'
                                }), currentYearSales, forecastedSales);
                            </script>

                            {{-- Line Chart --}}
                            // Line chart na lahat ng month
                            <script type="text/javascript">
                                google.charts.load('current', {
                                    'packages': ['corechart']
                                });
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() {
                                    var data = new google.visualization.DataTable();
                                    data.addColumn('string', 'Month');
                                    data.addColumn('number', 'Current Sales');
                                    data.addColumn('number', 'Forecasted Sales');

                                    <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                        $month = date('Y-m', mktime(0, 0, 0, $i, 1));
                                        $currentYearSales = App\Models\Transaction::whereMonth('created_at', $i)->whereYear('created_at', date('Y'))->sum('total_price') ?? 0;
                                        $previousYearSales =
                                            App\Models\Transaction::whereMonth('created_at', $i)
                                                ->whereYear('created_at', date('Y') - 1)
                                                ->sum('total_price') ?? 0;
                                        echo "data.addRow(['" . date('F', mktime(0, 0, 0, $i, 1)) . "', $currentYearSales, $previousYearSales]);";
                                    }
                                    ?>

                                    var options = {
                                        title: 'Sales Forecasting',
                                        curveType: 'function',
                                        legend: {
                                            position: 'bottom'
                                        },
                                        series: {
                                            0: {
                                                pointShape: 'circle',
                                                pointSize: 5,
                                                lineWidth: 2
                                            },
                                            1: {
                                                pointShape: 'circle',
                                                pointSize: 5,
                                                lineWidth: 2,
                                                lineDashStyle: [4, 4]
                                            } // This makes the line dotted
                                        },
                                        vAxis: {
                                            format: '₱ '
                                        },
                                        hAxis: {
                                            textStyle: {
                                                fontSize: 12 // Adjust as needed
                                            },
                                            slantedText: true,
                                            slantedTextAngle: 45
                                        },
                                        chartArea: {
                                            width: '90%',
                                            height: '70%'
                                        } // Adjust as needed
                                    };

                                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                                    chart.draw(data, options);
                                }
                            </script>

























                            <script type="text/javascript">
                                google.charts.load('current', {
                                    'packages': ['corechart']
                                });
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() {
                                    var data = new google.visualization.DataTable();
                                    data.addColumn('string', 'Month');
                                    data.addColumn('number', 'Current Sales');
                                    data.addColumn('number', 'Forecasted Sales');

                                    <?php
                                    $lastYearSales = [];
                                    $currentYearSalesData = [];
                                    $forecastedSales = [];
                                    
                                    for ($i = 1; $i <= 12; $i++) {
                                        $currentYearSales = App\Models\Transaction::whereMonth('created_at', $i)->whereYear('created_at', date('Y'))->sum('total_price') ?? 0;
                                        $previousYearSales =
                                            App\Models\Transaction::whereMonth('created_at', $i)
                                                ->whereYear('created_at', date('Y') - 1)
                                                ->sum('total_price') ?? 0;
                                    
                                        $currentYearSalesData[$i] = $currentYearSales;
                                        $lastYearSales[$i] = $previousYearSales;
                                    
                                        if ($i == 1) {
                                            $forecastedSales[$i] = $lastYearSales[$i];
                                        } else {
                                            $lastMonthActualSales = $currentYearSalesData[$i - 1];
                                            $lastMonthForecastedSales = $forecastedSales[$i - 1];
                                            // Check if the current sales and forecasted sales of the previous month are the same
                                            if ($lastMonthActualSales == $lastMonthForecastedSales) {
                                                // Make the forecasted sales for the current month equal to the sales of the same month last year
                                                $forecastedSales[$i] = $lastYearSales[$i];
                                            } elseif ($lastMonthForecastedSales > 0) {
                                                $differenceRatio = ($lastMonthActualSales - $lastMonthForecastedSales) / $lastMonthForecastedSales;
                                                $adjustmentFactor = 1 + $differenceRatio * 0.1;
                                                $forecastedSales[$i] = max(0, $lastYearSales[$i] * $adjustmentFactor);
                                            } else {
                                                $forecastedSales[$i] = $lastYearSales[$i];
                                            }
                                        }
                                    
                                        // Only add the row if there's current year sales data
                                        if ($currentYearSales > 0) {
                                            echo "data.addRow(['" . date('F', mktime(0, 0, 0, $i, 1)) . "', $currentYearSales, $forecastedSales[$i]]);";
                                        }
                                    }
                                    ?>

                                    var options = {
                                        title: 'Sales Forecasting',
                                        curveType: 'function',
                                        legend: {
                                            position: 'bottom'
                                        },
                                        series: {
                                            0: {
                                                pointShape: 'circle',
                                                pointSize: 5,
                                                lineWidth: 2
                                            },
                                            1: {
                                                pointShape: 'circle',
                                                pointSize: 5,
                                                lineWidth: 2,
                                                lineDashStyle: [4, 4]
                                            }
                                        },
                                        vAxis: {
                                            format: '₱ '
                                        },
                                        hAxis: {
                                            textStyle: {
                                                fontSize: 12
                                            },
                                            slantedText: true,
                                            slantedTextAngle: 45
                                        },
                                        chartArea: {
                                            width: '90%',
                                            height: '70%'
                                        }
                                    };

                                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                                    chart.draw(data, options);
                                }
                            </script>
















                            <div class="dropdown">
                                <label for="logout">
                                    @if (auth()->user()->role === 'Admin')
                                        <img class="icon-user" id="logoutBtn"
                                            src="{{ asset('images/icon-user.png') }}" alt="" width="100"
                                            height="auto">
                                    @endif
                                    @if (auth()->user()->role === 'Staff')
                                        {{-- <img class="icon-user" id="logoutBtn" src="{{ asset('images/icon-usersss.png') }}"
                                        alt="" width="100" height="auto"> --}}
                                        <i class="fa-solid fa-circle-user icon-user-staff" id="logoutBtn"
                                            style="color: #006181;"></i>
                                    @endif
                                </label>
                                <input type="checkbox" id="logout">
                                <div class="dropdown-menu" id="dropdownMenu">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                            class="dropdown-item">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                    {{-- <form method="POST" action="{{ route('logout') }}" >
                                    <button type="submit" class="dropdown-item" onclick="event.preventDefault();
                                    this.closest('form').submit();">Log out</button>
                                </form> --}}
                                    {{-- <a method="POST" class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="return confirm('Are you sure you want to log out?')">Log out</a> --}}
                                </div>
                            </div>
























                            {{-- Add Modal Quantity --}}
                            {{-- <div id="newModal" class="modal">
        <div class="modal-content">
            <span class="close closeModal">&times;</span>

            <form class="modal-form" action="{{ route('admin.supplierStore') }}" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-plus"></i>Add Supplier</h2>
                </center>
                <label class="modal-tops" for="">Company Name:</label>
                <input required autofocus type="text" name="company_name" id="autofocus" />
                <label for="">Contact Name:</label>
                <input required type="text" name="contact_name" id="" />
                <label for="">Contact Number:</label>
                <input required type="text" pattern="{5,15}" title="Enter a valid contact number" name="contact_num"
                    id="" value="">
                <label for="">Address:</label>
                <input required type="text" name="address" id="" />

                <label for="">Product:</label>
                <select required class="select_product" name="product_name" id="product_name">
                    <option value="" disabled selected>-- Select a Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->name }}"
                            {{ old('product_name') === $product->name ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                {{-- <input required type="text" name="product_name" id="" /> --}}


                            <input class="add" type="submit" value="Add" />
                            </form>
                            </div>
                            </div> --}}





















                            Lumang Log out

                            <!-- Log out -->
                            <div class="">
                                <label for="">
                                    @if (auth()->user()->role === 'Admin')
                                        {{-- <img class="icon-user" id="" src="{{ asset('images/icon-user.png') }}"
                                        alt="" width="100" height="auto"> --}}
                                        {{-- <img class="icon-user" src="{{ asset($usersss->photo) }}" alt="pic"
                                        width="100" height="auto"> --}}
                                        <img class="icon-user" src="{{ asset($userPhoto) }}" alt=""
                                            width="100" height="auto">


                                        {{-- @foreach ($usersss as $user)
                                    <img src="{{ asset($user->photo) }}" alt="{{ $user->name }}" class="icon-user" width="100" height="auto">
                                @endforeach --}}
                                    @endif
                                    {{-- @if (auth()->user()->role === 'Staff')
                                    
                                    <i class="fa-solid fa-circle-user icon-user-staff" id=""
                                        style="color: #006181;"></i>
                                @endif --}}
                                </label>
                            </div>




                            // Current change profile
                            <div class="">
                                <label for="edit-profile">
                                    {{-- @if (auth()->user()->role === 'Admin') --}}
                                    <img class="icon-user" src="{{ asset($userPhoto) }}" alt=""
                                        width="100" height="auto">
                                    {{-- @endif --}}
                                </label>
                            </div>





                            // transaction-styles.css Product Live Search

                            #productSuggestions {
                            display: none;
                            position: relative; /* Changed from absolute to relative */
                            top: 190px; /* Adjust based on your layout */
                            /* left: 0; */ /* Adjust based on your layout */
                            width: 86%; /* Match the input field width */
                            box-shadow: 0 4px 6px rgba(0,0,0,.1); /* Optional: adds a shadow for better separation */
                            max-height: 200px; /* Limit the height and make it scrollable */
                            overflow-y: auto;
                            border-radius: 0 0 4px 4px; /* Rounded corners at the bottom */
                            color: black;
                            padding: 5px;
                            text-decoration: none;
                            }

                            #productSuggestions:hover {
                            color: black;
                            background-color: transparent;
                            /* top: 150px; */
                            }

                            div#productSuggestions a:hover{
                            background-color: transparent;
                            }





                            // Supplier Product plus
                            <label for="">Product:</label>
                            {{-- <div class="product-plus"> --}}
                            <input required type="text" name="product_name" id="product-supp" />
                            {{-- <a href="#" id="plusSupplier"><i class="fa-regular fa-plus"></i></a> --}}
                            {{-- <button type="submit" id="plusSupplier"><i class="fa-regular fa-plus"></i></button> --}}
                            {{-- <button type="button" id="plusSupplier" onclick="submitFormAndReopenModal()" title="Click to add more product."><i
                            class="fa-regular fa-plus"></i></button> --}}
                            {{-- </div> --}}



                            <input required type="text" name="products[${newProductIndex}]" />





                            @section('modals')
                                {{-- Edit Modal --}}
                                <div id="editModal" class="editModal">
                                    <div class="modal-content">
                                        <a href="{{ route('staff.dashboard') }}"><span
                                                class="close closeEditModal">&times;</span></a>

                                        <form class="edit-modal-form"
                                            action="{{ route('staff.userUpdate', $userss->id) }}"
                                            enctype="multipart/form-data" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <center>
                                                <h2 style="margin: 0%; color:#333;"><i
                                                        class="fa-regular fa-pen-to-square edt-taas"></i>Edit User</h2>
                                            </center>

                                            <label class="label-name" for="name">Name:</label>
                                            <input required autofocus type="text" name="name" id="name"
                                                value="{{ old('name', $userss->name) }}" />

                                            <label for="email">Email:</label>
                                            <input required type="email" name="email" id="email"
                                                value="{{ old('email', $userss->email) }}" />
                                            @if ($errors->has('email'))
                                                <div class="user-text-danger">{{ $errors->first('email') }}</div>
                                            @endif
                                            {{-- <label for="old_password">Old Password:</label>
                                            <input required type="password" name="old_password" id="old_password" /> --}}

                                            <label for="password">New Password:</label>
                                            <input required type="password" name="password" id="password" />
                                            @if ($errors->has('password'))
                                                <div class="user-text-danger">{{ $errors->first('password') }}</div>
                                            @endif
                                            <label for="password_confirmation">Confirm New Password:</label>
                                            <input required type="password" name="password_confirmation"
                                                id="password_confirmation" />

                                            <div class="image">
                                                <div class="image-container">
                                                    <div class="image-column">
                                                        <label for="">Current Image:</label>
                                                        <img class="img-edit" src="{{ asset($userss->photo) }}"
                                                            alt="image" width="50px" height="auto">
                                                    </div>
                                                    <div class="image-column">
                                                        <label for="" class="change-image">Change Image:</label>
                                                        <div class="input_container_edit">
                                                            <input type="file" name="photo" id="fileUpload">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <label for="role">Role:</label>
                                            <select required name="role" id="role">
                                                {{-- <option disabled selected value="">-- Select Role --</option> --}}
                                                {{-- <option disabled value="Admin" {{ $userss->role === 'Admin' ? 'selected' : '' }}>Admin</option> --}}
                                                <option value="Staff" {{ $userss->role === 'Staff' ? 'selected' : '' }}>
                                                    Staff</option>
                                            </select>

                                            <input class="add" type="submit" value="Update" />
                                        </form>
                                    </div>
                                </div>
                            @endsection





                            <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deliveryNames = document.querySelectorAll('.delivery-name');
            const modal = document.getElementById('deliveryModal');
            const modalTable = document.getElementById('modalTable');
            const closeModal = document.querySelector('.closes');

            if (!modal || !closeModal) {
                console.error('Modal or closeModal not found');
                return;
            }

            closeModal.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            if (deliveryNames.length === 0) {
                console.error('No delivery names found');
                return;
            }

            deliveryNames.forEach(name => {
                name.addEventListener('click', function() {
                    const deliveryId = this.getAttribute('data-delivery-id');
                    console.log(`Delivery name clicked: ${deliveryId}`);

                    fetch(`/admin/delivery/details/${deliveryId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', data);

                            // Assuming we have a way to get prices. For example:
                            // const prices = { "Product1": 10, "Product2": 20, ... };
                            const products = JSON.parse(data.product);
                            const quantities = JSON.parse(data.quantity);

                            let tableContent = `
                                <tr>
                                    <th class="id-customer" colspan="3">Delivery ID: ${data.delivery_id}</th>
                                </tr>
                                <tr>
                                    <th class="id-customer" colspan="3">Customer: ${data.customer_name_id}</th>
                                </tr>
                                <tr>
                                    <th class="th-blue first">Product</th>
                                    <th class="th-blue">Quantity</th>
                                    <th class="th-blue">Total Price</th>
                                </tr>
                                `;

                            products.forEach((product, index) => {
                                const quantity = quantities[index];
                                // Placeholder for price calculation, adjust as needed
                                const price =
                                10; // Assuming a fixed price for simplicity; replace with actual price lookup
                                const totalPrice = quantity * price;

                                tableContent += `
                                <tr>
                                    <td>${product}</td>
                                    <td>${quantity}</td>
                                    <td>₱${totalPrice.toFixed(2)}</td>
                                </tr>
                                `;
                            });

                            modalTable.innerHTML = tableContent;
                            modal.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error loading delivery details:', error);
                        });
                });
            });
        });
    </script>
