@extends('../layouts.layout')

@section('title', 'Dashboard')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/dashboard-styles.css') }}">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    {{-- Line Chart --}}
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function calculateMovingAverage(data, currentIndex, windowSize) {
        var total = 0;
        for (var i = currentIndex; i > currentIndex - windowSize; i--) {
            if (i >= 0) {
                total += data.getValue(i, 1);
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

        // Fetch data for each month
        @for ($i = 1; $i <= 12; $i++)
            @php
                $monthDate = date('Y-m', mktime(0, 0, 0, $i, 1));
            @endphp

            // Make AJAX request to fetch data for the current month
            $.ajax({
                url: '/admin/get-current-earnings',
                method: 'GET',
                async: false, // Make the request synchronous to handle data order
                data: {
                    month: '{{ $monthDate }}',
                },
                success: function(response) {
                    var currentMonthEarnings = response.data;

                    // Make AJAX request to fetch forecast data for the current month
                    $.ajax({
                        url: '/admin/get-forecast-earnings',
                        method: 'GET',
                        async: false, // Make the request synchronous
                        data: {
                            month: '{{ $monthDate }}',
                        },
                        success: function(response) {
                            var forecastMonthEarnings = response.data;

                            // Add data to the chart
                            data.addRow([ '{{ $monthDate }}', currentMonthEarnings, forecastMonthEarnings ]);
                            monthsWithData.push('{{ $monthDate }}');
                            latestMonthWithData = new Date('{{ $monthDate }}');
                        },
                        error: function(error) {
                            console.error('Error fetching forecast month earnings:', error);
                        }
                    });
                },
                error: function(error) {
                    console.error('Error fetching current month earnings:', error);
                }
            });
        @endfor

        // Include the next month after the latest month with data
        if (latestMonthWithData !== null) {
            var nextMonth = new Date(latestMonthWithData);
            nextMonth.setMonth(nextMonth.getMonth() + 1);
            var nextMonthString = nextMonth.toLocaleString('default', {
                month: 'long'
            });

            // Make AJAX request to fetch forecast for the next month
            $.ajax({
                url: '/admin/get-forecast-earnings',
                method: 'GET',
                async: false, // Make the request synchronous
                data: {
                    month: '{{ $monthDate }}',
                },
                success: function(response) {
                    var nextMonthForecast = response.data;

                    // Check if the next month is not already in the array before adding it
                    if (!monthsWithData.includes(nextMonthString)) {
                        monthsWithData.push(nextMonthString);

                        // Add the data for the next month
                        data.addRow([nextMonthString, null, nextMonthForecast]);
                    }
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
    }
</script>

    {{-- Line Chart
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

            // Fetch data for each month
            // Fetch data for each month
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $monthDate = date('Y-m', mktime(0, 0, 0, $i, 1));
                @endphp

                var month = new Date('{{ $monthDate }}');

                // Make AJAX request to fetch current month earnings
                var currentMonthEarnings = 0;
                $.ajax({
                    url: '/admin/get-current-earnings', // Updated URL with /admin prefix
                    method: 'GET',
                    data: {
                        month: '{{ $monthDate }}',
                    },
                    success: function(response) {
                        currentMonthEarnings = response.data;
                    },
                    error: function(error) {
                        console.error('Error fetching current month earnings:', error);
                    }
                });

                // Make AJAX request to fetch forecast month earnings for the next year
                var forecastMonthEarnings = 0;
                $.ajax({
                    url: '/admin/get-forecast-earnings', // Updated URL with /admin prefix
                    method: 'GET',
                    data: {
                        month: '{{ $monthDate }}',
                    },
                    success: function(response) {
                        forecastMonthEarnings = response.data;
                    },
                    error: function(error) {
                        console.error('Error fetching forecast month earnings:', error);
                    }
                });
            @endfor

            // Include the next month after the latest month with data
            if (latestMonthWithData !== null) {
                var nextMonth = new Date(latestMonthWithData);
                nextMonth.setMonth(nextMonth.getMonth() + 1);
                var nextMonthString = nextMonth.toLocaleString('default', {
                    month: 'long'
                });

                // Make AJAX request to fetch forecast for the next month
                var nextMonthForecast = 0; // Replace with your actual AJAX logic

                // Check if the next month is not already in the array before adding it
                if (!monthsWithData.includes(nextMonthString)) {
                    monthsWithData.push(nextMonthString);

                    // Add the data for the next month
                    data.addRow([nextMonthString, null, nextMonthForecast]);
                }
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
        }
    </script> --}}






    {{-- Pie Chart --}}
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Product', 'Quantity'],
                <?php echo $pieChartData; ?>
            ]);

            var options = {
                title: 'High in Demand Products',
                //   is3D:true,
                //   pieHole: 0.4,
                titleTextStyle: {
                    color: '#414141',
                    fontSize: 28,
                    bold: true,
                    fontFamily: 'Arial, Helvetica, sans-serif',
                },
                backgroundColor: '#f0f0f0',
                slices: {
                    0: {
                        color: '#2c5c78'
                    },
                    1: {
                        color: '#2dc0d0'
                    },
                    2: {
                        color: '#6c6c6c'
                    },
                    3: {
                        color: '#050A30'
                    },
                    4: {
                        color: '#0000FF'
                    }
                }

            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>
@endsection

@section('side-navbar')

    <ul>
        <li>
            <div class="dashboard-container">
                <img class="icons-taas" src="{{ asset('images/dashboard-xxl.png') }}" alt="">
                <a href="{{ route('admin.dashboard') }}" class="sidebar top active">DASHBOARD</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <img src="{{ asset('images/product-xxl.png') }}" class="product-i" alt="">
                <a class="sidebar" href="{{ route('admin.product') }}">PRODUCT</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <img src="{{ asset('images/transaction.png') }}" class="transaction-i" alt="">
                <a class="sidebar" href="{{ route('admin.transaction') }}">TRANSACTION</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <img src="{{ asset('images/customer.png') }}" class="customer-i" alt="">
                <a class="sidebar" href="{{ route('admin.customer') }}">CUSTOMER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <img src="{{ asset('images/supplier.png') }}" class="supplier-i" alt="">
                <a class="sidebar" href="{{ route('admin.supplier') }}">SUPPLIER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <img src="{{ asset('images/supplier.png') }}" class="user-i" alt="">
                <a class="sidebar" href="{{ route('admin.supplier') }}">USERS</a>
            </div>
        </li>
    </ul>

@endsection

@section('main-content')

    <div class="content-dashboard">

        <div class="taas-content">
            <div class="taasbox-dashboard">
                <img src="{{ asset('images/product-xxlss.png') }}" class="product" alt="" />
                <div class="loob-box">
                    <div class="zero">{{ $productCount }}</div>
                    <div class="item-stock">Items in Stock</div>
                    <div class="baba-taasbox">Total Items in Stock</div>
                </div>
            </div>
            <div class="taasbox-dashboard">
                <img src="{{ asset('images/sales.png') }}" class="product" alt="" />
                <div class="loob-box">
                    <div class="zero">{{ $totalSalesQty }}</div>
                    <div class="item-stock">Today's Total Sales</div>
                    <div class="baba-taasbox">Number of Items Sold Today</div>
                </div>
            </div>
            <div class="taasbox-dashboard">
                <img src="{{ asset('images/transactions.png') }}" class="product" alt="" />
                <div class="loob-box">
                    <div class="zero">{{ $transactionCount }}</div>
                    <div class="item-stock">Total Transactions</div>
                    <div class="baba-taasbox">All-Time Total Transactions</div>
                </div>
            </div>
            <div class="taasbox-dashboard">
                <img src="{{ asset('images/earning.png') }}" class="product earn" alt="" />
                <div class="loob-box">
                    <div class="zero">₱ {{ $totalEarnings }}</div>
                    <div class="item-stock">Total Earnings Till Date</div>
                    <div class="baba-taasbox">All-Time Total Earnings</div>
                </div>
            </div>
        </div>

        {{-- Graph --}}
        <div class="graph">
            <div class="line-graph">
                <div id="curve_chart" style="height: 500px;"></div>
                <div class="chart-label chart-y-label">Earnings</div>
            </div>

            <div class="bar-graph">
                <div class="chart-container">
                    <canvas id="chart" width="1100" height="400"></canvas>
                    {{-- <canvas id="chart" width="834" height="400"></canvas> --}}
                    <div class="chart-label chart-y-label">Earnings</div>
                </div>
            </div>

            <div class="pie-graph">
                <div id="piechart" style="width: 700px; height: 700px;"></div>

                {{-- <canvas id="pieChart" width="800" height="750"></canvas> --}}
                {{-- <canvas id="pieChart" width="260px" height="260px"></canvas> --}}
                {{-- <div class="demand">High in Demand Products</div> --}}
            </div>

        </div>


        <div class="box-pinakababa">
            <select name="transactions" id="transactions">
                <option value="daily-transactions">Daily Transaction</option>
                <option value="weekly-transactions">Transaction by Weeks</option>
                <option value="monthly-transactions">Transaction by Months</option>
                <option value="yearly-transactions">Transaction by Year</option>
            </select>

            <table class="daily-transactions">
                <thead>
                    <tr>
                        <th colspan="4" class="th">Daily Transactions</th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Quantity Sold</th>
                        <th>Total Transactions</th>
                        <th>Total Earned</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ now()->format('F j, Y') }}</td>
                        <td> {{ App\Models\Transaction::whereDate('created_at', now()->format('Y-m-d'))->sum('qty') }}
                        </td>
                        <td> {{ App\Models\Transaction::whereDate('created_at', now()->format('Y-m-d'))->count() }}
                        </td>
                        <td>{{ App\Models\Transaction::whereDate('created_at', now()->format('Y-m-d'))->sum('total_earned') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="weekly-transactions">
                <thead>
                    <tr>
                        <th colspan="4" class="th">Transaction by Weeks</th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Quantity Sold</th>
                        <th>Total Transactions</th>
                        <th>Total Earned</th>
                    </tr>
                </thead>
                <?php
                // Calculate the start and end date for the current week
                $startDate = now()
                    ->startOfWeek()
                    ->format('Y-m-d');
                $endDate = now()
                    ->endOfWeek()
                    ->format('Y-m-d');
                
                // Query the database to get data for the current week
                $weekQtySold = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('qty');
                $weekTotalTransactions = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
                $weekTotalEarned = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_earned');
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->startOfWeek()->format('F j, Y') }} -
                            {{ now()->endOfWeek()->format('F j, Y') }}</td>
                        <td>{{ $weekQtySold }}</td>
                        <td>{{ $weekTotalTransactions }}</td>
                        <td>{{ $weekTotalEarned }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="monthly-transactions">
                <thead>
                    <tr>
                        <th colspan="4" class="th">Transaction by Months</th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Quantity Sold</th>
                        <th>Total Transactions</th>
                        <th>Total Earned</th>
                    </tr>
                </thead>
                <?php
                // Calculate the start and end date for the current month
                $startDate = now()
                    ->startOfMonth()
                    ->format('Y-m-d');
                $endDate = now()
                    ->endOfMonth()
                    ->format('Y-m-d');
                
                // Query the database to get data for the current month
                $monthQtySold = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('qty');
                $monthTotalTransactions = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
                $monthTotalEarned = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_earned');
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->startOfMonth()->format('F, Y') }}</td>
                        <td>{{ $monthQtySold }}</td>
                        <td>{{ $monthTotalTransactions }}</td>
                        <td>{{ $monthTotalEarned }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="yearly-transactions">
                <thead>
                    <tr>
                        <th colspan="4" class="th">Transaction by Year</th>
                    </tr>
                    <tr>
                        <th>Year</th>
                        <th>Quantity Sold</th>
                        <th>Total Transactions</th>
                        <th>Total Earned</th>
                    </tr>
                </thead>
                <?php
                // Calculate the start and end date for the current year
                $startDate = now()
                    ->startOfYear()
                    ->format('Y-m-d');
                $endDate = now()
                    ->endOfYear()
                    ->format('Y-m-d');
                
                // Query the database to get data for the current year
                $yearQtySold = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('qty');
                $yearTotalTransactions = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
                $yearTotalEarned = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_earned');
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->year }}</td>
                        <td>{{ $yearQtySold }}</td>
                        <td>{{ $yearTotalTransactions }}</td>
                        <td>{{ $yearTotalEarned }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

@endsection

@section('footer')

@endsection


@section('script')
    {{-- var productLabels = {!! json_encode($productLabels) !!};
     var productDatasets = {!! json_encode($productDatasets) !!}; --}}

    {{-- <script>
        fetch('/get-monthly-earnings-forecast')
            .then(response => response.json())
            .then(data => { 
                // Data received, create the line chart
                createLineChart(data.forecastedSales);
            })
            .catch(error => console.error('Error fetching data:', error));

        function createLineChart(forecastedSales) {
            // Assuming you have a canvas element with id="monthlyEarningsChart"
            var ctx = document.getElementById('monthlyEarningsChart').getContext('2d');

            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Current Month', 'Next Month'],
                    datasets: [{
                        label: 'Monthly Earnings Forecast',
                        borderColor: 'rgb(75, 192, 192)',
                        data: [getCurrentMonthEarnings(), forecastedSales],
                    }],
                },
            });
        }

        function getCurrentMonthEarnings() {
            // You might need to fetch or calculate the current month earnings here
            // For simplicity, assume it's 0 for the current example
            return 0;
        }
    </script> --}}

    <script>
        var labels = {!! json_encode($labels) !!};
        var datasets = {!! json_encode($datasets) !!};
    </script>

    <script src="{{ asset('js/dashboardTable.js') }}"></script>
    <script src="{{ asset('js/chart.js') }}"></script>

    <script>
        // Function to automatically close the notification panel after 5 seconds
        function autoCloseNotificationPanel() {
            const notificationPanel = document.getElementById('notificationPanel');

            // Show the notification panel
            notificationPanel.style.display = 'block';

            // Set a timeout to close the notification panel after 5 seconds
            setTimeout(function() {
                closeNotification();
            }, 10000);
        }

        // Call the function when the page loads (adjust this based on your actual login logic)
        document.addEventListener("DOMContentLoaded", function() {
            // Check if the login was successful
            const loginSuccess = {!! json_encode(Session::pull('login_success', false)) !!};

            if (loginSuccess) {
                // Call the function to show the notification panel and automatically close it
                autoCloseNotificationPanel();
            }
        });

        // Function to toggle the visibility of the notification panel
        function toggleNotificationPanel() {
            const notificationPanel = document.getElementById('notificationPanel');
            notificationPanelVisible = !notificationPanelVisible; // Toggle the visibility state

            if (notificationPanelVisible) {
                notificationPanel.style.display = 'block'; // Show the notification panel
            } else {
                // Do not hide the notification panel here
            }

            // If the panel is manually toggled, do not automatically close it
            // Do not set a timeout here
        }

        // Function to close the notification panel
        function closeNotification() {
            const notificationPanel = document.getElementById('notificationPanel');
            notificationPanelVisible = false; // Set the visibility state explicitly to false
            notificationPanel.style.display = 'none';
        }
    </script>

@endsection
