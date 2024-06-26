@extends('../layouts.layout')

@section('title', 'Product')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <p class="taas-new">Add Product</p>

            <hr>

            <form class="modal-form" action="{{ route('staff.productStore') }}" enctype="multipart/form-data" method="POST">
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
                        <label for="">Capital:</label>
                        <input required type="number" name="purchase_price" id="" value="{{ old('purchase_price') }}"
                            class="row2-input" />
                        @if ($errors->has('purchase_price'))
                            <div class="text-danger">{{ $errors->first('purchase_price') }}</div>
                        @endif
                    </div>
                    <div class="column">
                        <label for="">Unit Price:</label>
                        <input required type="number" name="selling_price" id="" value="{{ old('selling_price') }}"
                            class="row2-input" />
                        @if ($errors->has('selling_price'))
                            <div class="text-danger">{{ $errors->first('selling_price') }}</div>
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
                <a class="sidebar active" href="{{ route('staff.product') }}">
                    <img src="{{ asset('images/product-xxl.png') }}" class="product-i" alt="">
                    PRODUCT</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('staff.transaction') }}">
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
            <button type="button" id="newButton">Add Product</button>
            <div class="sort-by">
                {{-- <form action="#" method="GET"> --}}
                <form id="sortForm" action="#" method="GET">
                    <input type="hidden" name="sort" id="sortInput" value="{{ request('sort') }}">

                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sortSelect">
                        <option selected value="" {{ request('sort') === '' ? 'selected' : '' }}>--
                            Default Sorting --</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Product
                            Name (A-Z)</option>
                        <option value="category_asc" {{ request('sort') === 'category_asc' ? 'selected' : '' }}>Category
                        </option>
                        <option value="quantity_asc" {{ request('sort') === 'quantity_asc' ? 'selected' : '' }}>Quantity
                            in Stock (ascending)</option>
                        <option value="purchase_price_asc" {{ request('sort') === 'purchase_price_asc' ? 'selected' : '' }}>
                            Capital (ascending)</option>
                        <option value="selling_price_asc" {{ request('sort') === 'selling_price_asc' ? 'selected' : '' }}>Unit
                            Price
                            (ascending)
                        </option>
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
                    <th>Capital</th>
                    <th>Unit Price</th>
                    <th>Actions</th>
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
                                <td class="nowrap">₱ {{ number_format($product->purchase_price) }}</td>
                                <td class="nowrap">₱ {{ number_format($product->selling_price) }}</td>
                                <td class="actions">
                                    <div class="actions-container">
                                        <form action="{{ route('staff.productEdit', $product->id) }}" method="POST">
                                            @csrf
                                            @method('GET')
                                            
                                            <button type="submit" class="edit" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('staff.productDestroy', $product->id) }}" method="POST">
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
                url: '{{ route('staff.productSearch') }}',
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
