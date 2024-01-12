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

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <p class="taas-new"><i class="fa-regular fa-plus"></i>Add Product</p>

            <hr>

            <form class="modal-form" action="{{ route('admin.productStore') }}" enctype="multipart/form-data" method="POST">
                @csrf

                <div class="row1">
                    <div class="column">
                        <label class="modal-top" for="">Product code:</label>
                        <input required autofocus type="text" name="code" pattern="{3,11}" class="row1-input"
                            id="autofocus" value="{{ old('code') }}" />
                    </div>

                    <div class="column">

                        <label for="">Product Name:</label>
                        <select required class="select_product" name="name" id="product_name">
                            <option value="" disabled selected>-- Select a Product --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->product_name }}"
                                    {{ old('name') === $supplier->product_name ? 'selected' : '' }}>
                                    {{ $supplier->product_name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('name'))
                            <div class="text-danger">{{ $errors->first('name') }}</div>
                        @endif

                    </div>

                    <div class="column">
                        <label for="">Brand Name:</label>
                        <input required type="text" name="brand_name" id="" value="{{ old('brand_name') }}"
                            class="row1-input" />
                    </div>
                    
                    {{-- <div class="column">
                        <label for="">Stock Quantity:</label>
                        <input required type="number" name="quantity" id="" value="{{ old('quantity') }}"
                            class="row2-input" />
                        @if ($errors->has('quantity'))
                            <div class="text-danger">{{ $errors->first('quantity') }}</div>
                        @endif
                    </div> --}}

                </div>

                <div class="row2">

                    <div class="column">
                        <label for="">Stock Quantity:</label>
                        <input required type="number" name="quantity" id="" value="{{ old('quantity') }}"
                            class="row2-input" />
                        @if ($errors->has('quantity'))
                            <div class="text-danger">{{ $errors->first('quantity') }}</div>
                        @endif
                    </div>                 

                    <div class="column">
                        <label for="">Purchase Price:</label>
                        <input required type="number" name="capital" id="" value="{{ old('capital') }}"
                            class="row2-input" />
                        @if ($errors->has('capital'))
                            <div class="text-danger">{{ $errors->first('capital') }}</div>
                        @endif
                    </div>
                    <div class="column">
                        <label for="">Selling Price:</label>
                        <input required type="number" name="unit_price" id="" value="{{ old('unit_price') }}"
                            class="row2-input" />
                        @if ($errors->has('unit_price'))
                            <div class="text-danger">{{ $errors->first('unit_price') }}</div>
                        @endif
                    </div>

                    <div class="column">
                        <label for="">Category:</label>
                        <select required name="category" id="" class="row1-input select_categ">
                            <option value="" disabled selected>-- Select Category --</option>
                            <option value="Paper" {{ old('category') === 'Paper' ? 'selected' : '' }}>Paper
                            </option>
                            <option value="Tape" {{ old('category') === 'Tape' ? 'selected' : '' }}>Tape
                            </option>
                            <option value="Plastic" {{ old('category') === 'Plastic' ? 'selected' : '' }}>Plastic
                            </option>
                            <option value="Gloves" {{ old('category') === 'Gloves' ? 'selected' : '' }}>Gloves
                            </option>
                            <option value="Machine" {{ old('category') === 'Machine' ? 'selected' : '' }}>
                                Machine</option>
                            <option value="Food Material" {{ old('category') === 'Food Material' ? 'selected' : '' }}>Food
                                Material
                            </option>
                        </select>
                    </div>

                </div>

                <div class="row3">
                    <div class="column">
                        <label for="">Image:</label>
                        <div class="input_container">
                            <input type="file" name="photo" id="fileUpload">
                        </div>
                    </div>

                    <div class="column">
                        <label for="">Receive Notification when Quantity is:</label>
                        <div class="input_container_ginaya">
                            <input type="number" name="low_quantity_threshold" placeholder="Enter threshold"
                                title="Receive notification when the stock quantity reaches or falls below this value."
                                value="{{ old('low_quantity_threshold') }}" required />
                        </div>
                    </div>

                </div>
                <div class="row4">
                    <label for="">Product Description:</label>
                    <textarea required name="description" rows="5" placeholder="Eg. size..." cols="5" class=""
                        value="{{ old('description') }}">{{ old('description') }}</textarea>
                </div>

                <hr>
                <div class="buttons">
                    <input type="submit" id="saveButton" class="add-green save"
                        style="font-family: Arial, Helvetica, sans-serif; font-size: 1rem;" value="Add" />
                    <button type="button" class="closeModal">Cancel</button>
                </div>
            </form>

        </div>
    </div>

    {{-- Category Modal --}}
    <div id="categoryModal" class="categoryModal">
        <div class="modal-content-category">
                <form id="categoryForm">
                    <h3>Choose Category</h3>

                    <div>
                        <label for="category">Categories:</label>
                        <select required name="category" id="category" class="form-control">
                            <option value="Paper">Paper</option>
                            <option value="Tape">Tape</option>
                            <option value="Plastic">Plastic</option>
                            <option value="Gloves">Gloves</option>
                            <option value="Machine">Machine</option>
                            <option value="Food Material">Food Material</option>
                        </select>
                    </div>

                    <div class="categ-baba">
                        <button type="button" class="btn btn-primary" onclick="applyCategoryFilter()">Apply</button>
                        <a href="{{ route('admin.product') }}" class="anchor-categ">Close</a>
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
                        <option value="default_asc" {{ request('sort') === 'default_asc' ? 'selected' : '' }}>-- Default Sorting --</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Product Name</option>
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
                    <th>Brand Name</th>
                    <th>Product Description</th>
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
                            <td colspan="11">No data found.</td>
                        </tr>
                    @else
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->brand_name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->category }}</td>
                                <td>
                                    <img src="{{ asset($product->photo) }}" alt="{{ $product->name }}" width="auto"
                                        height="50px" style="background-color: transparent">
                                </td>
                                <td>{{ $product->quantity }}</td>
                                <td>₱ {{ number_format($product->capital) }}</td>
                                <td>₱ {{ number_format($product->unit_price) }}</td>
                                <td class="actions">
                                    <div class="actions-container">
                                        {{-- <form action="{{ route('admin.productEdit', $product->id) }}" method="POST">
                                            @csrf
                                            @method('GET')

                                            <button type="submit" class="edit" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form> --}}
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
    


@endsection
