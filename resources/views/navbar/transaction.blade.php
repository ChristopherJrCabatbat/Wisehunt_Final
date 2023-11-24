@extends('../layouts.layout')

@section('title', 'Transaction')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay editOverlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <a class="close closeModal">&times;</a>

            <form class="modal-form" action="{{ route('admin.transactionStore') }}" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;">Add Transaction</h2>
                </center>

                <label class="baba-h2 taas-select" for="customer">Customer:</label>
                <select required name="customer_name" id="customer" onchange="updateContactNumber()"
                    class="select customer">
                    <option value="" disabled selected>-- Select a Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->name }}" data-contact="{{ $customer->contact_num }}"
                            {{ old('customer_name') === $customer->name ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>

                {{-- <label for="contact_num">Contact Number:</label>
                    <input required readonly name="contact_num" id="contact_num" pattern="[0-9]{5,11}"
                        title="Enter a valid contact number" type="text" value="{{ old('contact_num') }}"
                        class="contact_num"> --}}

                <label for="product_name" class="taas-select">Product:</label>
                <select required name="product_name" id="product_name" class="select product_name product-select"
                    onchange="updateUnitPrice('newModal')">
                    <option value="" disabled selected>-- Select a Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->name }}" data-unit-price="{{ $product->unit_price }}"
                            {{ old('product_name') === $product->name ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>

                <div class="text-danger">{{ $errors->first('error') }}</div> <!-- Display the error message here -->

                {{-- <label for="">Amount Tendered:</label>
                    <input required name="amount_tendered" type="number" value="{{ old('amount_tendered') }}">
                    <div class="text-danger">{{ $errors->first('error_change') }}</div> --}}

                <label for="">Quantity:</label>
                <input required name="qty" class="qty" type="number" id="qty" value="{{ old('qty') }}">
                <div class="text-danger">{{ $errors->first('error_stock') }}</div>

                <label for="unit_price">Unit Price:</label>
                <input readonly required name="unit_price" id="unit_price" type="number" value="{{ old('unit_price') }}"
                    class="unit_price">

                <label for="unit_price">Total Price:</label>
                <input readonly name="total_price" id="total_price" type="number" value="{{ $totalPrice }}"
                    class="total_price">

                <input type="submit" value="Add" id="button-transac">
            </form>
        </div>

    </div>


    {{-- Edit Modal --}}
    @foreach ($transactions as $transaction)
        <div id="editModal{{ $transaction->id }}" class="modal editModal">
            <div class="modal-content">
                <a class="close closeEditModal">&times;</a>

                <form class="edit-modal-form" action="{{ route('admin.transactionUpdate', $transaction->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <center>
                        <h2 style="margin: 0%; color:#333;">Edit Transaction</h2>
                    </center>

                    <label class="baba-h2 taas-select" for="customer">Customer:</label>
                    <select class="select autofocus" name="customer_name" id="customer-edit"
                        onchange="editUpdateContactNumber()">

                        @foreach ($customers as $customer)
                            <option value="{{ $customer->name }}" data-contact-edit="{{ $customer->contact_num }}"
                                {{ old('customer_name', $transaction->customer_name) === $customer->name ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- <label for="contact_num">Contact Number:</label>
                    <input required readonly name="contact_num" id="contact_num-edit" pattern="[0-9]{5,11}"
                        title="Enter a valid contact number" type="text"
                        value="{{ old('contact_num', $transaction->contact_num) }}"> --}}


                    <label for="product_name" class="taas-select">Product:</label>
                    <select class="select product-select" name="product_name" id="product_name-edit"
                        onchange="updateUnitPrice('editModal{{ $transaction->id }}')">
                        @foreach ($products as $product)
                            <option value="{{ $product->name }}" data-unit-price="{{ $product->unit_price }}"
                                {{ old('product_name', $transaction->product_name) === $product->name ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-danger">{{ $errors->first('error') }}</div>

                    <label for="">Quantity:</label>
                    <input required id="qty-edit" class="qty" name="qty" type="number" value="{{ old('qty', $transaction->qty) }}">
                    <div class="text-danger">{{ $errors->first('error_stock') }}</div>

                    <label for="unit_price">Unit Price:</label>
                    <input readonly required name="unit_price" class="unit_price" id="unit_price-edit" type="number"
                        value="{{ old('unit_price', $transaction->unit_price) }}">

                    <label for="unit_price">Total Price:</label>
                    <input readonly name="total_price" id="total_price_edit" type="number" value="{{ $transaction->total_price }}"
                        class="total_price">

                    {{-- <label for="">Amount Tendered:</label>
                    <input required name="amount_tendered" type="number"
                        value="{{ old('amount_tendered', $transaction->amount_tendered) }}">
                    <div class="text-danger">{{ $errors->first('error_change') }}</div> --}}

                    <input type="submit" id="button-transac" value="Update">
                </form>
            </div>

        </div>
    @endforeach

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
                <img class="icons-taas" src="{{ asset('images/dashboard-xxl.png') }}" alt="">
                <a href="{{ route('admin.dashboard') }}" class="sidebar top">DASHBOARD</a>
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
                <a class="sidebar active" href="{{ route('admin.transaction') }}">TRANSACTION</a>
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
    <div class="content">
        <div class="taas">

            <div class="new-generate">
                {{-- <form action="{{ route('admin.newTransaction', ['sort' => request('sort')]) }}"> --}}
                <button type="button" id="newButton">New Transaction</button>

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
                        <option value="unit_price_asc" {{ request('sort') === 'unit_price_asc' ? 'selected' : '' }}>Unit
                            Price (ascending)
                        </option>
                        <option value="total_price_asc" {{ request('sort') === 'total_price_asc' ? 'selected' : '' }}>
                            Total
                            Price
                            (ascending)</option>
                        <option value="total_earned_asc" {{ request('sort') === 'total_earned_asc' ? 'selected' : '' }}>
                            Total Earned on Item
                            (descending)</option>
                    </select>
                </form>
            </div>

            {{-- Search --}}

            {{-- <form class="form-search" action="{{ route('admin.searchTransaction') }}" method="GET"> --}}
            {{-- <form class="form-search" action="#" method="GET"> --}}
            <div>
                <div class="searchs">
                    <div class="form-search">
                        <input type="text" name="search" id="search" required class="search-prod"
                            placeholder="Search product..." value="{{ $searchQuery }}" />
                        <i class="fa fa-search search-icon"></i>
                        {{-- <button class="search" type="submit"><img class="search"
                                src="{{ asset('images/search.png') }}" alt=""></button> --}}
                    </div>
                    {{-- <a href="{{ route('admin.transaction') }}" class="cancel-search">Cancel search</a> --}}
                </div>
                {{-- </form> --}}
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
                    {{-- <th>Contact Number</th> --}}
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    {{-- <th>Amount Tendered</th> --}}
                    {{-- <th>Change Due</th> --}}
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
                                {{-- <td>{{ $transaction->contact_num }}</td> --}}
                                <td>{{ $transaction->product_name }}</td>
                                <td>{{ $transaction->qty }}</td>
                                <td>{{ $transaction->unit_price }}</td>
                                <td>{{ $transaction->total_price }}</td>
                                {{-- <td>{{ $transaction->amount_tendered }}</td> --}}
                                {{-- <td>{{ $transaction->change_due }}</td> --}}
                                <td>{{ $transaction->total_earned }}</td>
                                <td>{{ optional($transaction->created_at)->format('M. d, Y') }}</td>
                                <td class="actions">
                                    <div class="actions-container">
                                        <form>
                                            <button type="button" class="edit editButton" id="edit"
                                                data-id="{{ $transaction->id }}">
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
    </script>

    {{-- Luma Auto Contact Number at Unit Price --}}
    {{-- <script>
        // Add Modal
        function updateContactNumber() {
            var customerSelect = document.getElementById('customer');
            var contactNumberInput = document.getElementById('contact_num');

            var selectedOption = customerSelect.options[customerSelect.selectedIndex];
            var contactNumber = selectedOption.getAttribute('data-contact');

            contactNumberInput.value = contactNumber;
        }

        function updateUnitPrice() {
            var productSelect = document.getElementById('product_name');
            var unitPriceInput = document.getElementById('unit_price');

            var selectedPrice = productSelect.options[productSelect.selectedIndex];
            var unitPrice = selectedPrice.getAttribute('data-unit-price');

            unitPriceInput.value = unitPrice;
        }


        // Edit Modal
        function editUpdateContactNumber() {
            var editCustomerSelect = document.getElementById('customer-edit');
            var editContactNumberInput = document.getElementById('contact_num-edit');

            var editSelectedOption = editCustomerSelect.options[editCustomerSelect.selectedIndex];
            var editContactNumber = editSelectedOption.getAttribute('data-contact-edit');

            editContactNumberInput.value = editContactNumber;
        }

        function editUpdateUnitPrice() {
            var editProductSelect = document.getElementById('product_name-edit');
            var editUnitPriceInput = document.getElementById('unit_price-edit');

            var editSelectedPrice = editProductSelect.options[editProductSelect.selectedIndex];
            var editUnitPrice = editSelectedPrice.getAttribute('data-unit-price-edit');

            editUnitPriceInput.value = editUnitPrice;
        }
    </script> --}}

    <!-- Auto Unit Price Script -->
    <script>
        function updateUnitPrice(elementId) {
            var productSelect = document.querySelector('#' + elementId + ' .product-select');
            var unitPriceInput = document.querySelector('#' + elementId + ' .unit_price');

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
                    var productSelect = qtyInput.closest('.modal-content').querySelector('.product-select');
                    var unitPrice = parseFloat(productSelect.options[productSelect.selectedIndex].getAttribute('data-unit-price')) || 0;
    
                    // Calculate the total price
                    var totalPrice = qty * unitPrice;
    
                    // Update the total price input field
                    var totalPriceInput = qtyInput.closest('.modal-content').querySelector('.total_price');
                    totalPriceInput.value = totalPrice.toFixed(2);
                });
            });
        });
    </script>


@endsection
