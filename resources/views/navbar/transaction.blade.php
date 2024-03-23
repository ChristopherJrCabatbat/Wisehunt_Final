@extends('../layouts.layout')

@section('title', 'Transaction')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <a class="close closeModal">&times;</a>

            <form class="modal-form" action="{{ route('admin.transactionStore') }}" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-plus"></i>Add Transaction</h2>
                </center>

                <label class="baba-h2 taas-select" for="customer">Customer:</label>
                <select required name="customer_name" id="autofocus" class="select customer">
                    <option value="" disabled selected>-- Select a Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->name }}" data-contact="{{ $customer->contact_num }}"
                            {{ old('customer_name') === $customer->name ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>

                {{-- <label for="product_name" class="taas-select">Product:</label>
                <select required name="product_name" id="product_name" class="select product_name product-select"
                    onchange="updateUnitPrice('newModal')">
                    <option value="" disabled selected>-- Select a Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->name }}" data-unit-price="{{ $product->selling_price }}"
                            {{ old('product_name') === $product->name ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select> --}}

                {{-- <label for="product_name" class="taas-select">Product:</label>
                <input class="form-control product_name" id="product_name" name="product_name" type="text"
                    placeholder="Type to search..." autocomplete="off">

                <div id="loadingIndicator" style="display: none;">Loading...</div>
                <div id="productSuggestions"
                    style="position: absolute; z-index: 1000; background: white; border: 1px solid #ccc;">
                </div> --}}

                <label for="product_name">Product:</label>
                <input class="form-control" id="product_name" name="product_name" type="text"
                    placeholder="Type to search..." autocomplete="off">

                <div id="productSuggestions" class="suggestions-dropdown">
                    <!-- Search suggestions will be appended here -->
                </div>

                <!-- Display validation error for product_name -->
                <div class="text-danger">{{ $errors->first('product_name') }}</div>


                <label for="">Quantity:</label>
                <input required name="qty" class="qty" type="number" id="qty" value="{{ old('qty') }}">

                <!-- Display validation error for qty -->
                <div class="text-danger">{{ $errors->first('qty') }}</div>

                <!-- Display error for insufficient quantity in stock -->
                @if ($errors->has('error_stock'))
                    <div class="text-danger-stock">{{ $errors->first('error_stock') }}</div>
                @endif


                <label for="selling_price">Price:</label>
                <input readonly required name="selling_price" id="selling_price" type="number"
                    value="{{ old('selling_price') }}" class="selling_price">

                <label for="selling_price">Total Price:</label>
                <input readonly name="total_price" id="total_price" type="number" value="{{ $totalPrice }}"
                    class="total_price">

                <input type="submit" value="Add" id="button-transac">
            </form>

        </div>
    </div>




    {{-- Report Modal --}}
    <div id="reportModal" class="reportModal">
        <div class="modal-content-report">
            <span class="close">&times;</span>

            <form class="modal-form" action="{{ route('admin.generateReport') }}" method="POST" target="_blank">
                @csrf
                <label class="modal-top" for="">Generate Report</label>
                <hr>
                <div class="row-report">
                    <div class="column-report">
                        <label for="">From Date:</label>
                        <input type="date" required name="from_date">
                    </div>
                    <div class="column-report">
                        <label for="">To Date:</label>
                        <input type="date" required name="to_date">
                    </div>
                </div>

                <label class="baba-h2 taas-select" for="customer">Customer:</label>
                <select required name="customer_name" class="select customer report">
                    <option value="" disabled selected>-- Select a Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->name }}"
                            {{ old('customer_name') === $customer->name ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                <hr>
                <div class="buttons-report">
                    <button type="submit">Generate</button>
                </div>
            </form>

        </div>
    </div>

@endsection

@section('side-navbar')

    <ul>
        <li>
            <div class="dashboard-container">
                <a class="sidebar top" href="{{ route('admin.dashboard') }}">
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
                <a class="sidebar active" href="{{ route('admin.transaction') }}">
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
                    USER</a>
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
                            Customer</option>
                        <option value="product_name_asc" {{ request('sort') === 'product_name_asc' ? 'selected' : '' }}>
                            Product</option>
                        <option value="qty_asc" {{ request('sort') === 'qty_asc' ? 'selected' : '' }}>Quantity</option>
                        <option value="selling_price_asc" {{ request('sort') === 'selling_price_asc' ? 'selected' : '' }}>
                            Price
                        </option>
                        <option value="total_price_asc" {{ request('sort') === 'total_price_asc' ? 'selected' : '' }}>
                            Total Price</option>
                        <option value="profit_asc" {{ request('sort') === 'profit_asc' ? 'selected' : '' }}>
                            Profit</option>

                        <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>
                            Date (Ascending)</option>
                        <option value="date_desc" {{ request('sort') === 'date_desc' ? 'selected' : '' }}>
                            Date (Descending)</option>

                    </select>
                </form>
            </div>

            {{-- Search --}}
            <div>
                <div class="searchs">
                    <div class="form-search">
                        <input type="text" name="search" id="search" required class="search-prod"
                            placeholder="Search transactions..." value="{{ $searchQuery }}" />
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
                    <th>Price</th>
                    <th>Total Price</th>
                    <th>Profit</th>
                    <th>Date</th>
                    {{-- <th>Actions</th> --}}
                </tr>

                <tbody class="all-data">
                    @if ($transactions->isEmpty())
                        <tr>
                            <td colspan="13">No data found.</td>
                        </tr>
                    @else
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td class="transcact-td">{{ $rowNumber++ }}</td>
                                <td class="transcact-td">{{ $transaction->customer_name }}</td>
                                <td class="transcact-td">{{ $transaction->product_name }}</td>
                                <td class="transcact-td">{{ $transaction->qty }}</td>
                                <td class="nowrap transcact-td">₱ {{ number_format($transaction->selling_price) }}</td>
                                <td class="nowrap transcact-td">₱ {{ number_format($transaction->total_price) }}</td>
                                <td class="nowrap transcact-td">₱ {{ number_format($transaction->profit) }}</td>
                                <td>{{ optional($transaction->created_at)->format('M. d, Y') }}</td>
                                {{-- <td class="actions">
                                    <div class="actions-container">
                                        <form action="{{ route('admin.transactionEdit', $transaction->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" class="edit" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.transactionDestroy', $transaction->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this?')"
                                                type="submit" class="delete" id="delete">
                                                <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td> --}}
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
    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
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
    {{-- <script type="text/javascript">
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
                url: '{{ route('admin.transactionSearch') }}',
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
    </script> --}}

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
                url: '{{ route('admin.transactionSearch') }}',
                data: {
                    'search': $value
                },
                success: function(data) {
                    console.log('AJAX Success:', data);
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


    <!-- Auto Price Script -->
    {{-- <script>
        function updateUnitPrice(elementId) {
            var productSelect = document.querySelector('#' + elementId + ' .product-select');
            var unitPriceInput = document.querySelector('#' + elementId + ' .selling_price');

            var selectedPrice = productSelect.options[productSelect.selectedIndex];
            var unitPrice = selectedPrice.getAttribute('data-unit-price');

            unitPriceInput.value = unitPrice;
        }
    </script> --}}


    {{-- Auto Total Price --}}
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select the quantity input field
            var qtyInput = document.querySelector('.qty'); // Assuming there's only one qty input for simplicity

            // Function to calculate and update total price
            function updateTotalPrice() {
                var qty = parseFloat(qtyInput.value) || 0; // Get the quantity value or 0 if not a number
                var sellingPrice = parseFloat(document.getElementById('selling_price').value) ||
                    0; // Get the selling price or 0 if not a number

                var totalPrice = qty * sellingPrice; // Calculate the total price

                document.getElementById('total_price').value = totalPrice.toFixed(
                    2); // Update the total price input field
            }

            // Listen for input changes on the quantity field
            qtyInput.addEventListener('input', updateTotalPrice);

            // You should also update the total price when a product is selected from the suggestions
            $(document).on('click', '#productSuggestions .list-group-item', function(e) {
                // Wait for the selling_price to be updated
                setTimeout(updateTotalPrice, 0);
            });
        });
    </script> --}}

    {{-- <script>
        $(document).ready(function() {
            var debounceTimer;
            $('#product_name').on('input', function() {
                var query = $(this).val();

                // Clear the current debounce timer to reset the delay
                clearTimeout(debounceTimer);

                if (query.length < 1) {
                    $('#productSuggestions').html('');
                    return;
                }

                // Show loading indicator or message
                $('#loadingIndicator').show();

                // Set a new debounce timer
                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: '{{ route('admin.searchProduct') }}', // Make sure this is correct in a .js file or use the full path
                        type: 'GET',
                        data: {
                            'query': query
                        },
                        success: function(data) {
                            // Hide loading indicator or message
                            $('#loadingIndicator').hide();

                            $('#productSuggestions').empty();
                            if (data.length > 0) { // Check if there are any products
                                $.each(data, function(index, product) {
                                    $('#productSuggestions').append(
                                        '<a href="#" class="list-group-item list-group-item-action" data-name="' +
                                        product.value + '" data-price="' +
                                        product.selling_price + '">' +
                                        product
                                        .value + '</a>');
                                });
                                $('#productSuggestions')
                                    .show(); // Show suggestions if there are products
                            } else {
                                $('#productSuggestions')
                                    .hide(); // Hide suggestions if there are no products
                            }
                        }
                    });
                }, 250);
            });

            // Hide the product suggestions when input loses focus but delay it to allow for suggestion click
            $('#product_name').blur(function() {
                setTimeout(function() {
                    $('#productSuggestions').hide();
                }, 200); // Delay hiding to allow click event on suggestions to be processed
            });

            $(document).on('click', '#productSuggestions .list-group-item', function(e) {
                e.preventDefault();
                var productName = $(this).data('name');
                var productPrice = $(this).data('price');

                $('#product_name').val(productName);
                $('#selling_price').val(productPrice);
                $('#productSuggestions').html('').hide();

                // Additional code as needed
            });
        });
    </script> --}}

    {{-- Live Search Product --}}
    
    {{-- <script>
        $(document).ready(function() {
            var debounceTimer;
            $('#product_name').on('input', function() {
                var query = $(this).val();

                clearTimeout(debounceTimer);

                if (query.length < 1) {
                    $('#productSuggestions').html('').hide();
                    return;
                }

                $('#productSuggestions').show(); // Optional: Show loading indicator

                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: '{{ route('admin.searchProduct') }}',
                        type: 'GET',
                        data: {
                            'query': query
                        },
                        success: function(data) {
                            $('#productSuggestions').empty();
                            if (data.length > 0) {
                                data.forEach(function(product) {
                                    $('#productSuggestions').append(
                                        '<div class="suggestion-item" data-value="' +
                                        product.value + '">' + product
                                        .value + '</div>');
                                });
                            } else {
                                $('#productSuggestions').append(
                                    '<div class="suggestion-item">No results found</div>'
                                );
                            }
                        }
                    });
                }, 250); // Adjust debounce time as needed
            });

            $(document).on('click', '.suggestion-item', function() {
                var selectedProduct = $(this).data('value');
                $('#product_name').val(selectedProduct);
                $('#productSuggestions').empty().hide();
            });

            // Optional: Hide suggestions when clicking outside
            $(document).mouseup(function(e) {
                var container = $("#productSuggestions");
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.hide();
                }
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            var debounceTimer;
            $('#product_name').on('input', function() {
                var query = $(this).val();
    
                clearTimeout(debounceTimer);
    
                if (query.length < 1) {
                    $('#productSuggestions').html('').hide();
                    return;
                }
    
                $('#productSuggestions').show(); // Optional: Show loading indicator
    
                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: '{{ route('admin.searchProduct') }}',
                        type: 'GET',
                        data: {
                            'query': query
                        },
                        success: function(data) {
                            $('#productSuggestions').empty();
                            if (data.length > 0) {
                                data.forEach(function(product) {
                                    $('#productSuggestions').append(
                                        `<div class="suggestion-item" data-value="${product.value}" data-unit-price="${product.selling_price}">${product.value}</div>`
                                    );
                                });
                            } else {
                                $('#productSuggestions').append(
                                    '<div class="suggestion-item">No results found</div>'
                                );
                            }
                        }
                    });
                }, 250); // Adjust debounce time as needed
            });
    
            $(document).on('click', '.suggestion-item', function() {
                var selectedProduct = $(this).data('value');
                var unitPrice = $(this).data('unit-price');
                $('#product_name').val(selectedProduct);
                $('#selling_price').val(unitPrice); // Update the selling price input
                $('#productSuggestions').empty().hide();
                updateTotalPrice(); // Calculate and update total price
            });
    
            // Calculate and update total price based on quantity and unit price
            function updateTotalPrice() {
                var qty = parseFloat($('#qty').val()) || 0;
                var sellingPrice = parseFloat($('#selling_price').val()) || 0;
                var totalPrice = qty * sellingPrice;
                $('#total_price').val(totalPrice.toFixed(2));
            }
    
            // Update total price when quantity changes
            $('#qty').on('input', updateTotalPrice);
    
            // Optional: Hide suggestions when clicking outside
            $(document).mouseup(function(e) {
                var container = $("#productSuggestions");
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.hide();
                }
            });
        });
    </script>
    

    {{-- <script>
        $(document).ready(function() {
            var debounceTimer;
            $('#product_name').on('input', function() {
                var query = $(this).val();

                clearTimeout(debounceTimer);

                if (query.length < 1) {
                    $('#productSuggestions').html('').hide();
                    return;
                }

                $('#productSuggestions').show(); // Optional: Show loading indicator

                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: '{{ route('admin.searchProduct') }}', // Make sure this URL is correct and routed properly
                        type: 'GET',
                        data: {
                            'query': query
                        },
                        success: function(data) {
                            $('#productSuggestions').empty();
                            if (data.length > 0) {
                                data.forEach(function(product) {
                                    // Ensure your server response includes the product name and selling price
                                    $('#productSuggestions').append(
                                        '<div class="suggestion-item" data-value="' +
                                        product.name +
                                        '" data-unit-price="' + product
                                        .selling_price + '">' + product
                                        .name + '</div>'
                                    );
                                });
                            } else {
                                $('#productSuggestions').append(
                                    '<div class="suggestion-item">No results found</div>'
                                    );
                            }
                        }
                    });
                }, 250); // Adjust debounce time as needed
            });

            $(document).on('click', '.suggestion-item', function() {
                var selectedProduct = $(this).data('value');
                var unitPrice = $(this).data('unit-price');
                $('#product_name').val(selectedProduct);
                $('#selling_price').val(unitPrice);
                $('#productSuggestions').empty().hide();
                updateTotalPrice(); // Calculate and update the total price
            });

            // Listen for input changes on the quantity field and calculate the total price
            $('#qty').on('input', updateTotalPrice);

            // Function to calculate and update total price
            function updateTotalPrice() {
                var qty = parseFloat($('#qty').val()) || 0;
                var sellingPrice = parseFloat($('#selling_price').val()) || 0;
                var totalPrice = qty * sellingPrice;
                $('#total_price').val(totalPrice.toFixed(2));
            }

            // Optional: Hide suggestions when clicking outside
            $(document).mouseup(function(e) {
                var container = $("#productSuggestions");
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    container.hide();
                }
            });
        });
    </script> --}}







@endsection
