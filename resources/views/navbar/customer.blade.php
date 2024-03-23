@extends('../layouts.layout')

@section('title', 'Customer')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay"></div>

    {{-- Add Modal --}}
    <div id="newModal" class="modal">
        <div class="modal-content">
            <span class="closeModal close">&times;</span>

            <form class="modal-form" action="{{ route('admin.customerStore') }}" method="POST">
                @csrf

                <center>
                    <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-plus"></i>Add Customer</h2>
                </center>

                <label class="modal-tops" for="">Company Name:</label>
                <input required autofocus type="text" name="name" value="{{ old('name') }}" id="autofocus" />
                @if ($errors->has('name'))
                    <div class="text-danger">{{ $errors->first('name') }}</div>
                @endif

                <label for="">Contact Name:</label>
                <input required type="text" name="contact_name" value="{{ old('contact_name') }}" id="" />
                @if ($errors->has('contact_name'))
                    <div class="text-danger">{{ $errors->first('contact_name') }}</div>
                @endif

                <label for="">Contact Number:</label>
                <input required type="tel" pattern="^\+?\d{4,14}$" title="Enter a valid contact number"
                    name="contact_num" value="{{ old('contact_num') }}" id="">

                @if ($errors->has('contact_num'))
                    <div class="text-danger">{{ $errors->first('contact_num') }}</div>
                @endif

                <label for="">Address:</label>
                <input required type="text" name="address" value="{{ old('address') }}" id="" />
                @if ($errors->has('address'))
                    <div class="text-danger">{{ $errors->first('address') }}</div>
                @endif

                <input class="add" type="submit" value="Add" />
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
                <a class="sidebar active" href="{{ route('admin.customer') }}">
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
                    {{-- <img src="{{ asset('images/supplier.png') }}" class="user-i" alt=""> --}}
                    USER</a>
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
                                        <form action="{{ route('admin.customerEdit', $customer->id) }}" method="POST">
                                            @csrf
                                            @method('GET')
                                            <button type="submit" class="edit editButton" id="edit">
                                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.customerDestroy', $customer->id) }}"
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

            <div class="pagination">{{ $customers->links('layouts.customPagination') }}</div>

        </div>

    </div>


@endsection

@section('footer')
    {{-- @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
    @if (session('message'))
        <script>
            alert('{{ session('message') }}');
        </script>
    @endif --}}
@endsection

@section('script')

@endsection
