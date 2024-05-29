@extends('../layouts.layout')

@section('title', 'Product')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS (Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

@endsection

@section('modals')

    <div class="overlay"></div>

    <!-- Edit Modal -->
    <div id="" class="editModal">
        <div class="edit-modal-content">
            <p class="taas-new"><i class="fa-regular fa-pen-to-square edt-taas"></i>Edit Product </p>
            <hr>
            <form class="edit-modal-form" action="{{ route('admin.productUpdate', $productss->id) }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                @method('PUT')

                <div class="row1">
                    <div class="column">
                        <label class="modal-top" for="">Product code:</label>
                        <input required type="text" name="code" pattern="{3,11}" class="row1-input"
                            value="{{ old('code', $productss->code) }}" />
                    </div>
                    <div class="column">
                        <label for="">Product Name:</label>
                        {{-- <select class="select_product" name="name" class="product_name">
                            @foreach ($suppliers as $supplier)

                                <option value="{{ $supplier->product_name }}"
                                    {{ old('name', $productss->name) === $supplier->product_name ? 'selected' : '' }}>
                                    {{ $supplier->product_name }}
                                </option>
                            @endforeach
                        </select> --}}

                        <input class="form-control" id="product_name" name="name" type="text"
                            placeholder="Type to search..." autocomplete="off" value="{{ old('code', $productss->name) }}">

                        <div id="productSuggestions" class="suggestions-dropdown">
                            <!-- Search suggestions will be appended here -->
                        </div>


                        @if ($errors->has('name'))
                            <div class="text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="column">
                        <label for="">Brand Name:</label>
                        <input required type="text" name="brand_name" id=""
                            value="{{ old('brand_name', $productss->brand_name) }}" class="row1-input" />
                    </div>

                </div>

                <div class="row2">

                    <div class="column">
                        <label for="">Stock Quantity:</label>
                        <input required autofocus type="number" name="quantity" class="row2-input autofocus"
                            value="{{ old('quantity', $productss->quantity) }}" />
                        @if ($errors->has('quantity'))
                            <div class="text-danger">{{ $errors->first('quantity') }}</div>
                        @endif
                    </div>

                    <div class="column">
                        <label for="">Unit:</label>
                        <select required name="unit" id="" class="row1-input unit">
                            <option value="" disabled selected>Select Unit</option>
                            <option value="Per item" {{ old('unit', $productss->unit) === 'Per item' ? 'selected' : '' }}>
                                Per item
                            </option>
                            <option value="Per box" {{ old('unit', $productss->unit) === 'Per box' ? 'selected' : '' }}>Per
                                box
                            </option>
                            <option value="Per case" {{ old('unit', $productss->unit) === 'Per case' ? 'selected' : '' }}>
                                Per case
                            </option>
                            <option value="Per pack" {{ old('unit', $productss->unit) === 'Per pack' ? 'selected' : '' }}>
                                Per pack
                            </option>
                            <option value="Per set" {{ old('unit', $productss->unit) === 'Per set' ? 'selected' : '' }}>
                                Per set</option>
                            <option value="Per box" {{ old('unit', $productss->unit) === 'Per box' ? 'selected' : '' }}>Per
                                ream
                            </option>
                            {{-- <option value="Paper" {{ old('category', $productss->category) === 'Paper' ? 'selected' : '' }}>Paper</option> --}}

                        </select>
                        @if ($errors->has('unit'))
                            <div class="text-danger">{{ $errors->first('unit') }}</div>
                        @endif
                    </div>

                    <div class="column">
                        <label for="">Capital:</label>
                        <input required type="number" name="purchase_price" id=""
                            value="{{ old('purchase_price', $productss->purchase_price) }}" class="row2-input" />
                        @if ($errors->has('purchase_price'))
                            <div class="text-danger">{{ $errors->first('purchase_price') }}</div>
                        @endif
                    </div>
                    <div class="column">
                        <label for="">Unit Price:</label>
                        <input required type="number" name="selling_price" id=""
                            value="{{ old('selling_price', $productss->selling_price) }}" class="row2-input" />
                        @if ($errors->has('selling_price'))
                            <div class="text-danger">{{ $errors->first('selling_price') }}</div>
                        @endif
                    </div>
                    <div class="column">
                        <label for="">Category:</label>
                        <select name="category" id="" class="row1-input select_categ">

                            <option value="Paper"
                                {{ old('category', $productss->category) === 'Paper' ? 'selected' : '' }}>Paper</option>

                            <option value="Tape"
                                {{ old('category', $productss->category) === 'Tape' ? 'selected' : '' }}>Tape</option>

                            <option value="Plastic"
                                {{ old('category', $productss->category) === 'Plastic' ? 'selected' : '' }}>Plastic
                            </option>

                            <option value="Gloves"
                                {{ old('category', $productss->category) === 'Gloves' ? 'selected' : '' }}>Gloves</option>

                            <option value="Machine"
                                {{ old('category', $productss->category) === 'Machine' ? 'selected' : '' }}>Machine
                            </option>

                            <option value="Food Material"
                                {{ old('category', $productss->category) === 'Food Material' ? 'selected' : '' }}>Food
                                Material</option>

                        </select>
                    </div>
                </div>

                <div class="row3-edit">
                    {{-- Image --}}
                    <div class="column">
                        <label for="">Current Image:</label>
                        <img class="img-edit" src="{{ asset($productss->photo) }}" alt="" width="50px"
                            height="auto">
                    </div>
                    <div class="column">
                        <label for="">Change Image:</label>
                        <div class="input_container_edit">
                            <input type="file" name="photo" id="fileUpload">
                        </div>
                    </div>
                    <div class="column">
                        <label for="">Receive Notification when Quantity is:</label>
                        <div class="input_container_ginaya_edit">
                            <input type="number" name="low_quantity_threshold" placeholder="Enter threshold"
                                title="Receive notification when the stock quantity reaches or falls below this value."
                                value="{{ old('low_quantity_threshold', $productss->low_quantity_threshold) }}"
                                required />

                        </div>
                    </div>
                </div>

                <div class="row4">
                    <label for="">Product Description:</label>
                    <textarea required name="description" rows="5" placeholder="Eg. brand of the product" cols="5"
                        class="" value="">{{ old('description', $productss->description) }}</textarea>
                </div>

                <hr>
                <div class="buttons">
                    <input type="submit" class="add-green"
                        style="font-size: 1rem; font-family: Arial, Helvetica, sans-serif;" value="Update" />
            </form>
            <form action="{{ route('admin.product') }}">
                <button type="submit" class="closeEditModal">Cancel</button>
            </form>
        </div>
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
                <a class="sidebar active" href="{{ route('admin.product') }}">
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
                    USER</a>
            </div>
        </li>
    </ul>

@endsection

@section('main-content')

    <div class="content">

        <div class="taas">
            <button type="button" id="newButton">Add Product</button>

            <div class="sort-by">
                <form id="sortForm" action="#" method="GET">
                    <input type="hidden" name="sort" id="sortInput" value="{{ request('sort') }}">

                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sortSelect" onchange="handleSortChange()">
                        <option selected value="">-- Click to sort --</option>
                        <option value="default_asc" {{ request('sort') === 'default_asc' ? 'selected' : '' }}>Default
                            Sorting</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Product Name
                        </option>
                        <option value="category_asc" data-toggle="modal" data-target="#categoryModal"
                            {{ request('sort') === 'category_asc' ? 'selected' : '' }}>Category</option>
                    </select>

                </form>
            </div>

            {{-- Search --}}
            <div>
                <div class="searchs">
                    <div class="form-search">
                        <input required type="search" id="search" name="search" placeholder="Search product..."
                            autocomplete="off" class="search-prod" />
                        <i class="fa fa-search search-icon"></i>
                    </div>

                </div>
            </div>

        </div>

        <div class="table" id="search-results">
            <table>
                <tr>
                    <th colspan="15" class="table-th">PRODUCT</th>
                </tr>
                @php
                    // Calculate the initial row number based on the current page
                    $rowNumber = ($products->currentPage() - 1) * $products->perPage() + 1;
                @endphp

                <tr>
                    <th>No.</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Brand Name</th>
                    <th>Product Description</th>
                    <th>Unit</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Stock Quantity</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>Delete</th>
                </tr>
                <tbody class="all-data">
                    @if ($products->isEmpty())
                        <tr>
                            <td colspan="20">No data found.</td>
                        </tr>
                    @else
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->brand_name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->unit }}</td>
                                <td>{{ $product->category }}</td>
                                <td>
                                    <img src="{{ asset($product->photo) }}" alt="{{ $product->name }}" width="auto"
                                        height="50px" style="background-color: transparent">
                                </td>
                                <td>{{ $product->quantity }}</td>
                                <td>₱ {{ number_format($product->purchase_price) }}</td>
                                <td>₱ {{ number_format($product->selling_price) }}</td>
                                <td class="actions">
                                    <div class="actions-container">
                                        <form action="{{ route('admin.productEdit', $product->id) }}" method="POST">
                                            @csrf
                                            @method('GET')

                                            <button type="submit" class="edit" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.productDestroy', $product->id) }}" method="POST">
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
                {{ $products->appends(['sort' => request('sort')])->links('layouts.customPagination') }}</div>
        </div>
    </div>

