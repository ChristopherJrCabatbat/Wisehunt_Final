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

    {{-- <div class="overlay"></div> --}}

    {{-- Add Modal --}}

    <form class="modal-form" id="addDeliveryForm" action="{{ route('admin.deliveryStore') }}" method="POST">
        <div id="newModal" class="modal">
            <div class="modal-content-delivery">
                <span class="close closeModal" onclick="window.closeModal()">&times;</span>
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333; "><i class="fa-regular fa-plus"></i>Add Delivery</h2>
                </center>
                <label class="modal-tops" for="">Delivery ID:</label>
                <input required autofocus type="text" name="delivery_id" id="autofocus" pattern="{5,15}"
                    value="{{ old('delivery_id') }}" />
                @if ($errors->has('delivery_id'))
                    <div class="text-danger">{{ $errors->first('delivery_id') }}</div>
                @endif

                <label class="modal-tops" for="">Name:</label>
                <input required type="text" name="name" id="" value="{{ old('name') }}" />
                @if ($errors->has('name'))
                    <div class="text-danger">{{ $errors->first('name') }}</div>
                @endif

                <label class="modal-tops" for="">Address:</label>
                <input required type="text" name="address" id="" value="{{ old('address') }}" />
                @if ($errors->has('address'))
                    <div class="text-danger">{{ $errors->first('address') }}</div>
                @endif

                <label for="">Pending Status:</label>
                <select required name="status" id="" class="">
                    <option disabled selected value="">-- Select Status --</option>
                    <option value="Delivered" {{ old('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="Not Delivered" {{ old('status') === 'Not Delivered' ? 'selected' : '' }}>Not Delivered
                    </option>
                </select>
                @if ($errors->has('status'))
                    <div class="text-danger">{{ $errors->first('status') }}</div>
                @endif

                <input class="add nextButton" type="button" id="nextButton" value="Next" />
            </div>
        </div>


        {{-- Product Selection Modal --}}
        <div id="productModal" class="" style="display:none;">
            <div class="modal-content-delivery-next">
                <span class="close closeModal" onclick="window.closeModal()">&times;</span>


                <center>
                    <h2 style="margin: 0%; color:#333; font-size: 1.4rem">Select Products to Deliver</h2>
                </center>

                {{-- Display products with checkboxes
                @foreach ($products as $product)
                    <label>
                        <input type="checkbox" name="product[]" value="{{ $product->name }}" />
                        {{ $product->name }}
                    </label>
                    <input type="number" name="quantity[]" placeholder="Quantity" />
                @endforeach --}}

                {{-- Display products with checkboxes --}}
                @foreach ($products as $index => $product)
                    <label>
                        <input type="checkbox" name="product[]" value="{{ $product->name }}" />
                        {{ $product->name }}
                    </label>
                    @if ($loop->first)
                        <!-- Display the first quantity input without any condition -->
                        <input type="number" name="quantity[{{ $index }}]" placeholder="Quantity" />
                    @else
                        <!-- Display the quantity input only if the checkbox is checked -->
                        @if (old('product') && in_array($product->name, old('product')))
                            <input type="number" name="quantity[{{ $index }}]" placeholder="Quantity" required />
                        @else
                            <input type="number" name="quantity[{{ $index }}]" placeholder="Quantity" />
                        @endif
                    @endif
                @endforeach


                <div class="buttons">
                    <input class="add backButton" type="button" id="backButton" value="Back" />
                    <input class="add" type="submit" value="Add" />
                </div>
            </div>
        </div>
    </form>


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
                    <th>Product/s</th>
                    <th>Quantity</th>
                    <th>Address</th>
                    <th>Pending Status</th>
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
                                <td>{{ $delivery->name }}</td>

                                {{-- <td>{{ $delivery->product }}</td>
                                <td>{{ $delivery->quantity }}</td> --}}

                                <td>
                                    @foreach (json_decode($delivery->product) as $index => $product)
                                        {{ $product }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>

                                {{-- <td>
                                    @foreach (json_decode($delivery->quantity) as $index => $quantity)
                                        {{ $quantity }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td> --}}

                                <td>
                                    @foreach (json_decode($delivery->quantity) as $index => $quantity)
                                        @if ($quantity !== null)
                                            {{ $quantity }}
                                            @if (!$loop->last && count(json_decode($delivery->quantity)) > 1)
                                                ,
                                            @endif
                                        @endif
                                    @endforeach
                                </td>

                                <td>{{ $delivery->address }}</td>
                                <td class="status">
                                    {{-- {{ $delivery->address }} --}}
                                    {{-- <form action="">
                                        <select required name="status" id="" class="">
                                            <option value="Delivered" {{ old('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="Not Delivered" {{ old('status') === 'Not Delivered' ? 'selected' : '' }}>Not Delivered</option>
                                        </select>
                                    </form> --}}
                                    
                                    <form id="statusForm">
                                        <select required name="status" id="status" class="status-select" data-delivery-id="{{ $delivery->id }}">
                                            <option value="Delivered" {{ $delivery->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="Not Delivered" {{ $delivery->status === 'Not Delivered' ? 'selected' : '' }}>Not Delivered</option>
                                        </select>
                                    </form>
                                    
                                </td>


                                {{-- <td class="actions">
                                    <div class="actions-container">
                                        <form action="{{ route('admin.deliveryEdit', $delivery->id) }}" method="POST">
                                            @csrf
                                            @method('GET')  
                                            <button type="submit" class="edit editButton" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.deliveryDestroy', $delivery->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this?')"
                                                type="submit" class="delete" id="delete">
                                                <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td> --}}

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

@endsection

@section('script')
    <script src="{{ asset('js/delivery.js') }}"></script>

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
        });
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelects = document.querySelectorAll('select[name="status"]');

            statusSelects.forEach((select) => {
                select.addEventListener('change', function() {
                    // Assuming the form has an ID 'addDeliveryForm'
                    const form = document.getElementById('addDeliveryForm');
                    form.submit(); // Submit the form when the status is changed
                });
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            $('.status-select').on('change', function() {
                const deliveryId = $(this).data('delivery-id');
                const selectedStatus = $(this).val();
    
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.deliveryUpdate") }}', // Replace with your actual route
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
    
@endsection
