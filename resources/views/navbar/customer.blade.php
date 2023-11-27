@extends('../layouts.layout')

@section('title', 'Customer')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">
@endsection

@section('modals')

    <div class="overlay editOverlay"></div>

    {{-- Add Modal --}}
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close ">&times;</span>

            <form class="modal-form" action="{{ route('admin.customerStore') }}" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;">Add Customer</h2>
                </center>
                <label class="modal-top" for="">Company Name:</label>
                <input required autofocus type="text" name="name" id="autofocus" />
                <label for="">Contact Name:</label>
                <input required type="text" name="contact_person" id="" />
                <label for="">Contact Number:</label>
                <input required type="text" pattern="{5,15}" title="Enter a valid contact number" name="contact_num"
                    id="" value="">
                <label for="">Address:</label>
                <input required type="text" name="address" id="" />
                {{-- <label for="">Item Sold:</label>
                <input required type="text" name="item_sold" id="" /> --}}

                <input class="add" type="submit" value="Add" />
            </form>

        </div>
    </div>

    {{-- Edit Modal --}}
    @foreach ($customers as $customer)
        <div id="editModal{{ $customer->id }}" class="modal editModal">
            <div class="modal-content">
                <span class="close closeEditModal">&times;</span>

                <form class="edit-modal-form" action="{{ route('admin.customerUpdate', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <center>
                        <h2 style="margin: 0%; color:#333;">Edit Customer</h2>
                    </center>

                    <label class="modal-top" for="">Company Name:</label>
                    <input required type="text" class="autofocus" name="name" id="" autofocus
                        value="{{ old('name', $customer->name) }}">
                    <label for="">Contact Name:</label>
                    <input required type="text" name="contact_person" id=""
                        value="{{ old('contact_person', $customer->contact_person) }}">

                    <label for="">Contact Number:</label>
                    <input required type="text" pattern="{5,15}" title="Enter a valid contact number"
                        name="contact_num" name="contact_num" id=""
                        value="{{ old('contact_num', $customer->contact_num) }}">

                    <label for="">Address:</label>
                    <input required type="text" name="address" id=""
                        value="{{ old('address', $customer->address) }}">
                    {{-- <label for="">Item Sold:</label>
                    <input required type="text" name="item_sold" id=""
                        value="{{ old('item_sold', $customer->item_sold) }}"> --}}

                    <input class="add" type="submit" value="Update">
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
                <a class="sidebar" href="{{ route('admin.product') }}">PRODUCT</a>
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
                <a class="sidebar active" href="{{ route('admin.customer') }}">CUSTOMER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <img src="{{ asset('images/supplier.png') }}" class="supplier-i" alt="">
                <a class="sidebar" href="{{ route('admin.supplier') }}">SUPPLIER</a>
            </div>
        </li>
        <li>
            <div class="baba-container">
                <img src="{{ asset('images/supplier.png') }}" class="user-i" alt="">
                <a class="sidebar" href="{{ route('admin.supplier') }}">USERS</a>
            </div>
        </li>
    </ul>

@endsection

@section('main-content')

    <div class="content">
        <div class="taas">
            <form id="addCustomerForm">
                <button class="add" type="button" id="addBtn">Add Customer</button>
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
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->contact_person }}</td>
                                <td>{{ $customer->contact_num }}</td>
                                <td>{{ $customer->address }}</td>
                                {{-- <td>{{ $customer->item_sold }}</td> --}}
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
