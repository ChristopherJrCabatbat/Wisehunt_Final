@extends('../layouts.layout')

@section('title', 'Transaction')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction-styles.css') }}">
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
                <form action="#">
                    <button type="submit">New Transaction</button>
                </form>
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
                        <option value="total_price_asc" {{ request('sort') === 'total_price_asc' ? 'selected' : '' }}>Total
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
                        <input type="text" name="search" required class="search-prod" placeholder="Search product..."
                            value="{{ $searchQuery }}" />
                        <button class="search" type="submit"><img class="search" src="{{ asset('images/search.png') }}"
                                alt=""></button>
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
                    <th>No.</th>
                    <th>Customer</th>
                    <th>Contact No.</th>
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
                                <td>{{ $transaction->created_at->format('M. d, Y') }}</td>
                                <td>
                                    <div class="edit-delete">
                                        <a href="Transactions/{{ $transaction->id }}/edit" class="edit">
                                            <img class="edit" src="{{ asset('images/edits.png') }}" alt="edit btn">
                                        </a>

                                        {{-- <form action="{{ route('admin.transactionDestroy', $transaction->id) }}" --}}
                                        <form action="#" method="POST" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="deletes"><img class="delete"
                                                    src="{{ asset('images/delete.png') }}" alt="delete btn"></button>
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

@endsection