@endsection

@section('footer')
    {{-- @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif --}}
@endsection

@section('script')


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
                url: '{{ route('admin.productSearch') }}',
                data: {
                    'search': $value
                },
                success: function(data) {
                    console.log(data);
                    if (data.trim() === "") {
                        contentContainer.html(
                            '<tr><td colspan="20" class="id">No Result Found</td></tr>');
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

    {{-- Auto Sorting --}}
    {{-- <script>
        // Automatically submit the form when the sorting option changes
        document.getElementById('sortSelect').addEventListener('change', function() {
            document.getElementById('sortForm').submit();
        });
    </script> --}}

    <script>
        function handleSortChange() {
            console.log('handleSortChange called');

            var selectedSort = document.getElementById('sortSelect').value;

            // Check if the selected sort is 'category_asc'
            if (selectedSort === 'category_asc') {
                // Use jQuery to show the modal
                $('#categoryModal').modal('show');
            } else {
                // Submit the form for other sorting options
                document.getElementById('sortForm').submit();
            }
        }
    </script>

    {{-- <script>
        function applyCategoryFilter() {
            var selectedCategory = document.getElementById('category').value;
            // You can use AJAX to fetch and update the table based on the selected category
            // For simplicity, let's assume you have a route that returns the filtered products
            var url = 'admin/products/filter/' + encodeURIComponent(selectedCategory);

            // Redirect to the filtered products route
            window.location.href = url;
        }
    </script> --}}

    <script>
        function applyCategoryFilter() {
            var selectedCategory = document.getElementById('category').value;
            var url = '/admin/products/filter/' + encodeURIComponent(selectedCategory);

            // Redirect to the filtered products route
            window.location.href = url;
        }
    </script>

    {{-- Live Search Product Name --}}
    {{-- <script>
        $(document).ready(function() {
            var debounceTimer;
            $('#name').on('input', function() {
                var query = $(this).val();

                clearTimeout(debounceTimer);

                if (query.length < 1) {
                    $('#productSuggestions').hide();
                    return;
                }

                $('#loadingIndicator').show();

                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: '{{ route('admin.searchProductName') }}',
                        type: 'GET',
                        data: {
                            'query': query
                        },
                        success: function(data) {
                            $('#loadingIndicator').hide();

                            if (data.length > 0) {
                                $('#productSuggestions').empty().show();
                                $.each(data, function(index, product) {
                                    $('#productSuggestions').append(
                                        '<a href="#" class="list-group-item list-group-item-action" data-name="' +
                                        product.value + '" data-price="' +
                                        product.selling_price + '">' +
                                        product
                                        .value + '</a>');
                                });
                            } else {
                                $('#productSuggestions').hide();
                            }
                        }
                    });
                }, 250);
            });

            // Hide suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#name, #productSuggestions').length) {
                    $('#productSuggestions').hide();
                }
            });

            // Optionally, show suggestions again when the input is focused and there is text
            $('#name').on('focus', function() {
                if (this.value.length > 0) {
                    $('#productSuggestions').show();
                }
            });
        });
    </script> --}}

    {{-- <script>
        $(document).ready(function() {
            var debounceTimer;
            $('#name').on('input', function() {
                var query = $(this).val();

                clearTimeout(debounceTimer);

                if (query.length < 1) {
                    $('#productSuggestions').hide();
                    return;
                }

                $('#loadingIndicator').show();

                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: '{{ route('admin.searchProductName') }}',
                        type: 'GET',
                        data: {
                            'query': query
                        },
                        success: function(data) {
                            $('#loadingIndicator').hide();

                            if (data.length > 0) {
                                $('#productSuggestions').empty().show();
                                $.each(data, function(index, product) {
                                    $('#productSuggestions').append(
                                        '<a href="#" class="list-group-item list-group-item-action" data-name="' +
                                        product.value + '" data-price="' +
                                        product.selling_price + '">' +
                                        product.value + '</a>');
                                });
                            } else {
                                $('#productSuggestions').hide();
                            }
                        }
                    });
                }, 250);
            });

            // Hide suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#name, #productSuggestions').length) {
                    $('#productSuggestions').hide();
                }
            });

            // Handle click on suggestion to fill the input and hide suggestions
            $('#productSuggestions').on('click', 'a', function(event) {
                event.preventDefault();
                var name = $(this).data('name');
                $('#name').val(name);
                $('#productSuggestions').hide();
            });

            // Optionally, show suggestions again when the input is focused and there is text
            $('#name').on('focus', function() {
                if (this.value.length > 0 && $('#productSuggestions').children().length > 0) {
                    $('#productSuggestions').show();
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
                        url: '{{ route('admin.searchSupplierProduct') }}',
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
    </script>




@endsection
