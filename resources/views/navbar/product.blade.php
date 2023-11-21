@extends('../layouts.layout')

@section('title', 'Product')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
@endsection

@section('modals')

    {{-- @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Open the modal if there are validation errors
                const newModal = document.getElementById("newModal");
                const overlay = document.querySelector(".overlay");
                newModal.style.display = "block";
                overlay.style.display = "block";
            });
        </script>
    @endif --}}

    <div class="overlay editOverlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <p class="taas-new">Add New Product</p>

            <hr>

            <form class="modal-form" action="{{ route('admin.productStore') }}" enctype="multipart/form-data" method="POST">
                {{-- <form class="modal-form" action="{{ route('admin.productStore') }}" enctype="multipart/form-data" method="POST" data-validation-url="{{ route('admin.validateProductStore') }}"> --}}
                @csrf

                <div class="row1">
                    <div class="column">
                        <label class="modal-top" for="">Product code:</label>
                        <input required autofocus type="text" name="code" pattern="[0-9]{3,11}" class="row1-input"
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
                        <label for="">Stock Quantity:</label>
                        <input required type="number" name="quantity" id="" value="{{ old('quantity') }}"
                            class="row1-input" />
                        @if ($errors->has('quantity'))
                            <div class="text-danger">{{ $errors->first('quantity') }}</div>
                        @endif
                    </div>
                </div>

                <div class="row2">

                    <div class="column">
                        <label for="">Capital:</label>
                        <input required type="number" name="capital" id="" value="{{ old('capital') }}"
                            class="row2-input" />
                        @if ($errors->has('capital'))
                            <div class="text-danger">{{ $errors->first('capital') }}</div>
                        @endif
                    </div>
                    <div class="column">
                        <label for="">Unit Price:</label>
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
                            <option value="Machine" {{ old('category') === 'Machine' ? 'selected' : '' }}>
                                Machine</option>
                            <option value="Food Material" {{ old('category') === 'Food Material' ? 'selected' : '' }}>Food
                                Material
                            </option>
                        </select>
                    </div>
                    <div class="column">
                        <label for="">Image:</label>
                        <input type="file" name="photo" id="" class="row2-input" />
                    </div>
                </div>
                <div class="row3">
                    <label for="">Product Description:</label>
                    <textarea required name="description" rows="5" placeholder="Eg. brand of the product" cols="5"
                        class="" value="{{ old('description') }}">{{ old('description') }}</textarea>
                </div>

                <hr>
                <div class="buttons">
                    <input type="submit" id="saveButton" class="add-green save"
                        style="font-family: 'Times New Roman', Times, serif; font-size: 1rem;" value="Add" />
                    {{-- <a href="{{ route('admin.product') }}" class="cancel closeModal">Cancel</a> --}}
                    <button type="button" class="closeModal">Cancel</button>
                </div>
            </form>

        </div>
    </div>


    <!-- Edit Modal -->
    @foreach ($products as $product)
        <div id="editModal{{ $product->id }}" class="modal editModal">
            <div class="edit-modal-content">
                <p class="taas-new">Edit Product </p>
                <hr>
                <form class="edit-modal-form" action="{{ route('admin.productUpdate', $product->id) }}"
                    enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row1">
                        <div class="column">
                            <label class="modal-top" for="">Product code:</label>
                            <input required type="text" name="code" pattern="[0-9]{3,11}" class="row1-input"
                                value="{{ $product->code }}" />
                        </div>
                        <div class="column">
                            <label for="">Product Name:</label>
                            <select class="select_product" name="name" class="product_name">
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->product_name }}"
                                        {{ $product->name === $supplier->product_name ? 'selected' : '' }}>
                                        {{ $supplier->product_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('name'))
                                <div class="text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="column">
                            <label for="">Stock Quantity:</label>
                            <input required autofocus type="number" name="quantity" class="row1-input autofocus"
                                value="{{ $product->quantity }}" />
                            @if ($errors->has('quantity'))
                                <div class="text-danger">{{ $errors->first('quantity') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row2">
                        <div class="column">
                            <label for="">Capital:</label>
                            <input required type="number" name="capital" id=""
                                value="{{ $product->capital }}" class="row2-input" />
                            @if ($errors->has('capital'))
                                <div class="text-danger">{{ $errors->first('capital') }}</div>
                            @endif
                        </div>
                        <div class="column">
                            <label for="">Unit Price:</label>
                            <input required type="number" name="unit_price" id=""
                                value="{{ $product->unit_price }}" class="row2-input" />
                            @if ($errors->has('unit_price'))
                                <div class="text-danger">{{ $errors->first('unit_price') }}</div>
                            @endif
                        </div>
                        <div class="column">
                            <label for="">Category:</label>
                            <select name="category" id="" class="row1-input select_categ">
                                <option value="Paper" {{ $product->category === 'Paper' ? 'selected' : '' }}>Paper
                                </option>
                                <option value="Machine" {{ $product->category === 'Machine' ? 'selected' : '' }}>
                                    Machine</option>
                                <option value="Food Material"
                                    {{ $product->category === 'Food Material' ? 'selected' : '' }}>
                                    Food Material</option>
                            </select>
                        </div>
                    </div>

                    <div class="row3-edit">
                        {{-- Image --}}
                        <div class="column">
                            <label for="">Current Image:</label>
                            <img class="img-edit" src="{{ asset($product->photo) }}" alt="" width="50px"
                                height="auto">
                        </div>
                        <div class="column">
                            <label for="">Change Image:</label>
                            {{-- <input required type="file" name="image" id="" class="row2-input" /> --}}
                            <input type="file" name="photo" id="" class="row2-input" />
                        </div>
                    </div>

                    <div class="row4">
                        <label for="">Product Description:</label>
                        <textarea required name="description" rows="5" placeholder="Eg. brand of the product" cols="5"
                            class="" value="">{{ $product->description }}</textarea>
                    </div>

                    <hr>
                    <div class="buttons">
                        <input type="submit" class="add-green"
                            style="font-family: 'Times New Roman', Times, serif; font-size: 1rem;" value="Update" />
                        <button type="button" class="closeEditModal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach


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
            <button type="button" id="newButton">Add New Product</button>
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
                        <option value="capital_asc" {{ request('sort') === 'capital_asc' ? 'selected' : '' }}>
                            Capital (ascending)</option>
                        <option value="unit_price_asc" {{ request('sort') === 'unit_price_asc' ? 'selected' : '' }}>Unit
                            Price
                            (ascending)
                        </option>
                    </select>
                    {{-- <button type="submit">Sort</button> --}}
                    {{-- <a href="{{ route('admin.product') }}" class="reset-sort">Reset sort</a> --}}
                </form>
            </div>
            <div>
                <form class="form-search" action="{{ route('admin.searchProduct') }}" method="GET">
                    {{-- <form class="form-search" action="" method="GET"> --}}
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
                    <th>Brand Name</th>
                    <th>Product Description</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Stock Quantity</th>
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
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->category }}</td>
                                <td>
                                    <img src="{{ asset($product->photo) }}" alt="{{ $product->name }}" width="auto"
                                        height="50px" style="background-color: transparent">
                                </td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->capital }}</td>
                                <td>{{ $product->unit_price }}</td>
                                <td class="actions">
                                    <div class="actions-container">
                                        <button type="button" class="edit editButton" id="edit"
                                            data-id="{{ $product->id }}">
                                            <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                        </button>

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
            </table>
            <div class="pagination">
                {{ $products->appends(['sort' => request('sort')])->links('layouts.customPagination') }}</div>
        </div>
    </div>

    <input type="hidden" id="showNotification" value="{{ count($lowQuantityNotifications) > 0 ? 'true' : 'false' }}">


@endsection

@section('footer')

@endsection

@section('script')
    <script>
        // Automatically submit the form when the sorting option changes
        document.getElementById('sortSelect').addEventListener('change', function() {
            document.getElementById('sortForm').submit();
        });
    </script>
@endsection
