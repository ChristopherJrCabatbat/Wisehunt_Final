@extends('../layouts.layout')

@section('title', 'Customer')

@section('styles-links')
    <link rel="stylesheet" href="{{ asset('css/product-transaction-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-supplier-styles.css') }}">
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
    </ul>

@endsection

@section('main-content')

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>

            {{-- <form class="modal-form" action="{{ route('admin.customerStore') }}" method="POST"> --}}
            <form class="modal-form" action="#" method="POST">
                @csrf
                <center>
                    <h2 style="margin: 0%; color:#333;">Add Customer</h2>
                </center>
                <label class="modal-top" for="">Customer:</label>
                <input required autofocus type="text" name="name" id="name" />
                <label for="">Contact Person:</label>
                <input required type="text" name="contact_person" id="" />
                <label for="">Address:</label>
                <input required type="text" name="address" id="" />
                <label for="">Contact Number:</label>
                <input required type="text" pattern="[0-9]{5,11}" title="Enter a valid contact number" name="contact_num"
                    id="" value="">
                <label for="">Item Sold:</label>
                <input required type="text" name="item_sold" id="" />
                {{-- <label for="">Quantity:</label>
                  <input required type="number" name="quantity" id="" /> --}}

                <input class="add" type="submit" value="Add" />
            </form>

        </div>
    </div>

    <div class="content">
        <div class="taas">
            <form id="addCustomerForm">
                <button class="add" type="button" id="addCustomerBtn">Add Customer</button>
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
                    <th>Customer Name</th>
                    <th>Contact Person</th>
                    <th>Address</th>
                    <th>Contact Number</th>
                    <th>Item Sold</th>
                    <th>Actions</th>
                </tr>

                <tbody>
                    @if ($customers->isEmpty())
                        <tr>
                            <td colspan="7">No results found.</td>
                        </tr>
                    @else
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->contact_person }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>{{ $customer->contact_num }}</td>
                                <td>{{ $customer->item_sold }}</td>
                                <td>
                                    <div class="edit-delete">
                                        <a href="Customers/{{ $customer->id }}/edit" class="edit">
                                            <img class="edit" src="{{ asset('images/edits.png') }}" alt="edit btn"></a>

                                        {{-- <form action="{{ route('admin.customerDestroy', $customer->id) }}" method="POST" --}}
                                        <form action="#" method="POST" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="deletes"><img class="delete"
                                                    src="{{ asset('images/delete.png') }}" alt="delete btn"></button>
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
