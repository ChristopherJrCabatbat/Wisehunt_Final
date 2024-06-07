@extends('../layouts.layout')

@section('title', 'Return')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/returned-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <span class="close closeModal">&times;</span>

            <form class="modal-form" action="{{ route('admin.returnedStore') }}" enctype="multipart/form-data"
                method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-plus"></i>Return Product/s</h2>
                </center>

                <label class="modal-tops" for="">Customer Information:</label>
                <input required autofocus type="text" name="company_name" id="autofocus"
                    value="{{ old('company_name') }}" placeholder="Company Name" />
                <input required autofocus type="text" name="contact_name" id="autofocus"
                    value="{{ old('contact_name') }}" placeholder="Contact Name" />
                <input required autofocus type="tel" pattern="^\+?\d{4,14}$" title="Enter a valid contact number"
                    name="contact_number" id="autofocus" value="{{ old('contact_number') }}" placeholder="Contact Number" />

                <label for="">Reason for Returning:</label>
                <textarea required name="reason" rows="3" placeholder="Eg. defective..." cols="5" class=""
                    value="{{ old('reason') }}">{{ old('reason') }}</textarea>

                <label for="">Date Returned:</label>
                <input value="{{ old('date_returned') }}" required type="date" name="date_returned" id="" />
                @if ($errors->has('date_returned'))
                    <div class="text-danger">{{ $errors->first('date_returned') }}</div>
                @endif

                <label for="">Product/s to Return:</label>
                <div id="product-input-container">
                    <div class="product-unit-group">
                        <input required type="text" name="returned_product[]" class="product-input" />
                    </div>
                </div>

                {{-- <input class="add" type="submit" value="Add" /> --}}
                <div class="add-save">
                    <button type="button" id="add-more-products" title="Click to add more product.">Return More
                        Product</button>
                    <input class="add" type="submit" value="Return Product/s" />
                </div>
            </form>
        </div>
    </div>

    <!-- Returned Products Modal -->
    <div id="viewProductsModal" class="modal-view">
        <!-- Modal content -->
        <div class="modal-content-view">
            <span class="close-view">&times;</span>
            <h2>Returned Product/s</h2>
            <div id="productsList">
                <!-- Products will be dynamically inserted here -->
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
                <a class="sidebar active" href="{{ route('admin.returned') }}">
                    <i class="fa-solid fa-arrow-rotate-left return-i" style="color: #ffffff;"></i>
                    RETURN</a>
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
            <form id="addCustomerForm">
                <button class="add" type="button" id="newButton">Return Product/s</button>
            </form>
        </div>
        <div class="table">
            <table>

                <tr>
                    <th colspan="11" class="table-th">RETURNS</th>
                </tr>

                @php
                    // Calculate the initial row number based on the current page
                    $rowNumber = ($returneds->currentPage() - 1) * $returneds->perPage() + 1;
                @endphp

                <tr>
                    <th>No.</th>
                    <th>Company Name</th>
                    <th>Contact Name</th>
                    <th>Contact Number</th>
                    <th>Returned Product/s</th>
                    <th>Reason for Returning</th>
                    <th>Date Returned</th>
                    <th>Actions</th>
                </tr>

                <tbody>
                    @if ($returneds->isEmpty())
                        <tr>
                            <td colspan="7">No data found.</td>
                        </tr>
                    @else
                        @foreach ($returneds as $returned)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>


                                <td>{{ $returned->company_name }}</td>
                                <td>{{ $returned->contact_name }}</td>
                                <td>{{ $returned->contact_number }}</td>
                                <td>{{ implode(', ', json_decode($returned->returned_product, true)) }}...
                                <td>{{ $returned->reason }}</td>
                                <td>{{ \Carbon\Carbon::parse($returned->date_returned)->format('M. d, Y') }}</td>

                                <td class="actions">
                                    <div class="actions-container">

                                        {{-- <form action="{{ route('admin.returnedEdit', $returned->id) }}" method="POST">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" class="edit editButton" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form> --}}

                                        {{-- onclick="showProducts('{{ addslashes($returned->company_name) }}', '{{ $returned->product_name_id }}', '{{ $returned->unit }}')"><img
                                                src="{{ asset('images/visible.png') }}" --}}
                                        <button type="button" class="view-button"
                                            onclick="showProducts('{{ addslashes($returned->company_name) }}', '{{ $returned->returned_product }}')"><img
                                                src="{{ asset('images/visible.png') }}" class="" alt=""
                                                style="height: auto; width: 23px;"></button>

                                        <form action="{{ route('admin.returnedDestroy', $returned->id) }}"
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

            </table>

            <div class="pagination">{{ $returneds->links('layouts.customPagination') }}</div>

        </div>
    </div>
@endsection

@section('footer')
    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
@endsection

@section('script')

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listener for adding more product inputs
            document.getElementById('add-more-products').addEventListener('click', function() {
                // Create a container div for the new input and dropdown
                var productUnitGroup = document.createElement('div');
                productUnitGroup.classList.add('product-unit-group');

                // Create the new input element
                var newInput = document.createElement('input');
                newInput.setAttribute('type', 'text');
                newInput.setAttribute('name', 'returned_product[]');
                newInput.setAttribute('required', 'true');
                newInput.classList.add('product-input');

                // Create the new dropdown element
                var newSelect = document.createElement('select');
                newSelect.setAttribute('name', 'unit[]');
                newSelect.classList.add('unit-select');

                // Append the input and dropdown to the container div
                productUnitGroup.appendChild(newInput);

                // Append the container div to the product input container
                document.getElementById('product-input-container').appendChild(productUnitGroup);
            });

            // Listener for the close button of the modal
            document.querySelector('.closeModal').addEventListener('click', function() {
                // Select all product input elements
                var inputs = document.querySelectorAll('#product-input-container .product-unit-group');
                // Keep only the first input, remove the rest
                if (inputs.length > 1) {
                    for (var i = 1; i < inputs.length; i++) {
                        inputs[i].parentNode.removeChild(inputs[i]);
                    }
                }

                // Optionally reset the value of the first input if needed
                inputs[0].querySelector('.product-input').value = '';
            });
        });
    </script>

    {{-- View Product Script --}}
    <script>
        function showProducts(companyName, returnedProductJson) {
            const returnedProduct = JSON.parse(returnedProductJson);
            let tableContent =
                `<table><tr><th colspan="2">Returned product/s of ${companyName}:</th></tr>`; // Use companyName for the table header
            for (let i = 0; i < returnedProduct.length; i++) {
                tableContent += `<tr><td>${returnedProduct[i]}</td></tr>`;
            }

            tableContent += '</table>';

            // Set the content in the modal's body
            document.getElementById('productsList').innerHTML = tableContent;

            // Show the modal
            document.getElementById('viewProductsModal').style.display = 'block';
        }

        // Initialization logic to hide the modal on page load, if not already done
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('viewProductsModal').style.display = 'none';
        });

        // Close modal logic
        var span = document.getElementsByClassName("close-view")[0];
        span.onclick = function() {
            document.getElementById('viewProductsModal').style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('viewProductsModal')) {
                document.getElementById('viewProductsModal').style.display = "none";
            }
        }
    </script>

@endsection
