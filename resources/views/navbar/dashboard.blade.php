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
                <a class="sidebar top active" href="{{ route('admin.dashboard') }}">
                    <img class="icons-taas" src="{{ asset('images/dashboard-xxl.png') }}" alt="">
                    DASHBOARD</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.product') }}">
                    <img src="{{ asset('images/product-xxl.png') }}" class="product-i" alt="">
                    PRODUCT</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.transaction') }}">
                    <img src="{{ asset('images/transaction.png') }}" class="transaction-i" alt="">
                    TRANSACTION</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.customer') }}">
                    <img src="{{ asset('images/customer.png') }}" class="customer-i" alt="">
                    CUSTOMER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.supplier') }}">
                    <img src="{{ asset('images/supplier.png') }}" class="supplier-i" alt="">
                    SUPPLIER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.delivery') }}">
                    <img src="{{ asset('images/delivery.png') }}" class="delivery-i" alt="">
                    DELIVERY</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.user') }}">
                    <i class="fa-solid fa-circle-user user-i" style="color: #ffffff;"></i>
                    {{-- <img src="{{ asset('images/supplier.png') }}" class="user-i" alt=""> --}}
                    USER</a>
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
                <img src="{{ asset('images/sales.png') }}" class="todays-total" alt="" />
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
                    <div class="item-stock">Total Earnings Till Date</div>
                    <div class="baba-taasbox">All-Time Total Earnings</div>
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
                <option value="monthly-transactions">Transaction by Month</option>
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
                <?php
                $dayTotalEarned = App\Models\Transaction::whereDate('created_at', now()->format('Y-m-d'))->sum('profit');
                ?>

                <tbody>
                    <tr>
                        <td>{{ now()->format('F j, Y') }}</td>
                        <td> {{ App\Models\Transaction::whereDate('created_at', now()->format('Y-m-d'))->sum('qty') }}
                        </td>
                        <td> {{ App\Models\Transaction::whereDate('created_at', now()->format('Y-m-d'))->count() }}
                        </td>
                        <td>₱ {{ number_format($dayTotalEarned) }}
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
                $weekTotalEarned = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('profit');
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->startOfWeek()->format('F j, Y') }} -
                            {{ now()->endOfWeek()->format('F j, Y') }}</td>
                        <td>{{ $weekQtySold }}</td>
                        <td>{{ $weekTotalTransactions }}</td>
                        <td>₱ {{ number_format($weekTotalEarned) }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="monthly-transactions">
                <thead>
                    <tr>
                        <th colspan="4" class="th">Transaction by Month</th>
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
                $monthTotalEarned = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('profit');
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->startOfMonth()->format('F, Y') }}</td>
                        <td>{{ $monthQtySold }}</td>
                        <td>{{ $monthTotalTransactions }}</td>
                        <td>₱ {{ number_format($monthTotalEarned) }}</td>
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
                $yearTotalEarned = App\Models\Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('profit');
                ?>
                <tbody>
                    <tr>
                        <td>{{ now()->year }}</td>
                        <td>{{ $yearQtySold }}</td>
                        <td>{{ $yearTotalTransactions }}</td>
                        <td>₱ {{ number_format($yearTotalEarned) }}</td>
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
