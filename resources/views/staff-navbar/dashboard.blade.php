@extends('../layouts.layout')

@section('title', 'Dashboard')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/dashboard-styles.css') }}">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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
            data.addColumn('number', 'Forecasted Sales');

            var monthsWithData = [];
            var latestMonthWithData = null;

            @for ($i = 1; $i <= 12; $i++)
                var month = new Date('{{ date('Y-m', mktime(0, 0, 0, $i, 1)) }}');
                var currentMonthSales = <?php echo App\Models\Transaction::whereMonth('created_at', $i)
                    ->whereYear('created_at', today()->year)
                    ->sum(DB::raw('qty * unit_price')) ?? 0; ?>;

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
                    var forecastedSales = calculateWeightedAverageDynamic(data, data.getNumberOfRows() - 1, dynamicAlpha);
                    var forecastedSalesLabel = '₱' + forecastedSales;

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




    // {{-- Pie Chart --}}
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
                <a class="sidebar top active" href="{{ route('staff.dashboard') }}">
                    <img class="icons-taas" src="{{ asset('images/dashboard-xxl.png') }}" alt="">
                    DASHBOARD</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('staff.product') }}">
                    <img src="{{ asset('images/product-xxl.png') }}" class="product-i" alt="">
                    PRODUCT</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('staff.transaction') }}">
                    <img src="{{ asset('images/transaction.png') }}" class="transaction-i" alt="">
                    TRANSACTION</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('staff.customer') }}">
                    <img src="{{ asset('images/customer.png') }}" class="customer-i" alt="">
                    CUSTOMER</a>
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
                    <div class="zero">₱ {{ number_format($totalEarnings) }}</div>
                    <div class="item-stock">Sales</div>
                    <div class="baba-taasbox">All-Time Total Sales</div>
                </div>
            </div>
        </div>

        {{-- Graph --}}
        <div class="graph">
            <div class="line-graph">
                <div id="curve_chart" style="height: 500px;"></div>
                <div class="sales">Sales</div>
            </div>

            <div class="bar-graph">
                <div class="chart-container">
                    <canvas id="chart" width="1100" height="400"></canvas>
                    <div class="chart-label chart-y-label">Earnings</div>
                </div>
            </div>

            <div class="pie-graph">
                <div id="piechart" style="width: 700px; height: 700px;"></div>
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
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <?php
    $dayTotalSales = App\Models\Transaction::whereDate('created_at', now()->format('Y-m-d'))->sum(DB::raw('qty * unit_price'));
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->format('F j, Y') }}</td>
                        <td> {{ App\Models\Transaction::whereDate('created_at', now()->format('Y-m-d'))->sum('qty') }}
                        </td>
                        <td> {{ App\Models\Transaction::whereDate('created_at', now()->format('Y-m-d'))->count() }}
                        </td>
                        <td>₱ {{ number_format($dayTotalSales) }}
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
                        <th>Total Sales</th>
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
                $weekTotalSales = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum(DB::raw('qty * unit_price'));
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->startOfWeek()->format('F j, Y') }} -
                            {{ now()->endOfWeek()->format('F j, Y') }}</td>
                        <td>{{ $weekQtySold }}</td>
                        <td>{{ $weekTotalTransactions }}</td>
                        <td>₱ {{ number_format($weekTotalSales) }}</td>
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
                        <th>Total Sales</th>
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
                $monthTotalSales = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum(DB::raw('qty * unit_price'));
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->startOfMonth()->format('F, Y') }}</td>
                        <td>{{ $monthQtySold }}</td>
                        <td>{{ $monthTotalTransactions }}</td>
                        <td>₱ {{ number_format($monthTotalSales) }}</td>
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
                        <th>Total Sales</th>
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
                $yearTotalSales = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum(DB::raw('qty * unit_price'));
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->year }}</td>
                        <td>{{ $yearQtySold }}</td>
                        <td>{{ $yearTotalTransactions }}</td>
                        <td>₱ {{ number_format($yearTotalSales) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

@endsection

@section('footer')

@endsection


@section('script')

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
