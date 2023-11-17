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
                <select autofocus required name="customer_name" id="autofocus" onchange="updateContactNumber()"
                    class="select">
                    <option value="" disabled selected>-- Select a Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->name }}" data-contact="{{ $customer->contact_num }}"
                            {{ old('customer_name') === $customer->name ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>

                <label for="contact_num">Contact Number:</label>
                <input required readonly name="contact_num" id="contact_num" pattern="[0-9]{5,11}"
                    title="Enter a valid contact number" type="text" value="{{ old('contact_num') }}">


                <label for="product_name" class="taas-select">Product Name:</label>
                <select required name="product_name" id="product_name" class="select" onchange="updateUnitPrice()">
                    <option value="" disabled selected>-- Select a Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->name }}" {{ old('product_name') === $product->name ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>

                <div class="text-danger">{{ $errors->first('error') }}</div> <!-- Display the error message here -->

                <label for="unit_price">Unit Price:</label>
                <input readonly required name="unit_price" id="unit_price" type="number" value="{{ old('unit_price') }}">

                <label for="">Amount Tendered:</label>
                <input required name="amount_tendered" type="number" value="{{ old('amount_tendered') }}">
                <div class="text-danger">{{ $errors->first('error_change') }}</div>

                <label for="">Quantity:</label>
                <input required name="qty" type="number" value="{{ old('qty') }}">
                <div class="text-danger">{{ $errors->first('error_stock') }}</div>

                <input type="submit" value="Add">
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
                    <select class="select autofocus" autofocus name="customer_name" id="customer"
                        onchange="updateContactNumber()">
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->name }}" data-contact="{{ $customer->contact_num }}"
                                {{ old('customer_name', $transaction->customer_name) === $customer->name ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>

                    <label for="contact_num">Contact Number:</label>
                    <input required readonly name="contact_num" id="contact_num" pattern="[0-9]{5,11}"
                        title="Enter a valid contact number" type="text"
                        value="{{ old('contact_num', $transaction->contact_num) }}">


                    <label for="product_name" class="taas-select">Product Name:</label>
                    <select class="select" name="product_name" id="product_name" onchange="updateUnitPrice()">
                        @foreach ($products as $product)
                            <option value="{{ $product->name }}"
                                {{ old('product_name', $transaction->product_name) === $product->name ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-danger">{{ $errors->first('error') }}</div>

                    <label for="unit_price">Unit Price:</label>
                    <input readonly required name="unit_price" id="unit_price" type="number"
                        value="{{ old('unit_price', $transaction->unit_price) }}">

                    <label for="">Quantity:</label>
                    <input required name="qty" type="number" value="{{ old('qty', $transaction->qty) }}">
                    <div class="text-danger">{{ $errors->first('error_stock') }}</div>
                    <label for="">Amount Tendered:</label>
                    <input required name="amount_tendered" type="number"
                        value="{{ old('amount_tendered', $transaction->amount_tendered) }}">
                    <div class="text-danger">{{ $errors->first('error_change') }}</div>

                    <input type="submit" value="Update">
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
                <form action="{{ route('admin.transaction') }}" method="GET">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sort">
                        <option selected value="" {{ request('sort') === '' ? 'selected' : '' }}>--
                            Default Sorting --</option>
                        <option value="customer_name_asc" {{ request('sort') === 'customer_name_asc' ? 'selected' : '' }}>
                            Customer
                            (ascending)</option>
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
                    <button type="submit">Sort</button>
                </form>
            </div>

            {{-- <form class="form-search" action="{{ route('admin.searchTransaction') }}" method="GET"> --}}
            <form class="form-search" action="#" method="GET">
                <div class="searchs">
                    <div class="form-search">
                        <input type="text" name="search" required class="search-prod"
                            placeholder="Search product..." value="{{ $searchQuery }}" />
                        <button class="search" type="submit"><img class="search"
                                src="{{ asset('images/search.png') }}" alt=""></button>
                    </div>
                    <a href="{{ route('admin.transaction') }}" class="cancel-search">Cancel search</a>
                </div>
            </form>

        </div>

        <div class="table">
            <table>

                <tr>
                    <th colspan="13" class="table-th">TRANSACTIONS</th>
                </tr>

                @php
                    // Calculate the initial row number based on the current page
                    $rowNumber = ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                @endphp

                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Contact Number</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Amount Tendered</th>
                    <th>Change Due</th>
                    <th>Total Earned on Item</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>

                <tbody>
                    @if ($transactions->isEmpty())
                        <tr>
                            <td colspan="13">No results found.</td>
                        </tr>
                    @else
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $transaction->customer_name }}</td>
                                <td>{{ $transaction->contact_num }}</td>
                                <td>{{ $transaction->product_name }}</td>
                                <td>{{ $transaction->qty }}</td>
                                <td>{{ $transaction->unit_price }}</td>
                                <td>{{ $transaction->total_price }}</td>
                                <td>{{ $transaction->amount_tendered }}</td>
                                <td>{{ $transaction->change_due }}</td>
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
                                        {{-- <form action="{{ route('productDestroy', $product->id) }}" method="POST"> --}}
                                        <form action="#" method="POST">
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

    <script>
        // Auto Contact Number
        function updateContactNumber() {
            var customerSelect = document.getElementById('customer');
            var contactNumberInput = document.getElementById('contact_num');

            var selectedOption = customerSelect.options[customerSelect.selectedIndex];
            var contactNumber = selectedOption.getAttribute('data-contact');

            contactNumberInput.value = contactNumber;
        }

        // Auto Unit Price
        function updateUnitPrice() {
            var productName = document.getElementById('product_name').value;
            var unitPriceField = document.getElementById('unit_price');

            // Find the product with the selected name in the products list
            var selectedProduct = @json($products);

            for (var i = 0; i < selectedProduct.length; i++) {
                if (selectedProduct[i].name === productName) {
                    unitPriceField.value = selectedProduct[i].unit_price;
                    break;
                }
            }
        }
    </script>

@endsection
