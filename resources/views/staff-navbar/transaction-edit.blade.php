@extends('../layouts.layout')

@section('title', 'Transaction')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay"></div>

    {{-- Edit Modal --}}
    <div id="editModal" class="editModal">
        <div class="modal-content">
            <a class="close closeModal" href="{{ route('staff.transaction') }}">&times;</a>

            <form class="modal-form" action="{{ route('staff.transactionUpdate', $transactionss->id) }}" method="POST">
                @csrf
                @method('PUT')

                <center>
                    <h2 style="margin: 0%; color:#333;">Edit Transaction</h2>
                </center>

                <label class="baba-h2 taas-select" for="customer">Customer:</label>
                <select class="select autofocus" name="customer_name" id="customer-edit">

                    @foreach ($customers as $customer)
                        <option value="{{ $customer->name }}" data-contact="{{ $customer->contact_num }}"
                            {{ old('customer_name', $transactionss->customer_name) === $customer->name ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>

                <label for="product_name" class="taas-select">Product:</label>
                <select class="select product-select" name="product_name" id="product_name-edit"
                    onchange="updateUnitPrice('editModal')">
                    @foreach ($products as $product)
                        <option value="{{ $product->name }}" data-unit-price="{{ $product->selling_price }}"
                            {{ old('product_name', $transactionss->product_name) === $product->name ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                <div class="text-danger">{{ $errors->first('error') }}</div>

                <label for="">Quantity:</label>
                <input required id="qty-edit" class="qty" name="qty" type="number"
                    value="{{ old('qty', $transactionss->qty) }}">
                <div class="text-danger">{{ $errors->first('qty') }}</div>
                <!-- Display error for insufficient quantity in stock -->
                @if ($errors->has('error_stock'))
                    <div class="text-danger-stock">{{ $errors->first('error_stock') }}</div>
                @endif

                <label for="selling_price">Unit Price:</label>
                <input readonly required name="selling_price" class="selling_price" id="selling_price-edit" type="number"
                    value="{{ old('selling_price', $transactionss->selling_price) }}">

                <label for="total_price">Total Price:</label>
                <input readonly name="total_price" id="total_price_edit" type="number"
                    value="{{ old('total_price', $transactionss->total_price) }}" class="total_price">

                <input type="submit" id="button-transac" value="Update">
            </form>
        </div>

    </div>

@endsection

@section('side-navbar')

    <ul>
        <li>
            <div class="dashboard-container">
                <a class="sidebar top" href="{{ route('staff.dashboard') }}">
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
                <a class="sidebar active" href="{{ route('staff.transaction') }}">
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
    <div class="content">
        <div class="taas">

            <div class="new-generate">
                <button type="button" id="newButton">Add Transaction</button>

                <form action="" id="generateReportForm">
                    <button type="button" id="generateReportBtn">Generate Report</button>
                </form>
            </div>

            <div class="sort-by">
                <form id="sortForm" action="#" method="GET">
                    <input type="hidden" name="sort" id="sortInput" value="{{ request('sort') }}">

                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sortSelect">
                        <option selected value="" {{ request('sort') === '' ? 'selected' : '' }}>--
                            Default Sorting --</option>
                        <option value="customer_name_asc" {{ request('sort') === 'customer_name_asc' ? 'selected' : '' }}>
                            Customer
                            (A-Z)</option>
                        <option value="product_name_asc" {{ request('sort') === 'product_name_asc' ? 'selected' : '' }}>
                            Product Name (A-Z)
                        </option>
                        <option value="qty_asc" {{ request('sort') === 'qty_asc' ? 'selected' : '' }}>Quantity
                            (ascending)</option>
                        <option value="selling_price_asc" {{ request('sort') === 'selling_price_asc' ? 'selected' : '' }}>Unit
                            Price (ascending)
                        </option>
                        <option value="total_price_asc" {{ request('sort') === 'total_price_asc' ? 'selected' : '' }}>
                            Total
                            Price
                            (ascending)</option>
                        <option value="profit_asc" {{ request('sort') === 'profit_asc' ? 'selected' : '' }}>
                            Total Earned on Item
                            (descending)</option>
                    </select>
                </form>
            </div>

            {{-- Search --}}
            <div>
                <div class="searchs">
                    <div class="form-search">
                        <input type="text" name="search" id="search" required class="search-prod"
                            placeholder="Search product..." value="{{ $searchQuery }}" />
                        <i class="fa fa-search search-icon"></i>
                    </div>
                </div>
            </div>

        </div>

        <div class="table" id="search-results">
            <table>
                <tr>
                    <th colspan="13" class="table-th">TRANSACTIONS</th>
                </tr>
                @php
                    $rowNumber = ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                @endphp

                <tr>
                    <th>No.</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Total Earn</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>

                <tbody class="all-data">
                    @if ($transactions->isEmpty())
                        <tr>
                            <td colspan="13">No data found.</td>
                        </tr>
                    @else
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $transaction->customer_name }}</td>
                                <td>{{ $transaction->product_name }}</td>
                                <td>{{ $transaction->qty }}</td>
                                <td>₱ {{ $transaction->selling_price }}</td>
                                <td>₱ {{ $transaction->total_price }}</td>
                                <td>₱ {{ $transaction->profit }}</td>
                                <td>{{ optional($transaction->created_at)->format('M. d, Y') }}</td>
                                <td class="actions">
                                    <div class="actions-container">
                                        <form action="{{ route('staff.transactionEdit', $transaction->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" class="edit" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('staff.transactionDestroy', $transaction->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this?')"
                                                type="submit" class="delete" id="delete">
                                                <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tbody id="content" class="search-data"></tbody>

            </table>

            <div class="pagination">
                {{ $transactions->appends(['sort' => request('sort')])->links('layouts.customPagination') }}</div>

        </div>

    </div>
@endsection

@section('footer')

@endsection

@section('script')
    <script src="{{ asset('js/generateReport.js') }}"></script>

    @if (session('forecastedSalesAlert'))
        <script>
            var forecastedSales = {!! json_encode(session('forecastedSalesAlert'), JSON_HEX_TAG) !!};
            alert(forecastedSales);
        </script>
    @endif

    @if (session('monthlyForecastedSalesAlert'))
        <script>
            var monthlyForecastedSales = {!! json_encode(session('monthlyForecastedSalesAlert'), JSON_HEX_TAG) !!};
            alert(monthlyForecastedSales);
        </script>
    @endif

    {{-- Auto Sorting --}}
    <script>
        // Automatically submit the form when the sorting option changes
        document.getElementById('sortSelect').addEventListener('change', function() {
            document.getElementById('sortForm').submit();
        });
    </script>

    {{-- Live Search --}}
    <script type="text/javascript">
        $('#search').on('input', function() {

            const contentContainer = $('#content');
            $value = $(this).val();

            if ($value) {
                $('.all-data').hide();
                $('.search-data').show();
            } else {
                $('.all-data').show();
                $('.search-data').hide();
            }

            // Clear the existing results instantly
            contentContainer.html('');

            $.ajax({
                type: 'get',
                url: '{{ route('staff.transactionSearch') }}',
                data: {
                    'search': $value
                },
                success: function(data) {
                    console.log(data);
                    if (data.trim() === "") {
                        contentContainer.html(
                            '<tr><td colspan="11" class="id">No Result Found</td></tr>');
                    } else {
                        contentContainer.html(data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    </script>

    <!-- Auto Unit Price Script -->
    <script>
        function updateUnitPrice(elementId) {
            var productSelect = document.querySelector('#' + elementId + ' .product-select');
            var unitPriceInput = document.querySelector('#' + elementId + ' .selling_price');

            var selectedPrice = productSelect.options[productSelect.selectedIndex];
            var unitPrice = selectedPrice.getAttribute('data-unit-price');

            unitPriceInput.value = unitPrice;
        }
    </script>



    {{-- Auto Total Price --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select the quantity input field
            var qtyInputs = document.querySelectorAll('.qty');

            qtyInputs.forEach(function(qtyInput) {
                // Add an event listener for the 'input' event on the quantity input field
                qtyInput.addEventListener('input', function() {
                    // Get the quantity value
                    var qty = parseFloat(qtyInput.value) || 0;

                    // Get the unit price value from the selected product
                    var productSelect = qtyInput.closest('.modal-content').querySelector(
                        '.product-select');
                    var unitPrice = parseFloat(productSelect.options[productSelect.selectedIndex]
                        .getAttribute('data-unit-price')) || 0;

                    // Calculate the total price
                    var totalPrice = qty * unitPrice;

                    // Update the total price input field
                    var totalPriceInput = qtyInput.closest('.modal-content').querySelector(
                        '.total_price');
                    totalPriceInput.value = totalPrice.toFixed(2);
                });
            });
        });
    </script>


@endsection
