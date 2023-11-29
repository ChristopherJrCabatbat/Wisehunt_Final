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
            data.addRow(['December', null ,90]);

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
    // <script type="text/javascript">
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
    //         var currentMonthEarnings = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
    //             ->whereYear('created_at', today()->year)
    //             ->sum('total_earned') ?? 0; ?>;
    //         var forecastMonthEarnings = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
    //             ->whereYear('created_at', today()->year + 1)
    //             ->sum('total_earned') ?? 0; ?>;

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
    // </script>


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
                    ->sum('total_earned') ?? 0; ?>;
                var forecastMonthEarnings = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                    ->whereYear('created_at', today()->year + 1)
                    ->sum('total_earned') ?? 0; ?>;

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
                <input required type="text" name="contact_person" id="" />

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
                ->sum(DB::raw('qty * unit_price')) ?? 0; ?>;

            if (currentMonthSales > 0) {
                data.addRow([month.toLocaleString('default', { month: 'long' }), currentMonthSales, null]);
                monthsWithData.push(month.toLocaleString('default', { month: 'long' }));
                latestMonthWithData = month;
            }
        @endfor

        // Include the next month after the latest month with data
        if (latestMonthWithData !== null) {
            var nextMonth = new Date(latestMonthWithData);
            nextMonth.setMonth(nextMonth.getMonth() + 1);
            var nextMonthString = nextMonth.toLocaleString('default', { month: 'long' });

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