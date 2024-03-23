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
    {{-- <div id="newModal" class="modal"> --}}
    <div id="newModal" class="modal" style="{{ session('reopenModal') ? 'display:block;' : 'display:none;' }}">

        <div class="modal-content">
            <span class="close closeModal">&times;</span>

            <form class="modal-form" action="{{ route('admin.supplierStore') }}" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-plus"></i>Add Supplier</h2>
                </center>
                <label class="modal-tops" for="">Company Name:</label>
                <input value="{{ old('company_name') }}" required autofocus type="text" name="company_name"
                    id="autofocus" />
                @if ($errors->has('company_name'))
                    <div class="text-danger">{{ $errors->first('company_name') }}</div>
                @endif

                <label for="">Contact Name:</label>
                <input value="{{ old('contact_name') }}" required type="text" name="contact_name" id="" />
                @if ($errors->has('contact_name'))
                    <div class="text-danger">{{ $errors->first('contact_name') }}</div>
                @endif

                <label for="">Contact Number:</label>
                <input value="{{ old('contact_num') }}" required type="tel" pattern="^\+?\d{4,14}$"
                    title="Enter a valid contact number" name="contact_num" id="" value="">
                @if ($errors->has('contact_num'))
                    <div class="text-danger">{{ $errors->first('contact_num') }}</div>
                @endif

                <label for="">Address:</label>
                <input value="{{ old('address') }}" required type="text" name="address" id="" />
                @if ($errors->has('address'))
                    <div class="text-danger">{{ $errors->first('address') }}</div>
                @endif

                {{-- <label for="">Product:</label>
                <input required type="text" name="product_name" id="product-supp" /> --}}

                <label for="">Product/s:</label>
                <div id="product-input-container">
                    <!-- Initial product input -->
                    <input required type="text" name="product_name[]" class="product-input" />
                    {{-- <button type="button" id="add-more-products">Add More Product</button> --}}
                </div>
                {{-- <button type="button" id="add-more-products" title="Click to add more product."><i class="fa-regular fa-plus"></i></button> --}}


                {{-- <div class="product-plus"> --}}
                {{-- <a href="#" id="plusSupplier"><i class="fa-regular fa-plus"></i></a> --}}
                {{-- <button type="submit" id="plusSupplier"><i class="fa-regular fa-plus"></i></button> --}}
                {{-- <button type="button" id="plusSupplier" onclick="submitFormAndReopenModal()" title="Click to add more product."><i
                            class="fa-regular fa-plus"></i></button> --}}
                {{-- </div> --}}

                {{-- <div id="productFields">
                    <label for="products[0]">Product:</label>
                    <input required type="text" name="products[0]" /> Pili ka ng isa kung ito
                    <input required type="text" name="product_name[0]" /> o ito
                </div>
                <button type="button" id="addProduct">Add Another Product</button> --}}

                @if ($errors->has('product_name'))
                    <div class="text-danger">{{ $errors->first('product_name') }}</div>
                @endif



                <div class="add-save">
                    <button type="button" id="add-more-products" title="Click to add more product.">Add More Product</button>
                    <input class="add" type="submit" value="Save Supplier" />
                </div>
            </form>
        </div>
    </div>

    {{-- Add Existing Quantity
    <div id="newModalQty" class="modal">
        <div class="modal-content">
            <span class="close closeModal">&times;</span>

            <form class="modal-form" action="{{ route('admin.supplierStoreQty') }}" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-plus"></i>Add Supplier</h2>
                </center>
                <label class="modal-tops" for="">Company Name:</label>
                <input required autofocus type="text" name="company_name" id="autofocus" />
                <label for="">Contact Name:</label>
                <input required type="text" name="contact_name" id="" />
                <label for="">Contact Number:</label>
                <input required type="text" pattern="{5,15}" title="Enter a valid contact number" name="contact_num"
                    id="" value="">
                <label for="">Address:</label>
                <input required type="text" name="address" id="" />

                <label for="">Product:</label>
                <select required class="select_product" name="product_name" id="product_name">
                    <option value="" disabled selected>-- Select a Product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->name }}" {{ old('product_name') === $product->name ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>

                <label for="">Quantity:</label>
                <input required type="number" name="quantity" />


                <input class="add" type="submit" value="Add" />
            </form>
        </div>
    </div> --}}

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
            <div class="buttons-quantity">
                <button class="add" type="button" id="newButton">Add Supplier</button>
                {{-- <button class="add" type="button" id="newButtonQty">Add Quantity of an Existing Product</button> --}}
            </div>




            <div class="sort-by">
                <form id="sortForm" action="#" method="GET">
                    <input type="hidden" name="sort" id="sortInput" value="{{ request('sort') }}">

                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sortSelect">
                        <option selected value="" {{ request('sort') === '' ? 'selected' : '' }}>--
                            Default Sorting --</option>
                        <option value="company_name_asc" {{ request('sort') === 'company_name_asc' ? 'selected' : '' }}>
                            Company Name</option>
                        {{-- <option value="contact_name_asc" {{ request('sort') === 'contact_name_asc' ? 'selected' : '' }}>
                            Contact Name</option>
                        <option value="address_asc" {{ request('sort') === 'address_asc' ? 'selected' : '' }}>Address
                        </option> --}}
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
                    <th>Product/s</th>
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
                                {{-- <td>{{ $supplier->product_name }}</td> --}}
                                <td>{{ implode(', ', json_decode($supplier->product_name, true)) }}</td>


                                <td class="actions">
                                    <div class="actions-container">
                                        <form action="{{ route('admin.supplierEdit', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" class="edit editButton" id="edit"
                                                title="Click this button to add to the product's quantity.">
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
    {{-- @if (session('message'))
        <script>
            alert('{{ session('message') }}');
        </script>
    @endif --}}
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

    {{-- Plus product  --}}
    <script>
        function submitFormAndReopenModal() {
            const form = document.querySelector('.modal-form'); // Adjust the selector as needed
            // Optionally, add a hidden input to indicate the modal should stay open
            const keepModalOpenInput = document.createElement('input');
            keepModalOpenInput.type = 'hidden';
            keepModalOpenInput.name = 'keepModalOpen';
            keepModalOpenInput.value = '1';
            form.appendChild(keepModalOpenInput);

            form.submit(); // Submit the form
        }
    </script>

    {{-- <script>
        document.getElementById('addProduct').addEventListener('click', function() {
            const newProductIndex = document.querySelectorAll('#productFields > input').length;
            const newFieldHTML = `
            <div>
                <label for="products[${newProductIndex}]">Product:</label>
                <input required type="text" name="products[${newProductIndex}]" />
            </div>
        `;
            document.getElementById('productFields').insertAdjacentHTML('beforeend', newFieldHTML);
        });
    </script> --}}

    <script>
        document.getElementById('add-more-products').addEventListener('click', function() {
            var newInput = document.createElement('input');
            newInput.setAttribute('type', 'text');
            newInput.setAttribute('name', 'product_name[]'); // Important: Use array notation for the name attribute
            newInput.setAttribute('required', 'true');
            newInput.classList.add('product-input');

            document.getElementById('product-input-container').appendChild(newInput);
        });
    </script>


    <script src="{{ asset('js/supplier.js') }}"></script>

@endsection
