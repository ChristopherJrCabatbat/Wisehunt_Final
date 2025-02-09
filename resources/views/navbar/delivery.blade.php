@extends('../layouts.layout')

@section('title', 'Delivery')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/delivery-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay"></div>

    {{-- Add Modal --}}

    <form class="modal-form" id="addDeliveryForm" action="{{ route('admin.deliveryStore') }}" method="POST">
        @csrf
        <div id="newModal" class="modal" style="@if ($errors->any()) display:block; @endif">
            <div class="modal-content-delivery">
                <span class="close closeModal" onclick="window.closeModal()">&times;</span>
                <center>
                    <h2 style="margin: 0%; color:#333; "><i class="fa-regular fa-plus"></i>Add Delivery</h2>
                </center>
                <label class="modal-tops" for="">Delivery ID:</label>
                {{-- pattern="^.{5,15}$" --}}
                <input required autofocus type="text" name="delivery_id" id="autofocus"
                    value="{{ old('delivery_id') }}" />
                @if ($errors->has('delivery_id'))
                    <div class="text-danger">{{ $errors->first('delivery_id') }}</div>
                @endif

                <label class="modal-tops" for="">Customer Name:</label>
                <input required type="text" name="customer_name_id" id=""
                    value="{{ old('customer_name_id') }}" />
                @if ($errors->has('customer_name_id'))
                    <div class="text-danger">{{ $errors->first('customer_name_id') }}</div>
                @endif

                <label for="">Mode of Payment:</label>
                <select required name="mode_of_payment" id="" class="">
                    <option disabled selected value="">-- Select Mode of Payment --</option>
                    <option value="Cash on Delivery" {{ old('mode_of_payment') === 'Cash on Delivery' ? 'selected' : '' }}>
                        Cash on Delivery
                    </option>
                    <option value="Bank Transfers" {{ old('mode_of_payment') === 'Bank Transfers' ? 'selected' : '' }}>Bank
                        Transfers
                    </option>
                </select>
                @if ($errors->has('mode_of_payment'))
                    <div class="text-danger">{{ $errors->first('mode_of_payment') }}</div>
                @endif

                {{-- <label class="modal-tops" for="">Address:</label>
                <input required type="text" name="address" id="" value="{{ old('address') }}" />
                @if ($errors->has('address'))
                    <div class="text-danger">{{ $errors->first('address') }}</div>
                @endif --}}

                {{-- <input type="text" value="Not Delivered" name="status" readonly> --}}
                <label for="">Pending Status:</label>
                <select required name="status" id="" class="" title="You can change the default after finishing adding a delivery.">
                    <option value="Not Delivered" {{ old('status') === 'Not Delivered' ? 'selected' : '' }}>Not Delivered
                    </option>
                </select>
                @if ($errors->has('status'))
                    <div class="text-danger">{{ $errors->first('status') }}</div>
                @endif

                <input class="add nextButton" type="button" id="nextButton" value="Next" />
            </div>
        </div>

        @php
            use App\Models\Transaction;

            $transactedQuantities = Transaction::all()->pluck('transacted_qty', 'product_name')->toArray();
        @endphp

        {{-- Product Selection Modal --}}
        <div id="productModal" class="" style="display:none;">
            <div class="modal-content-delivery-next">
                <span class="close closeModal" onclick="window.closeModal()">&times;</span>

                <center>
                    <h2 style="margin: 0%; color:#333; font-size: 1.4rem">Select Products to Deliver</h2>
                </center>

                {{-- Error message for delivery quantity --}}
                {{-- @if ($errors->has('error_delivery'))
                    <div class="text-danger">
                        {{ $errors->first('error_delivery') }}
                    </div>
                @endif --}}

                @if (count($products) === 0)
                    <p>No products available for delivery.</p>
                @else
                    {{-- Display products with checkboxes --}}
                    @foreach ($products as $index => $product)
                        <label>
                            <input type="checkbox" name="product[]" value="{{ $product->product_name_id }}"
                                {{ in_array($product->product_name_id, old('product', [])) ? 'checked' : '' }} />
                            {{ $product->product_name_id }}
                        </label>
                        @if ($loop->first)
                            <!-- Display the first quantity input without any condition -->
                            <input type="number" name="quantity[{{ $index }}]" placeholder="Quantity"
                                value="{{ old('quantity.' . $index) }}" />
                        @else
                            <!-- Display the quantity input only if the checkbox is checked -->
                            <input type="number" name="quantity[{{ $index }}]" placeholder="Quantity"
                                value="{{ in_array($product->product_name_id, old('product', [])) ? old('quantity.' . $index) : '' }}"
                                {{ in_array($product->product_name_id, old('product', [])) ? 'required' : '' }} />
                        @endif
                    @endforeach
                @endif

                <div class="buttons">
                    <input class="add backButton" type="button" id="backButton" value="Back" />
                    <input class="add" type="submit" value="Add" id="submit" />
                </div>
            </div>
        </div>
    </form>

    <!-- Delivery Modal -->
    {{-- <div id="deliveryModal" class="modal">
        <div class="modal-content-delivery-name">
            <span class="closes">&times;</span>
            <h2>Delivery Details</h2>
            <table id="modalTable">
                @foreach ($deliveries as $delivery)
                    <tr>
                        <th colspan="2">Delivery ID: {{ $delivery->delivery_id }}</th>
                    </tr>
                    <tr>
                        <th colspan="2">Customer: {{ $delivery->name }}</th>
                    </tr>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                    </tr>
                    <tr>
                        <td>{{ $delivery->product }}</td>
                        <td>{{ $delivery->quantity }}</td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div> --}}

    <!-- Delivery Modal -->
    <div id="deliveryModal" class="modal">
        <div class="modal-content-delivery-name">
            <span class="closes">&times;</span>
            <h2 class="delivery-datails">Delivery Details</h2>
            <table id="modalTable">
                <!-- Dynamically filled based on the delivery clicked -->
            </table>
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
                <a class="sidebar active" href="{{ route('admin.delivery') }}">
                    <img src="{{ asset('images/delivery.png') }}" class="delivery-i" alt="">
                    DELIVERY</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('admin.returned') }}">
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
                <button class="add" type="button" id="newButton">Add Delivery</button>
            </form>
        </div>
        <div class="table">
            <table>

                <tr>
                    <th colspan="11" class="table-th">DELIVERIES</th>
                </tr>

                @php
                    // Calculate the initial row number based on the current page
                    $rowNumber = ($deliveries->currentPage() - 1) * $deliveries->perPage() + 1;
                @endphp

                <tr>
                    <th>No.</th>
                    <th>Delivery ID</th>
                    <th>Name</th>
                    <th>Mode of Payement</th>
                    {{-- <th>Product/s</th>
                    <th>Quantity</th> --}}
                    {{-- <th>Address</th> --}}
                    <th class="status">Pending Status</th>
                    {{-- <th>Delete</th> --}}
                </tr>

                <tbody>
                    @if ($deliveries->isEmpty())
                        <tr>
                            <td colspan="7">No data found.</td>
                        </tr>
                    @else
                        {{-- @foreach ($users as $user) --}}
                        @foreach ($deliveries as $delivery)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $delivery->delivery_id }}</td>
                                {{-- <td class="delivery-name">{{ $delivery->name }}</td> --}}
                                <td class="delivery-name" data-delivery-id="{{ $delivery->id }}"
                                    title="Click to view more delivery details.">{{ $delivery->customer_name_id }}
                                </td>

                                <td>{{ $delivery->mode_of_payment }}</td>

                                {{-- <td>{{ $delivery->product }}</td>
                                <td>{{ $delivery->quantity }}</td> --}}

                                {{-- <td>
                                    @foreach (json_decode($delivery->product) as $index => $product) --}}
                                {{-- @foreach ($delivery->product as $index => $product) --}}
                                {{-- {{ $product }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td> --}}

                                {{-- <td>
                                    @foreach (json_decode($delivery->quantity) as $index => $quantity)
                                        {{ $quantity }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td> --}}

                                {{-- <td>
                                    @foreach (json_decode($delivery->quantity) as $index => $quantity) --}}
                                {{-- @foreach ($delivery->quantity as $index => $quantity) --}}
                                {{-- @if ($quantity !== null)
                                            {{ $quantity }}
                                            @if (!$loop->last && count(json_decode($delivery->quantity)) > 1) --}}
                                {{-- @if (!$loop->last && count($delivery->quantity) > 1) --}}
                                {{-- ,
                                            @endif
                                        @endif
                                    @endforeach
                                </td> --}}

                                {{-- {{ $delivery->address }} --}}
                                {{-- <form action="">
                                    <select required name="status" id="" class="">
                                        <option value="Delivered" {{ old('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="Not Delivered" {{ old('status') === 'Not Delivered' ? 'selected' : '' }}>Not Delivered</option>
                                    </select>
                                </form> --}}

                                <td class="status">
                                    <form id="statusForm">
                                        <select required name="status" id="status" class="status-select"
                                            data-delivery-id="{{ $delivery->id }}">
                                            <option value="Delivered"
                                                {{ $delivery->status === 'Delivered' ? 'selected' : '' }}>Delivered
                                            </option>
                                            <option value="Not Delivered"
                                                {{ $delivery->status === 'Not Delivered' ? 'selected' : '' }}>Not Delivered
                                            </option>
                                        </select>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>

            </table>

            <div class="pagination">{{ $deliveries->links('layouts.customPagination') }}</div>

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
    <script src="{{ asset('js/delivery.js') }}"></script>

    {{-- <script>
        var modalContent = document.querySelector('.modal-content-delivery-next');
        if (modalContent.clientHeight >= 609) {
            modalContent.classList.add('two-column-layout');
        } else {
            modalContent.classList.remove('two-column-layout');
        } --}}
    </script>

    {{-- Not let the user go to Next and submit if all the required fields are not filled. --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="product[]"]');
            const quantityInputs = document.querySelectorAll('input[name^="quantity["]');

            checkboxes.forEach((checkbox, index) => {
                checkbox.addEventListener('change', function() {
                    const quantityInput = quantityInputs[index];
                    if (this.checked) {
                        quantityInput.required = true;
                    } else {
                        quantityInput.required = false;
                        quantityInput.value = ''; // Clear the value if checkbox is unchecked
                    }
                });
            });

            const deliveryIdInput = document.getElementById('autofocus');
            const nameInput = document.getElementsByName('customer_name_id')[0];
            const addressInput = document.getElementsByName('mode_of_payment')[0];
            const statusSelect = document.getElementsByName('status')[0];
            const nextButton = document.getElementById('nextButton');

            // Function to check if all required inputs are filled
            function checkFormCompleteness() {
                const isComplete = deliveryIdInput.value.trim() !== '' &&
                    nameInput.value.trim() !== '' &&
                    // addressInput.value.trim() !== '' &&
                    addressInput.value !== '';
                statusSelect.value !== '';

                console.log('isComplete:', isComplete); // Log isComplete value
                // Do not disable the Next button
                // nextButton.disabled = !isComplete;

                return isComplete; // Return the boolean value
            }

            // Event listeners for input changes
            deliveryIdInput.addEventListener('input', checkFormCompleteness);
            nameInput.addEventListener('input', checkFormCompleteness);
            // addressInput.addEventListener('input', checkFormCompleteness);
            addressInput.addEventListener('change', checkFormCompleteness);
            statusSelect.addEventListener('change', checkFormCompleteness);

            // Handle Next button click
            nextButton.addEventListener('click', function() {
                console.log('Next button clicked');
                if (!checkFormCompleteness()) {
                    console.log('Fields not complete. Showing alert.');
                    alert('Please fill in all fields before proceeding.');
                } else {
                    console.log('Fields complete. Opening Product Selection Modal.');
                    document.getElementById('newModal').style.display = 'none';
                    document.getElementById('productModal').style.display = 'block';
                }
            });

            // Handle Back button click
            const backButton = document.getElementById('backButton');
            backButton.addEventListener('click', function() {
                // Add logic to go back to the previous step/modal if needed
                document.getElementById('newModal').style.display = 'block';
                document.getElementById('productModal').style.display = 'none';
            });
        });
    </script>

    {{-- Auto Pending Status --}}
    <script>
        $(document).ready(function() {
            $('.status-select').on('change', function() {
                const deliveryId = $(this).data('delivery-id');
                const selectedStatus = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.deliveryUpdate') }}', // Replace with your actual route
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'delivery_id': deliveryId,
                        'status': selectedStatus
                    },
                    success: function(response) {
                        // Handle success if needed
                        console.log(response);
                    },
                    error: function(error) {
                        // Handle error if needed
                        console.log(error);
                    }
                });
            });
        });
    </script>

    <script>
        var transactedQuantities = @json($transactedQuantities);
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form'); // Replace with your actual form selector
            const quantityInputs = document.querySelectorAll('input[name^="quantity["]');
            const addButton = document.getElementById('submit');

            addButton.addEventListener('click', function(event) {
                let isQuantityValid = true;

                quantityInputs.forEach(input => {
                    const index = input.name.match(/\[([^\]]+)\]/)[1]; // Extract index
                    const productName = getProductFromIndex(index); // Get product name from index
                    const enteredQuantity = parseInt(input.value, 10);

                    if (transactedQuantities[productName] !== undefined && enteredQuantity >
                        transactedQuantities[productName]) {
                        alert(
                            `Quantity for ${productName} exceeds transacted quantity. Available: ${transactedQuantities[productName]}`);
                        isQuantityValid = false;
                    }
                });

                if (isQuantityValid) {
                    form.submit(); // Only submit the form if all quantities are valid
                }
            });

            function getProductFromIndex(index) {
                // Implement this function based on how you map indices to product names
                // Example (you'll need to replace this with your actual logic):
                return document.querySelector(`input[name="product[${index}]"]`).value;
            }
        });
    </script> --}}

    <script>
        addButton.addEventListener('click', function(event) {
            let isQuantityValid = true;

            quantityInputs.forEach(input => {
                const index = input.name.match(/\[([^\]]+)\]/)[1]; // Extract index
                const productName = getProductFromIndex(index); // Get product name from index
                const enteredQuantity = parseInt(input.value, 10);

                if (transactedQuantities[productName] !== undefined && enteredQuantity >
                    transactedQuantities[productName]) {
                    alert(
                        `Quantity for ${productName} exceeds transacted quantity. Available: ${transactedQuantities[productName]}`
                    );
                    isQuantityValid = false;
                }
            });

            if (isQuantityValid) {
                form.submit(); // Only submit the form if all quantities are valid
            }
        });
    </script>

    {{-- Delivery Modal --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the modal
            var modal = document.getElementById('deliveryModal');

            // Get the <span> element that closes the modal
            var span = document.querySelector(".closes"); // More specific selector

            // When the user clicks on the deliverer's name, open the modal
            var names = document.querySelectorAll('.delivery-name');
            names.forEach(function(name) {
                name.onclick = function() {
                    modal.style.display = "block";
                }
            });

            // When the user clicks on <span> (x), close the modal
            if (span) { // Check if span is found to avoid null reference errors
                span.onclick = function() {
                    modal.style.display = "none";
                }
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script> --}}

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deliveryNames = document.querySelectorAll('.delivery-name');
            const modal = document.getElementById('deliveryModal');
            const modalTable = document.getElementById('modalTable');
            const closeModal = document.querySelector('.closes');

            if (!modal || !closeModal) {
                console.error('Modal or closeModal not found');
                return;
            }

            // Close modal event
            closeModal.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            if (deliveryNames.length === 0) {
                console.error('No delivery names found');
                return;
            }

            deliveryNames.forEach(name => {
                name.addEventListener('click', function() {
                    const deliveryId = this.getAttribute(
                        'data-delivery-id'
                    ); // Ensure each delivery name element has a data-delivery-id attribute
                    console.log(`Delivery name clicked: ${deliveryId}`);

                    fetch(`/admin/delivery/details/${deliveryId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', data);

                            // Parse product and quantity JSON strings into arrays
                            const products = JSON.parse(data.product);
                            const quantities = JSON.parse(data.quantity);

                            // Start building the table content with customer and delivery ID
                            let tableContent = `
        <tr>
            <th class="id-customer" colspan="3">Delivery ID: ${data.delivery_id}</th>
        </tr>
        <tr>
            <th class="id-customer" colspan="3">Customer: ${data.customer_name_id}</th>
        </tr>
        <tr>
            <th class="th-blue first">Product</th>
            <th class="th-blue">Quantity</th>
            <th class="th-blue">Total Price</th>
        </tr>
        `;

                                // Add a row for each product-quantity pair
                                products.forEach((product, index) => {
                                    const quantity = quantities[
                                        index]; // Match product with its quantity
                                    tableContent += `
                <tr>
                    <td>${product}</td>
                    <td>${quantity}</td>
                    <td>${quantity}</td>
                </tr>
            `;
                                });

                                // Update modalTable's innerHTML with the built table content
                                modalTable.innerHTML = tableContent;
                                modal.style.display = 'block';
                            })

                            .catch(error => {
                                console.error('Error loading delivery details:', error);
                            });

                    });
                });
            });
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deliveryNames = document.querySelectorAll('.delivery-name');
            const modal = document.getElementById('deliveryModal');
            const modalTable = document.getElementById('modalTable');
            const closeModal = document.querySelector('.closes');

            if (!modal || !closeModal) {
                console.error('Modal or closeModal not found');
                return;
            }

            closeModal.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            if (deliveryNames.length === 0) {
                console.error('No delivery names found');
                return;
            }

            deliveryNames.forEach(name => {
                name.addEventListener('click', function() {
                    const deliveryId = this.getAttribute('data-delivery-id');
                    console.log(`Delivery name clicked: ${deliveryId}`);

                    fetch(`/admin/delivery/details/${deliveryId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', data);

                            // Assuming we have a way to get prices. For example:
                            // const prices = { "Product1": 10, "Product2": 20, ... };
                            const products = JSON.parse(data.product);
                            const quantities = JSON.parse(data.quantity);

                            let tableContent = `
                                <tr>
                                    <th class="id-customer" colspan="3">Delivery ID: ${data.delivery_id}</th>
                                </tr>
                                <tr>
                                    <th class="id-customer" colspan="3">Customer: ${data.customer_name_id}</th>
                                </tr>
                                <tr>
                                    <th class="th-blue first">Product</th>
                                    <th class="th-blue">Quantity</th>
                                </tr>
                                `;

                            products.forEach((product, index) => {
                                const quantity = quantities[index];
                                // Placeholder for price calculation, adjust as needed
                                const price =
                                10; // Assuming a fixed price for simplicity; replace with actual price lookup
                                const totalPrice = quantity * price;

                                tableContent += `
                                <tr>
                                    <td>${product}</td>
                                    <td>${quantity}</td>
                                </tr>
                                `;
                            });

                            modalTable.innerHTML = tableContent;
                            modal.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error loading delivery details:', error);
                        });
                });
            });
        });
    </script>




@endsection
