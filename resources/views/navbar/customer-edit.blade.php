@extends('../layouts.layout')

@section('title', 'Customer')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">
@endsection

@section('modals')

    <div class="editOverlay"></div>


    {{-- Edit Modal --}}
        <div id="editModal" class="editModal">
            <div class="modal-content">
                <a href="{{ route('admin.customer') }}"><span class="close closeEditModal">&times;</span></a>

                <form class="edit-modal-form" action="{{ route('admin.customerUpdate', $customerss->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <center>
                        <h2 style="margin: 0%; color:#333;"><i class="fa-regular fa-pen-to-square edt-taas"></i>Edit Customer</h2>
                    </center>

                    <label class="modal-tops" for="">Company Name:</label>
                    <input required type="text" class="autofocus" name="customer_name_id" id="" autofocus
                        value="{{ old('customer_name_id', $customerss->customer_name_id) }}">

                    <label for="">Contact Name:</label>
                    <input required type="text" name="contact_name" id=""
                        value="{{ old('contact_name', $customerss->contact_name) }}">

                    <label for="">Contact Number:</label>
                    <input required type="tel" pattern="^\+?\d{4,14}$" title="Enter a valid contact number" title="Enter a valid contact number" name="contact_num"
                        name="contact_num" id="" value="{{ old('contact_num', $customerss->contact_num) }}">

                    <label for="">Address:</label>
                    <input required type="text" name="barangay" id=""
                        value="{{ old('barangay', $customerss->barangay) }}">
                    <input required type="text" name="municipality" id=""
                        value="{{ old('municipality', $customerss->municipality) }}">
                    <input required type="text" name="province" id=""
                        value="{{ old('province', $customerss->province) }}">

                    <input class="add" type="submit" value="Update">
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
                    {{-- <th>Item Sold</th> --}}
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
                                <td>{{ $customer->customer_name_id }}</td>
                                <td>{{ $customer->contact_name }}</td>
                                <td>{{ $customer->contact_num }}</td>
                                <td>{{ $customer->barangay }} {{ $customer->municipality }}, {{ $customer->province }}</td>                                {{-- <td>{{ $customer->item_sold }}</td> --}}
                                <td class="actions">
                                    <div class="actions-container">
                                        <form>
                                            <button type="button" class="edit editButton" id="edit"
                                                data-id="{{ $customer->id }}">
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

@endsection

@section('script')

@endsection
