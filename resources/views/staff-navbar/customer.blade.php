@extends('../layouts.layout')

@section('title', 'Customer')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">

    <style>
        .delivery-i {
            top: 259px;
        }
    </style>
@endsection

@section('modals')

    <div class="overlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <span class="closeModal close">&times;</span>

            <form class="modal-form" action="{{ route('staff.customerStore') }}" method="POST">
                @csrf

                <center>
                    <h2 style="margin: 0%; color:#333;">Add Customer</h2>
                </center>

                <label class="modal-top" for="">Company Name:</label>
                <input required autofocus type="text" name="name" id="autofocus" />

                <label for="">Contact Name:</label>
                <input required type="text" name="contact_name" id="" />

                <label for="">Contact Number:</label>
                <input required type="text" pattern="{5,15}" title="Enter a valid contact number" name="contact_num"
                    id="" value="">

                <label for="">Address:</label>
                <input required type="text" name="address" id="" />

                <input class="add" type="submit" value="Add" />
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
        {{-- <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('staff.product') }}">
                    <img src="{{ asset('images/product-xxl.png') }}" class="product-i" alt="">
                    PRODUCT</a>
            </div>
        </li> --}}
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('staff.transaction') }}">
                    <img src="{{ asset('images/transaction.png') }}" class="staff-transaction-i" alt="">
                    TRANSACTION</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar active" href="{{ route('staff.customer') }}">
                    <img src="{{ asset('images/customer.png') }}" class="staff-customer-i" alt="">
                    CUSTOMER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <a class="sidebar" href="{{ route('staff.delivery') }}">
                    <img src="{{ asset('images/delivery.png') }}" class="delivery-i" alt="">
                    DELIVERY</a>
            </div>
        </li>
    </ul>

@endsection

@section('main-content')

    <div class="content">
        <div class="taas">
            <form id="addCustomerForm">
                <button class="add" type="button" id="newButton">Add Customer</button>
            </form>
        </div>

        <div class="table">
            <table>
                <tr>
                    <th colspan="11" class="table-th">CUSTOMER LISTS</th>
                </tr>

                @php
                    $rowNumber = 1 + ($customers->currentPage() - 1) * $customers->perPage();
                @endphp

                <tr>
                    <th>No.</th>
                    <th>Company Name</th>
                    <th>Contact Name</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>

                <tbody>
                    @if ($customers->isEmpty())
                        <tr>
                            <td colspan="7">No data found.</td>
                        </tr>
                    @else
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->contact_name }}</td>
                                <td>{{ $customer->contact_num }}</td>
                                <td>{{ $customer->address }}</td>
                                <td class="actions">
                                    <div class="actions-container">
                                        <form action="{{ route('staff.customerEdit', $customer->id) }}" method="POST">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" class="edit editButton" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('staff.customerDestroy', $customer->id) }}" method="POST">
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

            <div class="pagination">{{ $customers->links('layouts.customPagination') }}</div>

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

@endsection
