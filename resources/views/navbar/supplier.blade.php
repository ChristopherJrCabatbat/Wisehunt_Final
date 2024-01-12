@extends('../layouts.layout')

@section('title', 'Supplier')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/supplier-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <span class="close closeModal">&times;</span>

            <form class="modal-form" action="{{ route('admin.supplierStore') }}" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-plus"></i>Add Supplier</h2>
                </center>
                <label class="modal-tops" for="">Company Name:</label>
                <input required autofocus type="text" name="company_name" id="autofocus" />
                <label for="">Contact Name:</label>
                <input required type="text" name="contact_name" id="" />
                <label for="">Contact Number:</label>
                <input required type="text" pattern="[0-9]{5,11}" title="Enter a valid contact number" name="contact_num"
                    id="" value="">
                <label for="">Address:</label>
                <input required type="text" name="address" id="" />
                <label for="">Product:</label>
                <input required type="text" name="product_name" id="" />


                <input class="add" type="submit" value="Add" />
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
                <a class="sidebar active" href="{{ route('admin.supplier') }}">
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
            <button class="add" type="button" id="newButton">Add Supplier</button>



            <div class="sort-by">
                <form id="sortForm" action="#" method="GET">
                    <input type="hidden" name="sort" id="sortInput" value="{{ request('sort') }}">

                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sortSelect">
                        <option selected value="" {{ request('sort') === '' ? 'selected' : '' }}>--
                            Default Sorting --</option>
                        <option value="company_name_asc" {{ request('sort') === 'company_name_asc' ? 'selected' : '' }}>
                            Company Name</option>
                        <option value="contact_name_asc"
                            {{ request('sort') === 'contact_name_asc' ? 'selected' : '' }}>
                            Contact Name</option>
                        <option value="address_asc" {{ request('sort') === 'address_asc' ? 'selected' : '' }}>Address
                        </option>
                        <option value="product_name_asc" {{ request('sort') === 'product_name_asc' ? 'selected' : '' }}>
                           Product
                        </option>

                    </select>
                </form>
            </div>

            {{-- <div class="sort-by">
                <form id="sortForm" action="#" method="GET">
                    <input type="hidden" name="sort_hidden" value="{{ request('sort') }}">
                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sortSelect">
                        <option selected value="" {{ request('sort') === '' ? 'selected' : '' }}>-- Default Sorting
                            --</option>
                        <option value="company_name_asc" {{ request('sort') === 'company_name_asc' ? 'selected' : '' }}>Company
                            Name</option>
                        <option value="contact_name_asc"
                            {{ request('sort') === 'contact_name_asc' ? 'selected' : '' }}>Contact Name</option>
                        <option value="address_asc" {{ request('sort') === 'address_asc' ? 'selected' : '' }}>Address
                        </option>
                        <option value="product_name_asc" {{ request('sort') === 'product_name_asc' ? 'selected' : '' }}>
                            Product</option>
                    </select>
                </form>

            </div> --}}

            {{-- Search --}}
            <div>
                <div class="searchs">
                    <div class="form-search">
                        <input required type="search" id="search" name="search" placeholder="Search supplier..."
                            autocomplete="off" class="search-prod" />
                        <i class="fa fa-search search-icon"></i>
                    </div>

                </div>
            </div>

        </div>
        <div class="table" id="search-results">
            <table>

                <tr>
                    <th colspan="11" class="table-th">SUPPLIERS</th>
                </tr>

                @php
                    // Calculate the initial row number based on the current page
                    $rowNumber = ($suppliers->currentPage() - 1) * $suppliers->perPage() + 1;
                @endphp

                <tr>
                    <th>No.</th>
                    <th>Company Name</th>
                    <th>Contact Name</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Product</th>
                    <th>Actions</th>
                </tr>

                <tbody class="all-data">
                    @if ($suppliers->isEmpty())
                        <tr>
                            <td colspan="7">No data found.</td>
                        </tr>
                    @else
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $supplier->company_name }}</td>
                                <td>{{ $supplier->contact_name }}</td>
                                <td>{{ $supplier->contact_num }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>{{ $supplier->product_name }}</td>

                                <td class="actions">
                                    <div class="actions-container">
                                        <form action="{{ route('admin.supplierEdit', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" class="edit editButton" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.supplierDestroy', $supplier->id) }}"
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
                {{ $suppliers->appends(['sort' => request('sort')])->links('layouts.customPagination') }}
            </div>

            {{-- <div class="pagination">{{ $suppliers->links('layouts.customPagination') }}</div> --}}

        </div>
    </div>
@endsection

@section('footer')

@endsection

@section('script')
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

            contentContainer.html('');

            $.ajax({
                type: 'get',
                url: '{{ route('admin.supplierSearch') }}',
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

@endsection
