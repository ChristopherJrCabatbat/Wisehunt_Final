@extends('../layouts.layout')

@section('title', 'Dashboard')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/dashboard-styles.css') }}">
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

            <div class="graph">
                {{-- <div style="width: 900px; margin: auto;">
                 Alisin ito </div>
                 --}}
                <div class="bar-graph">
                    <div class="chart-container">
                        <canvas id="chart" width="834" height="400"></canvas>
                        {{-- <canvas id="chart" width="1115" height="400"></canvas> --}}
                        <div class="chart-label chart-y-label">Earnings</div>
                    </div>
                </div>
                {{-- <div class="bar-graph">
                    <canvas id="barChart" width="700" height="400"></canvas>
                </div> --}}
                <div class="pie-graph">
                    <canvas id="pieChart" width="800" height="750"></canvas>
                    <div class="demand">Demand</div>
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

    <script></script>

@endsection
