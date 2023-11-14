@extends('../layouts.layout')

@section('title', 'Product')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
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
                <a class="sidebar active" href="{{ route('admin.product') }}">PRODUCT</a>
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

    <div class="content">

        <div class="taas">
            {{-- <form action="{{ route('admin.createProduct') }}"> --}}
            <form action="#">
                <button type="submit">Add New Product</button>
            </form>
            {{-- <div class="show-page">Show Per page <input type="text" /></div> --}}
            <div class="sort-by">
                {{-- <form action="{{ route('admin.product') }}" method="GET"> --}}
                <form action="#" method="GET">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sort">
                        <option selected value="" {{ request('sort') === '' ? 'selected' : '' }}>--
                            Default Sorting --</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Product
                            Name (A-Z)</option>
                        <option value="category_asc" {{ request('sort') === 'category_asc' ? 'selected' : '' }}>Category
                        </option>
                        <option value="quantity_asc" {{ request('sort') === 'quantity_asc' ? 'selected' : '' }}>Quantity in
                            Stock
                            (ascending)</option>
                        <option value="capital_asc" {{ request('sort') === 'capital_asc' ? 'selected' : '' }}>
                            Capital (ascending)</option>
                        <option value="unit_price_asc" {{ request('sort') === 'unit_price_asc' ? 'selected' : '' }}>Unit Price
                            (ascending)
                        </option>
                    </select>
                    <button type="submit">Sort</button>
                    {{-- <a href="{{ route('admin.product') }}" class="reset-sort">Reset sort</a> --}}
                </form>
            </div>
            <div>
                {{-- <form class="form-search" action="{{ route('admin.searchProduct') }}" method="GET"> --}}
                <form class="form-search" action="" method="GET">
                    <div class="searchs">
                        <div class="form-search">
                            <input required type="text" name="search" placeholder="Search product..."
                                class="search-prod" value="{{ $searchQuery }}" />
                            <button class="search" type="submit">
                                <img class="search"src="{{ asset('images/search.png') }}" alt="">
                            </button>
                        </div>
                        <a href="{{ route('admin.product') }}" class="cancel-search">Cancel search</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="table" id="search-results">
            <table>
                <tr>
                    <th colspan="11" class="table-th">PRODUCT</th>
                </tr>
                @php
                    // Calculate the initial row number based on the current page
                    $rowNumber = ($products->currentPage() - 1) * $products->perPage() + 1;
                @endphp
                <tr>
                    <th>No.</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>QTY in Stock</th>
                    <th>Capital</th>
                    <th>Unit Price</th>
                    <th>Actions</th>
                </tr>
                <tbody>
                    @if ($products->isEmpty())
                        <tr>
                            {{-- <td colspan="13">You searched for: {{ $searchQuery }}. No results found.</td> --}}
                            <td colspan="10">No results found.</td>
                        </tr>
                    @else
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->category }}</td>
                                <td>
                                    {{-- @if ($product->image_path)
                                        <img src="{{ asset('images/images' . $product->image) }}"
                                            alt="{{ $product->name }}" width="100" height="100">
                                    @else
                                        No Image
                                    @endif --}}
                                    <img src="{{ asset($product->photo) }}" alt="{{ $product->name }}" width="auto"
                                        height="50px" style="background-color: transparent">
                                </td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->capital }}</td>
                                <td>{{ $product->unit_price }}</td>
                                <td>
                                    <div class="edit-delete">
                                        <form id="" action="Products/{{ $product->id }}/edit">
                                            <button type="submit" id="" class="edit"><img class="edit"
                                                    src="{{ asset('assets/edits.png') }}" alt=""></button>
                                        </form>
                                        <form action="{{ route('admin.productDestroy', $product->id) }}" method="POST"
                                            onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="deletes"><img class="delete"
                                                    src="{{ asset('assets/delete.png') }}" alt=""></button>
                                            {{-- <input class="delete" type="submit" name="submit" value="Delete" />  --}}
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="pagination">
                {{ $products->appends(['sort' => request('sort')])->links('layouts.customPagination') }}</div>
        </div>
    </div>

@endsection

@section('footer')

@endsection

@section('script')

@endsection
